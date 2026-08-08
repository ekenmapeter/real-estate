<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliateCenterTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Nelson E.',
            'email' => 'nelson@example.com',
            'wallet_balance' => 27500.00,
        ]);

        $this->admin = User::factory()->create([
            'name' => 'Finance Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/affiliate-center')->assertRedirect('/login');
    }

    public function test_affiliate_center_overview_loads(): void
    {
        $this->actingAs($this->user)
            ->get('/affiliate-center')
            ->assertOk()
            ->assertSee('Affiliate Center')
            ->assertSee('Active Affiliate')
            ->assertSee('Gold Partner')
            ->assertSee('AVC483927')
            ->assertSee('Commission Wallet')
            ->assertSee('Conversion Funnel')
            ->assertSee('Monthly Earnings')
            ->assertSee('Assigned Projects')
            ->assertSee('Marketing Center')
            ->assertSee('Recent Referrals')
            ->assertSee('Finance Support')
            ->assertSee('John Smith')
            ->assertSee('Luxury Villas');
    }

    public function test_affiliate_center_uses_real_affiliate_code_when_present(): void
    {
        $this->user->update(['affiliate_code' => 'RAD8849']);

        $this->actingAs($this->user->fresh())
            ->get('/affiliate-center')
            ->assertOk()
            ->assertSee('RAD8849')
            ->assertDontSee('AVC483927');
    }

    public function test_section_page_loads_for_whitelisted_section(): void
    {
        $this->actingAs($this->user)
            ->get('/affiliate-center/my-referrals')
            ->assertOk()
            ->assertSee('My Referrals');

        $this->actingAs($this->user)
            ->get('/affiliate-center/commission-wallet')
            ->assertOk()
            ->assertSee('Commission Wallet');
    }

    public function test_invalid_section_returns_404(): void
    {
        $this->actingAs($this->user)
            ->get('/affiliate-center/not-a-real-section')
            ->assertNotFound();
    }

    public function test_admin_can_view_affiliate_center(): void
    {
        $this->actingAs($this->admin)
            ->get('/affiliate-center')
            ->assertOk()
            ->assertSee('Affiliate Center');
    }
}
