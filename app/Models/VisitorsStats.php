<?php

namespace App\Models;

use App\Models\Visitors\Visitors;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $date
 * @property $count
 */
class VisitorsStats extends Model
{
    protected $guarded = [];

    protected $table = 'visitors__stats';

    public $timestamps = false;

    public static function buildWeeklyStats(): void
    {
        $days = 8;
        $report_date = Carbon::today()->subDays($days);

        for ($i = 0; $i < $days; $i++) {
            $date = $report_date->addDay()->format('Y-m-d');

            VisitorsStats::where('date', $report_date->format('Y-m-d')
                ->update([
                    'count' => Visitors::whereDate('created_at', $date)->count(),
                ]));
        }
    }
}
