<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersPermission extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'id',
        'date_created',
        'date_modified',
        'created_by',
        'modified_by',
        'role_id',
        'menu1_id',
        'menu2_id',
        'menu3_id',
        'show',
        'create',
        'edit',
        'destroy',
        'export',
        'access_log',
        'audit_log'
    ];

    public function role()
    {
        return $this->belongsTo(UsersRole::class, 'role_id');
    }

    public function menu1()
    {
        return $this->belongsTo(Menus1::class, 'menu1_id');
    }

    public function menu2()
    {
        return $this->belongsTo(Menus2::class, 'menu2_id');
    }

    public function menu3()
    {
        return $this->belongsTo(Menus3::class, 'menu3_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}