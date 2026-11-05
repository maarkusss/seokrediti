<?php

namespace App\Http\Controllers\Admincp;

use App\Http\Controllers\Controller;
use App\Models\Admincp\AdminLogs;
use App\Models\Admincp\ConnectedRoutes;
use Illuminate\Http\Request;

class ConnectedUrlsController extends Controller
{
    /**
     * Show the questions index page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admincp.connected-urls.index')->with([
            'connectedUrls' => $this->getAllUrls(),
        ]);
    }

    /**
     * Show the lenders edit page.
     */
    public function create()
    {
        return view('admincp.connected-urls.add');
    }

    /**
     * Create new user
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'urlFrom' => 'required|url',
            'urlTo' => 'required|url',
        ]);

        $connectedRoute = new ConnectedRoutes();

        $connectedRoute->url_from = $data['urlFrom'];
        $connectedRoute->url_to = $data['urlTo'];

        $connectedRoute->save();

        $connectedRoute = new ConnectedRoutes();

        $connectedRoute->url_from = $data['urlTo'];
        $connectedRoute->url_to = $data['urlFrom'];

        $connectedRoute->save();

        AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Added connected route "' . $data['urlFrom'] . ' - ' . $data['urlTo'] . '"']);

        return redirect()->route('admincp.connected-urls.index')->with([
            'success' => 'Connected route has been added!',
        ]);
    }

    /**
     * Show the questions edit page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        return view('admincp.connected-urls.edit')->with([
            'connectedUrl' => $this->getCurrentConnectedUrl($id),
        ]);
    }

    public function update($id, Request $request)
    {
        $connectedUrl = $this->getCurrentConnectedUrl($id);

        switch ($request->input('action')) {
            case 'delete':
                $connectedUrl->delete();

                AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Deleted connected url "' . $request->url_from . '"']);

                return redirect(route('admincp.connected-urls.index'))->with('success', 'Connected url deleted!');
                break;

            case 'save':
                $data = $request->validate([
                    'urlFrom' => 'required|url',
                    'urlTo' => 'required|url',
                ]);

                $connectedUrl->update([
                    'url_from' => $data['urlFrom'],
                    'url_to' => $data['urlTo'],
                ]);

                AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Updated connected url "' . $request->url_from . '"']);

                return redirect(route('admincp.connected-urls.index'))->with('success', 'Connected url updated!');

                break;
        }
    }

    public function getAllUrls()
    {
        $urls = ConnectedRoutes::get();

        return $urls;
    }

    public function getCurrentConnectedUrl($id)
    {
        $url = ConnectedRoutes::where('id', $id)->first();

        return $url;
    }
}
