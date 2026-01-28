<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('promoter_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('profile_id')
                ->constrained('profiles')
                ->cascadeOnDelete();

            $table->json('skills')->nullable();
            $table->json('price_range')->nullable();
            $table->json('availability')->nullable();
            $table->json('portfolio_stats')->nullable();
            $table->json('badges')->nullable();

            $table->string('cover_photo')->nullable();
            $table->string('intro_video')->nullable();

            $table->boolean('is_verified')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promoter_details');
    }
};
