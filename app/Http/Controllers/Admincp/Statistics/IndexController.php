<?php

namespace App\Http\Controllers\Admincp\Statistics;

use App\Http\Controllers\Controller;
use App\Models\Admincp\Commissions;
use App\Models\Admincp\Lenders\Lenders;
use App\Models\Clicks;
use App\Models\Visitors\Visitors;

class IndexController extends Controller
{
    /**
     * Show the lenders index page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admincp.statistics.index')->with([
            'chart_data' => $this->getChartData(),
            'lenders' => $this->getLenders(),
        ]);
    }

    public function getChartData()
    {
        $rawPicker = request()->datePicker;

        if ($rawPicker) {
            $datePicker = explode(' to ', (string) $rawPicker);
            if (count($datePicker) == 1) {
                $datePicker[1] = $datePicker[0];
            }
            $from = date('Y-m-d', strtotime($datePicker[0]));
            $to = date('Y-m-d', strtotime($datePicker[1]));
            if (strtotime($from) > strtotime($to)) {
                [$from, $to] = [$to, $from];
            }
        } else {
            $from = date('Y-m-d', strtotime('-30 days'));
            $to = date('Y-m-d');
        }

        $chart = [
            'visitors' => [],
            'clicks' => [],
            'commissions' => [],
        ];

        for ($i = strtotime($from); $i <= strtotime($to); $i = $i + 86400) {
            $dayKey = date('Y-m-d', $i);
            $start = date('Y-m-d 00:00:00', $i);
            $end = date('Y-m-d 23:59:59', $i);

            $chart['visitors'][$dayKey] =
                Visitors::where('created_at', '>=', $start)
                    ->where('created_at', '<=', $end)
                    ->count();

            $chart['clicks'][$dayKey] =
                Clicks::where('created_at', '>=', $start)
                    ->where('created_at', '<=', $end)
                    ->count();

            $chart['commissions'][$dayKey] =
                Commissions::where('commission_date', '>=', $start)
                    ->where('commission_date', '<=', $end)
                    ->sum('commission');
        }

        return $chart;
    }

    public function getLenders()
    {
        $rawPicker = request()->datePicker;
        $array = [];

        if ($rawPicker) {
            $datePicker = explode(' to ', (string) $rawPicker);
            if (count($datePicker) == 1) {
                $datePicker[1] = $datePicker[0];
            }
            $from = date('Y-m-d 00:00:00', strtotime($datePicker[0]));
            $to = date('Y-m-d 23:59:59', strtotime($datePicker[1]));
            if (strtotime($from) > strtotime($to)) {
                [$from, $to] = [$to, $from];
            }
        } else {
            $from = date('Y-m-d 00:00:00', strtotime('-30 days'));
            $to = date('Y-m-d 23:59:59');
        }

        foreach (Lenders::orderBy('name')->get() as $lender) {
            $clicks = Clicks::where('lender_id', $lender->id)->where('created_at', '>=', $from)->where('created_at', '<=', $to)->count();
            $commissions = Commissions::where('lender_id', $lender->id)->where('status', 'A')->where('commission_date', '>=', $from)->where('commission_date', '<=', $to)->sum('commission');
            $epc = $clicks == 0 ? 0 : $commissions / $clicks;

            $array[$lender->id]['name'] = $lender->name;
            $array[$lender->id]['confirmed'] = $lender->commissions->where('status', 'A')->where('commission_date', '>=', $from)->where('commission_date', '<=', $to)->where('commission', '>', 0)->sum('commission');
            $array[$lender->id]['waiting'] = $lender->commissions->where('status', 'P')->where('commission_date', '>=', $from)->where('commission_date', '<=', $to)->where('commission', '>', 0)->sum('commission');
            $array[$lender->id]['clicks'] = $clicks;
            $array[$lender->id]['commssions'] = $commissions;
            $array[$lender->id]['epc'] = $epc;

            if ($epc == 0) {
                $style = 'color:grey';
            } elseif ($epc < 0.15) {
                $style = 'color:red;font-weight:bold;';
            } elseif ($epc < 0.25) {
                $style = 'color:red;';
            } elseif ($epc < 0.35) {
                $style = 'color:orange;';
            } elseif ($epc < 0.5) {
                $style = 'color:green;';
            } else {
                $style = 'color:green;font-weight:bold;';
            }

            $array[$lender->id]['style'] = $style;
        }

        return $array;
    }
}
