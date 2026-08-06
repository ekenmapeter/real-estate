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
        // 1. Create payment_channels table
        if (!Schema::hasTable('payment_channels')) {
            Schema::create('payment_channels', function (Blueprint $table) {
                $table->id();
                $table->string('method_key'); // bank_transfer, credit_card, wire_transfer, crypto
                $table->string('channel_name');
                $table->string('account_name')->nullable();
                $table->string('bank_or_provider')->nullable();
                $table->string('account_number')->nullable();
                $table->string('country')->nullable();
                $table->string('currency')->nullable();
                $table->string('swift_bic')->nullable();
                $table->string('iban')->nullable();
                $table->string('wallet_asset')->nullable(); // USDT, BTC, ETH, BNB, SOL
                $table->string('blockchain_network')->nullable(); // TRC-20, ERC-20, BEP-20, BTC, BSC, SOL
                $table->string('wallet_address')->nullable();
                $table->string('destination_tag_memo')->nullable();
                $table->decimal('min_deposit_amount', 15, 2)->default(10.00);
                $table->decimal('max_deposit_amount', 15, 2)->nullable();
                $table->decimal('daily_limit', 15, 2)->nullable();
                $table->decimal('current_capacity', 15, 2)->default(0.00);
                $table->string('processing_info')->nullable(); // e.g. "1-2 Hours", "Instant"
                $table->string('status')->default('active'); // active, inactive, full_capacity, maintenance, country_restricted, currency_restricted
                $table->string('visibility')->default('request_only'); // public, request_only
                $table->timestamps();
            });
        }

        // 2. Add extra deposit flow fields to deposits table
        Schema::table('deposits', function (Blueprint $table) {
            if (!Schema::hasColumn('deposits', 'deposit_type')) {
                $table->string('deposit_type')->default('finance_team')->after('deposit_code');
            }
            if (!Schema::hasColumn('deposits', 'payment_channel_id')) {
                $table->foreignId('payment_channel_id')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('deposits', 'deposit_amount')) {
                $table->decimal('deposit_amount', 15, 2)->nullable()->after('amount');
            }
            if (!Schema::hasColumn('deposits', 'deposit_currency')) {
                $table->string('deposit_currency')->default('USD')->after('deposit_amount');
            }
            if (!Schema::hasColumn('deposits', 'base_usd_value')) {
                $table->decimal('base_usd_value', 15, 2)->nullable()->after('deposit_currency');
            }
            if (!Schema::hasColumn('deposits', 'avc_rate')) {
                $table->decimal('avc_rate', 10, 4)->default(1.0000)->after('base_usd_value');
            }
            if (!Schema::hasColumn('deposits', 'gross_avc')) {
                $table->decimal('gross_avc', 15, 2)->nullable()->after('avc_rate');
            }
            if (!Schema::hasColumn('deposits', 'fee_amount')) {
                $table->decimal('fee_amount', 15, 2)->default(0.00)->after('gross_avc');
            }
            if (!Schema::hasColumn('deposits', 'net_avc')) {
                $table->decimal('net_avc', 15, 2)->nullable()->after('fee_amount');
            }
            if (!Schema::hasColumn('deposits', 'rate_locked_at')) {
                $table->timestamp('rate_locked_at')->nullable()->after('net_avc');
            }
            if (!Schema::hasColumn('deposits', 'sender_bank_name')) {
                $table->string('sender_bank_name')->nullable()->after('sender_account_name');
            }
            if (!Schema::hasColumn('deposits', 'crypto_asset')) {
                $table->string('crypto_asset')->nullable()->after('sender_email');
            }
            if (!Schema::hasColumn('deposits', 'crypto_network')) {
                $table->string('crypto_network')->nullable()->after('crypto_asset');
            }
            if (!Schema::hasColumn('deposits', 'tx_hash')) {
                $table->string('tx_hash')->nullable()->after('crypto_network');
            }
            if (!Schema::hasColumn('deposits', 'sender_wallet_address')) {
                $table->string('sender_wallet_address')->nullable()->after('tx_hash');
            }
            if (!Schema::hasColumn('deposits', 'card_last_four')) {
                $table->string('card_last_four')->nullable()->after('sender_wallet_address');
            }
            if (!Schema::hasColumn('deposits', 'card_brand')) {
                $table->string('card_brand')->nullable()->after('card_last_four');
            }
            if (!Schema::hasColumn('deposits', 'card_exp_month')) {
                $table->string('card_exp_month')->nullable()->after('card_brand');
            }
            if (!Schema::hasColumn('deposits', 'card_exp_year')) {
                $table->string('card_exp_year')->nullable()->after('card_exp_month');
            }
            if (!Schema::hasColumn('deposits', 'processor_token')) {
                $table->string('processor_token')->nullable()->after('card_exp_year');
            }
            if (!Schema::hasColumn('deposits', 'processor_session_id')) {
                $table->string('processor_session_id')->nullable()->after('processor_token');
            }
            if (!Schema::hasColumn('deposits', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('user_notes');
            }
            if (!Schema::hasColumn('deposits', 'internal_notes')) {
                $table->text('internal_notes')->nullable()->after('admin_notes');
            }
            if (!Schema::hasColumn('deposits', 'credited_at')) {
                $table->timestamp('credited_at')->nullable()->after('expires_at');
            }
            if (!Schema::hasColumn('deposits', 'credited_by')) {
                $table->foreignId('credited_by')->nullable()->after('credited_at');
            }
        });

        // 3. Create wallet_ledgers table for immutable double-credit prevention
        if (!Schema::hasTable('wallet_ledgers')) {
            Schema::create('wallet_ledgers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('deposit_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('credit_swap_id')->nullable();
                $table->string('transaction_type'); // deposit_credit, marketplace_payout, share_purchase, withdrawal_debit
                $table->string('reference_code')->unique();
                $table->decimal('credit_amount', 15, 2)->default(0.00);
                $table->decimal('debit_amount', 15, 2)->default(0.00);
                $table->decimal('balance_before', 15, 2);
                $table->decimal('balance_after', 15, 2);
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->text('description')->nullable();
                $table->string('status')->default('completed');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_ledgers');
        Schema::dropIfExists('payment_channels');
    }
};
