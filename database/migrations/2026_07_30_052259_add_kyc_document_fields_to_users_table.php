<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kyc_document_path')->nullable()->after('kyc_verified');
            $table->string('kyc_selfie_path')->nullable()->after('kyc_document_path');
            $table->string('kyc_status')->default('pending')->after('kyc_selfie_path');
            $table->timestamp('kyc_submitted_at')->nullable()->after('kyc_status');
            $table->text('kyc_rejected_reason')->nullable()->after('kyc_submitted_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kyc_document_path', 'kyc_selfie_path', 'kyc_status', 'kyc_submitted_at', 'kyc_rejected_reason']);
        });
    }
};
