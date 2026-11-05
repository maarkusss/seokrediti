<?php

namespace App\Http\Controllers;

use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\Lenders\LendersCategories;
use App\Models\Admincp\Lenders\LendersSorting;
use App\Models\Admincp\Lenders\LendersSortingEpc;
use App\Models\Admincp\Lenders\Visits;
use App\Models\Admincp\Lenders\VisitsSorting;
use App\Models\Admincp\LoanTypes\LoanTypes;
use App\Models\Admincp\Sections\Sections;
use App\Models\Reviews;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class DeepSectionController extends Controller
{
    /**
     * Return a deep section.
     *
     * @param string $name
     * @param string $deep_name
     * @return View
     * @throws ModelNotFoundException
     */
    public function index(Request $request, string $name, string $deep_name)
    {
        // 1. Check if first parameter is a Section (e.g. 'par-mums', 'blogs', 'atsauksmes')
        $section = Sections::where('route_name', $name)
            ->where('lang', App::getLocale())
            ->where('active', true)
            ->where('type', '!=', 'home')
            ->first();

        if ($section) {
            // Standalone/standard sections (e.g. 'par-mums') have no sub-pages -> strictly 404
            if ($section->type === 'standard') {
                abort(404);
            }

            // Reviews section (e.g. 'atsauksmes') -> only lender reviews allowed
            if ($section->type === 'reviews') {
                $lender = Lenders::where('route_name', $deep_name)->first();
                if (!$lender) {
                    abort(404);
                }

                $reviews = Reviews::where('lender_id', $lender->id)->orderByDesc('created_at')->get();
                $ratings = 0;
                $average_rating = '0.00';
                if ($reviews->count() > 0) {
                    foreach ($reviews as $review) {
                        $ratings += $review->rating;
                    }
                    $average_rating = number_format(($ratings / $reviews->count()), 2);
                }

                return view('reviews.show')->with([
                    'lender' => $lender,
                    'lenders' => Lenders::query()
                        ->whereHas('data', function ($query) {
                            $query->where('lang', App::getLocale());
                        })
                        ->get(),
                    'section' => $section,
                    'reviews' => $reviews,
                    'average_rating' => $average_rating,
                ]);
            }

            // Blog section (e.g. 'blogs') -> only direct blog post children allowed
            if ($section->type === 'blog') {
                $deepSection = Sections::where('parent_section_id', $section->id)
                    ->where('route_name', $deep_name)
                    ->where('lang', App::getLocale())
                    ->where('active', true)
                    ->first();

                if (!$deepSection) {
                    abort(404);
                }

                return view('blog.blog')->with([
                    'blogPost' => $deepSection,
                    'blogPostParent' => $section,
                ]);
            }

            abort(404);
        }

        // 2. Check if first parameter is a parent Loan Type (e.g. 'atrie-krediti', 'paterina-krediti')
        $parentLoanType = LoanTypes::whereNull('parent_type_id')
            ->where('route_name', $name)
            ->where('lang', App::getLocale())
            ->where('active', true)
            ->first();

        if (!$parentLoanType) {
            abort(404);
        }

        $loanType = LoanTypes::where('parent_type_id', $parentLoanType->id)
            ->where('route_name', $deep_name)
            ->where('lang', App::getLocale())
            ->where('active', true)
            ->first();

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

        return view('loan-type')->with([
            'loanType' => $loanType,
            'lenders' => $lenders_array,
            'reviews' => Reviews::whereNotNull('rating')->get(),
            'reviewSection' => Sections::where('type', 'reviews')->where('lang', App::getLocale())->where('active', true)->first(),
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
}
