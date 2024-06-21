<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cnpj',
        'cep',
        'address',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'observations'
    ];

    public function schools()
    {
        return $this->hasMany(School::class);
    }
}
