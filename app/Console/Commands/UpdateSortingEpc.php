<?php

namespace App\Console\Commands;

use App\Models\Admincp\Commissions;
use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\Lenders\LendersSorting;
use App\Models\Admincp\Lenders\LendersSortingEpc;
use App\Models\Admincp\Lenders\Visits;
use App\Models\Admincp\Lenders\VisitsSorting;
use App\Models\Admincp\Settings;
use App\Models\Clicks;
use Illuminate\Console\Command;

class UpdateSortingEpc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sorting:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return bool|mixed
     */
    public function msort()
    {
        $params = func_get_args();
        $array = array_pop($params);

        if (!is_array($array)) {
            return false;
        }

        $multisort_params = [];
        foreach ($params as $i => $param) {
            if (is_string($param)) {
                ${"param_$i"} = [];
                foreach ($array as $index => $row) {
                    ${"param_$i"}[$index] = $row[$param];
                }
            } else {
                ${"param_$i"} = $params[$i];
            }

            $multisort_params[] = &${"param_$i"};
        }
        $multisort_params[] = &$array;

        call_user_func_array('array_multisort', $multisort_params);

        return $array;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sortings = LendersSorting::orderBy('updated_at', 'asc')->take(10)->get();
        $lenders = Lenders::get();

        foreach ($sortings as $sorting) {
            $i = 1;
            $all_commissions_count = 0;
            $all_clicks_count = 0;
            $lenders_arr = [];

            $individual_days_epc = Settings::where('name', 'individual_days_epc')->first()?->value ?? 30;

            //$clicks = Clicks::where('utm_campaign', $sorting->campaign_name)->distinct('visitor_id')->count();

            echo 'Sorting - ' . $sorting->campaign_name . ".\r\n";

            foreach ($lenders as $lender) {
                echo 'Lender - ' . $lender->name . ".\r\n";

                $date_until = date('Y-m-d 23:59:59', strtotime($lender->daily_epc_before . ' days'));
                $date_from = date('Y-m-d 00:00:00', strtotime('-' . $individual_days_epc . ' days', strtotime($date_until)));

                echo 'Date until - ' . $date_until . ".\r\n";
                echo 'Date from - ' . $date_from . ".\r\n";

                $commissions_count = Commissions::where('lender_id', $lender->id)->where('status', 'A')
                    ->where('commission_date', '>=', $date_from)
                    ->where('commission_date', '<=', $date_until)
                    ->where('utm_campaign', $sorting->campaign_name)
                    ->sum('commission');

                $all_commissions_count += $commissions_count;

                $click_count = Clicks::where('lender_id', $lender->id)
                    ->where('created_at', '>=', $date_from)
                    ->where('created_at', '<=', $date_until)
                    ->where('utm_campaign', $sorting->campaign_name)
                    ->distinct('visitor_id')
                    ->count();

                $all_clicks_count += $click_count;

                if ($lender->guaranteed_epc) {
                    $epc = $lender->guaranteed_epc;
                } else {
                    $epc = ($click_count ? $commissions_count / $click_count : 0);
                }

                $lenders_arr[] = [
                    'id' => $lender->id,
                    'name' => $lender->name,
                    'commissions_count' => $commissions_count,
                    'clicks_count' => $click_count,
                    'epc' => $epc,
                    'lender_epc' => $lender->epc,
                ];
            }

            $new_lenders_arr = $this->msort('epc', SORT_DESC, SORT_NUMERIC, 'lender_epc', SORT_DESC, SORT_NUMERIC, 'clicks_count', SORT_DESC, SORT_NUMERIC, $lenders_arr);

            foreach ($new_lenders_arr as $key => $value) {
                LendersSortingEpc::updateOrCreate([
                    'sorting_id' => $sorting->id,
                    'lender_id' => $value['id'],
                ], [
                    'position' => $i,
                    'epc' => $value['epc'],
                    'clicks' => $value['clicks_count'],
                    'earnings' => $value['commissions_count'],
                ]);

                $i++;
            }

            $sorting->update([
                'clicks' => $all_clicks_count,
                'average_epc' => $all_clicks_count ? $all_commissions_count / $all_clicks_count : 0,
            ]);
        }

        $sortings = LendersSorting::where('clicks', '<', 500)->get();

        foreach ($sortings as $sorting) {
            LendersSortingEpc::where('sorting_id', $sorting->id)->delete();

            $sorting->delete();
        }

        if (date('H') == 2 && date('i') < 10) {
            Visits::where('created_at', '<', date('Ymd', strtotime('-10 days')))->delete();
            VisitsSorting::where('created_at', '<', date('Ymd', strtotime('-10 days')))->delete();
        }
    }
}
