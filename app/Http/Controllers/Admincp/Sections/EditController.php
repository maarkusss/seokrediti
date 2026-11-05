<?php

namespace App\Http\Controllers\Admincp\Sections;

use App\Http\Controllers\Controller;
use App\Models\Admincp\AdminLogs;
use App\Models\Admincp\Sections\Sections;
use App\Models\Admincp\Sections\SectionsHistroy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class EditController extends Controller
{
    /**
     * Show the lenders edit page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($section_id)
    {
        return view('admincp.sections.edit')->with([
            'section' => $this->getSection($section_id),
            'history' => $this->getSectionHistory($section_id),
            'sections' => $this->getAllSections(),
        ]);
    }

    /**
     * Edit section
     */
    public function update($id, Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'routing_name' => 'required',
            'title' => 'required',
        ]);

        if ($request->section > 0) {
            $parent_id = $request->section;
        } else {
            $parent_id = null;
        }

        SectionsHistroy::create([
            'section_id' => $id,
            'type' => $request->type,
            'user_id' => Auth()->id(),
            'parent_section_id' => $parent_id,
            'lang' => App::getLocale(),
            'title' => $data['title'],
            'description' => $request->googleDescription,
            'keywords' => $request->googleKeywords,
            'anchor_element_title' => $request->anchor_element_title,
            'h1' => $request->h1,
            'h1_description' => $request->h1_description,
            'name' => $data['name'],
            'route_name' => $data['routing_name'],
            'redirect_link' => $request->link,
            'source' => $request->sectionSource,
            'text' => $request->sectionText,
            'display_in_the_header' => $request->display_in_the_header,
            'image' => $request->image,
            'image_alt_text' => $request->image_alt_text,
        ]);

        Sections::where('id', $id)->update([
            'type' => $request->type,
            'active' => $request->active,
            'parent_section_id' => $parent_id,
            'lang' => App::getLocale(),
            'title' => $data['title'],
            'description' => $request->googleDescription,
            'keywords' => $request->googleKeywords,
            'anchor_element_title' => $request->anchor_element_title,
            'h1' => $request->h1,
            'h1_description' => $request->h1_description,
            'name' => $data['name'],
            'route_name' => $data['routing_name'],
            'redirect_link' => $request->link,
            'source' => $request->sectionSource,
            'section_html' => $request->section_html,
            'text' => $request->sectionText,
            'display_in_the_header' => $request->display_in_the_header,
            'image' => $request->image,
            'image_alt_text' => $request->image_alt_text,
        ]);

        AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Edited section "' . $data['name'] . '"']);

        return redirect()->back()->with('success', 'Section updated!');
    }

    /**
     * Get exact section
     */
    public function getSection($id)
    {
        if (request()->history) {
            return SectionsHistroy::where('section_id', $id)->where('id', request()->history)->firstOrFail();
        }

        return Sections::findOrFail($id);
    }

    /**
     * Get all sections
     */
    public function getAllSections()
    {
        return Sections::where('lang', App::getLocale())->where('parent_section_id', null)->get();
    }

    /**
     * Get exact section history
     */
    public function getSectionHistory($id)
    {
        return SectionsHistroy::where('section_id', $id)->orderBy('created_at', 'DESC')->get();
    }
}
