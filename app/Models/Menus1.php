<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menus1 extends Model
{
    protected $table = "menus1";

    public $timestamps = false;

    protected $fillable = [
        'id',
        'date_created',
        'date_modified',
        'created_by',
        'modified_by',
        'name',
        'url',
        'icon',
        'position',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    public function menus2()
    {
        return $this->hasMany(Menus2::class, 'menus1_id');
    }

    public function usersPermissions()
    {
        return $this->hasMany(UsersPermission::class, 'menu1_id');
    }
}
