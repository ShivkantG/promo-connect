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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('promoter_id')->constrained('profiles')->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained('profiles')->cascadeOnDelete();

            // Status & dates
            $table->string('status')->default('pending');
            // pending | accepted | rejected | cancelled | completed

            $table->date('booking_date')->nullable();
            $table->timestamp('confirmed_at')->nullable();

            // Contract & terms
            $table->json('contract_details')->nullable();
            $table->json('terms')->nullable();

            // Event timing
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();

            // Payment
            $table->string('payment_status')->default('pending');
            // pending | paid | released | failed
            $table->timestamp('payment_released_at')->nullable();

            // Cancellation
            $table->text('cancellation_reason')->nullable();
            $table->string('cancelled_by')->nullable();
            // promoter | brand | system

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
