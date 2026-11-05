<?php

namespace App\Services;

use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\Lenders\LendersData;
use App\Models\Admincp\Settings;

class LenderService
{
    /**
     * Unified response for
     * /api/lenders
     * /api/getalllenders (deprecated)
     *
     * @return array
     */
    public static function getAll(): array
    {
        $prefix = Settings::query()
            ->where('name', 'prefix')
            ->first();

        $lenders = Lenders::active()
            ->select([
                'id',
                'name',
                'image',
                'affiliate_link',
                'frequency',
                'first_loan',
                'max_amount',
                'min_years',
                'max_years',
                'receiving_time',
                'zero_percent',
            ])
            ->orderBy('position', 'ASC')
            ->get();

        $lang = 'lv';
        if (request()->input('lang')) {
            $available_locales = available_locales();
            if (in_array(request()->input('lang'), $available_locales)) {
                $lang = request()->input('lang');
            }
        }

        $lender_arr = [];
        if ($lenders->isNotEmpty()) {
            $i = 0;
            foreach ($lenders as $lender) {
                if ($lender->frequency) {
                    if (rand(1, $lender->frequency) == $lender->frequency) {
                        // all good, keep adding $lender into $lender_arr
                    } else {
                        // skip lender if it has not-null $lender->frequency and does not match the condition
                        continue;
                    }
                }

                $lendersdata = LendersData::query()
                    ->where('lender_id', $lender->id)
                    ->where('lang', $lang)
                    ->select([
                        'slogan',
                    ])
                    ->first();

                if ($lender->image) {
                    $lender->image = config('app.url') . $lender->image;
                }

                if ($prefix) {
                    $lender->affiliate_link = $lender->affiliate_link . '?s1=' . $prefix->value;
                }

                $lender_arr[$i] = $lender;
                $lender_arr[$i]['slogan'] = $lendersdata ? $lendersdata->slogan : null;
                $i++;
            }
        }

        return $lender_arr;
    }
}
