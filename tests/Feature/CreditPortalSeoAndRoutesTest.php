<?php

namespace Tests\Feature;

use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\Settings;
use App\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreditPortalSeoAndRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Extract and validate all JSON-LD blocks on an HTML page.
     */
    private function extractValidJsonLdBlocks(string $html): array
    {
        preg_match_all('/<script\s+type=["\']application\/ld\+json["\']\s*>(.*?)<\/script>/is', $html, $matches);
        $blocks = [];

        foreach ($matches[1] ?? [] as $rawJson) {
            $decoded = json_decode(trim($rawJson), true);
            $this->assertNotNull($decoded, 'Invalid JSON-LD block encountered: ' . trim($rawJson));
            $blocks[] = $decoded;
        }

        return $blocks;
    }

    private function assertZeroLegacyFootprints(string $html): void
    {
        $lower = mb_strtolower($html);
        $this->assertStringNotContainsString('eirokrediti', $lower);
        $this->assertStringNotContainsString('atrie.lv', $lower);
        $this->assertStringNotContainsString('kreditstar', $lower);
        $this->assertStringNotContainsString('vadjudzvlduy1', $lower);
        $this->assertStringNotContainsString('wonderpush', $lower);
        $this->assertStringNotContainsString('data-vocabulary.org', $lower);
        $this->assertStringNotContainsString('goodday.', $lower);
        $this->assertStringNotContainsString('name="keywords"', $lower);
    }

    public function test_homepage_renders_modern_portal_valid_json_ld_and_zero_legacy_footprints(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $html = $response->getContent();
        $this->assertZeroLegacyFootprints($html);
        $response->assertSee(brand_name());
        $response->assertSee('Credit24');
        $response->assertSee('Bigbank');

        $schemas = $this->extractValidJsonLdBlocks($html);
        $types = array_column($schemas, '@type');

        $this->assertContains('Organization', $types);
        $this->assertContains('WebSite', $types);
        $this->assertContains('ItemList', $types);
        $this->assertContains('FAQPage', $types);
    }

    public function test_white_label_brand_name_and_logo_are_dynamically_changeable(): void
    {
        Settings::updateOrCreate(['name' => 'name'], ['value' => 'ZibensFinanses']);
        Settings::updateOrCreate(['name' => 'brand_tagline'], ['value' => 'Viedais kredītu kompass']);
        Settings::updateOrCreate(['name' => 'brand_logo'], ['value' => '/images/brand/custom-logo.svg']);
        Settings::updateOrCreate(['name' => 'contact_email'], ['value' => 'redakcija@zibensfinanses.lv']);

        $home = $this->get('/');
        $home->assertStatus(200);
        $home->assertSee('ZibensFinanses');
        $home->assertSee('/images/brand/custom-logo.svg');
        $home->assertSee('redakcija@zibensfinanses.lv');

        $manifest = $this->get('/manifest.json');
        $manifest->assertStatus(200);
        $manifest->assertJsonFragment([
            'short_name' => 'ZibensFinanses',
            'name' => 'ZibensFinanses — Viedais kredītu kompass',
        ]);

        $llms = $this->get('/llms.txt');
        $llms->assertStatus(200);
        $llms->assertSee('# ZibensFinanses');
        $llms->assertSee('Viedais kredītu kompass');
    }

    public function test_all_primary_and_deep_loan_type_routes_return_200_with_valid_schema(): void
    {
        $routes = [
            '/atrie-krediti',
            '/paterina-krediti',
            '/kreditu-apvienosana',
            '/bezprocentu-krediti',
            '/auto-lizings',
            '/sms-krediti',
            '/atrie-krediti/no-18-gadiem',
            '/atrie-krediti/uz-12-menesiem',
            '/atrie-krediti/ar-sliktu-kreditvesturi',
            '/paterina-krediti/ilgtermina',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);

            $html = $response->getContent();
            $this->assertZeroLegacyFootprints($html);

            $schemas = $this->extractValidJsonLdBlocks($html);
            $types = array_column($schemas, '@type');
            $this->assertContains('Organization', $types);
            $this->assertContains('BreadcrumbList', $types);
        }
    }

    public function test_reviews_index_and_lender_profile_pages_render_aggregate_ratings(): void
    {
        $index = $this->get('/atsauksmes');
        $index->assertStatus(200);
        $this->assertZeroLegacyFootprints($index->getContent());

        $lenderPage = $this->get('/atsauksmes/credit24');
        $lenderPage->assertStatus(200);
        $html = $lenderPage->getContent();
        $this->assertZeroLegacyFootprints($html);

        $schemas = $this->extractValidJsonLdBlocks($html);
        $financialSchema = collect($schemas)->firstWhere('@type', 'FinancialService');
        $this->assertNotNull($financialSchema);
        $this->assertArrayHasKey('aggregateRating', $financialSchema);
        $this->assertGreaterThanOrEqual(1, $financialSchema['aggregateRating']['reviewCount']);
    }

    public function test_blog_index_article_and_legacy_301_redirect(): void
    {
        $index = $this->get('/blogs');
        $index->assertStatus(200);
        $this->assertZeroLegacyFootprints($index->getContent());

        $article = $this->get('/blogs/auto-kredits-vai-lizings-kas-izdevigak');
        $article->assertStatus(200);
        $html = $article->getContent();
        $this->assertZeroLegacyFootprints($html);

        $schemas = $this->extractValidJsonLdBlocks($html);
        $types = array_column($schemas, '@type');
        $this->assertContains('BlogPosting', $types);

        $legacyRedirect = $this->get('/blogs/auto-kredits-vai-lizings-kas-izdevigak/13');
        $legacyRedirect->assertStatus(301);
        $legacyRedirect->assertRedirect('/blogs/auto-kredits-vai-lizings-kas-izdevigak');
    }

    public function test_contact_page_and_captcha_validation_enforcement(): void
    {
        $page = $this->get('/par-mums');
        $page->assertStatus(200);
        $this->assertZeroLegacyFootprints($page->getContent());

        // Rejects wrong or missing CAPTCHA when session has captcha_code
        $badPost = $this->withSession(['captcha_code' => 'AB12'])
            ->from('/par-mums')
            ->post('/par-mums', [
                'name' => 'Jānis',
                'email' => 'janis@example.com',
                'question' => 'Vai salīdzinājums ir bezmaksas?',
                'captcha' => 'WRONG',
            ]);

        $badPost->assertRedirect('/par-mums');
        $badPost->assertSessionHasErrors('captcha');

        // Accepts matching CAPTCHA
        $goodPost = $this->withSession(['captcha_code' => 'AB12'])
            ->from('/par-mums')
            ->post('/par-mums', [
                'name' => 'Jānis',
                'email' => 'janis@example.com',
                'question' => 'Vai salīdzinājums ir bezmaksas?',
                'captcha' => 'ab12',
            ]);

        $goodPost->assertRedirect('/par-mums');
        $goodPost->assertSessionHasNoErrors();
    }

    public function test_legal_pages_and_unsubscribe_page_return_ok(): void
    {
        foreach (['/privatuma-politika', '/sikfaili', '/lietosanas-noteikumi', '/unsubscribe'] as $url) {
            $res = $this->get($url);
            $res->assertStatus(200);
            $this->assertZeroLegacyFootprints($res->getContent());
        }
    }

    public function test_affiliate_go_routes_set_noindex_headers(): void
    {
        $lender = Lenders::where('route_name', 'credit24')->firstOrFail();

        $byId = $this->get('/go/' . $lender->id . '/1');
        $byId->assertStatus(200);
        $byId->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->assertZeroLegacyFootprints($byId->getContent());

        $bySlug = $this->get('/go/credit24');
        $bySlug->assertStatus(200);
        $bySlug->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_technical_seo_endpoints_robots_sitemap_llms_and_manifest(): void
    {
        $robots = $this->get('/robots.txt');
        $robots->assertStatus(200);
        $robots->assertSee('Disallow: /go/');
        $robots->assertSee('/sitemap.xml');
        $this->assertZeroLegacyFootprints($robots->getContent());

        $sitemap = $this->get('/sitemap.xml');
        $sitemap->assertStatus(200);
        $sitemap->assertSee('<urlset', false);
        $sitemap->assertSee('/atrie-krediti');
        $sitemap->assertSee('/atsauksmes/credit24');
        $this->assertZeroLegacyFootprints($sitemap->getContent());

        $llms = $this->get('/llms.txt');
        $llms->assertStatus(200);
        $llms->assertSee('Ātrie kredīti');
        $this->assertZeroLegacyFootprints($llms->getContent());

        $manifest = $this->get('/manifest.json');
        $manifest->assertStatus(200);
        $manifest->assertJsonFragment(['display' => 'standalone']);
    }

    public function test_standalone_section_404_guards_and_legacy_lv_redirects(): void
    {
        foreach ([
            '/blogs/atrie-krediti',
            '/par-mums/atrie-krediti',
            '/par-mums/no-18-gadiem',
            '/blogs/no-18-gadiem',
            '/par-mums/credit24',
        ] as $invalidUrl) {
            $res = $this->get($invalidUrl);
            $res->assertStatus(404);
            $this->assertZeroLegacyFootprints($res->getContent());
        }

        $this->get('/lv/atrie-krediti')
            ->assertStatus(301)
            ->assertRedirect('/atrie-krediti');

        $this->get('/lv')
            ->assertStatus(301)
            ->assertRedirect('/');
    }

    public function test_admincp_authentication_and_white_label_settings_panel(): void
    {
        $this->get('/admincp')
            ->assertStatus(301)
            ->assertRedirect('/lv/admincp');

        $this->get('/login')
            ->assertStatus(301)
            ->assertRedirect('/lv/login');

        $loginPage = $this->get('/lv/login');
        $loginPage->assertStatus(200);
        $this->assertZeroLegacyFootprints($loginPage->getContent());

        $this->get('/lv/admincp')
            ->assertStatus(302)
            ->assertRedirect('/lv/login');

        $admin = User::where('username', 'admin')->firstOrFail();

        foreach ([
            '/lv/admincp',
            '/lv/admincp/sections',
            '/lv/admincp/lenders',
            '/lv/admincp/loantypes',
            '/lv/admincp/faqs',
            '/lv/admincp/bans',
            '/lv/admincp/settings',
        ] as $adminRoute) {
            $this->actingAs($admin)->get($adminRoute)->assertStatus(200);
        }

        // Update white-label brand settings via AdminCP POST
        $saveResponse = $this->actingAs($admin)
            ->from('/lv/admincp/settings')
            ->post('/lv/admincp/settings', [
                'name' => 'MansKredits.lv',
                'brand_tagline' => 'Jaunās paaudzes kredītu salīdzinātājs',
                'contact_email' => 'info@manskredits.lv',
                'brand_disclaimer' => 'Aizņemies atbildīgi!',
                'lang' => ['lv'],
                'sitemap' => 'https://manskredits.lv',
                'prefix' => 'mk',
                'api_key' => 'test_key',
                'individual_days_epc' => '30',
            ]);

        $saveResponse->assertRedirect();
        $this->assertSame('MansKredits.lv', brand_name());
        $this->assertSame('info@manskredits.lv', brand_email());
    }

    public function test_newsletter_subscription_with_captcha_and_hoi_token_creation(): void
    {
        // Rejects wrong CAPTCHA
        $badResponse = $this->withSession(['captcha_code' => 'XY89'])
            ->postJson('/subscribe', [
                'email' => 'abone@credito.lv',
                'captcha' => 'WRONG',
                'consent' => 1,
            ]);

        $badResponse->assertStatus(422);
        $badResponse->assertJsonFragment(['status' => 'error', 'field' => 'captcha']);

        // Accepts valid CAPTCHA and creates consumer + hoi_token
        $goodResponse = $this->withSession(['captcha_code' => 'XY89'])
            ->postJson('/subscribe', [
                'email' => 'abone@credito.lv',
                'captcha' => 'xy89',
                'consent' => 1,
            ]);

        $goodResponse->assertStatus(200);
        $goodResponse->assertJsonFragment(['status' => 'ok']);

        $this->assertDatabaseHas('consumers', [
            'email' => 'abone@credito.lv',
            'subscribed' => 1,
            'email_notifications' => 1,
        ]);

        // Re-subscribing an existing email updates token without fatal redeclare error
        $repeatResponse = $this->withSession(['captcha_code' => 'ZZ22'])
            ->postJson('/subscribe', [
                'email' => 'abone@credito.lv',
                'captcha' => 'zz22',
                'consent' => 1,
            ]);

        $repeatResponse->assertStatus(200);
        $repeatResponse->assertJsonFragment(['status' => 'ok']);
    }
}
