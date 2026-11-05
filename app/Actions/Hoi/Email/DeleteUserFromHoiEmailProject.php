<?php

namespace App\Actions\Hoi\Email;

use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteUserFromHoiEmailProject
{
    use AsAction;

    /**
     * Delete a user from Hoi e-mail project.
     *
     * @param  string $email
     * @return void
     */
    public function handle(string $email): void
    {
        if (env('HOI_USER_TOKEN') && env('HOI_EMAIL_PROJECT_TOKEN')) {
            Http::withHeaders([
                'usertoken' => env('HOI_USER_TOKEN'),
                'projecttoken' => env('HOI_EMAIL_PROJECT_TOKEN'),
            ])->delete('https://hoi.fm/api/mail/' . $email);
        }
    }
}
