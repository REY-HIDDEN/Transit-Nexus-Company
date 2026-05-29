<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('routes')) {
            Schema::create('routes', function (Blueprint $table) {
                $table->id('route_id');
                $table->string('origin');
                $table->string('destination');
                $table->decimal('distance', 8, 2);
                $table->decimal('ticket_price', 10, 2);
                $table->timestamps();

                $table->index(['origin', 'destination']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
