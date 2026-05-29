<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Bus;
use App\Models\Route as TransportRoute;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@transitnexus.test'],
            [
                'name' => 'Transit Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
        );

        $buses = collect([
            ['plate_number' => 'RAB 248K', 'capacity' => 42, 'driver_name' => 'Jean Hakizimana', 'agency' => 'Ritco', 'status' => 'active'],
            ['plate_number' => 'RAD 512M', 'capacity' => 35, 'driver_name' => 'Aline Uwase', 'agency' => 'Volcano Express', 'status' => 'active'],
            ['plate_number' => 'RAE 904T', 'capacity' => 29, 'driver_name' => 'Eric Ndayisenga', 'agency' => 'Virunga Express', 'status' => 'active'],
            ['plate_number' => 'RAF 102A', 'capacity' => 30, 'driver_name' => 'Pacifique Nshimiye', 'agency' => 'Horizon Express', 'status' => 'active'],
            ['plate_number' => 'RAG 773B', 'capacity' => 40, 'driver_name' => 'Emmanuel Mutabazi', 'agency' => 'Stella Express', 'status' => 'active'],
        ])->map(fn ($bus) => Bus::query()->firstOrCreate(
            ['plate_number' => $bus['plate_number']],
            $bus,
        ));

        $routes = collect([
            ['origin' => 'Nyabugogo', 'destination' => 'Musanze', 'distance' => 103.50, 'ticket_price' => 4500],
            ['origin' => 'Nyabugogo', 'destination' => 'Rubavu', 'distance' => 150.00, 'ticket_price' => 6000],
            ['origin' => 'Nyabugogo', 'destination' => 'Gicumbi', 'distance' => 72.40, 'ticket_price' => 3200],
            ['origin' => 'Nyabugogo', 'destination' => 'Huye', 'distance' => 125.00, 'ticket_price' => 5000],
            ['origin' => 'Musanze', 'destination' => 'Kigali', 'distance' => 103.50, 'ticket_price' => 4500],
        ])->map(fn ($route) => TransportRoute::query()->firstOrCreate(
            ['origin' => $route['origin'], 'destination' => $route['destination']],
            $route,
        ));

        // Seed some trips starting from today and the next 5 days
        $tripsData = [
            // Nyabugogo to Musanze
            ['bus_index' => 0, 'route_index' => 0, 'day_offset' => 0, 'time' => '07:30', 'arrival' => '10:00'],
            ['bus_index' => 1, 'route_index' => 0, 'day_offset' => 1, 'time' => '09:00', 'arrival' => '11:30'],
            ['bus_index' => 2, 'route_index' => 0, 'day_offset' => 1, 'time' => '13:00', 'arrival' => '15:30'],
            ['bus_index' => 4, 'route_index' => 0, 'day_offset' => 2, 'time' => '16:30', 'arrival' => '19:00'],
            // Nyabugogo to Rubavu
            ['bus_index' => 1, 'route_index' => 1, 'day_offset' => 0, 'time' => '08:00', 'arrival' => '11:30'],
            ['bus_index' => 3, 'route_index' => 1, 'day_offset' => 1, 'time' => '10:30', 'arrival' => '14:00'],
            ['bus_index' => 0, 'route_index' => 1, 'day_offset' => 2, 'time' => '14:00', 'arrival' => '17:30'],
            // Nyabugogo to Gicumbi
            ['bus_index' => 2, 'route_index' => 2, 'day_offset' => 0, 'time' => '09:30', 'arrival' => '11:30'],
            ['bus_index' => 4, 'route_index' => 2, 'day_offset' => 1, 'time' => '15:00', 'arrival' => '17:00'],
            // Nyabugogo to Huye
            ['bus_index' => 3, 'route_index' => 3, 'day_offset' => 0, 'time' => '06:30', 'arrival' => '09:30'],
            ['bus_index' => 0, 'route_index' => 3, 'day_offset' => 1, 'time' => '12:00', 'arrival' => '15:00'],
        ];

        $seededTrips = [];
        foreach ($tripsData as $data) {
            $seededTrips[] = Trip::query()->firstOrCreate(
                [
                    'bus_id' => $buses[$data['bus_index']]->bus_id,
                    'route_id' => $routes[$data['route_index']]->route_id,
                    'departure_date' => now()->addDays($data['day_offset'])->toDateString(),
                    'departure_time' => $data['time'],
                ],
                [
                    'arrival_time' => $data['arrival'],
                    'status' => 'scheduled',
                ]
            );
        }

        $trip = $seededTrips[0];

        Booking::query()->firstOrCreate(
            ['ticket_number' => 'TNX-DEMO-001'],
            [
                'trip_id' => $trip->trip_id,
                'passenger_name' => 'Demo Passenger',
                'phone_number' => '+250 788 000 001',
                'seat_number' => 1,
                'booking_date' => now()->toDateString(),
                'payment_status' => 'paid',
                'booking_status' => 'confirmed',
            ],
        );
    }
}
