<?php

namespace App\Listeners;

use App\Mail\LenderOffer;
use Illuminate\Support\Facades\Mail;

class AddEmailsToHoiAfterConfirmation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (env('APP_ENV') == 'production') {
            if (env('HOI_EMAIL_PROJECT_TOKEN')) {
                $client = new \GuzzleHttp\Client();

                $client->request('POST', 'https://hoi.fm/api/mail', ['query' => [
                    'name' => $event->user->first_name,
                    'mail' => $event->user->email,
                    'registration' => 1,
                ], 'headers' => [
                    'usertoken' => env('HOI_USER_TOKEN'),
                    'projecttoken' => env('HOI_EMAIL_PROJECT_TOKEN'),
                ]]);
            }

            if ($event->user->utm_source == 'goodaff' && env('GOODAFF_CAMPAIGN_ID')) {
                $client->request('GET', 'https://postback.goodaff.com', ['query' => [
                    'campaign_id' => env('GOODAFF_CAMPAIGN_ID'),
                    'transaction_id' => $event->user->id,
                    'click_id' => $event->user->utm_content,
                    'type' => 'cpl',
                    'status' => 'A',
                ]]);
            }

            Mail::to($event->user)->send(new LenderOffer($event->user));
        }
    }
}
