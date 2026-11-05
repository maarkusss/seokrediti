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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class DeepSectionController extends Controller
{
    /**
     * Return a deep section.
     *
     * @param Request $request
     * @param string $name
     * @param string $deep_name
     * @return View
     * @throws ModelNotFoundException
     */
    public function index(Request $request, string $name, string $deep_name)
    {
        $locale = App::getLocale();

        // 1. Check if first parameter is a Section (e.g. 'par-mums', 'blogs', 'atsauksmes')
        $section = Sections::where('route_name', $name)
            ->where('lang', $locale)
            ->where('active', true)
            ->where('type', '!=', 'home')
            ->first();

        if ($section) {
            // Standalone/standard sections (e.g. 'par-mums') have no sub-pages -> strictly 404
            if ($section->type === 'standard' || $section->type === 'contact') {
                abort(404);
            }

            // Reviews section (e.g. 'atsauksmes') -> only lender reviews allowed
            if ($section->type === 'reviews') {
                $lender = Lenders::with('data')->where('route_name', $deep_name)->first();
                if (!$lender) {
                    abort(404);
                }

                $reviews = Reviews::where('lender_id', $lender->id)->orderByDesc('created_at')->get();
                $ratings = 0;
                $average_rating = '0.00';
                $ratingBreakdown = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

                if ($reviews->count() > 0) {
                    foreach ($reviews as $review) {
                        $r = max(1, min(5, (int) round((float) $review->rating)));
                        $ratingBreakdown[$r]++;
                        $ratings += $review->rating;
                    }
                    $average_rating = number_format(($ratings / $reviews->count()), 2);
                }

                $otherLenders = Lenders::with(['data', 'reviews'])
                    ->where('active', true)
                    ->where('id', '!=', $lender->id)
                    ->orderBy('position')
                    ->take(6)
                    ->get();

                return view('reviews.show')->with([
                    'lender' => $lender,
                    'lenders' => $otherLenders,
                    'section' => $section,
                    'reviews' => $reviews,
                    'average_rating' => $average_rating,
                    'ratingBreakdown' => $ratingBreakdown,
                ]);
            }

            // Blog section (e.g. 'blogs') -> only direct blog post children allowed
            if ($section->type === 'blog') {
                $deepSection = Sections::where('parent_section_id', $section->id)
                    ->where('route_name', $deep_name)
                    ->where('lang', $locale)
                    ->where('active', true)
                    ->first();

                if (!$deepSection) {
                    abort(404);
                }

                $relatedPosts = Sections::where('parent_section_id', $section->id)
                    ->where('id', '!=', $deepSection->id)
                    ->where('lang', $locale)
                    ->where('active', true)
                    ->orderBy('order')
                    ->take(3)
                    ->get();

                return view('blog.blog')->with([
                    'blogPost' => $deepSection,
                    'blogPostParent' => $section,
                    'relatedPosts' => $relatedPosts,
                ]);
            }

            abort(404);
        }

        // 2. Check if first parameter is a parent Loan Type (e.g. 'atrie-krediti', 'paterina-krediti')
        $parentLoanType = LoanTypes::with('children')
            ->whereNull('parent_type_id')
            ->where('route_name', $name)
            ->where('lang', $locale)
            ->where('active', true)
            ->first();

        if (!$parentLoanType) {
            abort(404);
        }

        $loanType = LoanTypes::with('parent')
            ->where('parent_type_id', $parentLoanType->id)
            ->where('route_name', $deep_name)
            ->where('lang', $locale)
            ->where('active', true)
            ->first();

        if (!$loanType) {
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

        $faqs = Faqs::where('lang', $locale)->where('active', true)->orderBy('order')->get();
        $siblingLoanTypes = LoanTypes::whereNull('parent_type_id')
            ->where('lang', $locale)
            ->where('active', true)
            ->orderBy('order')
            ->get();

        return view('loan-type')->with([
            'loanType' => $loanType,
            'parentLoanType' => $parentLoanType,
            'siblingLoanTypes' => $siblingLoanTypes,
            'lenders' => $lenders_array,
            'faqs' => $faqs,
            'reviews' => Reviews::whereNotNull('rating')->get(),
            'reviewSection' => Sections::where('type', 'reviews')->where('lang', $locale)->where('active', true)->first(),
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
}
