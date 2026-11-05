<?php

namespace App\Models\Admincp\Lenders;

use Illuminate\Database\Eloquent\Model;

class LendersSortingEpc extends Model
{
    protected $guarded = [];

    protected $table = 'lenders__sorting_epc';

    public function lender()
    {
        return $this->belongsTo('\App\Models\Admincp\Lenders\Lenders', 'lender_id');
    }
}
