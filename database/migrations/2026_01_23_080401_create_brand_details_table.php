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
        Schema::create('brand_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('profile_id')
                ->constrained('profiles')
                ->cascadeOnDelete();

            // Company info
            $table->string('company_name');
            $table->string('gst_number')->nullable();
            $table->string('industry')->nullable();
            $table->string('company_size')->nullable(); // small, medium, enterprise
            $table->string('website')->nullable();

            // Social & verification
            $table->json('social_profiles')->nullable(); // instagram, linkedin, etc
            $table->json('verification_docs')->nullable(); // gst pdf, pan, etc

            // Metrics
            $table->float('brand_score')->default(0);
            $table->float('promoter_satisfaction_rate')->default(0);

            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_details');
    }
};
