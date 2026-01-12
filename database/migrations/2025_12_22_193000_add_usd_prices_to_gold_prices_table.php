<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('gold_prices', function (Blueprint $table) {
            // Gold USD
            $table->decimal('karat_24_usd', 10, 2)->default(0)->after('karat_24');
            $table->decimal('karat_22_usd', 10, 2)->default(0)->after('karat_22');
            $table->decimal('karat_21_usd', 10, 2)->default(0)->after('karat_21');
            $table->decimal('karat_18_usd', 10, 2)->default(0)->after('karat_18');
            $table->decimal('traditional_gold_usd', 10, 2)->default(0)->after('traditional_gold');

            // Silver USD
            $table->decimal('silver_price_usd', 10, 2)->default(0)->after('silver_price');
            $table->decimal('silver_21_usd', 10, 2)->default(0)->after('silver_21');
            $table->decimal('silver_18_usd', 10, 2)->default(0)->after('silver_18');
            $table->decimal('traditional_silver_usd', 10, 2)->default(0)->after('traditional_silver');
        });
    }

    public function down()
    {
        Schema::table('gold_prices', function (Blueprint $table) {
            $table->dropColumn([
                'karat_24_usd',
                'karat_22_usd',
                'karat_21_usd',
                'karat_18_usd',
                'traditional_gold_usd',
                'silver_price_usd',
                'silver_21_usd',
                'silver_18_usd',
                'traditional_silver_usd'
            ]);
        });
    }
};
