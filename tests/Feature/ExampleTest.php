<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verify homepage renders resiliently even on a freshly migrated empty database.
     */
    public function test_homepage_renders_on_empty_migrated_database(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
