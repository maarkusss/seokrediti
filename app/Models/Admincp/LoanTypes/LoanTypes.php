<?php

namespace App\Models\Admincp\LoanTypes;

use App\Models\Admincp\Faqs\FaqSections;
use App\Models\Admincp\Lenders\Lenders;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LoanTypes extends Model
{
    protected $guarded = [];


    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', 1);
    }

    public function categories()
    {
        return $this->hasMany('\App\Models\Admincp\Lenders\LendersCategories', 'loan_type_id');
    }

    public function lenders()
    {
        return $this->belongsToMany(Lenders::class, 'lenders__categories', 'loan_type_id', 'lender_id');
    }

    /**
     * Return the min_amount of loan type lenders.
     *
     * @return int
     */
    public function minAmount()
    {
        return Lenders::whereIn('id', $this->categories()->pluck('lender_id'))->where('active', true)->min('min_amount');
    }

    /**
     * Return the max_amount of loan type lenders.
     *
     * @return int
     */
    public function maxAmount()
    {
        return Lenders::whereIn('id', $this->categories()->pluck('lender_id'))->where('active', true)->max('max_amount');
    }



    public function children()
    {
        return $this->hasMany(self::class, 'parent_type_id')->where('active', true);
        //  return self::where('active', true)->where('parent_type_id', $this->id)->get();
    }

    /**
     * Return test loantype faq
     *
     * @param int $faqId
     * @return bool
     */
    public function faq($faqId): bool
    {
        return FaqSections::where('loan_type_id', $this->id)->where('faq_id', $faqId)->exists();
    }

    public function faqs()
    {
        return FaqSections::where('loan_type_id', $this->id)->select('faq_id')->get();
    }

    /**
     * Return loan type's section
     *
     * @return HasOne
     */
    public function section(): HasOne
    {
        return $this->hasOne(LoanTypesSections::class, 'loan_type_id');
    }
}
