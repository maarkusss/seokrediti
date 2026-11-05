<?php

namespace App\Http\Controllers\Admincp\Consumers;

use App\Http\Controllers\Controller;
use App\Models\Admincp\AdminLogs;
use App\Models\Admincp\Consumers\Consumers;

class IndexController extends Controller
{
    /**
     * Show the lenders index page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admincp.consumers.index')->with([
            'consumers' => $this->getAllConsumers(),
        ]);
    }

    /**
     * Return all consumers
     * @return mixed
     */
    public function getAllConsumers()
    {
        if (request()->search) {
            return Consumers::where('email', request()->search)->paginate(50);
        }

        return Consumers::paginate(50);
    }

    public function destroy($id)
    {
        Consumers::where('id', $id)->delete();

        AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Deleted consumer "' . $id . '"']);

        return redirect()->back()->with('success', 'Consumer deleted!');
    }
}
