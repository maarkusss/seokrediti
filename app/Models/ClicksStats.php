<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $date
 * @property $count
 */
class ClicksStats extends Model
{
    protected $guarded = [];

    protected $table = 'clicks__stats';

    public $timestamps = false;

    public static function buildWeeklyStats()
    {
        $days = 8;
        $report_date = Carbon::today()->subDays($days);

        for ($i = 0; $i < $days; $i++) {
            $date = $report_date->addDay()->format('Y-m-d');

            ClicksStats::where('date', $report_date->format('Y-m-d')
                ->update([
                    'count' => Clicks::whereDate('created_at', $date)->count(),
                ]));
        }
    }
}
