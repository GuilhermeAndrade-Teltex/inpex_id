<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendToEmail extends Model
{
    use HasFactory;

    
    protected $table = 'send_to_email';
    
    public $timestamps = false;
    
    protected $fillable = [
        'module_id',
        'user_id',
        'send_to',
        'send_cc',
        'send_bcc',
        'page_title',
        'content_title',
        'header_description',
        'content_description',
        'attach',
        'config_file',
        'users_email',
        'log',
        'status',
        'module',
        'date_modified',
    ];
}
