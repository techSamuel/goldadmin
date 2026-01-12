<?php

namespace App\Observers;

use App\Models\GoldPrice;
use App\Services\FCMService;
use Illuminate\Support\Facades\Log;

class GoldPriceObserver
{
    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    /**
     * Handle the GoldPrice "created" event.
     */
    public function created(GoldPrice $goldPrice): void
    {
        try {
            // Get the previous price record (latest before current)
            $previousPrice = GoldPrice::where('id', '<', $goldPrice->id)
                ->orderBy('id', 'desc')
                ->first();

            if (!$previousPrice) {
                return; // First record, no notification needed
            }

            // Check Gold Prices
            $gold22Diff = $goldPrice->karat_22 - $previousPrice->karat_22;
            $gold24Diff = $goldPrice->karat_24 - $previousPrice->karat_24;

            // Check Silver Prices
            $silver22Diff = $goldPrice->silver_price - $previousPrice->silver_price;
            $silver24Diff = $goldPrice->silver_24 - $previousPrice->silver_24;

            // Threshold (0 = any change triggers notification)
            $threshold = 0;

            if (
                abs($gold22Diff) > $threshold || abs($gold24Diff) > $threshold ||
                abs($silver22Diff) > $threshold || abs($silver24Diff) > $threshold
            ) {

                $this->sendPriceAlert($goldPrice, $gold22Diff, $silver22Diff, $gold24Diff, $silver24Diff);
            } else {
                session()->flash('notification_status', 'ℹ️ No notification sent (No price change).');
                Log::info("Price Alert Skipped: No Change");
            }

        } catch (\Exception $e) {
            Log::error("GoldPriceObserver Error: " . $e->getMessage());
        }
    }

    protected function sendPriceAlert($currentPrice, $goldDiff, $silverDiff, $gold24Diff = 0, $silver24Diff = 0)
    {
        $title = "স্বর্ণের দাম আপডেট"; // Gold Price Update
        $body = "";

        // Gold Message Logic
        $goldHasMsg = false;
        $goldPriceToShow = $currentPrice->karat_22;
        $goldKaratLabel = "২২ ক্যারেট";

        if (abs($goldDiff) > 0) {
            $status = $goldDiff > 0 ? "বেড়েছে" : "কমেছে";
            $body .= "স্বর্ণের দাম {$status}। ";
            $goldHasMsg = true;
            // Default show 22K
        } elseif (abs($gold24Diff) > 0) {
            $status = $gold24Diff > 0 ? "বেড়েছে" : "কমেছে";
            $body .= "স্বর্ণের (24K) দাম {$status}। ";
            $goldHasMsg = true;
            $goldPriceToShow = $currentPrice->karat_24;
            $goldKaratLabel = "২৪ ক্যারেট";
        }

        if ($goldHasMsg) {
            $formattedGold = number_format($goldPriceToShow, 0);
            $body .= "বর্তমান {$goldKaratLabel} প্রতি ভরি ৳$formattedGold. ";
        }

        // Silver Message Logic
        $silverHasMsg = false;
        $silverPriceToShow = $currentPrice->silver_price;
        $silverKaratLabel = "২২ ক্যারেট (Silver)";

        if (abs($silverDiff) > 0) {
            $status = $silverDiff > 0 ? "বেড়েছে" : "কমেছে";
            $body .= "রুপার দাম {$status}। ";
            $silverHasMsg = true;
        } elseif (abs($silver24Diff) > 0) {
            $status = $silver24Diff > 0 ? "বেড়েছে" : "কমেছে";
            $body .= "রুপার (24K) দাম {$status}। ";
            $silverHasMsg = true;
            $silverPriceToShow = $currentPrice->silver_24;
            $silverKaratLabel = "২৪ ক্যারেট (Silver)";
        }

        if ($silverHasMsg) {
            $formattedSilver = number_format($silverPriceToShow, 0);
            $body .= "বর্তমান {$silverKaratLabel} প্রতি ভরি ৳$formattedSilver.";
        }

        // Send Notification
        $result = $this->fcmService->sendNotification($title, $body, 'price_alerts');

        // Feedback for Admin Panel
        if ($result['success']) {
            session()->flash('notification_status', 'Notification Sent: ' . $title);
        } else {
            session()->flash('notification_error', 'Price Updated but Notification Failed: ' . ($result['message'] ?? 'Unknown Error'));
        }

        Log::info("Price Alert Sent: $body");
    }
}
