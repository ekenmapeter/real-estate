<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'direction')) {
                $table->string('direction')->nullable()->after('type'); // 'credit' or 'debit'
            }
            if (!Schema::hasColumn('transactions', 'category')) {
                $table->string('category')->nullable()->after('direction');
            }
            if (!Schema::hasColumn('transactions', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('category');
            }
            if (!Schema::hasColumn('transactions', 'fiat_equivalent')) {
                $table->decimal('fiat_equivalent', 15, 2)->nullable()->after('amount');
            }
            if (!Schema::hasColumn('transactions', 'fee_amount')) {
                $table->decimal('fee_amount', 15, 2)->default(0)->after('fiat_equivalent');
            }
            if (!Schema::hasColumn('transactions', 'notes')) {
                $table->text('notes')->nullable()->after('description');
            }
            if (!Schema::hasColumn('transactions', 'receipt_proof')) {
                $table->string('receipt_proof')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('transactions', 'related_type')) {
                $table->string('related_type')->nullable()->after('receipt_proof');
            }
            if (!Schema::hasColumn('transactions', 'related_id')) {
                $table->unsignedBigInteger('related_id')->nullable()->after('related_type');
            }
        });

        // Add indexes for performance using Schema methods safely
        Schema::table('transactions', function (Blueprint $table) {
            $table->index('category');
            $table->index('direction');
            $table->index(['related_type', 'related_id']);
        });

        // Backfill existing records with direction and category based on type
        DB::table('transactions')->whereNull('direction')->update([
            'direction' => DB::raw("CASE
                WHEN type IN ('deposit', 'credit', 'bonus', 'referral_bonus', 'cashback', 'roi', 'rental_income', 'refund', 'marketplace_purchase', 'signup_bonus') THEN 'credit'
                WHEN type IN ('withdrawal', 'debit', 'investment', 'property_purchase', 'fee', 'marketplace_sale', 'send_funds') THEN 'debit'
                ELSE 'credit'
            END"),
        ]);

        DB::table('transactions')->whereNull('category')->update([
            'category' => DB::raw("CASE
                WHEN type IN ('deposit', 'credit') THEN 'deposit'
                WHEN type IN ('withdrawal', 'debit') THEN 'withdrawal'
                WHEN type LIKE '%marketplace%' OR type LIKE '%credit_swap%' THEN 'marketplace'
                WHEN type LIKE '%escrow%' THEN 'escrow'
                WHEN type IN ('investment', 'property_purchase') THEN 'investment'
                WHEN type IN ('roi', 'rental_income', 'bonus', 'signup_bonus', 'cashback') THEN 'earnings'
                WHEN type IN ('fee', 'processing_fee') THEN 'fees'
                WHEN type IN ('referral_bonus') THEN 'referral'
                WHEN type IN ('refund') THEN 'adjustment'
                ELSE 'deposit'
            END"),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropIndex(['direction']);
            $table->dropIndex(['related_type', 'related_id']);

            $columns = ['direction', 'category', 'payment_method', 'fiat_equivalent', 'fee_amount', 'notes', 'receipt_proof', 'related_type', 'related_id'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('transactions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
