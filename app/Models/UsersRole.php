<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersRole extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'id',
        'date_created',
        'date_modified',
        'created_by',
        'modified_by',
        'name',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}