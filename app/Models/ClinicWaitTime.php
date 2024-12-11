<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicWaitTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'fixed_wait_time',
        'time_per_patient',
        'fixed_wait',
    ];
}
