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
        Schema::create('event_participations', function (Blueprint $table) {
            $table->id();

            // 🔗 Relations
            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->cascadeOnDelete();

            $table->foreignId('event_id')
                ->constrained('events')
                ->cascadeOnDelete();

            $table->foreignId('promoter_id')
                ->constrained('profiles')
                ->cascadeOnDelete();

            // 🧑‍🎤 Participation details
            $table->string('role')->nullable();
            // e.g. DJ, Host, Influencer

            $table->text('responsibilities')->nullable();
            $table->decimal('hours_worked', 5, 2)->nullable();

            // 📊 Performance & portfolio
            $table->boolean('converted_to_portfolio')->default(false);
            $table->json('performance_metrics')->nullable();
            // rating, punctuality, engagement, feedback, etc.

            $table->timestamps();

            // 🚫 Prevent duplicate participation for same booking
            $table->unique('booking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_participations');
    }
};
