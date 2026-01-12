<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        \App\Models\Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        // Initialize default prices
        \App\Models\GoldPrice::create([
            'karat_24' => 0,
            'karat_22' => 0,
            'karat_21' => 0,
            'karat_18' => 0,
            'silver_price' => 0,
        ]);
    }
}
