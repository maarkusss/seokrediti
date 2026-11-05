<?php

namespace App\Models\Admincp\LoanTypes;

use Illuminate\Database\Eloquent\Model;

class LoanTypesSections extends Model
{
    protected $guarded = [];

    protected $table = 'loan_types__section';

    public function loanType()
    {
        return $this->belongsTo(LoanTypes::class, 'loan_type_id');
    }
}
