<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // Relación de uno a muchos
    public function user ()
    {
        return $this->belongsTo(Notification::class);
    }
}
