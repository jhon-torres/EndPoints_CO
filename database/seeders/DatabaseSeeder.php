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
            'names' => 'Paco Sant',
            'surnames' => 'Doe Serve',
            'email' => 'admin@epn.edu.ec',
            'password' => bcrypt('password'),
            'user_state' => 1,
            'phone' => '0978451236',
            'address' => 'Las Casas'
        ]);
        \App\Models\User::create([
            'identity_card_user' => '1314253678',
            'rol_id' => '2',
            'names' => 'Gabriel S',
            'surnames' => 'Alvarado',
            'email' => 'gabo@epn.edu.ec',
            'password' => bcrypt('password'),
            'user_state' => 1,
            'phone' => '0978451209',
            'address' => 'Solanda',
            'profile_picture_id' => 'users/uainyflgajbiahmq0gz9',
            'profile_picture_url' => 'https://res.cloudinary.com/dugisz2vj/image/upload/v1688394469/users/uainyflgajbiahmq0gz9.png',
            'profesional_description' => 'Aqui va una pequeña descripción del odontólogo'

        ]);
        \App\Models\User::create([
            'identity_card_user' => '1712345077',
            'rol_id' => '3',
            'names' => 'Jhon',
            'surnames' => 'Torres',
            'email' => 'jhon64t@gmail.com',
            'password' => bcrypt('password'),
            'user_state' => 1,
            'phone' => '0978145255',
            'address' => 'Quito Norte'
        ]);
    }
}
