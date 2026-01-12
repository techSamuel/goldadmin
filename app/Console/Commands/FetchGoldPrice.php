<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoldPriceApiService;

class FetchGoldPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gold:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch live gold prices from the configured API';

    /**
     * Execute the console command.
     */
    public function handle(GoldPriceApiService $service)
    {
        $this->info('Starting Gold Price Fetch...');

        $result = $service->fetchAndStorePrice();

        if ($result['success']) {
            $this->info('Successfully updated gold prices!');
            return Command::SUCCESS;
        } else {
            $this->error('Failed to update prices: ' . $result['message']);
            return Command::FAILURE;
        }
    }
}
