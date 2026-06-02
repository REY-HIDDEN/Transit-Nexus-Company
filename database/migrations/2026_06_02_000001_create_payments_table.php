<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id('payment_id');
                $table->unsignedBigInteger('booking_id')->index();
                $table->decimal('amount', 10, 2)->comment('Amount paid by the user');
                $table->string('payment_method')->default('cash')->index()->comment('cash, mobile_money, credit_card');
                $table->boolean('insurance')->default(false)->comment('Whether the user opted for insurance');
                $table->decimal('insurance_fee', 10, 2)->nullable()->comment('Insurance fee if opted');
                $table->string('status')->default('completed')->index()->comment('pending, completed, failed, refunded');
                $table->string('transaction_reference')->nullable()->comment('Reference for mobile money or credit card');
                $table->timestamp('paid_at')->nullable();
                $table->timestamp('verified_at')->nullable();
                $table->unsignedBigInteger('verified_by')->nullable()->index();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('booking_id')->references('booking_id')->on('bookings')->cascadeOnDelete();
                $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
