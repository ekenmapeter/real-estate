<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('cardholder_name');
            $table->string('address')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address');
            $table->string('country')->nullable()->after('city');
            $table->string('card_type')->default('virtual')->after('country');
        });
    }

    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'city', 'country', 'card_type']);
        });
    }
};
