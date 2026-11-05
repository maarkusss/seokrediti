<?php

namespace App\Http\Controllers\Unsubscribe;

use App\Actions\Hoi\Email\ChangeUserActivityInHoiEmailProject;
use App\Http\Controllers\Controller;
use App\Models\Admincp\Consumers\Consumers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class IndexController extends Controller
{
    /**
     * Display the e-mail unsubscribe view.
     *
     * @return View
     */
    public function index(): View
    {
        return view('unsubscribe');
    }

    /**
     * Update the consumer email_notification column, activity in Hoi e-mail project.
     *
     * @param  Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:191'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', __('E-mail entered incorrectly!'));
        }

        $consumer = Consumers::where('email', $request->email)->first();

        // If there's a consumer, set the e-mail notifications to false
        // and update the consumers's activity in Hoi e-mail project to false
        if ($consumer) {
            $consumer->update(['email_notifications' => false]);
            ChangeUserActivityInHoiEmailProject::run($consumer->email, false);

            return redirect()->back()->with('success', __('E-mail has been successfully unsubscribed from e-mail notifications!'));
        } else {
            return redirect()->back()->with('error', __('E-mail not found!'));
        }
    }
}
