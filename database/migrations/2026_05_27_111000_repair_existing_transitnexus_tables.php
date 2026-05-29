<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->repairUsersTable();
        $this->repairBusesTable();
        $this->repairRoutesTable();
        $this->repairTripsTable();
        $this->repairBookingsTable();
    }

    public function down(): void
    {
        //
    }

    private function repairUsersTable(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        if (Schema::hasColumn('users', 'user_id') && ! Schema::hasColumn('users', 'id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('user_id', 'id');
            });
        }

        if (Schema::hasColumn('users', 'username') && ! Schema::hasColumn('users', 'name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('username', 'name');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'email')) {
                $table->string('email')->nullable()->unique()->after('name');
            }

            if (! Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }

            if (! Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken()->after('password');
            }

            if (! Schema::hasColumn('users', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('users', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    private function repairBusesTable(): void
    {
        if (! Schema::hasTable('buses')) {
            return;
        }

        Schema::table('buses', function (Blueprint $table) {
            if (! Schema::hasColumn('buses', 'driver_name')) {
                $table->string('driver_name')->default('Unassigned Driver')->after('capacity');
            }

            if (! Schema::hasColumn('buses', 'status')) {
                $table->string('status')->default('active')->index()->after('driver_name');
            }

            if (! Schema::hasColumn('buses', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('buses', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    private function repairRoutesTable(): void
    {
        if (! Schema::hasTable('routes')) {
            return;
        }

        Schema::table('routes', function (Blueprint $table) {
            if (! Schema::hasColumn('routes', 'distance')) {
                $table->decimal('distance', 8, 2)->default(0)->after('destination');
            }

            if (! Schema::hasColumn('routes', 'ticket_price')) {
                $table->decimal('ticket_price', 10, 2)->default(0)->after('distance');
            }

            if (! Schema::hasColumn('routes', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('routes', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    private function repairTripsTable(): void
    {
        if (! Schema::hasTable('trips')) {
            return;
        }

        if (Schema::hasColumn('trips', 'date') && ! Schema::hasColumn('trips', 'departure_date')) {
            Schema::table('trips', function (Blueprint $table) {
                $table->renameColumn('date', 'departure_date');
            });
        }

        Schema::table('trips', function (Blueprint $table) {
            if (! Schema::hasColumn('trips', 'departure_time')) {
                $table->time('departure_time')->default('08:00:00')->after('departure_date');
            }

            if (! Schema::hasColumn('trips', 'arrival_time')) {
                $table->time('arrival_time')->nullable()->after('departure_time');
            }

            if (! Schema::hasColumn('trips', 'status')) {
                $table->string('status')->default('scheduled')->index()->after('arrival_time');
            }

            if (! Schema::hasColumn('trips', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('trips', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    private function repairBookingsTable(): void
    {
        if (! Schema::hasTable('bookings')) {
            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'phone_number')) {
                $table->string('phone_number')->default('')->after('passenger_name');
            }

            if (! Schema::hasColumn('bookings', 'seat_number')) {
                $table->unsignedSmallInteger('seat_number')->default(1)->after('phone_number');
            }

            if (! Schema::hasColumn('bookings', 'booking_date')) {
                $table->date('booking_date')->nullable()->after('seat_number');
            }

            if (! Schema::hasColumn('bookings', 'payment_status')) {
                $table->string('payment_status')->default('pending')->index()->after('booking_date');
            }

            if (! Schema::hasColumn('bookings', 'booking_status')) {
                $table->string('booking_status')->default('confirmed')->index()->after('payment_status');
            }

            if (! Schema::hasColumn('bookings', 'ticket_number')) {
                $table->string('ticket_number')->nullable()->unique()->after('booking_status');
            }

            if (! Schema::hasColumn('bookings', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('bookings', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }
};
