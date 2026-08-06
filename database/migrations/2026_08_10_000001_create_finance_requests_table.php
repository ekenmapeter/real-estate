<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('finance_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_id')->unique(); // e.g. FR-250520-0001
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['deposit', 'withdrawal'])->default('deposit');
            $table->string('country')->default('Philippines');
            $table->string('currency')->default('PHP - Philippine Peso');
            $table->decimal('amount', 15, 2);
            $table->string('payment_method'); // e.g. GCash, Bank Transfer, Maya, USDT
            $table->string('sender_name');
            $table->string('sender_account');
            $table->string('sender_email');
            $table->text('user_notes')->nullable();
            
            // Statuses: under_review, payment_instructions_assigned, evidence_submitted, under_verification, completed, rejected, cancelled
            $table->string('status')->default('under_review');
            
            // Admin Provided Payment Details (Step 5)
            $table->string('assigned_payment_method')->nullable();
            $table->string('assigned_account_name')->nullable();
            $table->string('assigned_account_number')->nullable();
            $table->string('assigned_reference')->nullable();
            $table->text('assigned_instructions')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            // User Submitted Payment Evidence (Step 6)
            $table->string('payment_evidence')->nullable();
            $table->text('evidence_notes')->nullable();
            $table->timestamp('evidence_submitted_at')->nullable();
            
            // Admin Finalization
            $table->text('admin_notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_requests');
    }
};
