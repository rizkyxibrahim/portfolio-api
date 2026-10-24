<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HealthEndpointTest extends TestCase
{
    public function test_health_endpoint_returns_standard_json_response(): void
    {
        $response = $this->getJson('/api/v1/public/health');

        $response
            ->assertOk()
            ->assertExactJson([
                'success' => true,
                'message' => 'API is running.',
                'code' => 'API_HEALTHY',
                'data' => [
                    'status' => 'ok',
                ],
            ]);
    }
}
