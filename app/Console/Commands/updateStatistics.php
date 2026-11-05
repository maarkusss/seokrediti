<?php

namespace App\Console\Commands;

use App\Http\Middleware\Visitors;
use App\Models\Admincp\Commissions;
use App\Models\Admincp\Statistics;
use App\Models\Clicks;
use Illuminate\Console\Command;

class updateStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistics:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Statistics update';

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
        $visitors = Visitors::whereDate('created_at', date('Y-m-d'))->count();
        $clicks = Clicks::whereDate('created_at', date('Y-m-d'))->count();
        $commissions = Commissions::where('commission_date', date('Y-m-d'))->count();

        Statistics::updateOrCreate([
            'date' => date('d-m-Y'),
        ], [
            'clicks' => $clicks,
            'visitors' => $visitors,
            'commissions' => $commissions,
        ]);
    }
}
