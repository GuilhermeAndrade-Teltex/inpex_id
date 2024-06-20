<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table = 'users_tokens';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'date_created',
        'date_modified',
        'created_by',
        'modified_by',
        'status',
        'expires_in',
        'token'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
