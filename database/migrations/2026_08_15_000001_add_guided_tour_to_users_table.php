<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'guided_tour_completed_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('guided_tour_completed_at')->nullable()->after('kyc_rejected_reason');
            });
        }

        if (! Schema::hasColumn('users', 'guided_tour_skipped_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('guided_tour_skipped_at')->nullable()->after('guided_tour_completed_at');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['guided_tour_completed_at', 'guided_tour_skipped_at']);
        });
    }
};
