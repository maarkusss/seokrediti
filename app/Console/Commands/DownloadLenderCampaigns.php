<?php

namespace App\Console\Commands;

use App\Models\LenderCampaign;
use App\Services\GoodAff\GoodAffService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class DownloadLenderCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lendercampaigns:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download LenderCampaign data from Goodaff';

    /**
     * @var GoodAffService
     */
    protected $goodaff_service;

    public function __construct()
    {
        $this->goodaff_service = App::make(GoodAffService::class);

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $campaign_names = $this->goodaff_service->getCampaignNames();

        if (empty($campaign_names)) {
            $this->error('No results returned');
            return Command::FAILURE;
        }

        $this->info('Retrieved details on ' . count($campaign_names) . ' LenderCampaigns');

        foreach ($campaign_names as $id => $details) {
            $this->comment('Updating LenderCampaign ' . $id);

            LenderCampaign::query()
                ->updateOrInsert([
                    'id' => $id,
                ], $details);
        }

        return Command::SUCCESS;
    }
}
