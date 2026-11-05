<?php

namespace App\Http\Controllers\Admincp\Lenders;

use App\Http\Controllers\Controller;
use App\Models\Admincp\AdminLogs;
use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\Lenders\LendersSorting;
use App\Models\Admincp\Lenders\LendersSortingEpc;

class IndexController extends Controller
{
    /**
     * Show the lenders index page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admincp.lenders.index')->with([
            'lenders' => $this->getAllLenders(),
            'sortings' => $this->getAllSortings(),
        ]);
    }

    public function getAllLenders()
    {
        $lenders_array = [];

        if (request()->sorting) {
            foreach (LendersSortingEpc::where('sorting_id', request()->sorting)->orderBy('epc', 'DESC')->orderBy('clicks', 'DESC')->get() as $sorting) {
                $sorting->lender->epc = $sorting->epc;
                $sorting->lender->clicks = $sorting->clicks;
                $sorting->lender->earnings = $sorting->earnings;
                $sorting->lender->position = $sorting->position;

                $lenders_array[] = $sorting->lender;
            }
        } else {
            foreach (Lenders::orderBy('guaranteed_epc', 'DESC')->orderBy('position')->orderBy('epc', 'DESC')->get() as $lender) {
                $lenders_array[] = $lender;
            }
        }

        return $lenders_array;
    }

    public function getAllSortings()
    {
        return LendersSorting::orderBy('clicks', 'DESC')->get();
    }

    public function update()
    {
        $arr = request()->position;
        asort($arr);

        $i = 0;

        foreach ($arr as $key => $value) {
            $i++;
            $this->updateLenderPosition($key, $i);
        }

        foreach (request()->frequency as $key => $value) {
            $this->updateLenderFrequency($key, $value);
        }

        foreach (request()->daily_epc as $key => $value) {
            $this->updateLenderDailyEpc($key, $value);
        }

        foreach (request()->daily_epc_before as $key => $value) {
            $this->updateLenderDailyEpcBefore($key, $value);
        }

        foreach (request()->guaranteed_epc as $key => $value) {
            $this->updateLenderGuaranteedEpc($key, $value);
        }

        foreach (request()->active as $key => $value) {
            $this->updateLenderActive($key, $value);
        }

        AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Created all lenders!']);

        return redirect()->back()->with('success', 'Lenders updated!');
    }

    /**
     * @param $id
     * @param $position
     */
    public function updateLenderPosition($id, $value)
    {
        $lender = $this->getLenderById($id);

        $lender->update([
            'position' => $value,
        ]);
    }

    /**
     * @param $id
     * @param $frequency
     */
    public function updateLenderFrequency($id, $value)
    {
        $lender = $this->getLenderById($id);

        $lender->update([
            'frequency' => $value,
        ]);
    }

    /**
     * @param $id
     * @param $value
     */
    public function updateLenderDailyEpc($id, $value)
    {
        $lender = $this->getLenderById($id);

        $lender->update([
            'daily_epc' => $value,
        ]);
    }

    /**
     * @param $id
     * @param $value
     */
    public function updateLenderDailyEpcBefore($id, $value)
    {
        $lender = $this->getLenderById($id);

        $lender->update([
            'daily_epc_before' => $value,
        ]);
    }

    /**
     * @param $id
     * @param $value
     */
    public function updateLenderGuaranteedEpc($id, $value)
    {
        $lender = $this->getLenderById($id);

        $lender->update([
            'guaranteed_epc' => $value,
        ]);
    }

    /**
     * @param $id
     * @param $value
     */
    public function updateLenderActive($id, $value)
    {
        $lender = $this->getLenderById($id);

        $lender->update([
            'active' => $value,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getLenderById($id)
    {
        return Lenders::findOrFail($id);
    }
}
