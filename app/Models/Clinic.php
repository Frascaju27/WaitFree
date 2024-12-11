<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'address', 'city', 'state', 'zip_code', 'country', 'logo'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function integrations()
    {
        return $this->hasMany(ThirdPartyIntegration::class);
    }
}
