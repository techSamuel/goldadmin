<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('gold_prices', function (Blueprint $table) {
            $table->decimal('silver_24', 10, 2)->default(0)->after('traditional_gold'); // Start of silver section logically
            $table->decimal('silver_24_usd', 10, 2)->default(0)->after('traditional_gold_usd');
        });
    }

    public function down()
    {
        Schema::table('gold_prices', function (Blueprint $table) {
            $table->dropColumn(['silver_24', 'silver_24_usd']);
        });
    }
};
