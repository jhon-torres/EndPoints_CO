<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment_status extends Model
{
    use HasFactory;

    // relacion uno a muchos
    public function medical_appointment ()
    {
        return $this->hasMany('App\Models\Medical_appointment');
    }
}
