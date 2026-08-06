<?php

namespace Tests\Feature;

use App\Models\SavedWithdrawalMethod;
use App\Models\User;
use App\Models\WalletLedger;
use App\Models\Withdrawal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvcWithdrawalSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@example.com',
            'wallet_balance' => 1000.00,
            'pending_withdrawals' => 0.00,
            'daily_withdrawal_limit' => 10000.00,
            'role' => 'user',
        ]);

        $this->admin = User::factory()->create([
            'name' => 'Finance Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
    }

    public function test_withdrawal_hub_page_loads_for_authenticated_user()
    {
        $response = $this->actingAs($this->user)->get('/withdraw');

        $response->assertStatus(200);
        $response->assertSee('Withdraw / Sell AVC');
        $response->assertSee('1,000');
        $response->assertSee('Withdraw Through Finance Team');
        $response->assertSee('Sell AVC on Marketplace');
    }

    public function test_user_submits_bank_withdrawal_and_locks_pending_balance()
    {
        $response = $this->actingAs($this->user)->post('/withdraw/create', [
            'withdrawal_method' => 'bank_transfer',
            'amount' => 500.00,
            'account_name' => 'Juan Dela Cruz',
            'bank_or_provider' => 'BDO Unibank',
            'account_number' => '123456789012',
            'confirm_checkbox' => '1',
            'password' => 'password', // User factory default password
        ]);

        $response->assertSessionHasNoErrors();

        // Verify balance shift: Available = 500.00, Pending = 500.00
        $this->user->refresh();
        $this->assertEquals(500.00, (float) $this->user->wallet_balance);
        $this->assertEquals(500.00, (float) $this->user->pending_withdrawals);

        $withdrawal = Withdrawal::where('user_id', $this->user->id)->first();
        $this->assertNotNull($withdrawal);
        $this->assertEquals(500.00, (float) $withdrawal->amount);
        $this->assertEquals(497.50, (float) $withdrawal->estimated_net_payout); // $500 - $2.50 fee
        $this->assertEquals('finance_review', $withdrawal->status);
    }

    public function test_insufficient_avc_balance_blocks_withdrawal()
    {
        $response = $this->actingAs($this->user)->post('/withdraw/create', [
            'withdrawal_method' => 'bank_transfer',
            'amount' => 1500.00, // User has only 1000 AVC
            'account_name' => 'Juan Dela Cruz',
            'confirm_checkbox' => '1',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1000.00, (float) $this->user->fresh()->wallet_balance);
        $this->assertEquals(0.00, (float) $this->user->fresh()->pending_withdrawals);
    }

    public function test_daily_withdrawal_limit_enforced()
    {
        $this->user->daily_withdrawal_limit = 300.00;
        $this->user->save();

        $response = $this->actingAs($this->user)->post('/withdraw/create', [
            'withdrawal_method' => 'bank_transfer',
            'amount' => 500.00, // Exceeds $300 daily limit
            'account_name' => 'Juan Dela Cruz',
            'confirm_checkbox' => '1',
        ]);

        $response->assertSessionHas('error');
    }

    public function test_user_saves_withdrawal_destination_method()
    {
        $response = $this->actingAs($this->user)->post('/saved-withdrawal-methods', [
            'method_key' => 'bank_transfer',
            'title' => 'BDO •••• 9012',
            'account_name' => 'Juan Dela Cruz',
            'bank_or_provider' => 'BDO Unibank',
            'account_number' => '123456789012',
        ]);

        $saved = SavedWithdrawalMethod::where('user_id', $this->user->id)->first();
        $this->assertNotNull($saved);
        $this->assertEquals('BDO •••• 9012', $saved->title);
    }

    public function test_admin_completes_withdrawal_deducts_pending_balance_and_logs_ledger()
    {
        // 1. User submits withdrawal
        $withdrawal = Withdrawal::create([
            'user_id' => $this->user->id,
            'withdrawal_code' => 'WDR-2026-000100',
            'withdrawal_method' => 'bank_transfer',
            'amount' => 500.00,
            'account_name' => 'Juan Dela Cruz',
            'status' => 'finance_review',
        ]);

        $this->user->wallet_balance = 500.00;
        $this->user->pending_withdrawals = 500.00;
        $this->user->save();

        // 2. Admin completes payout
        $response = $this->actingAs($this->admin)->post('/admin/withdrawals/' . $withdrawal->id . '/complete', [
            'transaction_reference' => 'BANK-REF-9948210',
            'admin_notes' => 'Payout sent via BDO online transfer.',
        ]);

        $response->assertSessionHas('success');
        $this->user->refresh();
        $this->assertEquals(500.00, (float) $this->user->wallet_balance);
        $this->assertEquals(0.00, (float) $this->user->pending_withdrawals); // Deducted permanently!

        $this->assertEquals('completed', $withdrawal->fresh()->status);
        $this->assertEquals('BANK-REF-9948210', $withdrawal->fresh()->transaction_reference);
    }

    public function test_admin_rejects_withdrawal_refunds_pending_balance_to_user_wallet()
    {
        $withdrawal = Withdrawal::create([
            'user_id' => $this->user->id,
            'withdrawal_code' => 'WDR-2026-000101',
            'withdrawal_method' => 'bank_transfer',
            'amount' => 500.00,
            'account_name' => 'Juan Dela Cruz',
            'status' => 'finance_review',
        ]);

        $this->user->wallet_balance = 500.00;
        $this->user->pending_withdrawals = 500.00;
        $this->user->save();

        $response = $this->actingAs($this->admin)->post('/admin/withdrawals/' . $withdrawal->id . '/reject', [
            'admin_notes' => 'Invalid bank account number provided.',
        ]);

        $response->assertSessionHas('success');
        $this->user->refresh();
        $this->assertEquals(1000.00, (float) $this->user->wallet_balance); // Refunded back!
        $this->assertEquals(0.00, (float) $this->user->pending_withdrawals);
        $this->assertEquals('rejected', $withdrawal->fresh()->status);
    }

    public function test_user_cancels_withdrawal_refunds_balance()
    {
        $withdrawal = Withdrawal::create([
            'user_id' => $this->user->id,
            'withdrawal_code' => 'WDR-2026-000102',
            'withdrawal_method' => 'bank_transfer',
            'amount' => 500.00,
            'account_name' => 'Juan Dela Cruz',
            'status' => 'finance_review',
        ]);

        $this->user->wallet_balance = 500.00;
        $this->user->pending_withdrawals = 500.00;
        $this->user->save();

        $response = $this->actingAs($this->user)->post('/withdraw/' . $withdrawal->id . '/cancel');

        $this->user->refresh();
        $this->assertEquals(1000.00, (float) $this->user->wallet_balance); // Refunded back!
        $this->assertEquals(0.00, (float) $this->user->pending_withdrawals);
        $this->assertEquals('cancelled', $withdrawal->fresh()->status);
    }

    public function test_withdrawal_request_detail_page_loads()
    {
        $withdrawal = Withdrawal::create([
            'user_id' => $this->user->id,
            'withdrawal_code' => 'WDR-2026-000103',
            'withdrawal_method' => 'bank_transfer',
            'amount' => 500.00,
            'account_name' => 'Juan Dela Cruz',
            'status' => 'finance_review',
        ]);

        $response = $this->actingAs($this->user)->get('/withdraw/' . $withdrawal->id);

        $response->assertStatus(200);
        $response->assertSee('WDR-2026-000103');
        $response->assertSee('Withdrawal Status Timeline');
    }
}
