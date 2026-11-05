<?php

namespace App\Actions\Hoi\Email;

use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateUserNameInHoiEmailProject
{
    use AsAction;

    /**
     * Update user's name in Hoi e-mail project.
     *
     * @param  string $email
     * @param  string $name
     * @param  bool $active_value
     * @return void
     */
    public function handle(string $email, string $name, bool $active_value): void
    {
        if (env('HOI_USER_TOKEN') && env('HOI_EMAIL_PROJECT_TOKEN')) {
            Http::withHeaders([
                'usertoken' => env('HOI_USER_TOKEN'),
                'projecttoken' => env('HOI_EMAIL_PROJECT_TOKEN'),
            ])->put('https://hoi.fm/api/mail/' . $email, [
                'name' => $name,
                'active' => $active_value,
            ]);
        }
    }
}
