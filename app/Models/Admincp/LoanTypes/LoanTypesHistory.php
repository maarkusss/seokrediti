<?php

namespace App\Models\Admincp\LoanTypes;

use Illuminate\Database\Eloquent\Model;

class LoanTypesHistory extends Model
{
    protected $guarded = [];

    protected $table = 'loan_types__history';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
