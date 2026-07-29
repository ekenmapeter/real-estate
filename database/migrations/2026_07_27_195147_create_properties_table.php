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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('location');
            $table->string('category')->default('Residential');
            $table->string('image_url')->nullable();
            $table->decimal('price_per_share', 15, 2);
            $table->integer('total_shares');
            $table->integer('available_shares');
            $table->decimal('roi_percentage', 5, 2);
            $table->integer('investment_duration_months')->default(12);
            $table->text('description')->nullable();
            $table->string('status')->default('active'); // active, sold_out, upcoming
            $table->timestamps();
        });

        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->integer('shares_bought');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('expected_roi_amount', 15, 2);
            $table->decimal('roi_earned', 15, 2)->default(0.00);
            $table->string('status')->default('active'); // active, completed
            $table->timestamps();
        });

        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('deposit_code')->unique();
            $table->decimal('amount', 15, 2);
            $table->string('payment_method'); // bank_transfer, credit_card, wire_transfer, crypto, financial_assistant
            $table->text('details')->nullable();
            $table->string('reference_id')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });

        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('withdrawal_code')->unique();
            $table->decimal('amount', 15, 2);
            $table->string('withdrawal_method'); // bank_transfer, paypal, crypto
            $table->text('account_details')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // deposit, withdrawal, property_investment, roi_payout, send_funds, receive_funds, affiliate_earning
            $table->decimal('amount', 15, 2);
            $table->string('reference')->nullable();
            $table->string('description');
            $table->string('status')->default('completed'); // completed, pending, rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('withdrawals');
        Schema::dropIfExists('deposits');
        Schema::dropIfExists('investments');
        Schema::dropIfExists('properties');
    }
};
