<?php

namespace App\Http\Controllers\Admincp\Faq;

use App\Http\Controllers\Controller;
use App\Models\Admincp\AdminLogs;
use App\Models\Admincp\Faqs\Faqs;
use App\Models\Admincp\Faqs\FaqSections;
use App\Models\Admincp\LoanTypes\LoanTypes;
use App\Models\Admincp\Sections\Sections;
use App\Models\Admincp\SectionTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faqs = Faqs::where('lang', App::getLocale())->orderBy('order')->get();

        return view('admincp.faqs.index')->with(['faqs' => $faqs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admincp.faqs.add')->with(['sections' => $this->getSections(), 'loanTypes' => $this->getLoanTypes()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $faq = new Faqs();

        $faq->active = $request->active;
        $faq->order = $this->getLastOrderId();
        $faq->lang = App::getLocale();
        $faq->question = $data['question'];
        $faq->answer = $data['answer'];
        $faq->topic = $request->topic;

        $faq->save();

        if ($request->section) {
            foreach ($request->section as $key => $value) {
                if ($value == 1) {
                    FaqSections::create([
                        'faq_id' => $faq->id,
                        'section_id' => $key,
                    ]);
                }
            }
        }

        // if ($request->sectionTag) {
        //     foreach ($request->sectionTag as $key => $value) {
        //         if ($value == 1) {
        //             FaqSections::create([
        //                 'faq_id' => $faq->id,
        //                 'section_tag_id' => $key,
        //             ]);
        //         }
        //     }
        // }

        if ($request->loanType) {
            foreach ($request->loanType as $key => $value) {
                if ($value == 1) {
                    FaqSections::create([
                        'faq_id' => $faq->id,
                        'loan_type_id' => $key,
                    ]);
                }
            }
        }

        AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Created faq "' . $data['question'] . '"']);

        return redirect()->route('admincp.faqs.index')->with([
            'success' => 'Faq has been created!',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admincp.faqs.edit')->with([
            'faq' => $this->getFaqs($id),
            'sections' => $this->getSections(),
            // 'sectionTags' => $this->getSectionTags(),
            'loanTypes' => $this->getLoanTypes(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $faq = Faqs::where('id', $id)->firstOrFail();

        $data = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $faq->update([
            'active' => $request->active,
            'lang' => App::getLocale(),
            'question' => $data['question'],
            'answer' => $data['answer'],
            'topic' => $request->topic,

        ]);

        FaqSections::where('faq_id', $faq->id,)->delete();

        if ($request->section) {
            foreach ($request->section as $key => $value) {
                if ($value == 1) {
                    FaqSections::create([
                        'faq_id' => $faq->id,
                        'section_id' => $key,
                    ]);
                }
            }
        }

        // if ($request->sectionTag) {
        //     foreach ($request->sectionTag as $key => $value) {
        //         if ($value == 1) {
        //             FaqSections::create([
        //                 'faq_id' => $faq->id,
        //                 'section_tag_id' => $key,
        //             ]);
        //         }
        //     }
        // }

        if ($request->loanType) {
            foreach ($request->loanType as $key => $value) {
                if ($value == 1) {
                    FaqSections::create([
                        'faq_id' => $faq->id,
                        'loan_type_id' => $key,
                    ]);
                }
            }
        }

        AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Edited faq "' . $data['question'] . '"']);

        return redirect()->back()->with('success', 'Faq updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Faqs::where('id', $id)->delete();

        FaqSections::where('faq_id', $id)->delete;

        AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Deleted faq "' . $id . '"']);

        return redirect()->route('admincp.faqs.index')->with([
            'success' => 'Faqs has been deleted!',
        ]);
    }

    /**
     * Get last order id
     */
    public function getLastOrderId()
    {
        $faq = Faqs::where('lang', App::getLocale())->orderBy('order', 'DESC')->first();

        if ($faq) {
            return $faq->order + 1;
        }

        return 1;
    }

    /**
     * Save edits
     */
    public function updateOrder()
    {
        $arr = request()->faq['order'];

        asort($arr);

        $i = 0;

        foreach ($arr as $id => $value) {
            $i++;

            Faqs::where('id', $id)->update(['order' => $i]);
        }

        foreach (request()->faq['active'] as $id => $value) {
            Faqs::where('id', $id)->update(['active' => $value]);
        }

        AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Updated faq\'s info in faqs main page']);

        return redirect()->back()->with('success', 'faq\'s updated!');
    }

    /**
     * Get exact faq
     */
    public function getFaqs($id)
    {
        return Faqs::findorfail($id);
    }

    /**
     * Get all sections
     */
    public function getSections()
    {
        return Sections::where('lang', App::getLocale())->get();
    }

    /**
     * Get all section tags
     */
    // public function getSectionTags()
    // {
    //     return SectionTag::where('lang', App::getLocale())->get();
    // }

    /**
     * Get all loan types
     */
    public function getLoantypes()
    {
        return LoanTypes::where('lang', App::getLocale())->get();
    }
}
