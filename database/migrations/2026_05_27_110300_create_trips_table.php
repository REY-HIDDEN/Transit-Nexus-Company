<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('trips')) {
            Schema::create('trips', function (Blueprint $table) {
                $table->id('trip_id');
                $table->foreignId('bus_id')->constrained('buses', 'bus_id')->cascadeOnDelete();
                $table->foreignId('route_id')->constrained('routes', 'route_id')->cascadeOnDelete();
                $table->date('departure_date')->index();
                $table->time('departure_time');
                $table->time('arrival_time')->nullable();
                $table->string('status')->default('scheduled')->index();
                $table->timestamps();

                $table->index(['bus_id', 'departure_date']);
                $table->index(['route_id', 'departure_date']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
