<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ad_mob_configs', function (Blueprint $table) {
            $table->id();
            $table->string('platform')->default('android'); // android, ios
            $table->string('banner_id')->nullable();
            $table->string('interstitial_id')->nullable();
            $table->string('rewarded_id')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_mob_configs');
    }
};
