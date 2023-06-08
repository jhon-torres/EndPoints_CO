<?php

namespace Database\Seeders;

use App\Models\Appointment_status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Appointment_status::create([
            'description' => 'disponible',
        ]);
        Appointment_status::create([
            'description' => 'no disponible',
        ]);
    }
}
