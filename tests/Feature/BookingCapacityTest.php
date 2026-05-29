<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Route as TransportRoute;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingCapacityTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_is_rejected_when_trip_is_full(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $bus = Bus::create([
            'plate_number' => 'TEST-001',
            'capacity' => 1,
            'driver_name' => 'Test Driver',
            'status' => 'active',
        ]);

        $route = TransportRoute::create([
            'origin' => 'Musanze',
            'destination' => 'Kigali',
            'distance' => 103.5,
            'ticket_price' => 4500,
        ]);

        $trip = Trip::create([
            'bus_id' => $bus->bus_id,
            'route_id' => $route->route_id,
            'departure_date' => now()->addDay()->toDateString(),
            'departure_time' => '08:00',
            'arrival_time' => '10:30',
            'status' => 'scheduled',
        ]);

        $this->post(route('bookings.store'), [
            'trip_id' => $trip->trip_id,
            'passenger_name' => 'First Passenger',
            'phone_number' => '+250788111111',
            'payment_status' => 'paid',
        ])->assertRedirect();

        $this->post(route('bookings.store'), [
            'trip_id' => $trip->trip_id,
            'passenger_name' => 'Second Passenger',
            'phone_number' => '+250788222222',
            'payment_status' => 'paid',
        ])->assertSessionHasErrors('trip_id');
    }
}
