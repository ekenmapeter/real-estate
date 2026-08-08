<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuidedTourTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_new_user_gets_auto_tour_flag(): void
    {
        $user = User::factory()->create([
            'name' => 'new',
            'email' => 'kofiadjo09@gmail.com',
            'created_at' => now()->subDay(),
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('guidedTour(true, false)', false)
            ->assertSee('Welcome to AVC');
    }

    public function test_existing_user_does_not_get_auto_tour(): void
    {
        $user = User::factory()->create([
            'name' => 'new',
            'email' => 'kofiadjo09@gmail.com',
            'created_at' => now()->subMonths(3),
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('guidedTour(false, false)', false);
    }

    public function test_forced_tour_via_query_param(): void
    {
        $user = User::factory()->create([
            'name' => 'new',
            'email' => 'kofiadjo09@gmail.com',
            'created_at' => now()->subMonths(3),
            'guided_tour_completed_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/dashboard?tour=1')
            ->assertOk()
            ->assertSee('guidedTour(false, true)', false)
            ->assertSee('Welcome to AVC');
    }

    public function test_completed_users_never_get_auto_tour(): void
    {
        $user = User::factory()->create([
            'name' => 'new',
            'email' => 'kofiadjo09@gmail.com',
            'created_at' => now()->subDay(),
            'guided_tour_completed_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('guidedTour(false, false)', false);
    }

    public function test_skipped_users_never_get_auto_tour(): void
    {
        $user = User::factory()->create([
            'name' => 'new',
            'email' => 'kofiadjo09@gmail.com',
            'created_at' => now()->subDay(),
            'guided_tour_skipped_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('guidedTour(false, false)', false);
    }

    public function test_tour_completion_is_persisted(): void
    {
        $user = User::factory()->create([
            'name' => 'new',
            'email' => 'kofiadjo09@gmail.com',
            'created_at' => now()->subDay(),
        ]);

        $this->actingAs($user)
            ->post('/dashboard/tour/complete')
            ->assertRedirect('/dashboard');

        $this->assertNotNull($user->fresh()->guided_tour_completed_at);
    }

    public function test_tour_skip_is_persisted(): void
    {
        $user = User::factory()->create([
            'name' => 'new',
            'email' => 'kofiadjo09@gmail.com',
            'created_at' => now()->subDay(),
        ]);

        $this->actingAs($user)
            ->post('/dashboard/tour/skip')
            ->assertRedirect('/dashboard');

        $this->assertNotNull($user->fresh()->guided_tour_skipped_at);
    }

    public function test_guests_are_redirected_for_tour_actions(): void
    {
        $this->post('/dashboard/tour/complete')->assertRedirect('/login');
        $this->post('/dashboard/tour/skip')->assertRedirect('/login');
    }

    public function test_tour_sections_have_spotlight_targets(): void
    {
        $user = User::factory()->create([
            'name' => 'new',
            'email' => 'kofiadjo09@gmail.com',
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('tour-balance')
            ->assertSee('tour-marketplace')
            ->assertSee('tour-investments')
            ->assertSee('tour-portfolio')
            ->assertSee('tour-sell')
            ->assertSee('tour-withdraw')
            ->assertSee('Welcome to AVC');
    }
}
