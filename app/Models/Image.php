<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';

    public $timestamps = false;

    protected $fillable = [
        'module',
        'module_id',
        'status',
        'date_created',
        'date_modified',
        'order',
        'source',
        'name_cropped',
        'path_cropped',
        'width_cropped',
        'height_cropped',
        'name_original',
        'path_original',
        'width_original',
        'height_original',
        'name_thumbs',
        'path_thumbs',
        'width_thumbs',
        'height_thumbs',
        'extension',
        'html_attributions',
    ];

    protected $dates = ['date_created', 'date_modified'];
}
