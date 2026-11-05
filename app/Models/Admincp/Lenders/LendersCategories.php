<?php

namespace App\Models\Admincp\Lenders;

use Illuminate\Database\Eloquent\Model;

class LendersCategories extends Model
{
    protected $guarded = [];

    protected $table = 'lenders__categories';

    public function lender()
    {
        return $this->belongsTo('\App\Models\Admincp\Lenders\Lenders', 'lender_id');
    }
}
