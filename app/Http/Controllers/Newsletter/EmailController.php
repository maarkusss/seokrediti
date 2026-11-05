<?php

namespace App\Http\Controllers\Newsletter;

use App\Actions\Hoi\Email\CreateUserInHoiEmailProject;
use App\Actions\Hoi\Email\DeleteUserFromHoiEmailProject;
use App\Http\Controllers\Controller;
use App\Models\Admincp\Consumers\Consumers;
use App\Models\ConsumerHoiToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Admincp\Consumers\ConsumersHoiToken;

class EmailController extends Controller
{
    protected function validator(Request $request)
    {
        return Validator::make($request->input(), [
            'email' => ['required', 'email', 'max:191'],
        ]);
    }

    /**
     * Create a new user.
     *
     * @param  Request $request
     * @return RedirectResponse
     */
    protected function index(Request $request): RedirectResponse
    {
        $data = $this->validator($request);
        $hoi_token = Str::random(6);
        $fingerprint = $request->cookie('fingerprint');

        if ($data->fails()) {
            return redirect()->to(url()->previous() . '#newsletter_form')->with('error', __('The e-mail was entered incorrectly!'));
        }

        if (!null == ((Consumers::where('email', $data->validate()['email']))->first())) {
            $consumer = $this->update($data, $hoi_token, $fingerprint);

            // Delete user from Hoi Email project
            DeleteUserFromHoiEmailProject::run($consumer->email);

            // Post the user to Hoi Email project
            CreateUserInHoiEmailProject::run($consumer);
        } else {
            $consumer = $this->create($data, $hoi_token, $fingerprint);

            // Create a new ConsumerHoiToken
            ConsumersHoiToken::create([
                'consumer_id' => $consumer->id,
                'hoi_token' => $hoi_token,
            ]);

            // Post the user to Hoi Email project
            CreateUserInHoiEmailProject::run($consumer);
        }

        return redirect()->to(url()->previous() . '#newsletter_form')->with('success', __('Subscription successful!'));
    }

    /**
     * Create a new user.
     *
     * @param  $data, $hoi_token
     * @return Consumers
     */
    protected function create($data, $hoi_token, $fingerprint): Consumers
    {
        // Create a new User
        $consumer = Consumers::create([
            'email' => $data->validate()['email'],
            'country' => 'lv',
            'hoi_token' => $hoi_token,
            'fingerprint' => $fingerprint,
            'visitor_id' => request()->cookie('visitor_id'),
            'utm_source' => request()->cookie('utm_source'),
            'utm_campaign' => request()->cookie('utm_campaign'),
            'utm_medium' => request()->cookie('utm_medium'),
            'utm_content' => request()->cookie('utm_content'),
            'gclid' => request()->cookie('gclid'),
            'ip' => request()->ip(),
        ]);

        return $consumer;
    }

    /**
     * Updating a existing user.
     *
     * @param  $data, $hoi_token
     * @return Consumers
     */
    protected function update($data, $hoi_token, $fingerprint): Consumers
    {
        // Update User
        $consumer = Consumers::where('email', $data->validate()['email'])->update([
            'country' => App::getLocale(),
            'hoi_token' => $hoi_token,
            'fingerprint' => $fingerprint,
            'visitor_id' => request()->cookie('visitor_id'),
            'utm_source' => request()->cookie('utm_source'),
            'utm_campaign' => request()->cookie('utm_campaign'),
            'utm_medium' => request()->cookie('utm_medium'),
            'utm_content' => request()->cookie('utm_content'),
            'gclid' => request()->cookie('gclid'),
            'ip' => request()->ip(),
        ]);

        $consumer = Consumers::where('email', $data->validate()['email'])->first();

        return $consumer;
    }
}
