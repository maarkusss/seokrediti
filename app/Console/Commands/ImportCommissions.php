<?php

namespace App\Console\Commands;

use App\Models\Admincp\Commissions;
use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\Settings;
use App\Models\LenderCampaign;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ImportCommissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commissions:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports commissions from Goodaff';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $prefix = Settings::where('name', 'prefix')->first();

        $commissions_link = Settings::where('name', 'commissions_link')->pluck('value');
        $commissions_id = Settings::where('name', 'commissions_id')->pluck('value');
        $commissions_token = Settings::where('name', 'commissions_token')->pluck('value');

        $client = new Client();

        // Checks if admincp settings has commissions link if doesn't give hardcoded
        if (isset($commissions_link[0])) {
            $commissions_link = $commissions_link[0];
        } else {
            $commissions_link = 'https://api.goodaff.com/api/v1/commissions';
        }

        //Checks if admincp settings has commissions id and token if doesn't give hardcoded
        if (isset($commissions_id[0]) && isset($commissions_token[0])) {
            $goodaff_users = [
                "user" => $commissions_id[0],
                "token" => $commissions_token[0],
            ];
        } else {
            $goodaff_users = [
                "user" => "2",
                "token" => "61ccf85eed1ffa77e20e25871de79ef8",
            ];
        }

        $response = $client->request('POST', $commissions_link, ['query' => [
            'user' => $goodaff_users['user'],
            'token' => $goodaff_users['token'],
            'datefrom' => date("Y-m-d", strtotime("-7 days")),
            'dateto' => date("Y-m-d"),
            's1' => $prefix?->value ?? 'eiro'
        ]]);



        $json = json_decode($response->getBody());

        if ($json) {
            $lenderid_arr = LenderCampaign::query()
                ->orderBy('id', 'ASC')
                ->pluck('name', 'id')
                ->toArray();

            foreach ($json as $id => $data) {
                $commission = Commissions::where('uid', $data->id)->first();
                if ($data->campaign_id && array_key_exists($data->campaign_id, $lenderid_arr)) {
                    $explode_name = explode(' - ', $lenderid_arr[$data->campaign_id]);
                    if ($commission) {
                        if ($commission->status != $data->status) {
                            $commission->update([
                                'status' => $data->status,
                                'commission_date' => date('Y-m-d H:i:s'),
                            ]);
                        }
                    } else {
                        if (array_key_exists($data->campaign_id, $lenderid_arr) && $data->commission > 0) {
                            $lender = Lenders::where('name', 'like', $explode_name[0] . '%')->first();
                            if ($lender) {
                                Commissions::create([
                                    'visitor_id' => $data->visitor_id,
                                    'uid' => $data->id,
                                    'transaction_id' => $data->transaction_id,
                                    'commission' => $data->commission,
                                    'lender_id' => $lender->id,
                                    'referrer' => $data->click_referer,
                                    'ip' => $data->click_ip,
                                    'status' => $data->status,
                                    'utm_campaign' => $data->s2,
                                    'utm_source' => $data->s5,
                                    'gclid' => $data->s3,
                                    'commission_date' => $data->created_date,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
