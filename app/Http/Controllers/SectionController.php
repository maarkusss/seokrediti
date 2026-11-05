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
use App\Models\Reviews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SectionController extends Controller
{
    /**
     * Return a section.
     *
     * @param Request $request
     * @param string $name
     * @return View
     * @throws HttpException
     */
    public function index(Request $request, string $name)
    {
        $locale = App::getLocale();
        $loanType = LoanTypes::with('children')
            ->where('route_name', $name)
            ->whereNull('parent_type_id')
            ->where('lang', $locale)
            ->where('active', true)
            ->first();

        $section = Sections::where('route_name', $name)
            ->where('lang', $locale)
            ->where('active', true)
            ->where('type', '!=', 'home')
            ->first();

        $faqs = Faqs::where('lang', $locale)->where('active', true)->orderBy('order')->get();

        if (!$loanType && !$section) {
            abort(404);
        }

        $utm_campaign = $request->utm_campaign ?: $request->cookie('utm_campaign');
        $sorting = $utm_campaign ? LendersSorting::where('campaign_name', $utm_campaign)->first() : null;
        $sorting_epc = $sorting ? LendersSortingEpc::where('sorting_id', $sorting->id)->get() : collect();

        $lenders = [];
        $lenders_array = [];

        if ($loanType) {
            $bot = is_bot();
            $visit = $this->createVisit($bot);

            if ($utm_campaign && $sorting_epc->isNotEmpty()) {
                foreach ($sorting_epc as $sortItem) {
                    $lender = Lenders::with(['data', 'reviews'])->where('id', $sortItem->lender_id)->first();
                    if (!$lender) {
                        continue;
                    }

                    $inCategory = LendersCategories::where('loan_type_id', $loanType->id)
                        ->where('lender_id', $lender->id)
                        ->exists();

                    if ($lender->active && $inCategory) {
                        if ($lender->frequency) {
                            if (rand(1, $lender->frequency) == $lender->frequency) {
                                $lenders[$sortItem->position] = $lender;
                            }
                        } else {
                            $lenders[$sortItem->position] = $lender;
                        }
                    }
                }
            } else {
                $categories = LendersCategories::where('loan_type_id', $loanType->id)->get();

                foreach ($categories as $category) {
                    $lender = Lenders::with(['data', 'reviews'])->where('id', $category->lender_id)->first();
                    if ($lender && $lender->position && $lender->active) {
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

            $i = 1;
            foreach ($lenders as $lender) {
                $this->createVisitSorting($visit, $lender->id, $i, $bot);
                $lenders_array[$i++] = $lender;
            }
        }

        if ($loanType) {
            $siblingLoanTypes = LoanTypes::whereNull('parent_type_id')
                ->where('lang', $locale)
                ->where('active', true)
                ->orderBy('order')
                ->get();

            return view('loan-type')->with([
                'loanType' => $loanType,
                'parentLoanType' => null,
                'siblingLoanTypes' => $siblingLoanTypes,
                'lenders' => $lenders_array,
                'faqs' => $faqs,
                'reviews' => Reviews::whereNotNull('rating')->get(),
                'reviewSection' => Sections::where('type', 'reviews')->where('lang', $locale)->where('active', true)->first(),
            ]);
        } elseif ($section->type == 'contact') {
            $loanTypes = LoanTypes::where('lang', $locale)->whereNull('parent_type_id')->where('active', true)->orderBy('order')->get();

            return view('contact-us')->with([
                'section' => $section,
                'loanTypes' => $loanTypes,
                'faqs' => $faqs,
            ]);
        } elseif ($section->type == 'reviews') {
            $lendersList = Lenders::with(['data', 'reviews'])
                ->orderByRaw('CASE WHEN active = 1 THEN 0 ELSE 1 END ASC')
                ->orderByRaw('CASE WHEN position IS NULL THEN 1 ELSE 0 END ASC')
                ->orderBy('position', 'asc')
                ->orderBy('name', 'asc')
                ->get();

            $recentReviews = Reviews::with('lender')->latest()->take(12)->get();

            return view('reviews.index')->with([
                'lenders' => $lendersList,
                'recentReviews' => $recentReviews,
                'section' => $section,
            ]);
        } elseif ($section->type == 'blog') {
            $blogPosts = Sections::where('parent_section_id', $section->id)
                ->where('lang', $locale)
                ->where('type', 'blog')
                ->where('active', true)
                ->orderBy('order')
                ->get();

            return view('blog.index')->with([
                'section' => $section,
                'blogPosts' => $blogPosts,
            ]);
        } else {
            return view('footer-page')->with([
                'section' => $section,
                'faqs' => $faqs,
            ]);
        }
    }

    public function createVisit($bot): int
    {
        $visit = new Visits();
        $visit->visitor_id = request()->cookie('visitor_id') ?: null;
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
