<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'cpf',
        'date_of_birth',
        'gender',
        'enrollment',
        'grade',
        'class',
        'education_level',
        'responsible_name',
        'responsible_phone',
        'responsible_email',
        'cep',
        'address',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'observations',
        'faces_id'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function image()
    {
        return $this->hasOne(Image::class, 'module_id')->where('module', 'corsight_image');
    }
}
