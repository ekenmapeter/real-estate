<?php

namespace Tests\Feature;

use App\Models\Deposit;
use App\Models\PaymentChannel;
use App\Models\User;
use App\Models\WalletLedger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AvcDepositSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;
    protected PaymentChannel $channel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'John Smith',
            'email' => 'john@example.com',
            'wallet_balance' => 500.00,
            'role' => 'user',
        ]);

        $this->admin = User::factory()->create([
            'name' => 'Finance Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $this->channel = PaymentChannel::create([
            'method_key' => 'bank_transfer',
            'channel_name' => 'BDO Unibank Deposit',
            'account_name' => 'Aurevia Corp',
            'account_number' => '0081-2299-4410',
            'country' => 'Philippines',
            'currency' => 'PHP',
            'min_deposit_amount' => 10.00,
            'status' => 'active',
        ]);
    }

    public function test_deposit_hub_page_loads_for_authenticated_user()
    {
        $response = $this->actingAs($this->user)->get('/deposit');

        $response->assertStatus(200);
        $response->assertSee('Deposit / Buy AVC');
        $response->assertSee('500 AVC');
        $response->assertSee('Deposit Through Finance Team');
        $response->assertSee('Buy AVC From Marketplace');
    }

    public function test_payment_channel_selection_page_loads()
    {
        $response = $this->actingAs($this->user)->get('/deposit/channel/bank_transfer');

        $response->assertStatus(200);
        $response->assertSee('Bank Transfer / GCash');
        $response->assertSee('Payment Instructions');
    }

    public function test_user_creates_bank_transfer_deposit_request()
    {
        $response = $this->actingAs($this->user)->post('/deposit/create', [
            'payment_method' => 'bank_transfer',
            'amount' => 500.00,
            'deposit_currency' => 'USD',
            'sender_account_name' => 'John Smith',
            'sender_bank_name' => 'BDO Unibank',
            'country' => 'Philippines',
        ]);

        $response->assertSessionHasNoErrors();
        $deposit = Deposit::where('user_id', $this->user->id)->first();
        $this->assertNotNull($deposit, 'Deposit should have been created. Response: ' . $response->getContent());
        $this->assertEquals(500.00, (float) $deposit->amount);
        $this->assertEquals(500.00, (float) $deposit->net_avc);
        $this->assertEquals('submitted', $deposit->status);
        $response->assertRedirect(route('deposit.show', $deposit->id));
    }

    public function test_crypto_deposit_prevents_duplicate_tx_hash()
    {
        $hash = '0x1234567890abcdef1234567890abcdef';

        Deposit::create([
            'user_id' => $this->user->id,
            'deposit_code' => 'DEP-EXISTING',
            'payment_method' => 'crypto',
            'amount' => 100.00,
            'tx_hash' => $hash,
            'status' => 'payment_submitted',
        ]);

        $response = $this->actingAs($this->user)->post('/deposit/create', [
            'payment_method' => 'crypto',
            'amount' => 200.00,
            'crypto_asset' => 'USDT',
            'crypto_network' => 'TRC-20',
            'tx_hash' => $hash,
        ]);

        $response->assertSessionHasErrors('tx_hash');
    }

    public function test_admin_assigns_payment_instructions_with_expiration_timer()
    {
        $deposit = Deposit::create([
            'user_id' => $this->user->id,
            'deposit_code' => 'DEP-2026-000100',
            'payment_method' => 'bank_transfer',
            'amount' => 500.00,
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/deposits/' . $deposit->id . '/assign-instructions', [
            'beneficiary_name' => 'Aurevia Real Estate Inc',
            'bank_or_provider' => 'BDO Unibank',
            'account_number' => '0081-2299-4410',
            'expiration_minutes' => 30,
        ]);

        $response->assertSessionHasNoErrors();
        $updatedDeposit = $deposit->fresh();
        $this->assertEquals('payment_instructions_assigned', $updatedDeposit->status);
        $this->assertNotNull($updatedDeposit->admin_instructions);
        $this->assertNotNull($updatedDeposit->expires_at);
        $this->assertTrue($updatedDeposit->expires_at->isFuture());
    }

    public function test_user_submits_payment_proof()
    {
        $deposit = Deposit::create([
            'user_id' => $this->user->id,
            'deposit_code' => 'DEP-2026-000101',
            'payment_method' => 'bank_transfer',
            'amount' => 500.00,
            'status' => 'payment_instructions_assigned',
        ]);

        $file = UploadedFile::fake()->create('proof.jpg', 500);

        $response = $this->actingAs($this->user)->post('/deposit/' . $deposit->id . '/proof', [
            'payment_proof' => $file,
            'user_notes' => 'Paid via BDO online mobile app',
        ]);

        $updatedDeposit = $deposit->fresh();
        $this->assertEquals('payment_submitted', $updatedDeposit->status);
        $this->assertNotNull($updatedDeposit->receipt_proof);
        $this->assertEquals('Paid via BDO online mobile app', $updatedDeposit->user_notes);
    }

    public function test_admin_credits_avc_uses_wallet_ledger_and_prevents_duplicate_crediting()
    {
        $deposit = Deposit::create([
            'user_id' => $this->user->id,
            'deposit_code' => 'DEP-2026-000102',
            'payment_method' => 'bank_transfer',
            'amount' => 500.00,
            'net_avc' => 500.00,
            'status' => 'payment_submitted',
        ]);

        $initialBalance = $this->user->wallet_balance; // 500.00

        // 1. Credit AVC
        $response = $this->actingAs($this->admin)->post('/admin/deposits/' . $deposit->id . '/credit-avc');

        $response->assertSessionHas('success');
        $this->assertEquals($initialBalance + 500.00, $this->user->fresh()->wallet_balance);
        $this->assertEquals('avc_credited', $deposit->fresh()->status);
        $this->assertNotNull($deposit->fresh()->credited_at);

        // Verify Wallet Ledger record created
        $ledger = WalletLedger::where('deposit_id', $deposit->id)->first();
        $this->assertNotNull($ledger);
        $this->assertEquals(500.00, $ledger->credit_amount);

        // 2. Attempt duplicate credit -> Should be blocked and not double credit!
        $response2 = $this->actingAs($this->admin)->post('/admin/deposits/' . $deposit->id . '/credit-avc');
        $response2->assertSessionHas('error');
        $this->assertEquals($initialBalance + 500.00, $this->user->fresh()->wallet_balance); // Balance remains unchanged!
    }

    public function test_deposit_request_detail_page_loads()
    {
        $deposit = Deposit::create([
            'user_id' => $this->user->id,
            'deposit_code' => 'DEP-2026-000103',
            'payment_method' => 'bank_transfer',
            'amount' => 500.00,
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($this->user)->get('/deposit/' . $deposit->id);

        $response->assertStatus(200);
        $response->assertSee('DEP-2026-000103');
        $response->assertSee('Request Status Timeline');
    }

    public function test_user_can_cancel_deposit_request()
    {
        $deposit = Deposit::create([
            'user_id' => $this->user->id,
            'deposit_code' => 'DEP-2026-000104',
            'payment_method' => 'bank_transfer',
            'amount' => 500.00,
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($this->user)->post('/deposit/' . $deposit->id . '/cancel');

        $this->assertEquals('cancelled', $deposit->fresh()->status);
    }
}
