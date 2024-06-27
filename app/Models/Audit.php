<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_created',
        'created_by',
        'module_id',
        'module',
        'action',
        'old_value',
        'new_value',
        'description',
    ];
}
