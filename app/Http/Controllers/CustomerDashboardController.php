<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Route as TransportRoute;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CustomerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'tickets');

        $bookings = Booking::with(['trip.bus', 'trip.route'])
            ->where('user_id', $request->user()->id)
            ->latest('booking_id')
            ->paginate(8);

        $routes = TransportRoute::withCount('trips')->latest('route_id')->get();

        $trips = Trip::with(['bus', 'route'])
            ->whereDate('departure_date', '>=', now()->toDateString())
            ->orderBy('departure_date')
            ->orderBy('departure_time')
            ->get();

        return view('account.index', compact('bookings', 'tab', 'routes', 'trips'));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($data);

        return redirect()->route('account', ['tab' => 'profile'])
            ->with('success', 'Profile updated.');
    }

    public function updateAvatar(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        if ($user->avatar) {
            Storage::delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return redirect()->route('account', ['tab' => 'profile'])
            ->with('success', 'Avatar updated.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('account', ['tab' => 'password'])
            ->with('success', 'Password changed.');
    }
}
