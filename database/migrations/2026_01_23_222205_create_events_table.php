<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // 🔗 Brand / Organizer Profile
            $table->foreignId('profile_id')
                ->constrained('profiles')
                ->cascadeOnDelete();

            // 🔹 Basic Event Info
            $table->string('title');
            $table->text('description')->nullable();

            $table->string('category');
            $table->string('subcategory')->nullable();
            $table->enum('event_type', ['online', 'offline', 'hybrid'])
                ->default('offline');

            // 🔹 Location (JSON for flexibility)
            $table->json('location')->nullable();
            /*
              location = {
                address,
                city,
                lat,
                lng,
                radius
              }
            */

            // 🔹 Dates & Requirements
            $table->json('dates')->nullable();        // start_date, end_date
            $table->json('requirements')->nullable(); // skills, gender, age, followers

            // 🔹 Budget & Perks
            $table->json('budget_details')->nullable(); // min, max, currency
            $table->json('perks')->nullable();          // food, stay, travel, goodies

            // 🔹 Status & Visibility
            $table->enum('status', ['draft', 'open', 'closed', 'completed'])
                ->default('draft');

            $table->enum('visibility', ['public', 'private'])
                ->default('public');

            $table->date('application_deadline')->nullable();

            // 🔹 Auto Matching Criteria
            $table->json('auto_match_criteria')->nullable();
            /*
              auto_match_criteria = {
                category,
                city,
                min_followers,
                language,
                experience
              }
            */

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
