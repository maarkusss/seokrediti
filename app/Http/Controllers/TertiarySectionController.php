<?php

namespace App\Http\Controllers;

use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\LoanTypes\LoanTypes;
use App\Models\Admincp\Sections\Sections;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TertiarySectionController extends Controller
{
    /**
     * Redirect legacy tertiary blog URLs to canonical deep section URL.
     *
     * @param string $primarySlug
     * @param string $secondarySlug
     * @param string $tertiarySlug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(string $primarySlug, string $secondarySlug, string $tertiarySlug)
    {
        $parentSection = Sections::where('route_name', $primarySlug)
            ->where('lang', App::getLocale())
            ->where('type', 'blog')
            ->where('active', true)
            ->first();

        if (!$parentSection) {
            abort(404);
        }

        $deepSection = Sections::where('parent_section_id', $parentSection->id)
            ->where('route_name', $secondarySlug)
            ->where('lang', App::getLocale())
            ->where('active', true)
            ->first();

        if (!$deepSection) {
            abort(404);
        }

        $query = request()->getQueryString();
        $target = route('section.deep', [
            'name' => $primarySlug,
            'deep_name' => $secondarySlug,
        ]);

        if ($query) {
            $target .= '?' . $query;
        }

        return redirect($target, 301);
    }
}
