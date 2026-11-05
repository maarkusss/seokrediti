<?php

namespace App\Http\Controllers\Admincp\Sections;

use App\Http\Controllers\Controller;
use App\Models\Admincp\AdminLogs;
use App\Models\Admincp\Sections\Sections;
use App\Models\Admincp\Sections\SectionsHistroy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AddController extends Controller
{
    /**
     * Show the lenders edit page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admincp.sections.add')->with([
            'sections' => $this->getAllSections(),
        ]);
    }

    /**
     * Get all sections
     */
    public function getAllSections()
    {
        return Sections::where('lang', App::getLocale())->where('parent_section_id', null)->get();
    }

    /**
     * Add new section
     */
    public function create(Request $request)
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

        $section = new Sections();

        $section->type = $request->type;
        $section->active = $request->active;
        $section->order = $this->getLastOrderId($parent_id);
        $section->parent_section_id = $parent_id;
        $section->lang = App::getLocale();
        $section->title = $data['title'];
        $section->description = $request->googleDescription;
        $section->keywords = $request->googleKeywords;
        $section->anchor_element_title = $request->anchor_element_title;
        $section->h1 = $request->h1;
        $section->h1_description = $request->h1_description;
        $section->name = $data['name'];
        $section->route_name = $data['routing_name'];
        $section->redirect_link = $request->link;
        $section->source = $request->sectionSource;
        $section->section_html = $request->section_html;
        $section->text = $request->sectionText;
        $section->display_in_the_header = $request->display_in_the_header;
        $section->image = $request->image;
        $section->image_alt_text = $request->image_alt_text;

        $section->save();

        SectionsHistroy::create([
            'section_id' => $section->id,
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

        AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Created section "' . $data['name'] . '"']);

        return redirect(route('admincp.sections.index'))->with('success', 'Section has been created!');
    }

    /**
     * Get last order id
     */
    public function getLastOrderId($parent_id)
    {
        if ($parent_id > 0) {
            $section = Sections::where('parent_section_id', $parent_id)->where('lang', App::getLocale())->orderBy('order', 'DESC')->first();
        } else {
            $section = Sections::orderBy('order', 'DESC')->where('lang', App::getLocale())->first();
        }

        if ($section) {
            return $section->order + 1;
        } else {
            return 1;
        }
    }
}
