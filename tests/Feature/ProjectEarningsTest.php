<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectEarningsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'new',
            'email' => 'kofiadjo09@gmail.com',
            'wallet_balance' => 0,
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/project-earnings')->assertRedirect('/login');
    }

    public function test_project_earnings_page_loads(): void
    {
        $this->actingAs($this->user)
            ->get('/project-earnings')
            ->assertOk()
            ->assertSee('Project Earnings')
            ->assertSee('Total Earnings')
            ->assertSee('Available Earnings')
            ->assertSee("Today's Earnings")
            ->assertSee('Active Cycles')
            ->assertSee('Green City Apartments')
            ->assertSee('Ocean View Residences')
            ->assertSee('Skyline Offices')
            ->assertSee('+2.35 AVC')
            ->assertSee('+1.15 AVC')
            ->assertSee('+0.85 AVC')
            ->assertSee('Total Today')
            ->assertSee('Earnings by Active Cycle')
            ->assertSee('Shares Owned')
            ->assertSee('Last Credited')
            ->assertSee('View Details')
            ->assertSee('Earnings History')
            ->assertSee('AVC Wallet')
            ->assertSee('About Project Earnings')
            ->assertSee('View All Transactions');
    }

    public function test_filter_by_project_shows_only_that_project(): void
    {
        $this->actingAs($this->user)
            ->get('/project-earnings?project=green-city')
            ->assertOk()
            ->assertSee('Showing earnings for')
            ->assertSee('Green City Apartments')
            ->assertSee('View All Earnings')
            ->assertDontSee('Ocean View Residences');
    }

    public function test_filter_by_real_project_uuid_matches_cycle_title(): void
    {
        $project = Project::create([
            'title' => 'Green City Apartments',
            'location' => 'Manila, Philippines',
            'category' => 'Multi-Family',
            'target_amount' => 100000,
            'minimum_investment' => 100,
            'expected_return_percentage' => 12,
            'investment_duration_months' => 12,
            'status' => 'active',
        ]);

        $this->actingAs($this->user)
            ->get('/project-earnings?project=' . $project->uuid)
            ->assertOk()
            ->assertSee('Showing earnings for')
            ->assertSee('Green City Apartments');
    }

    public function test_unknown_filter_shows_all_earnings(): void
    {
        $this->actingAs($this->user)
            ->get('/project-earnings?project=not-a-real-project')
            ->assertOk()
            ->assertDontSee('Showing earnings for')
            ->assertSee('Green City Apartments')
            ->assertSee('Skyline Offices');
    }

    public function test_admin_can_view_project_earnings(): void
    {
        $admin = User::factory()->create([
            'name' => 'Finance Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get('/project-earnings')
            ->assertOk()
            ->assertSee('Project Earnings');
    }
}
