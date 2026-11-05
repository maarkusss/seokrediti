<?php

namespace App\Models\Admincp\Lenders;

use Database\Factories\LendersFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $name
 * @property $image
 * @property $affiliate_link
 * @property $position
 * @property $frequency
 * @property $daily_epc
 * @property $daily_epc_before
 * @property $guaranteed_epc
 * @property $earnings
 * @property $clicks
 * @property $epc
 * @property $first_loan
 * @property $max_amount
 * @property $min_term
 * @property $max_term
 * @property $min_years
 * @property $max_years
 * @property $receiving_time
 * @property $active
 * @property $zero_percent
 * @property $top_lender
 *
 * @method active()
 * @method topLenders()
 */
class Lenders extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', 1);
    }

    public function scopeTopLenders(Builder $query): Builder
    {
        return $query->where('top_lender', 1);
    }

    public function categories()
    {
        return $this->hasMany('\App\Models\Admincp\Lenders\LendersCategories', 'lender_id');
    }

    public function commissions()
    {
        return $this->hasMany('\App\Models\Admincp\Commissions', 'lender_id');
    }

    public function clicksFromTable()
    {
        return $this->hasMany('\App\Models\Clicks', 'lender_id');
    }

    public function data()
    {
        return $this->hasOne('\App\Models\Admincp\Lenders\LendersData', 'lender_id')->where('lang', App()->getLocale());
    }

    public function dataAPI()
    {
        return $this->hasOne('\App\Models\Admincp\Lenders\LendersData', 'lender_id');
    }

    public function sessions()
    {
        return $this->hasMany('\App\Models\Admincp\Lenders\VisitsSorting', 'lender_id');
    }

    public function reviews()
    {
        return $this->hasMany('\App\Models\Reviews', 'lender_id');
    }

    // protected static function newFactory(): Factory
    // {
    //     return LendersFactory::new();
    // }
}
