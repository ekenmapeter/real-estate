<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectDurationTier;
use App\Models\ProjectShareCycle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectMarketplaceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        // Create verified investor user
        $this->user = User::factory()->create([
            'name' => 'Sarah Jenkins',
            'email' => 'sarah@example.com',
            'wallet_balance' => 27500.00,
            'kyc_verified' => true,
            'kyc_status' => 'approved',
        ]);

        // Create active project
        $this->project = Project::create([
            'title' => 'Suburban Garden Homes',
            'location' => 'Cebu City, Philippines',
            'category' => 'Multi-Family',
            'target_amount' => 350000.00,
            'share_price' => 100.00,
            'minimum_investment' => 100.00,
            'expected_return_percentage' => 16.00,
            'investment_duration_months' => 12,
            'funding_closing_date' => now()->addDays(362),
            'rating' => 4.1,
            'description' => 'Gated community of 20 single-family homes.',
            'status' => 'active',
            'is_verified' => true,
        ]);

        // Create duration tier (14 Days: 10 shares required, 4% return)
        ProjectDurationTier::create([
            'project_id' => $this->project->id,
            'duration_key' => '14_days',
            'duration_label' => '14 Days',
            'duration_days' => 14,
            'required_shares' => 10,
            'min_avc_value' => 1000.00,
            'target_earnings_pct' => 4.00,
            'is_popular' => true,
        ]);
    }

    public function test_marketplace_catalog_loads_successfully()
    {
        $response = $this->get('/project-marketplace');

        $response->assertStatus(200);
        $response->assertSee('Project Marketplace');
        $response->assertSee('Suburban Garden Homes');
    }

    public function test_view_project_page_loads_successfully()
    {
        $response = $this->get('/project-marketplace/' . $this->project->uuid);

        $response->assertStatus(200);
        $response->assertSee('Suburban Garden Homes');
        $response->assertSee('Available Earning Durations');
        $response->assertSee('14 Days Cycle');
    }

    public function test_share_earnings_calculator_endpoint()
    {
        $response = $this->postJson('/project-marketplace/' . $this->project->uuid . '/calculate', [
            'duration_key' => '14_days',
            'shares' => 10,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'shares' => 10,
            'share_price' => 100.00,
            'purchase_amount' => 1000.00,
            'target_earnings_pct' => 4.00,
            'projected_earnings' => 40.00,
            'completion_value' => 1040.00,
            'activation_status' => 'Active',
        ]);
    }

    public function test_user_cannot_buy_shares_with_insufficient_balance()
    {
        $this->user->update(['wallet_balance' => 500.00]); // Less than 1000 AVC needed

        $response = $this->actingAs($this->user)
            ->post('/project-marketplace/' . $this->project->uuid . '/buy', [
                'duration_key' => '14_days',
                'shares' => 10,
            ]);

        $response->assertSessionHas('error');
        $this->assertEquals(500.00, $this->user->fresh()->wallet_balance);
        $this->assertEquals(0, ProjectShareCycle::count());
    }

    public function test_purchasing_shares_below_required_threshold_creates_pending_activation_cycle()
    {
        // Buy 5 shares (10 required for activation)
        $response = $this->actingAs($this->user)
            ->post('/project-marketplace/' . $this->project->uuid . '/buy', [
                'duration_key' => '14_days',
                'shares' => 5,
            ]);

        $response->assertRedirect(route('portfolio.index'));
        
        // User balance deducted 5 * 100 = 500
        $this->assertEquals(27000.00, $this->user->fresh()->wallet_balance);

        // Cycle created with status pending_activation
        $cycle = ProjectShareCycle::first();
        $this->assertNotNull($cycle);
        $this->assertEquals('pending_activation', $cycle->status);
        $this->assertEquals(5, $cycle->shares_owned);
        $this->assertEquals(10, $cycle->required_shares);
        $this->assertNull($cycle->activated_at);
        $this->assertNull($cycle->completion_date);
    }

    public function test_topping_up_shares_to_meet_threshold_activates_earning_cycle()
    {
        // 1. Initial purchase of 5 shares (Pending)
        $this->actingAs($this->user)
            ->post('/project-marketplace/' . $this->project->uuid . '/buy', [
                'duration_key' => '14_days',
                'shares' => 5,
            ]);

        $cycle = ProjectShareCycle::first();
        $this->assertEquals('pending_activation', $cycle->status);

        // 2. Top-up purchase of remaining 5 shares
        $this->actingAs($this->user)
            ->post('/project-marketplace/' . $this->project->uuid . '/buy', [
                'duration_key' => '14_days',
                'shares' => 5,
            ]);

        $updatedCycle = $cycle->fresh();
        $this->assertEquals(10, $updatedCycle->shares_owned);
        $this->assertEquals('active', $updatedCycle->status);
        $this->assertNotNull($updatedCycle->activated_at);
        $this->assertNotNull($updatedCycle->completion_date);
        $this->assertEquals(1000.00, $updatedCycle->total_purchase_amount);
        $this->assertEquals(40.00, $updatedCycle->projected_earnings);
        $this->assertEquals(1040.00, $updatedCycle->completion_value);
    }

    public function test_my_portfolio_page_loads_for_authenticated_user()
    {
        // Seed an active cycle
        ProjectShareCycle::create([
            'user_id' => $this->user->id,
            'project_id' => $this->project->id,
            'duration_key' => '14_days',
            'duration_label' => '14 Days',
            'duration_days' => 14,
            'shares_owned' => 10,
            'required_shares' => 10,
            'share_price' => 100.00,
            'total_purchase_amount' => 1000.00,
            'target_earnings_pct' => 4.00,
            'projected_earnings' => 40.00,
            'completion_value' => 1040.00,
            'status' => 'active',
            'purchased_at' => now(),
            'activated_at' => now(),
            'completion_date' => now()->addDays(14),
        ]);

        $response = $this->actingAs($this->user)->get('/my-portfolio');

        $response->assertStatus(200);
        $response->assertSee('My Portfolio');
        $response->assertSee('Active Cycles');
        $response->assertSee('Suburban Garden Homes');
        $response->assertSee('1,000 AVC');
    }

    public function test_cycle_receipt_page_loads()
    {
        $cycle = ProjectShareCycle::create([
            'user_id' => $this->user->id,
            'project_id' => $this->project->id,
            'duration_key' => '14_days',
            'duration_label' => '14 Days',
            'duration_days' => 14,
            'shares_owned' => 10,
            'required_shares' => 10,
            'share_price' => 100.00,
            'total_purchase_amount' => 1000.00,
            'target_earnings_pct' => 4.00,
            'projected_earnings' => 40.00,
            'completion_value' => 1040.00,
            'status' => 'active',
            'purchased_at' => now(),
            'activated_at' => now(),
            'completion_date' => now()->addDays(14),
        ]);

        $response = $this->actingAs($this->user)->get('/my-portfolio/cycle/' . $cycle->id . '/receipt');

        $response->assertStatus(200);
        $response->assertSee($cycle->receipt_number);
        $response->assertSee('Official Project Share Cycle Certificate');
    }

    public function test_process_cycle_earnings_command_credits_matured_cycle_to_user_wallet()
    {
        // Create an active cycle whose completion date is in the past
        $cycle = ProjectShareCycle::create([
            'user_id' => $this->user->id,
            'project_id' => $this->project->id,
            'duration_key' => '14_days',
            'duration_label' => '14 Days',
            'duration_days' => 14,
            'shares_owned' => 10,
            'required_shares' => 10,
            'share_price' => 100.00,
            'total_purchase_amount' => 1000.00,
            'target_earnings_pct' => 4.00,
            'projected_earnings' => 40.00,
            'completion_value' => 1040.00,
            'status' => 'active',
            'purchased_at' => now()->subDays(15),
            'activated_at' => now()->subDays(15),
            'completion_date' => now()->subDay(), // Matured 1 day ago
        ]);

        $initialBalance = $this->user->wallet_balance; // 27500.00

        // Run artisan command
        $this->artisan('projects:process-earnings')
            ->assertExitCode(0);

        // Verify user received principal + ROI (1040.00 AVC)
        $this->assertEquals($initialBalance + 1040.00, $this->user->fresh()->wallet_balance);
        $this->assertEquals('completed', $cycle->fresh()->status);
        $this->assertNotNull($cycle->fresh()->earnings_credited_at);
    }
}
