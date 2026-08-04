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
            $table->string('listing_number')->nullable()->after('reference');
            $table->text('notes')->nullable()->after('admin_note');
            $table->json('logs')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_swaps', function (Blueprint $table) {
            $table->dropColumn(['listing_number', 'notes', 'logs']);
        });
    }
};