<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'client_id',
        'name',
        'regional',
        'responsible',
        'cep',
        'address',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'watchlist_id',
        'observations'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
