<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id('booking_id');
                $table->foreignId('trip_id')->constrained('trips', 'trip_id')->cascadeOnDelete();
                $table->string('passenger_name');
                $table->string('phone_number');
                $table->unsignedSmallInteger('seat_number');
                $table->date('booking_date');
                $table->string('payment_status')->default('pending')->index();
                $table->string('booking_status')->default('confirmed')->index();
                $table->string('ticket_number')->unique();
                $table->timestamps();

                $table->unique(['trip_id', 'seat_number']);
                $table->index(['trip_id', 'booking_date']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
