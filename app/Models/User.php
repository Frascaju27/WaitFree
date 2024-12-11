<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['clinic_id', 'name', 'email', 'phone', 'role', 'password'];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
