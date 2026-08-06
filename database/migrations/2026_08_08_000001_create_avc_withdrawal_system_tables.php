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
        // 1. Add wallet locking & daily limit fields to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'pending_withdrawals')) {
                $table->decimal('pending_withdrawals', 15, 2)->default(0.00)->after('wallet_balance');
            }
            if (!Schema::hasColumn('users', 'daily_withdrawal_limit')) {
                $table->decimal('daily_withdrawal_limit', 15, 2)->default(10000.00)->after('pending_withdrawals');
            }
            if (!Schema::hasColumn('users', 'transaction_pin')) {
                $table->string('transaction_pin')->nullable()->after('password');
            }
        });

        // 2. Create saved_withdrawal_methods table
        if (!Schema::hasTable('saved_withdrawal_methods')) {
            Schema::create('saved_withdrawal_methods', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('method_key'); // bank_transfer, mobile_wallet, wire_transfer, crypto
                $table->string('title'); // e.g. "BDO Unibank", "GCash", "USDT (TRC-20)"
                $table->string('account_name');
                $table->string('bank_or_provider')->nullable();
                $table->string('account_number')->nullable();
                $table->string('masked_account_number')->nullable(); // e.g. •••• 9012
                $table->string('account_type')->nullable(); // Savings, Checking
                $table->string('swift_bic')->nullable();
                $table->string('iban')->nullable();
                $table->string('routing_number')->nullable();
                $table->string('bank_address')->nullable();
                $table->string('country')->nullable();
                $table->string('currency')->nullable();
                $table->string('crypto_asset')->nullable();
                $table->string('crypto_network')->nullable();
                $table->string('wallet_address')->nullable();
                $table->string('destination_tag_memo')->nullable();
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });
        }

        // 3. Update withdrawals table with comprehensive fields
        Schema::table('withdrawals', function (Blueprint $table) {
            if (!Schema::hasColumn('withdrawals', 'withdrawal_type')) {
                $table->string('withdrawal_type')->default('finance_team')->after('withdrawal_code');
            }
            if (!Schema::hasColumn('withdrawals', 'saved_withdrawal_method_id')) {
                $table->foreignId('saved_withdrawal_method_id')->nullable()->after('withdrawal_method');
            }
            if (!Schema::hasColumn('withdrawals', 'avc_amount')) {
                $table->decimal('avc_amount', 15, 2)->nullable()->after('amount');
            }
            if (!Schema::hasColumn('withdrawals', 'avc_rate')) {
                $table->decimal('avc_rate', 10, 4)->default(1.0000)->after('avc_amount');
            }
            if (!Schema::hasColumn('withdrawals', 'gross_usd_value')) {
                $table->decimal('gross_usd_value', 15, 2)->nullable()->after('avc_rate');
            }
            if (!Schema::hasColumn('withdrawals', 'platform_fee')) {
                $table->decimal('platform_fee', 15, 2)->default(0.00)->after('gross_usd_value');
            }
            if (!Schema::hasColumn('withdrawals', 'processing_fee')) {
                $table->decimal('processing_fee', 15, 2)->default(2.50)->after('platform_fee');
            }
            if (!Schema::hasColumn('withdrawals', 'estimated_net_payout')) {
                $table->decimal('estimated_net_payout', 15, 2)->nullable()->after('processing_fee');
            }
            if (!Schema::hasColumn('withdrawals', 'payout_currency')) {
                $table->string('payout_currency')->default('USD')->after('estimated_net_payout');
            }
            if (!Schema::hasColumn('withdrawals', 'bank_or_provider')) {
                $table->string('bank_or_provider')->nullable()->after('account_name');
            }
            if (!Schema::hasColumn('withdrawals', 'account_type')) {
                $table->string('account_type')->nullable()->after('account_number');
            }
            if (!Schema::hasColumn('withdrawals', 'swift_bic')) {
                $table->string('swift_bic')->nullable()->after('account_type');
            }
            if (!Schema::hasColumn('withdrawals', 'iban')) {
                $table->string('iban')->nullable()->after('swift_bic');
            }
            if (!Schema::hasColumn('withdrawals', 'routing_number')) {
                $table->string('routing_number')->nullable()->after('iban');
            }
            if (!Schema::hasColumn('withdrawals', 'bank_address')) {
                $table->string('bank_address')->nullable()->after('routing_number');
            }
            if (!Schema::hasColumn('withdrawals', 'crypto_asset')) {
                $table->string('crypto_asset')->nullable()->after('bank_address');
            }
            if (!Schema::hasColumn('withdrawals', 'crypto_network')) {
                $table->string('crypto_network')->nullable()->after('crypto_asset');
            }
            if (!Schema::hasColumn('withdrawals', 'wallet_address')) {
                $table->string('wallet_address')->nullable()->after('crypto_network');
            }
            if (!Schema::hasColumn('withdrawals', 'destination_tag_memo')) {
                $table->string('destination_tag_memo')->nullable()->after('wallet_address');
            }
            if (!Schema::hasColumn('withdrawals', 'user_notes')) {
                $table->text('user_notes')->nullable()->after('destination_tag_memo');
            }
            if (!Schema::hasColumn('withdrawals', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('user_notes');
            }
            if (!Schema::hasColumn('withdrawals', 'transaction_reference')) {
                $table->string('transaction_reference')->nullable()->after('admin_notes');
            }
            if (!Schema::hasColumn('withdrawals', 'receipt_proof')) {
                $table->text('receipt_proof')->nullable()->after('transaction_reference');
            }
            if (!Schema::hasColumn('withdrawals', 'processed_at')) {
                $table->timestamp('processed_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('withdrawals', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('processed_at');
            }
            if (!Schema::hasColumn('withdrawals', 'processed_by')) {
                $table->foreignId('processed_by')->nullable()->after('completed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_withdrawal_methods');
    }
};
