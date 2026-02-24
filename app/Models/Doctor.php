<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'specialty_id',
        'license',
        'biography',
    ];

    //Relación con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Relación con Specialty
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
}
