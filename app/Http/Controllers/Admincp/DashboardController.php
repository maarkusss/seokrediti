<?php

namespace App\Http\Controllers\Admincp;

use App\Http\Controllers\Controller;
use App\Models\Admincp\Commissions;
use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\LoanTypes\LoanTypes;
use App\Models\Clicks;
use App\Models\ClicksStats;
use App\Models\Lender\LenderFilter;
use App\Models\LenderCampaign;
use App\Models\Visitors\Visitors;
use App\Models\VisitorsStats;
use Carbon\Carbon;
use Goodday\GoRewrites\Models\GoRewrite;
use Illuminate\Contracts\Support\Renderable;
use InvalidArgumentException;

class DashboardController extends Controller
{
    public static $STATS_TODAY = [
        'clicks' => 0,
        'commissions' => 0,
        'visitors' => 0,
    ];

    public static $STATS_YESTERDAY = [
        'clicks' => 0,
        'commissions' => 0,
        'visitors' => 0,
    ];

    public function index(): Renderable
    {
        $this->fillTodayStats();
        $this->fillYesterdayStats();

        return view('admincp.dashboard')->with([
            'data' => $this->getStatistics(),
            'chart_data' => $this->getChartData(),
        ]);
    }

    protected function fillTodayStats(): void
    {
        $today = Carbon::today();

        static::$STATS_TODAY['clicks'] = Clicks::query()->where('created_at', '>=', $today->format('Y-m-d 00:00:00'))->where('created_at', '<=', $today->format('Y-m-d 23:59:59'))->count();
        static::$STATS_TODAY['commissions'] = Commissions::approved()->where('commission_date', '>=', $today->format('Y-m-d 00:00:00'))->where('commission_date', '<=', $today->format('Y-m-d 23:59:59'))->sum('commission');
        static::$STATS_TODAY['visitors'] = Visitors::query()->where('created_at', '>=', $today->format('Y-m-d 00:00:00'))->where('created_at', '<=', $today->format('Y-m-d 23:59:59'))->count();
    }

    protected function fillYesterdayStats(): void
    {
        $yesterday = Carbon::yesterday();

        static::$STATS_YESTERDAY['clicks'] = Clicks::query()->where('created_at', '>=', $yesterday->format('Y-m-d 00:00:00'))->where('created_at', '<=', $yesterday->format('Y-m-d 23:59:59'))->count();
        static::$STATS_YESTERDAY['commissions'] = Commissions::approved()->where('commission_date', '>=', $yesterday->format('Y-m-d 00:00:00'))->where('commission_date', '<=', $yesterday->format('Y-m-d 23:59:59'))->sum('commission');
        static::$STATS_YESTERDAY['visitors'] = Visitors::query()->where('created_at', '>=', $yesterday->format('Y-m-d 00:00:00'))->where('created_at', '<=', $yesterday->format('Y-m-d 23:59:59'))->count();
    }

    public function getChartData(): array
    {
        $chart = [];
        $days = 8;
        $report_date = Carbon::today()->subDays($days);

        $clicks_cache = ClicksStats::where('date', '>=', $report_date->format('Y-m-d'))->pluck('count', 'date')
            ->toArray();

        $visitors_cache = VisitorsStats::where('date', '>=', $report_date->format('Y-m-d'))->pluck('count', 'date')
            ->toArray();

        for ($i = 0; $i < $days; $i++) {
            $date = $report_date->addDay()->format('Y-m-d');

            if ($report_date->isToday()) {
                $clicks_count = static::$STATS_TODAY['clicks'];
                $commissions_count = static::$STATS_TODAY['commissions'];
                $visitors_count = static::$STATS_TODAY['visitors'];
            } elseif ($report_date->isYesterday()) {
                $clicks_count = static::$STATS_YESTERDAY['clicks'];
                $commissions_count = static::$STATS_YESTERDAY['commissions'];
                $visitors_count = static::$STATS_YESTERDAY['visitors'];
            } else {
                $commissions_count = Commissions::where('status', 'A')->whereDate('commission_date', $date)->sum('commission');

                if (($clicks_count = $clicks_cache[$date] ?? -1) < 0) {
                    $clicks_count = Clicks::where('created_at', '>=', $date . ' 00:00:00')->where('created_at', '<=', $date . ' 23:59:59')->count();
                    ClicksStats::updateOrCreate(['date' => $date], ['count' => $clicks_count]);
                }

                if (($visitors_count = $visitors_cache[$date] ?? -1) < 0) {
                    $visitors_count = Visitors::where('created_at', '>=', $date . ' 00:00:00')->where('created_at', '<=', $date . ' 23:59:59')->count();
                    VisitorsStats::updateOrCreate(['date' => $date], ['count' => $visitors_count]);
                }
            }

            $chart['clicks'][$date] = $clicks_count;
            $chart['commissions'][$date] = $commissions_count;
            $chart['visitors'][$date] = $visitors_count;
        }

        return $chart;
    }

    public function getStatistics(): array
    {
        $array['clicks']['today'] = static::$STATS_TODAY['clicks'];
        $array['clicks']['yesterday'] = static::$STATS_YESTERDAY['clicks'];

        $array['commissions']['today'] = static::$STATS_TODAY['commissions'];
        $array['commissions']['yesterday'] = static::$STATS_YESTERDAY['commissions'];

        $array['visitors']['today'] = static::$STATS_TODAY['visitors'];
        $array['visitors']['yesterday'] = static::$STATS_YESTERDAY['visitors'];

        $array['clicks']['percentage'] = $this->calcPercentage($array, 'clicks');
        $array['commissions']['percentage'] = $this->calcPercentage($array, 'commissions');
        $array['visitors']['percentage'] = $this->calcPercentage($array, 'visitors');

        $array['clicks']['color'] = $this->calcColorStyling($array, 'clicks');
        $array['commissions']['color'] = $this->calcColorStyling($array, 'commissions');
        $array['visitors']['color'] = $this->calcColorStyling($array, 'visitors');

        if (auth()->user()->can('view lenders')) {
            $array['lenders']['total'] = Lenders::query()->count();
            $array['lenders']['active'] = Lenders::active()->count();
            $array['lenders']['link'] = route('admincp.lenders.index');

            $array['lendercampaigns']['total'] = LenderCampaign::query()->count();
            $array['lendercampaigns']['active'] = LenderCampaign::active()->count();
            $array['lendercampaigns']['link'] = route('admincp.lenders.campaigns.index');
        }

        if (auth()->user()->can('view go-rewrites')) {
            $array['go_rewrites']['total'] = GoRewrite::query()->count();
            $array['go_rewrites']['active'] = GoRewrite::active()->count();
            $array['go_rewrites']['link'] = route('admincp.go-rewrites.index');
        }

        if (auth()->user()->can('view loantypes')) {
            $array['loantypes']['total'] = LoanTypes::query()->count();
            $array['loantypes']['active'] = LoanTypes::active()->count();
            $array['loantypes']['link'] = route('admincp.loantypes.index');
        }

        return $array;
    }

    protected function calcPercentage(array $array, string $type): string
    {
        if (!in_array($type, ['clicks', 'commissions', 'visitors'])) {
            throw new InvalidArgumentException('Cannot calculate percentage for the passed type: ' . $type);
        }

        if ($array[$type]['yesterday'] == 0) {
            return number_format(($array[$type]['today'] - $array[$type]['yesterday']) * 100, 2);
        }

        return number_format(($array[$type]['today'] - $array[$type]['yesterday']) / $array[$type]['yesterday'] * 100, 2);
    }

    protected function calcColorStyling(array $array, string $type): string
    {
        if (!in_array($type, ['clicks', 'commissions', 'visitors'])) {
            throw new InvalidArgumentException('Cannot calculate styling for the passed type: ' . $type);
        }

        if ($array[$type]['today'] < $array[$type]['yesterday']) {
            return 'text-red-500';
        } elseif ($array[$type]['today'] == $array[$type]['yesterday']) {
            return 'text-gray-500';
        }

        return 'text-green-500';
    }
}
