<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardOverviewTest extends TestCase
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
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_dashboard_overview_loads(): void
    {
        $this->actingAs($this->user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee($this->expectedGreeting())
            ->assertSee('AVC Credits Balance')
            ->assertSee('Total Deposited')
            ->assertSee('Total Withdrawn')
            ->assertSee('Available Balance')
            ->assertSee('Pending Balance')
            ->assertSee('Quick Actions')
            ->assertSee('Deposit AVC')
            ->assertSee('Send AVC')
            ->assertSee('Receive AVC')
            ->assertSee('Buy Project Shares')
            ->assertSee('Portfolio Summary')
            ->assertSee('Earnings Overview')
            ->assertSee('Active Investments')
            ->assertSee('Luxury Villas')
            ->assertSee('My AVC Card')
            ->assertSee('Apply for AVC Card')
            ->assertSee('Affiliate Summary')
            ->assertSee('Finance Requests')
            ->assertSee('Recent Activity')
            ->assertSee('Recent Transactions')
            ->assertSee('Market Highlights')
            ->assertSee('Documents & Verification')
            ->assertSee('Support Center')
            ->assertSee('RDR-209311');
    }

    public function test_legacy_dashboard_still_works(): void
    {
        $this->actingAs($this->user)
            ->get('/dashboard-legacy')
            ->assertOk();
    }

    public function test_kyc_badge_shows_verified_for_verified_users(): void
    {
        $this->user->update(['kyc_verified' => true, 'kyc_status' => 'approved']);

        $this->actingAs($this->user->fresh())
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Verified');
    }

    public function test_verification_required_badge_for_unverified_users(): void
    {
        $this->actingAs($this->user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Verification Required');
    }

    public function test_admin_can_view_dashboard_overview(): void
    {
        $admin = User::factory()->create([
            'name' => 'Finance Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('AVC Credits Balance');
    }

    protected function expectedGreeting(): string
    {
        $hour = (int) now()->format('G');

        return $hour < 12 ? 'Good Morning' : ($hour < 18 ? 'Good Afternoon' : 'Good Evening');
    }
}
