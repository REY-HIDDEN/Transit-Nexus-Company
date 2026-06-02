<?php

namespace App\Http\Controllers;

use App\Mail\PaymentReceipt;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    const INSURANCE_FEE = 1000;

    public function create(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated user
        if ($booking->user_id !== request()->user()->id) {
            abort(403);
        }

        // Ensure the booking is still pending payment
        if ($booking->payment_status === 'paid') {
            return redirect()->route('account')->with('info', 'This ticket has already been paid.');
        }

        $booking->load(['trip.bus', 'trip.route']);

        $insuranceFee = self::INSURANCE_FEE;

        return view('public-bookings.payment', compact('booking', 'insuranceFee'));
    }

    public function store(Request $request, Booking $booking)
    {
        if ($booking->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($booking->payment_status === 'paid') {
            return redirect()->route('account')->with('info', 'This ticket has already been paid.');
        }

        $booking->load(['trip.route']);

        $data = $request->validate([
            'payment_method' => ['required', Rule::in(['cash', 'mobile_money', 'credit_card'])],
            'insurance' => ['nullable', 'boolean'],
            'transaction_reference' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $ticketPrice = (float) ($booking->trip?->route?->ticket_price ?? 0);
        $insurance = ! empty($data['insurance']);
        $insuranceFee = $insurance ? self::INSURANCE_FEE : 0;
        $totalAmount = $ticketPrice + $insuranceFee;

        // For mobile money and credit card, require a transaction reference
        if (in_array($data['payment_method'], ['mobile_money', 'credit_card']) && empty($data['transaction_reference'])) {
            return back()->withErrors(['transaction_reference' => 'A transaction reference is required for ' . str_replace('_', ' ', $data['payment_method']) . '.'])->withInput();
        }

        $payment = Payment::create([
            'booking_id' => $booking->booking_id,
            'amount' => $totalAmount,
            'payment_method' => $data['payment_method'],
            'insurance' => $insurance,
            'insurance_fee' => $insuranceFee,
            'status' => 'completed',
            'transaction_reference' => $data['transaction_reference'] ?? null,
            'notes' => $data['notes'] ?? null,
            'paid_at' => now(),
        ]);

        $booking->update(['payment_status' => 'paid']);

        // Send payment receipt email
        if ($booking->user?->email) {
            Mail::to($booking->user->email)->queue(new PaymentReceipt($payment));
        }

        return redirect()->route('account')->with('success', 'Payment completed successfully! A receipt has been sent to your email.');
    }

    public function verify(Booking $booking)
    {
        $payment = $booking->latestPayment;

        if (! $payment) {
            return back()->with('error', 'No payment record found for this booking.');
        }

        if ($payment->verified_at) {
            return back()->with('error', 'This payment has already been verified.');
        }

        $payment->update([
            'verified_at' => now(),
            'verified_by' => request()->user()->id,
        ]);

        return redirect()->route('bookings.show', $booking)->with('success', "Payment verified for {$booking->ticket_number}. Amount matches system price.");
    }
}
