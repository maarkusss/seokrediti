<?php

use App\Http\Controllers\Admincp\Admins\IndexController as AdmincpAdminsIndexController;
use App\Http\Controllers\Admincp\Bans\IndexController as AdmincpBansIndexController;
use App\Http\Controllers\Admincp\ConnectedUrlsController as AdmincpConnectedUrlsController;
use App\Http\Controllers\Admincp\Consumers\IndexController as AdmincpConsumersIndexController;
use App\Http\Controllers\Admincp\DashboardController as AdmincpDashboardController;
use App\Http\Controllers\Admincp\Faq\IndexController as AdmincpFaqIndexController;
use App\Http\Controllers\Admincp\ImageController;
use App\Http\Controllers\Admincp\RedirectLinks\IndexController as AdmincpRedirectLinksIndexController;
use App\Http\Controllers\Admincp\Lenders\AddController as AdmincpLendersAddController;
use App\Http\Controllers\Admincp\Lenders\EditController as AdmincpLendersEditController;
use App\Http\Controllers\Admincp\Lenders\IndexController as AdmincpLendersIndexController;
use App\Http\Controllers\Admincp\LoanTypes\IndexController as AdmincpLoanTypesIndexController;
use App\Http\Controllers\Admincp\Sections\AddController as AdmincpSectionsAddController;
use App\Http\Controllers\Admincp\Sections\EditController as AdmincpSectionsEditController;
use App\Http\Controllers\Admincp\Sections\IndexController as AdmincpSectionsIndexController;
use App\Http\Controllers\Admincp\Settings\IndexController as AdmincpSettingsIndexController;
use App\Http\Controllers\Admincp\Statistics\IndexController as AdmincpStatisticsIndexController;
use App\Http\Controllers\Admincp\Visitors\IndexController as AdmincpVisitorsIndexController;
use App\Http\Controllers\Admincp\AdminLogs\IndexController as AdmincpAdminLogsIndexController;
use App\Http\Controllers\Admincp\SitemapIgnoredUrlsController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('/admincp')->as('admincp.')->group(function () {
    Route::get('/', [AdmincpDashboardController::class, 'index'])->name('dashboard');

    Route::get('/api', [AdmincpDashboardController::class, 'api'])->name('api');

    Route::resource('admins', AdmincpAdminsIndexController::class)->except(['show'])->middleware('permission:view admins');
    Route::resource('adminlogs', AdmincpAdminLogsIndexController::class)->except(['show'])->middleware('permission:view adminlogs');
    Route::resource('bans', AdmincpBansIndexController::class)->except(['show'])->middleware('permission:view bans');

    Route::group(['prefix' => 'sections', 'middleware' => ['permission:view sections']], function () {
        Route::get('/', [AdmincpSectionsIndexController::class, 'index'])->name('sections.index');

        Route::group(['middleware' => ['permission:edit sections']], function () {
            Route::post('/', [AdmincpSectionsIndexController::class, 'update'])->name('sections.index.save');
        });

        Route::group(['prefix' => 'edit/{section_id}', 'middleware' => ['permission:edit sections']], function () {
            Route::get('/', [AdmincpSectionsEditController::class, 'index'])->name('sections.edit.index');
            Route::post('/', [AdmincpSectionsEditController::class, 'update'])->name('sections.edit.post');
        });

        Route::group(['prefix' => 'add', 'middleware' => ['permission:add sections']], function () {
            Route::get('/', [AdmincpSectionsAddController::class, 'index'])->name('sections.add.index');
            Route::post('/', [AdmincpSectionsAddController::class, 'create'])->name('sections.add.post');
        });
    });

    Route::group(['prefix' => 'sitemap-ignored-urls', 'middleware' => ['permission:view sections']], function () {
        Route::get('/', [SitemapIgnoredUrlsController::class, 'index'])
            ->name('sitemap-ignored-urls.index');
    
        Route::group(['middleware' => ['permission:edit sections']], function () {
            Route::post('/', [SitemapIgnoredUrlsController::class, 'updateIndex'])
                ->name('sitemap-ignored-urls.index.save');
    
            Route::get('/edit/{id}', [SitemapIgnoredUrlsController::class, 'edit'])
                ->name('sitemap-ignored-urls.edit.index');
    
            Route::post('/edit/{id}', [SitemapIgnoredUrlsController::class, 'update'])
                ->name('sitemap-ignored-urls.edit.post');
    
            Route::post('/delete/{id}', [SitemapIgnoredUrlsController::class, 'delete'])
                ->name('sitemap-ignored-urls.delete');
        });
    
        Route::group(['prefix' => 'add', 'middleware' => ['permission:add sections']], function () {
            Route::get('/', [SitemapIgnoredUrlsController::class, 'add'])
                ->name('sitemap-ignored-urls.add.index');
    
            Route::post('/', [SitemapIgnoredUrlsController::class, 'create'])
                ->name('sitemap-ignored-urls.add.post');
        });
    });

    Route::group(['prefix' => 'visitors', 'middleware' => ['permission:view visitors']], function () {
        Route::get('/', [AdmincpVisitorsIndexController::class, 'index'])->name('visitors.index');

        Route::group(['prefix' => '{visitor_id}'], function () {
            Route::get('/', [AdmincpVisitorsIndexController::class, 'visitor'])->name('visitors.visitor');
        });
    });

    Route::middleware('permission:view loantypes')->group(function () {
        Route::resource('loantypes', AdmincpLoanTypesIndexController::class)->except(['show']);
        Route::post('loantypes/updateOrder', [AdmincpLoanTypesIndexController::class, 'updateOrder'])->name('loantypes.updateOrder');
    });

    Route::group(['prefix' => 'lenders', 'middleware' => ['permission:view lenders']], function () {
        Route::get('/', [AdmincpLendersIndexController::class, 'index'])->name('lenders.index');

        Route::post('/', [AdmincpLendersIndexController::class, 'update'])->name('lenders.index.post')->middleware('permission:edit lenders');

        Route::group(['prefix' => 'edit/{lender_id}', 'middleware' => ['permission:edit lenders']], function () {
            Route::get('/', [AdmincpLendersEditController::class, 'index'])->name('lenders.edit.index');
            Route::post('/', [AdmincpLendersEditController::class, 'update'])->name('lenders.edit.post');
        });

        Route::group(['prefix' => 'add', 'middleware' => ['permission:add lenders']], function () {
            Route::get('/', [AdmincpLendersAddController::class, 'index'])->name('lenders.add.index');
            Route::post('/', [AdmincpLendersAddController::class, 'create'])->name('lenders.add.post');
        });
    });

    Route::group(['prefix' => 'statistics', 'middleware' => ['permission:view statistics']], function () {
        Route::get('/', [AdmincpStatisticsIndexController::class, 'index'])->name('statistics.index');
    });

    Route::group(['prefix' => 'consumers', 'middleware' => ['permission:view consumers']], function () {
        Route::get('/', [AdmincpConsumersIndexController::class, 'index'])->name('consumers.index');
        Route::post('/{consumer_id}', [AdmincpConsumersIndexController::class, 'destroy'])->name('consumers.delete')->middleware('permission:delete consumers');
    });

    Route::middleware('permission:view faqs')->group(function () {
        Route::resource('faqs', AdmincpFaqIndexController::class)->except(['show']);
        Route::post('faqs/updateOrder', [AdmincpFaqIndexController::class, 'updateOrder'])->name('faqs.updateOrder');
    });

    Route::group(['prefix' => 'settings', 'middleware' => ['permission:view settings']], function () {
        Route::get('/', [AdmincpSettingsIndexController::class, 'index'])->name('settings.index');
        Route::post('/', [AdmincpSettingsIndexController::class, 'update'])->name('settings.index.post')->middleware('permission:edit settings');
    });

    Route::group(['prefix' => 'reviews', 'middleware' => ['permission:view reviews']], function () {
        Route::get('/', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('/delete/{id}', [ReviewController::class, 'delete'])->name('reviews.delete');
    });

    // Route::resource('redirect-links', AdmincpRedirectLinksIndexController::class)->only(['index', 'store', 'destroy'])->middleware('permission:view redirectlinks');

    Route::group(['middleware' => ['permission:view redirectlinks']], function () {
        Route::resource('redirect-links', AdmincpRedirectLinksIndexController::class)->except(['show']);
    });

    Route::resource('connected-urls', AdmincpConnectedUrlsController::class)->except(['destroy', 'show'])->middleware('permission:view connected urls');

    Route::resource('images', ImageController::class)->only(['index', 'store', 'destroy'])->middleware('permission:view images');
});