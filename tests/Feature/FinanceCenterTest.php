<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceCenterTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Finance Tester',
            'email' => 'financetester@radiantrealty.com',
            'wallet_balance' => 25000.00,
        ]);
    }

    public function test_finance_overview_page_loads_for_authenticated_user()
    {
        $response = $this->actingAs($this->user)->get('/finance');

        $response->assertStatus(200);
        $response->assertSee('Finance Center Overview');
        $response->assertSee('25,000');
        $response->assertSee('Lifetime Deposits');
    }

    public function test_transaction_history_page_loads_with_records()
    {
        Transaction::create([
            'user_id' => $this->user->id,
            'type' => 'deposit',
            'direction' => 'credit',
            'category' => 'deposit',
            'payment_method' => 'Bank Transfer',
            'amount' => 500.00,
            'fiat_equivalent' => 500.00,
            'reference' => 'TXN-TEST-001',
            'description' => 'Test Deposit Transaction',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->user)->get('/finance/transactions');

        $response->assertStatus(200);
        $response->assertSee('Complete Transaction History');
        $response->assertSee('TXN-TEST-001');
        $response->assertSee('+500.00 AVC');
    }

    public function test_transaction_history_filters_by_category()
    {
        Transaction::create([
            'user_id' => $this->user->id,
            'type' => 'withdrawal',
            'direction' => 'debit',
            'category' => 'withdrawal',
            'payment_method' => 'GCash',
            'amount' => 200.00,
            'reference' => 'TXN-WITH-002',
            'description' => 'Test Withdrawal',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->user)->get('/finance/transactions?category=withdrawal');

        $response->assertStatus(200);
        $response->assertSee('TXN-WITH-002');
        $response->assertSee('-200.00 AVC');
    }

    public function test_transaction_detail_page_loads()
    {
        $txn = Transaction::create([
            'user_id' => $this->user->id,
            'type' => 'deposit',
            'direction' => 'credit',
            'category' => 'deposit',
            'payment_method' => 'Bank Transfer',
            'amount' => 1000.00,
            'reference' => 'TXN-DETAIL-003',
            'description' => 'Detail test transaction',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->user)->get("/finance/transactions/{$txn->id}");

        $response->assertStatus(200);
        $response->assertSee('TXN-DETAIL-003');
        $response->assertSee('Detail test transaction');
    }

    public function test_csv_export_downloads_streamed_file()
    {
        Transaction::create([
            'user_id' => $this->user->id,
            'type' => 'deposit',
            'direction' => 'credit',
            'category' => 'deposit',
            'payment_method' => 'Crypto',
            'amount' => 300.00,
            'reference' => 'TXN-EXPORT-004',
            'description' => 'Export test transaction',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->user)->get('/finance/transactions/export/csv');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}
