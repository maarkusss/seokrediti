<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitemapIgnoredUrl extends Model
{
    protected $fillable = [
        'url',
        'active',
    ];
}