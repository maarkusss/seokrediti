<?php

namespace Tests\Feature;

use Tests\TestCase;

class EirokreditiRoutesTest extends TestCase
{
    /**
     * Test home page loads with 200 and proper brand text.
     */
    public function test_home_page_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Eirokrediti');
    }

    /**
     * Test primary loan type routes return 200.
     */
    public function test_loan_type_routes_return_ok(): void
    {
        $routes = [
            '/atrie-krediti',
            '/sms-krediti',
            '/bezprocentu-krediti',
            '/kreditu-apvienosana',
            '/paterina-krediti',
            '/auto-lizings',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    /**
     * Test deep loan type routes return 200.
     */
    public function test_deep_loan_type_route_returns_ok(): void
    {
        $response = $this->get('/atrie-krediti/no-18-gadiem');
        $response->assertStatus(200);
    }

    /**
     * Test about us page returns 200.
     */
    public function test_about_us_page_returns_ok(): void
    {
        $response = $this->get('/par-mums');
        $response->assertStatus(200);
    }

    /**
     * Test reviews index and individual lender review page return 200.
     */
    public function test_reviews_index_returns_ok(): void
    {
        $response = $this->get('/atsauksmes');
        $response->assertStatus(200);
    }

    public function test_reviews_lender_returns_ok(): void
    {
        $responseCredit24 = $this->get('/atsauksmes/credit24');
        $responseCredit24->assertStatus(200);
    }

    /**
     * Test blog index and article routing.
     */
    public function test_blog_pages_return_ok(): void
    {
        $response = $this->get('/blogs');
        $response->assertStatus(200);

        // Slug-only URL returns 200 (modern canonical URL)
        $responseSlugOnly = $this->get('/blogs/auto-kredits-vai-lizings-kas-izdevigak');
        $responseSlugOnly->assertStatus(200);

        // Legacy URL with ID redirects 301 to slug-only canonical URL
        $responseArticle = $this->get('/blogs/auto-kredits-vai-lizings-kas-izdevigak/13');
        $responseArticle->assertStatus(301);
        $responseArticle->assertRedirect('/blogs/auto-kredits-vai-lizings-kas-izdevigak');
    }

    /**
     * Test robots.txt returns 200 with proper sitemap directive.
     */
    public function test_robots_txt_returns_ok(): void
    {
        $response = $this->get('/robots.txt');
        $response->assertStatus(200);
        $response->assertSee('Sitemap: https://www.eirokrediti.lv/sitemap.xml');
    }
    public function test_affiliate_go_route_works(): void
    {
        $lender = \App\Models\Admincp\Lenders\Lenders::first();
        if ($lender) {
            $response = $this->get('/go/' . $lender->id . '/1');
            $response->assertStatus(200);
        }
    }

    /**
     * Test that standalone sections (par-mums, blogs) strictly disallow loan types or invalid subpaths (must 404).
     */
    public function test_standalone_sections_do_not_allow_loan_subpaths(): void
    {
        $invalidUrls = [
            '/blogs/atrie-krediti',
            '/par-mums/atrie-krediti',
            '/par-mums/no-18-gadiem',
            '/blogs/no-18-gadiem',
            '/par-mums/credit24',
        ];

        foreach ($invalidUrls as $url) {
            $response = $this->get($url);
            $response->assertStatus(404);
        }
    }

    /**
     * Test legacy /lv/... URLs redirect 301 to clean URLs.
     */
    public function test_legacy_lv_prefix_redirects_301(): void
    {
        $response = $this->get('/lv/atrie-krediti');
        $response->assertStatus(301);
        $response->assertRedirect('/atrie-krediti');

        $responseHome = $this->get('/lv');
        $responseHome->assertStatus(301);
        $responseHome->assertRedirect('/');
    }

    /**
     * Test admincp and auth use /lv locale and bare routes redirect to /lv.
     */
    public function test_admincp_uses_lv_locale_and_bare_routes_redirect(): void
    {
        // Bare admincp redirects to /lv/admincp
        $this->get('/admincp')
            ->assertStatus(301)
            ->assertRedirect('/lv/admincp');

        $this->get('/admincp/sections')
            ->assertStatus(301)
            ->assertRedirect('/lv/admincp/sections');

        // Bare login redirects to /lv/login
        $this->get('/login')
            ->assertStatus(301)
            ->assertRedirect('/lv/login');

        // Admin login page returns 200 and loads admin CSS bundle
        $loginResponse = $this->get('/lv/login');
        $loginResponse->assertStatus(200);
        $loginResponse->assertSee('assets/admin-');

        // Unauthenticated access to /lv/admincp redirects to /lv/login
        $this->get('/lv/admincp')
            ->assertStatus(302)
            ->assertRedirect('/lv/login');

        // Authenticated admin can access /lv/admincp and subpages
        $user = \App\User::first();
        if ($user) {
            $dashboardResponse = $this->actingAs($user)->get('/lv/admincp');
            $dashboardResponse->assertStatus(200);
            $dashboardResponse->assertSee('assets/admin-');
            $dashboardResponse->assertSee('list-none');

            $this->actingAs($user)
                ->get('/lv/admincp/sections')
                ->assertStatus(200);

            $this->actingAs($user)
                ->get('/lv/admincp/bans')
                ->assertStatus(200)
                ->assertSee('Add ban');

            $this->actingAs($user)
                ->get('/lv/admincp/faqs')
                ->assertStatus(200)
                ->assertSee('Add faq');

            $this->actingAs($user)
                ->get('/lv/admincp/settings')
                ->assertStatus(200);
        }
    }
}
