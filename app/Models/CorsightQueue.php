<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorsightQueue extends Model
{
    use HasFactory;

    protected $table = "corsight_queue";

    protected $fillable = [
        'status',
        'module_id',
        'module',
        'data',
        'endpoint',
        'log'
    ];
}
