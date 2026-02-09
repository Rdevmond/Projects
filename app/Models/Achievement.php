<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = ['title', 'content', 'image_path', 'date', 'is_active'];
    protected $casts = [
        'date' => 'datetime',
        'is_active' => 'boolean'
    ];
}
