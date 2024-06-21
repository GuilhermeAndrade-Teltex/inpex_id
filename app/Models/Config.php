<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'corsight_api_token',
        'corsight_api_token_expiration',
        'app_name',
        'app_url',
        'admin_email',
        'maintenance_mode',
    ];

    protected $casts = [
        'corsight_api_token_expiration' => 'integer',
        'maintenance_mode' => 'boolean',
    ];
}