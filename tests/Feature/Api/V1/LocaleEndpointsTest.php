<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_locale_list_returns_supported_languages(): void
    {
        $this->getJson('/api/v1/public/locales')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('code', 'LOCALES_RETRIEVED')
            ->assertJsonCount(2, 'data');
    }

    public function test_authenticated_user_can_update_locale(): void
    {
        $user = User::factory()->create([
            'locale' => 'en',
        ]);

        $this->actingAs($user, 'sanctum')
            ->putJson('/api/v1/auth/locale', [
                'locale' => 'id',
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('code', 'LOCALE_UPDATED')
            ->assertJsonPath('data.locale', 'id')
            ->assertHeader('Content-Language', 'id');

        $this->assertSame('id', $user->fresh()->locale);
    }

    public function test_guest_cannot_update_locale(): void
    {
        $this->putJson('/api/v1/auth/locale', [
            'locale' => 'id',
        ])
            ->assertUnauthorized()
            ->assertJsonPath('success', false);
    }

    public function test_unsupported_locale_is_rejected(): void
    {
        $user = User::factory()->create([
            'locale' => 'en',
        ]);

        $this->actingAs($user, 'sanctum')
            ->putJson('/api/v1/auth/locale', [
                'locale' => 'fr',
            ])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('code', 'VALIDATION_FAILED');

        $this->assertSame('en', $user->fresh()->locale);
    }
}
