<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record_detail extends Model
{
    use HasFactory;

    //relacion uno a muchos
    public function Medical_record()
    {
        return $this->hasMany('App\Models\Medical_record');
    }
}
