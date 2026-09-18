<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_forms_use_https_behind_the_render_proxy(): void
    {
        $proxyHeaders = [
            'X-Forwarded-For' => '203.0.113.10',
            'X-Forwarded-Port' => '443',
            'X-Forwarded-Proto' => 'https',
        ];

        $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.10'])
            ->withHeaders($proxyHeaders)
            ->get('/')
            ->assertOk()
            ->assertSee('action="https://localhost/confirmacion"', false);

        $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.10'])
            ->withHeaders($proxyHeaders)
            ->get('/protocolo-traje')
            ->assertOk()
            ->assertSee('action="https://localhost/protocolo-traje"', false);
    }
}
