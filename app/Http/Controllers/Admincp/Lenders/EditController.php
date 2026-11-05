<?php

namespace App\Http\Controllers\Admincp\Lenders;

use App\Http\Controllers\Controller;
use App\Models\Admincp\AdminLogs;
use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\Lenders\LendersCategories;
use App\Models\Admincp\Lenders\LendersData;
use App\Models\Admincp\LoanTypes\LoanTypes;
use App\Models\Admincp\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class EditController extends Controller
{
    /**
     * Show the lenders edit page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($id)
    {
        return view('admincp.lenders.edit')->with([
            'lender' => $this->getCurrentLender($id),
            'lender_data' => $this->getCurrentLenderData($id),
            'categories' => $this->getCategories(),
            'languages' => $this->getLanguages(),
        ]);
    }

    public function update($id, Request $request)
    {
        $lender = $this->getCurrentLender($id);

        switch ($request->input('action', 'save')) {
            case 'delete':
                $lender->delete();

                AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Deleted lender "' . $request->name . '"']);

                return redirect(route('admincp.lenders.index'))->with('success', 'Lender deleted!');
                break;

            case 'save':
                LendersCategories::where('lender_id', $id)->delete();

                $data = $request->validate([
                    'name' => 'required',
                    'affLink' => 'required',
                    'route_name' => 'required',
                    'firstLoan' => 'required',
                    'minAmount' => 'required',
                    'maxAmount' => 'required',
                    'minTerm' => 'required',
                    'maxTerm' => 'required',
                    'minYears' => 'required',
                    'maxYears' => 'required',
                    'receivingTime' => 'required',
                ]);

                if ($request->image) {
                    $path = Storage::putFile('public/logos', $request->file('image'));

                    $path = explode('/', $path);
                    $path = '/storage/' . $path[1] . '/' . $path[2];

                    $lender->update([
                        'image' => $path,
                    ]);
                }

                $lender->update([
                    'name' => $data['name'],
                    'affiliate_link' => $data['affLink'],
                    'route_name' => $data['route_name'],
                    'first_loan' => $data['firstLoan'],
                    'min_amount' => $data['minAmount'],
                    'max_amount' => $data['maxAmount'],
                    'min_term' => $data['minTerm'],
                    'max_term' => $data['maxTerm'],
                    'min_years' => $data['minYears'],
                    'max_years' => $data['maxYears'],
                    'receiving_time' => $data['receivingTime'],
                    'active' => $request->active,
                    'zero_percent' => $request->zero_percent,
                    'top_lender' => $request->top_lender,
                ]);

                LendersData::updateOrCreate([
                    'lender_id' => $id,
                    'lang' => App::getLocale(),
                ], [
                    'slogan' => $request->slogan,
                    'company_name' => $request->companysName,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'max_apr' => $request->maxAPR,
                    'apr_example' => $request->aprExample,
                    'title' => $request->title,
                    'meta_description' => $request->meta_description,
                    'description' => $request->description,
                    'section_html' => $request->section_html,
                    'h1' => $request->h1,
                    'h1_description' => $request->h1_description,
                    'additional_text_1' => $request->additional_text_1,
                    'additional_text_2' => $request->additional_text_2,
                    'additional_text_3' => $request->additional_text_3,
                    'additional_text_4' => $request->additional_text_4,
                    'text_under_box' => $request->text_under_box,
                    'work_time_m_f' => $request->work_time_m_f,
                    'work_time_sa' => $request->work_time_sa,
                    'work_time_su' => $request->work_time_su,
                    'image_alt_text' => $request->image_alt_text,
                    'anchor_title' => $request->anchor_title,
                ]);

                if (!empty($request->category) && is_array($request->category)) {
                    foreach ($request->category as $key => $value) {
                        if ($value == 1) {
                            LendersCategories::create([
                                'lender_id' => $lender->id,
                                'loan_type_id' => $key,
                            ]);
                        }
                    }
                }

                AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Updated lender "' . $data['name'] . '"']);

                return redirect()->back()->witH('success', 'Lender updated!');

                break;
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCurrentLender($id)
    {
        return Lenders::findOrFail($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCurrentLenderData($id)
    {
        return LendersData::where('lender_id', $id)->where('lang', App::getLocale())->first();
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return LoanTypes::where('active', 1)->orderBy('order', 'ASC')->get();
    }

    /**
     * @return mixed
     */
    public function getLanguages()
    {
        return Settings::where('name', 'lang')->get();
    }
}
