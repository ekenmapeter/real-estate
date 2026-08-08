<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportCenterTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'new',
            'email' => 'kofiadjo09@gmail.com',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/support')->assertRedirect('/login');
    }

    public function test_support_center_hub_loads(): void
    {
        $this->actingAs($this->user)
            ->get('/support')
            ->assertOk()
            ->assertSee('Support & Help Center')
            ->assertSee('Open Support Request')
            ->assertSee('My Requests')
            ->assertSee('Account Manager')
            ->assertSee('Open Requests')
            ->assertSee('Awaiting User Response')
            ->assertSee('Resolved Requests')
            ->assertSee('Average Response Time')
            ->assertSee('How can we help you?')
            ->assertSee('KYC & Verification')
            ->assertSee('Withdrawal Support')
            ->assertSee('Project Investment')
            ->assertSee('Affiliate Support')
            ->assertSee('Security & Reports')
            ->assertSee('AVC-SUP-2026-008421')
            ->assertSee('Withdrawal is still pending after 48 hours')
            ->assertSee('Live Support')
            ->assertSee('Help Articles & FAQs', false)
            ->assertSee('Schedule a Meeting')
            ->assertSee('Report an Issue')
            ->assertSee('support@avcrealestate.com')
            ->assertSee('Preferred Contact Method');
    }

    public function test_request_detail_page_loads(): void
    {
        $this->actingAs($this->user)
            ->get('/support/AVC-SUP-2026-008421')
            ->assertOk()
            ->assertSee('AVC-SUP-2026-008421')
            ->assertSee('Withdrawal is still pending after 48 hours')
            ->assertSee('Conversation')
            ->assertSee('Status History')
            ->assertSee('Attachments')
            ->assertSee('withdrawal_receipt.pdf')
            ->assertSee('Send Reply')
            ->assertSee('James Carter');
    }

    public function test_unknown_request_reference_returns_404(): void
    {
        $this->actingAs($this->user)
            ->get('/support/AVC-SUP-2026-999999')
            ->assertNotFound();
    }

    public function test_admin_can_view_support_center(): void
    {
        $admin = User::factory()->create([
            'name' => 'Finance Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get('/support')
            ->assertOk()
            ->assertSee('Support & Help Center');
    }
}
