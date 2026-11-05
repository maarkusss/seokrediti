<?php

namespace App\Console;

use App\Console\Commands\createCsv;
use App\Console\Commands\CreateYandexCsv;
use App\Console\Commands\DownloadLenderCampaigns;
use App\Console\Commands\GenerateSitemap;
use App\Console\Commands\importCommissions;
use App\Console\Commands\updateEpc;
use App\Console\Commands\updateSortingEpc;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CreateCsv::class,
        GenerateSitemap::class,
        CreateYandexCsv::class,
        ImportCommissions::class,
        UpdateEpc::class,
        UpdateSortingEpc::class,
        DownloadLenderCampaigns::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->minutePaddedSchedule($schedule);
        // $this->defaultSchedule($schedule);
    }

    protected function minutePaddedSchedule(Schedule $schedule)
    {
        $cron_minute = config('app.cron_minute') ?? 0;
        if ($cron_minute < 0 || $cron_minute > 59) {
            throw new \InvalidArgumentException('Please check config("app.cron_minute") value, it must be a valid minute value');
        }

        $every_ten_minutes = [];
        for ($i = $cron_minute; $i < 60; $i += 10) {
            $every_ten_minutes[] = $i;
        }

        $cron_minute_every_10_minutes = implode(',', $every_ten_minutes);
        $cron_minute_padded = str_pad($cron_minute, 2, '0', STR_PAD_LEFT);

        $schedule->command(ImportCommissions::class)
            ->hourlyAt($cron_minute);
        $schedule->command(UpdateEpc::class)
            ->cron($cron_minute_every_10_minutes . ' * * * *');
        $schedule->command(UpdateSortingEpc::class)
            ->hourlyAt($cron_minute);
        $schedule->command(CreateCsv::class)
            ->hourlyAt($cron_minute);
        $schedule->command(CreateYandexCsv::class)
            ->cron($cron_minute . ' 0,12 * * *');
        $schedule->command(GenerateSitemap::class)
            ->dailyAt('00:' . $cron_minute_padded);
        $schedule->command(DownloadLenderCampaigns::class)
            ->dailyAt('10:' . $cron_minute_padded);
    }

    protected function defaultSchedule(Schedule $schedule)
    {
        // $schedule->command('statistics:update')->hourly();
        // $schedule->command('consumers:update')->everyMinute();
        $schedule->command(ImportCommissions::class)
            ->hourly();
        $schedule->command(UpdateEpc::class)
            ->everyTenMinutes();
        $schedule->command(UpdateSortingEpc::class)
            ->hourly();
        $schedule->command(CreateCsv::class)
            ->hourly();
        $schedule->command(CreateYandexCsv::class)
            ->twiceDaily(0, 12);
        $schedule->command(GenerateSitemap::class)
            ->daily();
        $schedule->command(DownloadLenderCampaigns::class)
            ->dailyAt('01:10');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
