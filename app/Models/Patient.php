<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends model
{
    use Notifiable,HasFactory;

    // Define the table and fillable fields
    protected $table = 'patients';

    protected $fillable = [
        'name', 'email', 'phone', 'dob', 'gender', 'notes', 'password','clinic_id'
    ];

    // Hide the password and remember_token fields
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function mostRecentStatus()
    {
        return $this->hasOne(PatientStatus::class, 'patient_id')
            ->latest();  // Order by most recent status
    }
}
