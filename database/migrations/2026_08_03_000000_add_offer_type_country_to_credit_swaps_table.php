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
        Schema::table('credit_swaps', function (Blueprint $table) {
            $table->string('offer_type')->default('sell')->after('user_id'); // sell | buy
            $table->string('country')->nullable()->after('payment_method');
            $table->foreignId('seller_id')->nullable()->after('buyer_id')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_swaps', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropColumn(['offer_type', 'country', 'seller_id']);
        });
    }
};