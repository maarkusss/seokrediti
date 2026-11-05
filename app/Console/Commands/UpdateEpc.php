<?php

namespace App\Console\Commands;

use App\Models\Admincp\Commissions;
use App\Models\Admincp\Lenders\Lenders;
use App\Models\Clicks;
use Illuminate\Console\Command;

class UpdateEpc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'epc:update';

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
        $lenders = Lenders::get();

        foreach ($lenders as $lender) {
            echo 'Lender - ' . $lender->name . ".\r\n";

            if ($lender->epc_before || $lender->daily_epc) {
                $date_until = date('Y-m-d 23:59:59', strtotime($lender->daily_epc_before . ' days'));
                $date_from = date('Y-m-d 00:00:00', strtotime('-' . $lender->daily_epc . ' days', strtotime($date_until)));

                $commissions_count = Commissions::where('lender_id', $lender->id)->where('status', 'A')
                    ->where('commission_date', '>=', $date_from)
                    ->where('commission_date', '<=', $date_until)
                    ->sum('commission');

                $click_count = Clicks::where('lender_id', $lender->id)
                    ->where('created_at', '>=', $date_from)
                    ->where('created_at', '<=', $date_until)
                    ->distinct('visitor_id')
                    ->count();

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
                ];

                $new_lenders_arr = $this->msort('epc', SORT_DESC, SORT_NUMERIC, 'commissions_count', SORT_DESC, SORT_NUMERIC, 'clicks_count', SORT_DESC, SORT_NUMERIC, $lenders_arr);

                $i = 1;
                foreach ($new_lenders_arr as $key => $value) {
                    Lenders::where('id', $value['id'])->update([
                        'position' => $i,
                        'epc' => $value['epc'],
                        'earnings' => $value['commissions_count'],
                        'clicks' => $value['clicks_count'],
                    ]);
                    $i++;
                }
            }

            echo 'Commission count = ' . $commissions_count . ".\r\n";
            echo 'EPC = ' . $epc . ".\r\n";
            echo "--------------\r\n";
        }
    }
}
