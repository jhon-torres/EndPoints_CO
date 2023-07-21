<?php

namespace Database\Seeders;

use App\Models\Medical_appointment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicalAppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Medical_appointment::create([
            'id_status' => 2,
            'identity_card_user' => '1314253678',
            'date' => '2023-07-21',
            'start_time' => "09:00",
            'end_time' => "10:00",
            'id_patient' => '1712345077'
        ]);
        Medical_appointment::create([
            'id_status' => 1,
            'identity_card_user' => '1314253678',
            'date' => '2023-07-22',
            'start_time' => "09:00",
            'end_time' => "10:00",
            'id_patient' => null
        ]);
    }
}
