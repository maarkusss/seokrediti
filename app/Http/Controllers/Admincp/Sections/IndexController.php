<?php

namespace App\Http\Controllers\Admincp\Sections;

use App\Http\Controllers\Controller;
use App\Models\Admincp\AdminLogs;
use App\Models\Admincp\Sections\Sections;
use Illuminate\Support\Facades\App;

class IndexController extends Controller
{
    /**
     * Show the lenders index page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admincp.sections.index')->with([
            'sections' => $this->getAllSections(),
        ]);
    }

    /**
     * Get all sections
     */
    public function getAllSections()
    {
        return Sections::where('lang', App::getLocale())->where('parent_section_id', null)->orderBy('order')->get();
    }

    /**
     * Save edits
     */
    public function update()
    {
        $arr = request()->section['order'];

        if (request()->subsection) {
            $subs_arr = request()->subsection['order'];

            asort($subs_arr);

            $i = 0;

            foreach ($subs_arr as $id => $value) {
                $i++;

                Sections::where('id', $id)->update(['order' => $i]);
            }

            foreach (request()->subsection['active'] as $id => $value) {
                Sections::where('id', $id)->update(['active' => $value]);
            }
        }

        asort($arr);

        $i = 0;

        foreach ($arr as $id => $value) {
            $i++;

            Sections::where('id', $id)->update(['order' => $i]);
        }

        foreach (request()->section['active'] as $id => $value) {
            Sections::where('id', $id)->update(['active' => $value]);
        }

        AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Updated all section info on Section main page']);

        return redirect()->back()->with('success', 'Sections updated!');
    }
}
