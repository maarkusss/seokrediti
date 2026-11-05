<?php

use App\Http\Controllers\Admincp\Settings\IndexController as AdmincpSettingsIndexController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\ConsumerController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\DeepSectionController;
use App\Http\Controllers\GoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Newsletter\EmailController as NewsletterEmailController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\TertiarySectionController;
use App\Http\Controllers\Unsubscribe\IndexController as UnsubscribeIndexController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [
        'dev_behind_goodwall',
    ],
], function () {
    // Hoi token redirect to offers
    Route::get('/u/{hoi_token}/{sending_token?}', [ConsumerController::class, 'consumerTokenRedirect']);

    Route::group(['middleware' => ['redirect_links', 'locales', 'campaigns', 'visitors']], function () {

        Route::get('/', [HomeController::class, 'index'])->name('homepage');
        Route::post('/', [ConsumerController::class, 'postOffer'])->name('post.offer');

        Route::post('/subscribe', [NewsletterEmailController::class, 'index'])
            ->middleware('throttle:8,1')
            ->name('subscribe.email');

        Route::resource('/unsubscribe', UnsubscribeIndexController::class)->only(['index', 'store']);

        $adminLocale = (Request::segment(1) && in_array(Request::segment(1), ['lv', 'ru'])) ? Request::segment(1) : 'lv';

        Route::group([
            'prefix' => $adminLocale,
            'middleware' => 'behind_goodwall',
        ], function () {
            require __DIR__ . '/auth.php';
            require __DIR__ . '/web_admincp.php';
        });

        // Bare /admincp redirect to /lv/admincp (preserving any subpaths & queries)
        Route::get('/admincp/{any?}', function ($any = null) {
            $query = request()->getQueryString();
            $target = '/lv/admincp' . ($any ? '/' . $any : '') . ($query ? '?' . $query : '');
            return redirect($target, 301);
        })->where('any', '.*');

        // Bare /login redirect to /lv/login
        Route::get('/login', function () {
            $query = request()->getQueryString();
            return redirect('/lv/login' . ($query ? '?' . $query : ''), 301);
        });
        Route::post('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);

        // Review post route
        Route::post('/{companies_name}/{route_name}', [ReviewController::class, 'create'])->name('companies.review.create');

        Route::get('/go/{lender_id}/{lender_position}', [GoController::class, 'index'])->name('go');
        Route::get('/go/{lender_slug}', [GoController::class, 'bySlug'])->name('go.slug');

        Route::get('/manifest.json', [SeoController::class, 'manifestJson'])->name('manifest.json');
        Route::get('/robots.txt', [SeoController::class, 'robotsTxt'])->name('robots.txt');
        Route::get('/sitemap.xml', [SeoController::class, 'sitemapXml'])->name('sitemap.xml');
        Route::get('/llms.txt', [SeoController::class, 'llmsTxt'])->name('llms.txt');
        Route::get('/captcha', [CaptchaController::class, 'generate'])->name('captcha');

        Route::get('/{name}', [SectionController::class, 'index'])->name('section');
        Route::get('/{name}/{deep_name}', [DeepSectionController::class, 'index'])->name('section.deep');
        Route::get('/{primarySlug}/{secondarySlug}/{tertiarySlug}', [TertiarySectionController::class, 'index'])->name('section.tertiary');

        Route::post('/{name}', [ContactUsController::class, 'sendMail'])->name('section.post');
    });

    Route::group(['prefix' => 'setup', 'middleware' => 'setupCheck'], function () {
        Route::get('/', [AdmincpSettingsIndexController::class, 'index'])->name('setup.index');
        Route::post('/', [AdmincpSettingsIndexController::class, 'update'])->name('setup.post');
    });
});
