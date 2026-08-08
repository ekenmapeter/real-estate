<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvcTransferTest extends TestCase
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
        $this->get('/transfer')->assertRedirect('/login');
    }

    public function test_transfer_hub_loads(): void
    {
        $this->actingAs($this->user)
            ->get('/transfer')
            ->assertOk()
            ->assertSee('AVC Transfer')
            ->assertSee('Send and receive AVC Credits between verified AVC members.')
            ->assertSee('Available AVC Balance')
            ->assertSee('Daily Transfer Limit')
            ->assertSee('Monthly Transfer Usage')
            ->assertSee('Transfer Fee')
            ->assertSee('Transfers Today')
            ->assertSee('Send AVC')
            ->assertSee('Receive AVC')
            ->assertSee('Transfer History')
            ->assertSee('TRF-2026-000482')
            ->assertSee('TRF-2026-000479')
            ->assertSee('Sarah Jenkins');
    }

    public function test_send_page_loads(): void
    {
        $this->actingAs($this->user)
            ->get('/transfer/send')
            ->assertOk()
            ->assertSee('Transfer Amount (AVC)')
            ->assertSee('Search Recipient')
            ->assertSee('Transfer Note')
            ->assertSee('Transaction Summary')
            ->assertSee('Recipient receives')
            ->assertSee('Continue to PIN Confirmation')
            ->assertSee('sarah@example.com')
            ->assertSee('RDR-884901');
    }

    public function test_receive_page_loads(): void
    {
        $this->actingAs($this->user)
            ->get('/transfer/receive')
            ->assertOk()
            ->assertSee('Receive AVC')
            ->assertSee('Personal AVC QR Code')
            ->assertSee('AVC ID')
            ->assertSee('Registered Email')
            ->assertSee('Username')
            ->assertSee('RDR-209311')
            ->assertSee('kofiadjo09@gmail.com')
            ->assertSee('Receive AVC Credits only from')
            ->assertSee('Share Details');
    }

    public function test_receipt_page_loads(): void
    {
        $this->actingAs($this->user)
            ->get('/transfer/history/TRF-2026-000482')
            ->assertOk()
            ->assertSee('AVC Transfer Receipt')
            ->assertSee('TRF-2026-000482')
            ->assertSee('Transfer Fee')
            ->assertSee('Sarah Jenkins')
            ->assertSee('Monthly rent share')
            ->assertSee('Download Receipt');

        $this->actingAs($this->user)
            ->get('/transfer/history/TRF-2026-999999')
            ->assertNotFound();
    }

    public function test_pin_verification_endpoint_accepts_valid_pin(): void
    {
        $this->user->update(['transaction_pin' => bcrypt('8849')]);

        $this->actingAs($this->user->fresh())
            ->postJson('/transfer/pin/verify', ['pin' => '8849'])
            ->assertOk()
            ->assertJson(['valid' => true]);
    }

    public function test_pin_verification_endpoint_rejects_invalid_pin(): void
    {
        $this->user->update(['transaction_pin' => bcrypt('8849')]);

        $this->actingAs($this->user->fresh())
            ->postJson('/transfer/pin/verify', ['pin' => '0000'])
            ->assertOk()
            ->assertJson(['valid' => false]);
    }

    public function test_pin_verification_endpoint_requires_pin_setup_when_missing(): void
    {
        $this->actingAs($this->user)
            ->postJson('/transfer/pin/verify', ['pin' => '8849'])
            ->assertOk()
            ->assertJson(['valid' => false, 'needs_pin' => true]);
    }

    public function test_dashboard_still_loads_with_new_transfer_menu(): void
    {
        $this->actingAs($this->user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('AVC Transfer');
    }

    public function test_admin_can_view_transfer_center(): void
    {
        $admin = User::factory()->create([
            'name' => 'Finance Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get('/transfer')
            ->assertOk()
            ->assertSee('AVC Transfer');
    }
}
