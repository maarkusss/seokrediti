<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $name
 * @property $campaign_domain
 * @property $active
 *
 * @method active()
 */
class LenderCampaign extends Model
{
    protected $guarded = [];
    protected $table = 'lendercampaigns';
    public $timestamps = false;

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', 1);
    }
}
