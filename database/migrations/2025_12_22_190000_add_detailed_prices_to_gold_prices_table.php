<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('gold_prices', function (Blueprint $table) {
            $table->decimal('traditional_gold', 10, 2)->default(0)->after('karat_18');

            // We assume 'silver_price' is 22K. We'll rename it later or just map it.
            // Let's explicitly add other silver columns.
            $table->decimal('silver_21', 10, 2)->default(0)->after('silver_price');
            $table->decimal('silver_18', 10, 2)->default(0)->after('silver_21');
            $table->decimal('traditional_silver', 10, 2)->default(0)->after('silver_18');
        });
    }

    public function down()
    {
        Schema::table('gold_prices', function (Blueprint $table) {
            $table->dropColumn(['traditional_gold', 'silver_21', 'silver_18', 'traditional_silver']);
        });
    }
};
