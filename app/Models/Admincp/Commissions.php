<?php

namespace App\Models\Admincp;

use App\Services\GoodAff\GoodAffStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Commissions extends Model
{
    protected $guarded = [];
    protected $table = 'commissions';

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', GoodAffStatus::APPROVED);
    }
}
