<?php

namespace App\Console\Commands;

use App\Models\Admincp\Commissions;
use App\Models\Admincp\Lenders\Lenders;
use Illuminate\Console\Command;

class CreateCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates CSV file for Adword';

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
        $fp = fopen('public/komisijas_adwordam.csv', 'w');

        $list = [
            ['Parameters:TimeZone=Europe/Riga'],
            ['Google Click ID', 'Conversion Name', 'Conversion Time', 'Conversion Value', 'Conversion Currency'],
        ];
        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }

        $commissions = Commissions::where('status', 'A')
            ->where('gclid', '!=', '')
            ->where('commission', '>', 0)
            ->where('gclid', '!=', 'webpush')
            ->where('gclid', '!=', 'email')
            ->where('gclid', '!=', 'sms-automatic')
            ->where('gclid', '!=', 'email-automatic')
            ->where('gclid', 'not like', 'goodaff%')
            ->where('commission_date', '>=', date('Y-m-d 00:00:00', strtotime('-3 days')))
            ->where('commission_date', '>=', '2021-01-22 13:00:00')
            ->orderBy('id', 'DESC')
            ->get();

        foreach ($commissions as $row) {
            $lender = Lenders::where('id', $row->lender_id)->first();

            $lender_name = explode('.', $lender->name);

            fputcsv($fp, [$row->gclid, $lender_name[0], $row->commission_date, $row->commission, 'EUR']);
        }

        fclose($fp);
    }
}
