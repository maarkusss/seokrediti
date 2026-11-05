<?php

namespace App\Http\Controllers\Admincp\Bans;

use App\Http\Controllers\Controller;
use App\Models\Admincp\AdminLogs;
use App\Models\Admincp\Bans\Bans;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Show the lenders index page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admincp.bans.index')->with([
            'bans' => $this->getAllBans(),
        ]);
    }

    /**
     * Show the lenders edit page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('admincp.bans.add');
    }

    /**
     * Add new ban
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'ip' => 'required',
            'reason' => 'required',
        ]);

        Bans::create([
            'ip' => $data['ip'],
            'reason' => $data['reason'],
        ]);

        AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Added ban "' . $data['ip'] . '"']);

        return redirect(route('admincp.bans.index'))->with('success', 'Ban has been added!');
    }

    /**
     * Show the bans edit page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        return view('admincp.bans.edit')->with([
            'ban' => $this->getCurrentBan($id),
        ]);
    }

    /**
     * Edit current ban
     */
    public function update($id, Request $request)
    {
        $ban = $this->getCurrentBan($id);

        $data = $request->validate([
            'ip' => 'required',
            'reason' => 'required',
        ]);

        $ban->update([
            'ip' => $data['ip'],
            'reason' => $data['reason'],
        ]);

        AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Updated ban "' . $ban->ip . '"']);

        return redirect()->back()->with('success', 'Ban has been updated!');
    }

    public function destroy($id)
    {
        $ban = $this->getCurrentBan($id);

        $ban->delete();

        AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Removed ban "' . $ban->ip . '"']);

        return redirect(route('admincp.bans.index'))->with('success', 'Ban has been removed!');
    }

    /**
     * Show ban by id
     */
    public function getCurrentBan($id)
    {
        return Bans::findOrFail($id);
    }

    /**
     * Get all bans
     */
    public function getAllBans()
    {
        return Bans::paginate(20);
    }
}
