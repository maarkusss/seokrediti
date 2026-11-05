<?php

namespace App\Http\Controllers;

use App\Models\Admincp\Settings;
use App\Models\Reviews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Reviews::with('lender')
            ->where('lang', app()->getLocale())
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        return view('admincp.reviews.index')->with([
            'reviews' => $reviews,
        ]);
    }

    public function create(Request $request)
    {
        Log::info('Review POST saņemts', [
            'lenderID' => $request->lenderID,
            'all' => $request->all(),
        ]);

        if ($request->has('comment') && !$request->has('text')) {
            $request->merge(['text' => $request->input('comment')]);
        }

        // Drošības koda (CAPTCHA) pārbaude
        $captchaInput = trim((string)$request->input('captcha'));
        $captchaExpected = (string)session('captcha_code');

        if (empty($captchaInput) || empty($captchaExpected) || strcasecmp($captchaInput, $captchaExpected) !== 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nepareizs drošības kods! Lūdzu, ievadiet attēlā redzamo kodu vēlreiz.');
        }

        // Notīra izmantoto kodu, lai to nevarētu izmantot atkārtoti
        session()->forget('captcha_code');

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'text' => 'required|string|max:2000',
        ]);

        $review = new Reviews();

        $review->lender_id = $request->lenderID;
        $review->name = $data['name'];
        $review->rating = $data['rating'];
        $review->text = $data['text'];
        $review->lang = app()->getLocale();
        $review->ip = request()->ip();

        $review->save();

        return redirect()->back()->with('success', __('Review has been submitted!'));
    }

    public function delete($id)
    {
        Reviews::where('id', $id)->delete();

        return redirect(route('admincp.reviews.index'))->with('success', 'Review has been deleted!');
    }
}
