<?php

namespace App\Actions\Hoi\Email;

use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;

class ChangeUserActivityInHoiEmailProject
{
    use AsAction;

    /**
     * Update user's active status in Hoi e-mail project.
     *
     * @param  string $email
     * @param  bool $active_value
     * @return void
     */
    public function handle(string $email, bool $active_value): void
    {
        if (env('HOI_USER_TOKEN') && env('HOI_EMAIL_PROJECT_TOKEN')) {
            Http::withHeaders([
                'usertoken' => env('HOI_USER_TOKEN'),
                'projecttoken' => env('HOI_EMAIL_PROJECT_TOKEN'),
            ])->put('https://hoi.fm/api/mail/' . $email, [
                'active' => $active_value,
            ]);
        }
    }
}
