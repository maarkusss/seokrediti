<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clicks extends Model
{
    protected $guarded = [];

    public function lender()
    {
        return $this->belongsTo('App\Models\Admincp\Lenders\Lenders', 'lender_id');
    }
}
