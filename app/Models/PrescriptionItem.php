<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    protected $fillable = [
        'appointment_id',
        'medicine_name',
        'dosage',
        'frequency',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
