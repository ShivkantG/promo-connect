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
        Schema::create('portfolio_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('type', ['event', 'certificate', 'award']);

            $table->string('title');
            $table->text('description')->nullable();

            $table->date('date')->nullable();
            $table->string('brand_name')->nullable();

            $table->json('media_urls')->nullable();           // photos/videos
            $table->json('skills_demonstrated')->nullable();  // skills list

            $table->boolean('verified')->default(false);
            $table->foreignId('verified_by')->nullable()
                ->references('id')->on('users')->nullOnDelete();

            $table->boolean('is_public')->default(true);
            $table->integer('order_index')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_items');
    }
};
