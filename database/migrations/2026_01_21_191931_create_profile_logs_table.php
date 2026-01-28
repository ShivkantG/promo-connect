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
        Schema::create('profile_logs', function (Blueprint $table) {
            $table->id();

            // 🔗 Relation
            $table->foreignId('profile_id')
                ->constrained('profiles')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // 🔹 Action info
            $table->enum('action', [
                'create',
                'update',
                'verify',
                'unverify'
            ]);

            // 🔹 Change tracking
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_logs');
    }
};
