<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menus3 extends Model
{
    protected $table = "menus3";

    public $timestamps = false;

    protected $fillable = [
        'id',
        'date_created',
        'date_modified',
        'created_by',
        'modified_by',
        'menus2_id',
        'name',
        'url',
        'icon',
        'position',
        'dashboard',
        'method'
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
        return $this->belongsTo(Menus2::class, 'menus2_id');
    }
}
