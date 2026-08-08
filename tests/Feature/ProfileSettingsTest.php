<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileSettingsTest extends TestCase
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
        $this->get('/profile-settings')->assertRedirect('/login');
    }

    public function test_profile_settings_page_loads(): void
    {
        $this->actingAs($this->user)
            ->get('/profile-settings')
            ->assertOk()
            ->assertSee('Profile & Settings')
            ->assertSee('Manage your account, security and identity verification.')
            ->assertSee('RDR-209311')
            ->assertSee('AVC8X7K2')
            ->assertSee('Gold Member')
            ->assertSee('AVC Balance')
            ->assertSee('Total Portfolio Value')
            ->assertSee('Lifetime Earnings')
            ->assertSee('Affiliate Earnings')
            ->assertSee('Pending Deposits')
            ->assertSee('Pending Withdrawals')
            ->assertSee('KYC Status')
            ->assertSee('KYC Verification')
            ->assertSee('Security Overview')
            ->assertSee('Quick Actions')
            ->assertSee('Linked Accounts')
            ->assertSee('Upgrade to VIP')
            ->assertSee('Personal Information')
            ->assertSee('Contact Information')
            ->assertSee('Account Information');
    }

    public function test_page_uses_real_account_data_when_present(): void
    {
        $this->user->update([
            'account_id' => 'AVC-209311',
            'affiliate_code' => 'RAD8849',
            'preferred_currency' => 'PHP',
            'wallet_balance' => 1200,
        ]);

        $this->actingAs($this->user->fresh())
            ->get('/profile-settings')
            ->assertOk()
            ->assertSee('AVC-209311')
            ->assertSee('RAD8849')
            ->assertSee('PHP')
            ->assertSee('1,200 AVC')
            ->assertDontSee('RDR-209311')
            ->assertDontSee('AVC8X7K2');
    }

    public function test_all_tabs_are_present_in_tab_bar(): void
    {
        $this->actingAs($this->user)
            ->get('/profile-settings')
            ->assertOk()
            ->assertSee('Profile')
            ->assertSee('Security')
            ->assertSee('KYC Verification')
            ->assertSee('Notifications')
            ->assertSee('Preferences')
            ->assertSee('Linked Accounts');
    }

    public function test_kyc_progress_section_renders(): void
    {
        $this->actingAs($this->user)
            ->get('/profile-settings')
            ->assertOk()
            ->assertSee('Verification Progress')
            ->assertSee('60%')
            ->assertSee('Government ID')
            ->assertSee('Selfie Verification')
            ->assertSee('Proof of Address')
            ->assertSee('Additional Verification')
            ->assertSee('Submit KYC Documents');
    }

    public function test_admin_can_view_profile_settings(): void
    {
        $admin = User::factory()->create([
            'name' => 'Finance Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get('/profile-settings')
            ->assertOk()
            ->assertSee('Profile & Settings');
    }
}
