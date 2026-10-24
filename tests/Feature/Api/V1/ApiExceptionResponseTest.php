<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

class ApiExceptionResponseTest extends TestCase
{
    public function test_unknown_api_endpoint_returns_standard_404_response(): void
    {
        $response = $this->getJson('/api/v1/public/does-not-exist');

        $response
            ->assertNotFound()
            ->assertExactJson([
                'success' => false,
                'message' => 'Resource not found.',
                'code' => 'RESOURCE_NOT_FOUND',
                'errors' => null,
            ]);
    }
}
