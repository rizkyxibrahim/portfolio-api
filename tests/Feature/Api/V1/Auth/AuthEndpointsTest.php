<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuthEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_verification_email(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'username' => 'testuser123',
            'email' => 'testuser@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'locale' => 'en',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('code', 'REGISTRATION_SUCCESSFUL');

        $user = User::where('email', 'testuser@example.com')->firstOrFail();

        $this->assertSame('user', $user->role);
        $this->assertNull($user->email_verified_at);

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_user_can_login_with_username_email_or_phone(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser123',
            'email' => 'testuser@example.com',
            'phone' => '+6281234567890',
            'password' => 'Password123!',
        ]);

        foreach (
            [
                'testuser123',
                'testuser@example.com',
                '+6281234567890',
            ] as $identifier
        ) {
            $this->postJson('/api/v1/auth/login', [
                'identifier' => $identifier,
                'password' => 'Password123!',
            ])
                ->assertOk()
                ->assertJsonPath('success', true)
                ->assertJsonPath('code', 'LOGIN_SUCCESSFUL')
                ->assertJsonPath('data.token_type', 'Bearer');
        }
    }

    public function test_login_fails_with_incorrect_password(): void
    {
        User::factory()->create([
            'username' => 'testuser123',
            'email' => 'testuser@example.com',
            'password' => 'Password123!',
        ]);

        $this->postJson('/api/v1/auth/login', [
            'identifier' => 'testuser123',
            'password' => 'WrongPassword123!',
        ])
            ->assertUnauthorized()
            ->assertJsonPath('success', false)
            ->assertJsonPath('code', 'INVALID_CREDENTIALS');
    }

    public function test_authenticated_user_can_retrieve_profile_and_logout(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('test-device')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.id', $user->id);

        $this->withToken($token)
            ->postJson('/api/v1/auth/logout')
            ->assertOk()
            ->assertJsonPath('code', 'LOGOUT_SUCCESSFUL');

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name' => 'test-device',
        ]);
    }

    public function test_email_verification_requires_a_valid_signed_url(): void
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->getEmailForVerification()),
            ],
        );

        $this->getJson($verificationUrl)
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('code', 'EMAIL_VERIFIED');

        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
