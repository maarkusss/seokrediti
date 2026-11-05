<?php

namespace App\Http\Controllers\Admincp\RedirectLinks;

use App\Http\Controllers\Controller;
use App\Models\Admincp\RedirectLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('admincp.redirect-links.index')->with([
            'redirect_links' => RedirectLink::orderBy('url_to', 'desc')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admincp.redirect-links.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'url_from' => ['required', 'string', 'max:191'],
            'url_to' => ['required', 'string', 'max:191'],
        ]);

        RedirectLink::create([
            'url_from' => $validated['url_from'],
            'url_to' => $validated['url_to'],
        ]);

        return redirect(route('admincp.redirect-links.index'))->with([
            'success' => 'Redirect link has been added!',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  RedirectLink $redirect_link
     * @return View
     */
    public function edit(RedirectLink $redirect_link): View
    {
        return view('admincp.redirect-links.edit')->with([
            'redirect_link' => $redirect_link,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  RedirectLink $redirect_link
     * @return RedirectResponse
     */
    public function update(Request $request, RedirectLink $redirect_link): RedirectResponse
    {
        $validated = $request->validate([
            'url_from' => ['required', 'string', 'max:191'],
            'url_to' => ['required', 'string', 'max:191'],
        ]);

        $redirect_link->update([
            'url_from' => $validated['url_from'],
            'url_to' => $validated['url_to'],
        ]);

        return redirect(route('admincp.redirect-links.index'))->with([
            'success' => 'Redirect link has been updated!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RedirectLink $redirect_link
     * @return RedirectResponse
     */
    public function destroy(RedirectLink $redirect_link): RedirectResponse
    {
        $redirect_link->delete();

        return redirect(route('admincp.redirect-links.index'))->with([
            'success' => 'Redirect link has been deleted!',
        ]);
    }
}
