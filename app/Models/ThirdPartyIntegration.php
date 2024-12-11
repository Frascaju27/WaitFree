<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThirdPartyIntegration extends Model
{
    protected $fillable = ['clinic_id', 'service_name', 'api_key', 'access_token', 'settings', 'connected_at'];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
