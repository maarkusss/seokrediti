<?php

namespace App\Models\Admincp\Lenders;

use Illuminate\Database\Eloquent\Model;

class VisitsSorting extends Model
{
    protected $guarded = [];

    protected $table = 'lenders__visits_sorting';

    public function visit()
    {
        return $this->belongsTo('\App\Models\Admincp\Lenders\Visits', 'visit_id')->where('bot', 0);
    }
}
