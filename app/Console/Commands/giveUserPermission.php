<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class giveUserPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:give';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Give permission to first user';

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
        $user = User::where('id', 1)->firstOrFail();

        if ($user) {
            $array[] = 'view admins';
            $array[] = 'edit admins';
            $array[] = 'delete admins';
            $array[] = 'add admins';

            $array[] = 'view bans';
            $array[] = 'edit bans';
            $array[] = 'delete bans';
            $array[] = 'add bans';

            $array[] = 'view connectedCountries';
            $array[] = 'edit connectedCountries';
            $array[] = 'delete connectedCountries';
            $array[] = 'add connectedCountries';

            $array[] = 'view settings';
            $array[] = 'edit settings';
            $array[] = 'edit settings config';

            $array[] = 'view sections';
            $array[] = 'edit sections';
            $array[] = 'delete sections';
            $array[] = 'add sections';

            $array[] = 'view visitors';

            $array[] = 'view loantypes';
            $array[] = 'edit loantypes';
            $array[] = 'delete loantypes';
            $array[] = 'add loantypes';

            $array[] = 'view consumers';
            $array[] = 'delete consumers';

            $array[] = 'view lenders';
            $array[] = 'edit lenders';
            $array[] = 'delete lenders';
            $array[] = 'add lenders';

            $array[] = 'view faqs';
            $array[] = 'edit faqs';
            $array[] = 'delete faqs';
            $array[] = 'add faqs';

            $array[] = 'view statistics';

            $array[] = 'view adminlogs';

            $array[] = 'view images';

            $array[] = 'view history';

            $array[] = 'edit permissions';

            $array[] = 'view reviews';

            $array[] = 'view connected urls';

            $array[] = 'view redirectlinks';
            $array[] = 'add redirectlinks';
            $array[] = 'edit redirectlinks';
            $array[] = 'delete redirectlinks';

            foreach ($array as $perm) {
                if (!Permission::where('name', $perm)->exists()) {
                    Permission::create(['name' => $perm]);
                    $user->givePermissionTo($perm);
                }
            }
        }
    }
}
