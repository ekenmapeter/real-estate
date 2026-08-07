<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('category'); // project_investment, property, finance, marketplace, verification, statement
            $table->string('document_type');
            $table->string('title');
            $table->string('reference')->unique();
            $table->nullableMorphs('related'); // related_type + related_id
            $table->string('file_path');
            $table->string('status')->default('new');
            // new, active, completed, verified, pending, archived, rejected
            $table->timestamp('issued_at')->nullable();
            $table->json('metadata')->nullable();
            $table->string('share_token')->nullable()->unique();
            $table->timestamps();

            $table->index(['user_id', 'category']);
            $table->index(['user_id', 'document_type', 'related_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
