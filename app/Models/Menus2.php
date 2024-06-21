<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menus2 extends Model
{
    protected $table = "menus2";

    public $timestamps = false;

    protected $fillable = [
        'id',
        'date_created',
        'date_modified',
        'created_by',
        'modified_by',
        'menus1_id',
        'name',
        'url',
        'icon',
        'position',
        'iframe'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    public function menus1()
    {
        return $this->belongsTo(Menus1::class, 'menus1_id');
    }

    public function menus3()
    {
        return $this->hasMany(Menus3::class, 'menus2_id');
    }

    public function usersPermissions()
    {
        return $this->hasMany(UsersPermission::class, 'menu2_id');
    }
}
