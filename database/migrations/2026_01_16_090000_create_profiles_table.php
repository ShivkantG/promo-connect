<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            // 🔗 Relation
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🔹 Profile type
            $table->enum('type', ['promoter', 'brand', 'client'])
                ->index();

            // 🔹 Common profile data
            $table->json('basic_info')->nullable();          // name, mobile, photo, city
            $table->json('professional_info')->nullable();  // category, skills, experience
            $table->json('preferences')->nullable();         // user preferences
            $table->json('settings')->nullable();            // notification, privacy

            // 🔹 Public stats
            $table->float('rating')->default(0);
            $table->unsignedInteger('review_count')->default(0);
            $table->unsignedInteger('completion_rate')->default(0);

            // 🔹 Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // 🚫 One profile per user
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
