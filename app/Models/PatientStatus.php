<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientStatus extends Model
{
    protected $fillable = ['patient_id', 'clinic_id', 'status', 'status_changed_at'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
