<?php

namespace App\Http\Controllers;

use App\Models\Admincp\Faqs\Faqs;
use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\Lenders\LendersCategories;
use App\Models\Admincp\Lenders\LendersSorting;
use App\Models\Admincp\Lenders\LendersSortingEpc;
use App\Models\Admincp\Lenders\Visits;
use App\Models\Admincp\Lenders\VisitsSorting;
use App\Models\Admincp\LoanTypes\LoanTypes;
use App\Models\Admincp\Sections\Sections;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Return the homepage of the website.
     *
     * @param Request $request
     * @return RedirectResponse|View
     */
    public function index(Request $request)
    {
        $homepage = Sections::where('type', 'home')->where('lang', App::getLocale())->firstOrFail();
        $faqs = Faqs::where('lang', App::getLocale())->where('active', true)->get();

        if ($homepage->redirect_link) {
            return redirect($homepage->redirect_link);
        }

        $loanType =  LoanTypes::where('lang', app()->getLocale())->where('active', true)->orderBy('order', 'asc')->whereNotNull('parent_type_id')->first();

        if (!$loanType) {
            abort(404);
        }

        $utm_campaign = $request->utm_campaign ? $request->utm_campaign : $request->cookie('utm_campaign');

        $sorting = LendersSorting::where('campaign_name', $utm_campaign)->first();

        if ($sorting) {
            $sorting_epc = LendersSortingEpc::where('sorting_id', $sorting->id)->get();
        } else {
            $sorting_epc = [];
        }

        $lenders = [];
        $lenders_array = [];

        if ($loanType) {
            $bot = false;

            if (stristr(request()->header('user-agent'), 'bot') || stristr(request()->header('user-agent'), 'crawler') || stristr(request()->header('user-agent'), 'Skype')) {
                $bot = true;
            }

            $visit = $this->createVisit($bot);

            if ($utm_campaign && count($sorting_epc) !== 0) {
                foreach ($sorting_epc as $sorting) {
                    $lender = Lenders::where('id', $sorting->lender_id)->first();

                    $categories = LendersCategories::where('loan_type_id', $loanType->id)->where('lender_id', $lender->id)->exists();

                    if ($lender->active && $categories) {
                        if ($lender->frequency) {
                            if (rand(1, $lender->frequency) == $lender->frequency) {
                                $lenders[$sorting->position] = $lender;
                            }
                        } else {
                            $lenders[$sorting->position] = $lender;
                        }
                    }
                }
            } else {
                $categories = LendersCategories::where('loan_type_id', $loanType->id)->get();

                foreach ($categories as $category) {
                    $lender = Lenders::where('id', $category->lender_id)->first();
                    if ($lender) {
                        if ($lender->position && $lender->active) {
                            if ($lender->frequency) {
                                if (rand(1, $lender->frequency) == $lender->frequency) {
                                    $lenders[$lender->position] = $lender;
                                }
                            } else {
                                $lenders[$lender->position] = $lender;
                            }
                        }
                    }
                }
            }

            if ($lenders) {
                ksort($lenders);
            }

            $i = 1;

            foreach ($lenders as $lender) {
                $this->createVisitSorting($visit, $lender->id, $i, $bot);

                $lenders_array[$i++] = $lender;
            }
        }

        $blogSection = Sections::where('type', 'blog')->where('lang', App::getLocale())->first();
        $latestBlogs = [];

        if ($blogSection) {
            $latestBlogs = Sections::where('parent_section_id', $blogSection->id)
                ->where('active', true)
                ->orderBy('order')
                ->take(3)
                ->get();
        }

        return view('home')->with([
            'homepage' => $homepage,
            'loanType' => $loanType,
            'lenders' =>  $lenders_array,
            'faqs' => $faqs,
            'latestBlogs' => $latestBlogs,
            'blogSection' => $blogSection,
        ]);
    }

    public function createVisit($bot): int
    {
        $visit = new Visits();

        if (request()->cookie('visitor_id')) {
            $visit->visitor_id = request()->cookie('visitor_id');
        } else {
            $visit->visitor_id = null;
        }

        $visit->utm_campaign = request()->cookie('utm_campaign');
        $visit->bot = $bot;

        $visit->save();

        return $visit->id;
    }

    public function createVisitSorting($visit_id, $lender_id, $position, $bot)
    {
        VisitsSorting::create([
            'visit_id' => $visit_id,
            'lender_id' => $lender_id,
            'position' => $position,
            'bot' => $bot,
        ]);

        return true;
    }

    public function getAllLenders($limit)
    {
        $bot = false;

        if (stristr(request()->header('user-agent'), 'bot') || stristr(request()->header('user-agent'), 'crawler') || stristr(request()->header('user-agent'), 'Skype')) {
            $bot = true;
        }

        $visit = $this->createVisit($bot);

        $lenders_array = [];
        $utm_campaign = request()->utm_campaign ? request()->utm_campaign : request()->cookie('utm_campaign');

        $sorting = LendersSorting::where('campaign_name', $utm_campaign)->first();

        if ($sorting) {
            $sorting_epc = LendersSortingEpc::where('sorting_id', $sorting->id)->get();
        } else {
            $sorting_epc = [];
        }

        $lenders = [];

        if ($utm_campaign && count($sorting_epc) !== 0) {
            foreach ($sorting_epc as $sorting) {
                if (request()->e != 1) {
                    $lender = Lenders::where('id', $sorting->lender_id)->first();
                } else {
                    $lender = Lenders::where('id', $sorting->lender_id)->where('frequency', null)->first();
                }

                if ($lender) {
                    if ($lender->active) {
                        if ($lender->frequency) {
                            if (rand(1, $lender->frequency) == $lender->frequency) {
                                $lenders[$sorting->position] = $lender;
                            }
                        } else {
                            $lenders[$sorting->position] = $lender;
                        }
                    }
                }
            }
        } else {
            $lenders_model = Lenders::where(['active' => 1])->orderBy('position', 'ASC')->get();

            foreach ($lenders_model as $lender) {
                if ($lender->position && $lender->active) {
                    if ($lender->frequency) {
                        if (rand(1, $lender->frequency) == $lender->frequency) {
                            $lenders[$lender->position] = $lender;
                        }
                    } else {
                        $lenders[$lender->position] = $lender;
                    }
                }
            }
        }

        if ($lenders) {
            ksort($lenders);
        }

        if ($limit) {
            $lenders = array_slice($lenders, 0, $limit, true);
        }

        $i = 1;

        foreach ($lenders as $lender) {
            $this->createVisitSorting($visit, $lender->id, $i, $bot);

            $lenders_array[$i++] = $lender;
        }

        return $lenders_array;
    }
}
