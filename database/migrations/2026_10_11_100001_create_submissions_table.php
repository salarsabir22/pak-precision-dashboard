<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->string('type', 32);
            $table->string('reference', 40)->unique();
            $table->string('status', 24)->default('new');
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->json('payload')->nullable();
            $table->text('notes')->nullable();
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['site_id', 'type']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
