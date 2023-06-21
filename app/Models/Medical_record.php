<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medical_record extends Model
{
    use HasFactory;

    protected $guarded = [];

    // relacion uno a uno
    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    //relacion uno a muchos
    public function record_details()
    {
        return $this->belongsTo(Medical_record::class);
    }

}
