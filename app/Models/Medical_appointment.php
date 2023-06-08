<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medical_appointment extends Model
{
    use HasFactory;

    //relacion uno a muchos
    public function user(){
        return $this->belongsTo(User::class);
    }

    // relacion uno a muchos
    public function status()
    {
        return $this->belongsTo(Appointment_status::class);
    }
}
