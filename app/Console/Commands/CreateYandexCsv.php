<?php

namespace App\Console\Commands;

use App\Models\Admincp\Commissions;
use CURLFile;
use Illuminate\Console\Command;

class CreateYandexCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yandexcsv:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates Yandex CSV file';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $commissions = Commissions::query()
            ->where('status', 'A')
            ->where('commission', '>', 0)
            ->where('gclid', '!=', '')
            ->where('utm_source', 'yandex')
            ->where('commission_date', '>=', date('Y-m-d', strtotime('-1 days')))
            ->orderBy('created_at', 'DESC')
            ->get();

        if ($commissions->count() > 0) {

            $fp = fopen('public/yandex.csv', 'w');

            $list = array(
                array('Yclid', 'Target', 'DateTime', 'Price', 'Currency')
            );

            foreach ($list as $fields) {
                fputcsv($fp, $fields);
            }

            foreach ($commissions as $row) {
                fputcsv($fp, array($row->gclid, "Sale", strtotime($row->commission_date . " +4 hours"), $row->commission, "EUR"));
            }

            fclose($fp);

            // Specify the tag number
            $counter = config('services.yandex.metrika_counter');
            // Specify the OAuth token
            $token = config('services.yandex.metrika_token');
            // Specify the user ID type: CLIENT_ID, USER_ID or YCLID
            $client_id_type = config('services.yandex.client_id_type');
            $comment = "automatic API";

            $curl = curl_init("https://api-metrika.yandex.ru/management/v1/counter/$counter/offline_conversions/upload?client_id_type=$client_id_type&comment=" . urlencode($comment));

            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array('file' => new CURLFile(public_path('yandex.csv'))));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: multipart/form-data", "Authorization: OAuth $token"));

            $result = curl_exec($curl);
            curl_close($curl);

            echo "Need submit to API " . count($commissions) . " and response are: " . $result;
        } else {
            echo "Empty commissions";
        }
    }
}
