<?php

namespace Tests\Feature\Api\V1;

use App\Models\FinanceTransaction;
use App\Models\PortfolioProject;
use App\Models\PortfolioSkill;
use App\Models\Reminder;
use App\Models\Schedule;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DomainApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'username' => 'testuser',
        ]);
    }

    protected function getAuthHeaders(): array
    {
        $token = $this->user->createToken('test-token')->plainTextToken;
        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    // ============ Authentication ============

    public function test_login_with_username(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'identity' => 'testuser',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status', 'code', 'message', 'data', 'meta'
            ])
            ->assertJson([
                'status' => 'success',
                'code' => 'LOGIN_SUCCESS',
            ]);
    }

    public function test_login_with_email(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'identity' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 'LOGIN_SUCCESS']);
    }

    public function test_login_with_wrong_password(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'identity' => 'testuser',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['code' => 'AUTHENTICATION_REQUIRED']);
    }

    public function test_get_me(): void
    {
        $response = $this->withHeaders($this->getAuthHeaders())
            ->getJson('/api/v1/auth/me');

        $response->assertStatus(200)
            ->assertJson(['code' => 'PROFILE_RETRIEVED'])
            ->assertJsonStructure([
                'status', 'code', 'message', 'data', 'meta'
            ]);
    }

    public function test_logout(): void
    {
        $response = $this->withHeaders($this->getAuthHeaders())
            ->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['code' => 'LOGOUT_SUCCESS']);
    }

    // ============ Todos ============

    public function test_create_todo_with_scheduling(): void
    {
        $response = $this->withHeaders($this->getAuthHeaders())
            ->postJson('/api/v1/todos', [
                'title' => 'Test Todo',
                'description' => 'Test description',
                'scheduled_start_at' => '2026-10-28T09:00:00',
                'scheduled_end_at' => '2026-10-28T10:30:00',
                'due_at' => '2026-10-29T17:00:00',
                'priority' => 'high',
            ]);

        $response->assertStatus(201)
            ->assertJson(['code' => 'TODO_CREATED'])
            ->assertJsonStructure([
                'status', 'code', 'message', 'data', 'meta'
            ]);

        $this->assertDatabaseHas('todos', [
            'title' => 'Test Todo',
            'scheduled_start_at' => '2026-10-28 09:00:00',
            'due_at' => '2026-10-29 17:00:00',
        ]);
    }

    public function test_create_todo_without_scheduling(): void
    {
        $response = $this->withHeaders($this->getAuthHeaders())
            ->postJson('/api/v1/todos', [
                'title' => 'Simple Todo',
            ]);

        $response->assertStatus(201)
            ->assertJson(['code' => 'TODO_CREATED']);

        $this->assertDatabaseHas('todos', [
            'title' => 'Simple Todo',
            'scheduled_start_at' => null,
            'due_at' => null,
        ]);
    }

    public function test_get_todos(): void
    {
        Todo::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->getAuthHeaders())
            ->getJson('/api/v1/todos');

        $response->assertStatus(200)
            ->assertJson(['code' => 'TODOS_RETRIEVED']);
    }

    public function test_todo_ownership_isolation(): void
    {
        $otherUser = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->withHeaders($this->getAuthHeaders())
            ->getJson("/api/v1/todos/{$todo->id}");

        $response->assertStatus(403);
    }

    public function test_update_todo(): void
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->getAuthHeaders())
            ->putJson("/api/v1/todos/{$todo->id}", [
                'title' => 'Updated Todo',
                'is_completed' => true,
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 'TODO_UPDATED']);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => 'Updated Todo',
            'is_completed' => true,
        ]);
    }

    public function test_delete_todo(): void
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->getAuthHeaders())
            ->deleteJson("/api/v1/todos/{$todo->id}");

        $response->assertStatus(200)
            ->assertJson(['code' => 'TODO_DELETED']);

        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }

    // ============ Schedules ============

    public function test_create_schedule_with_recurring(): void
    {
        $response = $this->withHeaders($this->getAuthHeaders())
            ->postJson('/api/v1/schedules', [
                'title' => 'Weekly Meeting',
                'start_time' => '2026-10-28T22:00:00',
                'end_time' => '2026-10-29T06:00:00',
                'recurring' => true,
                'recurring_pattern' => 'weekly',
                'timezone' => 'Asia/Jakarta',
                'is_overnight' => true,
            ]);

        $response->assertStatus(201)
            ->assertJson(['code' => 'SCHEDULE_CREATED']);

        $this->assertDatabaseHas('schedules', [
            'title' => 'Weekly Meeting',
            'recurring' => true,
            'recurring_pattern' => 'weekly',
            'timezone' => 'Asia/Jakarta',
            'is_overnight' => true,
        ]);
    }

    public function test_get_schedules(): void
    {
        Schedule::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->getAuthHeaders())
            ->getJson('/api/v1/schedules');

        $response->assertStatus(200)
            ->assertJson(['code' => 'SCHEDULES_RETRIEVED']);
    }

    // ============ Reminders ============

    public function test_create_reminder_with_recurring_and_delivery(): void
    {
        $response = $this->withHeaders($this->getAuthHeaders())
            ->postJson('/api/v1/reminders', [
                'title' => 'Daily Standup',
                'reminder_at' => '2026-10-28T08:00:00',
                'recurring' => true,
                'recurring_pattern' => 'daily',
                'delivery_status' => 'pending',
                'dedupe_key' => 'daily-standup-2026',
            ]);

        $response->assertStatus(201)
            ->assertJson(['code' => 'REMINDER_CREATED']);

        $this->assertDatabaseHas('reminders', [
            'title' => 'Daily Standup',
            'recurring' => true,
            'recurring_pattern' => 'daily',
            'delivery_status' => 'pending',
            'dedupe_key' => 'daily-standup-2026',
        ]);
    }

    public function test_get_reminders(): void
    {
        Reminder::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->getAuthHeaders())
            ->getJson('/api/v1/reminders');

        $response->assertStatus(200)
            ->assertJson(['code' => 'REMINDERS_RETRIEVED']);
    }

    // ============ Finance Transactions ============

    public function test_create_finance_transaction(): void
    {
        $response = $this->withHeaders($this->getAuthHeaders())
            ->postJson('/api/v1/finance/transactions', [
                'type' => 'expense',
                'amount' => 75000,
                'category' => 'makanan',
                'transaction_date' => '2026-10-27',
            ]);

        $response->assertStatus(201)
            ->assertJson(['code' => 'TRANSACTION_CREATED']);

        $this->assertDatabaseHas('finance_transactions', [
            'type' => 'expense',
            'amount' => 75000,
            'category' => 'makanan',
        ]);
    }

    public function test_get_finance_transactions(): void
    {
        FinanceTransaction::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->getAuthHeaders())
            ->getJson('/api/v1/finance/transactions');

        $response->assertStatus(200)
            ->assertJson(['code' => 'TRANSACTIONS_RETRIEVED']);
    }

    // ============ Portfolio Skills ============

    public function test_create_portfolio_skill(): void
    {
        $response = $this->withHeaders($this->getAuthHeaders())
            ->postJson('/api/v1/portfolio/skills', [
                'name' => 'PHP',
                'category' => 'programming',
                'level' => 'advanced',
                'years_of_experience' => 5,
            ]);

        $response->assertStatus(201)
            ->assertJson(['code' => 'PORTFOLIO_SKILL_CREATED']);

        $this->assertDatabaseHas('portfolio_skills', [
            'name' => 'PHP',
            'category' => 'programming',
            'level' => 'advanced',
        ]);
    }

    public function test_get_portfolio_skills(): void
    {
        PortfolioSkill::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->getAuthHeaders())
            ->getJson('/api/v1/portfolio/skills');

        $response->assertStatus(200)
            ->assertJson(['code' => 'PORTFOLIO_SKILLS_RETRIEVED']);
    }

    // ============ Portfolio Projects ============

    public function test_create_portfolio_project(): void
    {
        $response = $this->withHeaders($this->getAuthHeaders())
            ->postJson('/api/v1/portfolio/projects', [
                'title' => 'Xibra API',
                'description' => 'Central REST API',
                'github_url' => 'https://github.com/rizkyxibrahim/xibra-api',
                'is_featured' => true,
            ]);

        $response->assertStatus(201)
            ->assertJson(['code' => 'PORTFOLIO_PROJECT_CREATED']);

        $this->assertDatabaseHas('portfolio_projects', [
            'title' => 'Xibra API',
            'is_featured' => true,
        ]);
    }

    public function test_get_portfolio_projects(): void
    {
        PortfolioProject::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->getAuthHeaders())
            ->getJson('/api/v1/portfolio/projects');

        $response->assertStatus(200)
            ->assertJson(['code' => 'PORTFOLIO_PROJECTS_RETRIEVED']);
    }

    // ============ Public Endpoints ============

    public function test_public_profile(): void
    {
        $response = $this->getJson('/api/v1/public/profile');

        $response->assertStatus(200)
            ->assertJson(['code' => 'PUBLIC_PROFILE_RETRIEVED']);
    }

    public function test_public_projects(): void
    {
        $response = $this->getJson('/api/v1/public/projects');

        $response->assertStatus(200)
            ->assertJson(['code' => 'PUBLIC_PROJECTS_RETRIEVED']);
    }
}