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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $locale = App::getLocale();
        $homepage = Sections::where('type', 'home')->where('lang', $locale)->first()
            ?? Sections::where('type', 'home')->first()
            ?? new Sections([
                'type' => 'home',
                'lang' => $locale,
                'name' => 'Sākums',
                'title' => brand_name() . ' — ' . brand_tagline(),
                'h1' => 'Salīdzini kredītus gudri un caurspīdīgi',
            ]);

        if ($homepage->redirect_link) {
            return redirect($homepage->redirect_link);
        }

        $faqs = Faqs::where('lang', $locale)->where('active', true)->orderBy('order')->get();

        // Load parent loan categories with children for homepage bento grid
        $parentLoanTypes = LoanTypes::where('lang', $locale)
            ->whereNull('parent_type_id')
            ->where('active', true)
            ->with('children')
            ->orderBy('order', 'asc')
            ->get();

        // Primary featured loan type for default sorting, gracefully falling back to root loan type
        $loanType = LoanTypes::where('lang', $locale)
            ->where('active', true)
            ->orderBy('order', 'asc')
            ->whereNull('parent_type_id')
            ->first()
            ?? LoanTypes::where('lang', $locale)
                ->where('active', true)
                ->orderBy('order', 'asc')
                ->first();

        // Showcase all active licensed lenders on the homepage comparison (with UTM EPC support)
        $lenders_array = $this->getAllLenders(15);

        $blogSection = Sections::where('type', 'blog')->where('lang', $locale)->where('active', true)->first();
        $latestBlogs = collect();

        if ($blogSection) {
            $latestBlogs = Sections::where('parent_section_id', $blogSection->id)
                ->where('active', true)
                ->orderBy('order')
                ->take(3)
                ->get();
        }

        $reviewSection = Sections::where('type', 'reviews')->where('lang', $locale)->where('active', true)->first();
        $recentReviews = Reviews::with('lender')
            ->whereNotNull('rating')
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        $allReviewsCount = Reviews::whereNotNull('rating')->count();
        $avgPortalRating = $allReviewsCount > 0
            ? number_format((float) Reviews::whereNotNull('rating')->avg('rating'), 1)
            : '4.8';

        return view('home')->with([
            'homepage' => $homepage,
            'loanType' => $loanType,
            'parentLoanTypes' => $parentLoanTypes,
            'lenders' => $lenders_array,
            'faqs' => $faqs,
            'latestBlogs' => $latestBlogs,
            'blogSection' => $blogSection,
            'reviewSection' => $reviewSection,
            'recentReviews' => $recentReviews,
            'allReviewsCount' => $allReviewsCount,
            'avgPortalRating' => $avgPortalRating,
        ]);
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

    public function getAllLenders($limit)
    {
        $bot = is_bot();
        $visit = $this->createVisit($bot);

        $lenders_array = [];
        $utm_campaign = request()->utm_campaign ?: request()->cookie('utm_campaign');
        $sorting = $utm_campaign ? LendersSorting::where('campaign_name', $utm_campaign)->first() : null;
        $sorting_epc = $sorting ? LendersSortingEpc::where('sorting_id', $sorting->id)->get() : collect();

        $lenders = [];

        if ($utm_campaign && $sorting_epc->isNotEmpty()) {
            foreach ($sorting_epc as $sortItem) {
                if (request()->e != 1) {
                    $lender = Lenders::with(['data', 'reviews'])->where('id', $sortItem->lender_id)->first();
                } else {
                    $lender = Lenders::with(['data', 'reviews'])->where('id', $sortItem->lender_id)->where('frequency', null)->first();
                }

                if ($lender && $lender->active) {
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
            $lenders_model = Lenders::with(['data', 'reviews'])->where(['active' => 1])->orderBy('position', 'ASC')->get();

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
