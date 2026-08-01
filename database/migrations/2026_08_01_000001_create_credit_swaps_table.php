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
        Schema::create('credit_swaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Seller
            $table->foreignId('buyer_id')->nullable()->constrained('users')->onDelete('set null'); // Buyer
            $table->decimal('amount', 15, 2);
            $table->string('payment_method'); // Bank Transfer, GCash, Wire, Crypto, Cash
            $table->text('payment_details'); // Account details where buyer pays seller
            $table->string('status')->default('active'); // active, pending_payment, completed, cancelled
            $table->string('reference')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_swaps');
    }
};
