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
        Schema::table('deposits', function (Blueprint $table) {
            $table->string('country')->default('Philippines')->after('payment_method');
            $table->string('currency')->default('PHP')->after('country');
            $table->string('sender_account_name')->nullable()->after('currency');
            $table->string('sender_account_number')->nullable()->after('sender_account_name');
            $table->string('sender_email')->nullable()->after('sender_account_number');
            $table->text('receipt_proof')->nullable()->after('details');
            $table->text('user_notes')->nullable()->after('receipt_proof');
            $table->json('admin_instructions')->nullable()->after('user_notes');
            $table->timestamp('expires_at')->nullable()->after('admin_instructions');
        });

        Schema::table('withdrawals', function (Blueprint $table) {
            $table->string('country')->default('Philippines')->after('withdrawal_method');
            $table->string('currency')->default('PHP')->after('country');
            $table->string('account_name')->nullable()->after('currency');
            $table->string('account_number')->nullable()->after('account_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropColumn([
                'country',
                'currency',
                'sender_account_name',
                'sender_account_number',
                'sender_email',
                'receipt_proof',
                'user_notes',
                'admin_instructions',
                'expires_at',
            ]);
        });

        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn([
                'country',
                'currency',
                'account_name',
                'account_number',
            ]);
        });
    }
};
