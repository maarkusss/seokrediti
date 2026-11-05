<?php

namespace App\Models\Admincp\Sections;

use Illuminate\Database\Eloquent\Model;

class SectionsHistroy extends Model
{
    protected $guarded = [];

    protected $table = 'sections__history';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
