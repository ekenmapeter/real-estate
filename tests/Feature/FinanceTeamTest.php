<?php

namespace Tests\Feature;

use App\Models\FinanceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FinanceTeamTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'John Smith',
            'email' => 'john@gmail.com',
            'wallet_balance' => 12450.00,
        ]);

        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@radiantrealty.com',
            'role' => 'admin',
        ]);
    }

    public function test_user_submits_finance_request()
    {
        $response = $this->actingAs($this->user)->post('/finance/team/store', [
            'type' => 'deposit',
            'country' => 'Philippines',
            'currency' => 'PHP - Philippine Peso',
            'amount' => 4990.00,
            'payment_method' => 'GCash',
            'sender_name' => 'John Smith',
            'sender_account' => '09171234567',
            'sender_email' => 'john@gmail.com',
            'user_notes' => 'Please send details for GCash! Thank you.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('finance_requests', [
            'user_id' => $this->user->id,
            'amount' => 4990.00,
            'payment_method' => 'GCash',
            'status' => 'under_review',
        ]);
    }

    public function test_admin_assigns_payment_instructions_and_timer()
    {
        $fr = FinanceRequest::create([
            'request_id' => 'FR-250520-0001',
            'user_id' => $this->user->id,
            'type' => 'deposit',
            'country' => 'Philippines',
            'currency' => 'PHP - Philippine Peso',
            'amount' => 4990.00,
            'payment_method' => 'GCash',
            'sender_name' => 'John Smith',
            'sender_account' => '09171234567',
            'sender_email' => 'john@gmail.com',
            'status' => 'under_review',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/finance-requests/{$fr->id}/assign-instructions", [
            'assigned_payment_method' => 'GCash',
            'assigned_account_name' => 'RINNY P.',
            'assigned_account_number' => '09658726718',
            'assigned_reference' => 'RDR250520001',
            'expiration_minutes' => 20,
            'assigned_instructions' => 'Please send the exact amount.',
        ]);

        $response->assertRedirect();
        $fr->refresh();

        $this->assertEquals('payment_instructions_assigned', $fr->status);
        $this->assertEquals('RINNY P.', $fr->assigned_account_name);
        $this->assertEquals('09658726718', $fr->assigned_account_number);
        $this->assertNotNull($fr->expires_at);
    }

    public function test_user_uploads_payment_evidence()
    {
        Storage::fake('public');

        $fr = FinanceRequest::create([
            'request_id' => 'FR-250520-0001',
            'user_id' => $this->user->id,
            'type' => 'deposit',
            'country' => 'Philippines',
            'currency' => 'PHP - Philippine Peso',
            'amount' => 4990.00,
            'payment_method' => 'GCash',
            'sender_name' => 'John Smith',
            'sender_account' => '09171234567',
            'sender_email' => 'john@gmail.com',
            'status' => 'payment_instructions_assigned',
        ]);

        $file = UploadedFile::fake()->create('GCash_Receipt.png', 500, 'image/png');

        $response = $this->actingAs($this->user)->post("/finance/team/request/{$fr->request_id}/evidence", [
            'receipt' => $file,
            'evidence_notes' => 'Payment sent. Please confirm.',
        ]);

        $response->assertRedirect();
        $fr->refresh();

        $this->assertEquals('evidence_submitted', $fr->status);
        $this->assertNotNull($fr->payment_evidence);
    }

    public function test_admin_approves_evidence_and_credits_wallet()
    {
        $fr = FinanceRequest::create([
            'request_id' => 'FR-250520-0001',
            'user_id' => $this->user->id,
            'type' => 'deposit',
            'country' => 'Philippines',
            'currency' => 'PHP - Philippine Peso',
            'amount' => 4990.00,
            'payment_method' => 'GCash',
            'sender_name' => 'John Smith',
            'sender_account' => '09171234567',
            'sender_email' => 'john@gmail.com',
            'status' => 'evidence_submitted',
            'payment_evidence' => 'finance_evidence/test_receipt.png',
        ]);

        $initialBalance = (float) $this->user->wallet_balance;

        $response = $this->actingAs($this->admin)->post("/admin/finance-requests/{$fr->id}/approve", [
            'admin_notes' => 'Verified receipt and credited balance.',
        ]);

        $response->assertRedirect();
        $fr->refresh();
        $this->user->refresh();

        $this->assertEquals('completed', $fr->status);
        $this->assertEquals($initialBalance + 4990.00, (float) $this->user->wallet_balance);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'type' => 'deposit',
            'amount' => 4990.00,
            'reference' => 'FR-250520-0001',
            'status' => 'completed',
        ]);
    }
}
