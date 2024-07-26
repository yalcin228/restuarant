<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\OrderType;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
                'name'              => 'Superadmin',
                'email'             => 'superadmin@gmail.com',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
                'role'              => 'super-admin',
                'restaurant_id'     => null,
        ]);

        OrderType::insert([
            ['name' => 'Table'],
            ['name' => 'Online'],
        ]);

        Restaurant::create([
            'name' => 'Borani',
            'address' => 'Xetai',
            'phone' => '123456789',
            'email' => 'borani@gmail.com',
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'role' => 'admin',
            'restaurant_id' => 1,
            'end_date' => now()->addDays(30)
        ]);
    }
}
