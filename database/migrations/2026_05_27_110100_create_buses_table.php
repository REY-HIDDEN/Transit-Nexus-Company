<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('buses')) {
            Schema::create('buses', function (Blueprint $table) {
                $table->id('bus_id');
                $table->string('plate_number')->unique();
                $table->unsignedSmallInteger('capacity');
                $table->string('driver_name');
                $table->string('status')->default('active')->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
