<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'identity_card_user' => '1122334455',
            'rol_id' => '1',
            'names' => 'Admin User',
            'surnames' => 'Test Test',
            'email' => 'admin@epn.edu.ec',
            'password' => bcrypt('password'),
            'user_state' => 1,
            'phone' => '0978451236',
            'address' => 'Quito Sur'
        ]);
    }
}
