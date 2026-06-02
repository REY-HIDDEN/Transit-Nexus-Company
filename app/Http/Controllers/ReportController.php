<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function payments(Request $request)
    {
        $from = $request->date('from') ?? now()->startOfDay();
        $to = $request->date('to') ?? now()->endOfDay();

        $payments = Payment::with(['booking.trip.route', 'booking.user', 'verifiedBy'])
            ->whereBetween('paid_at', [$from->startOfDay(), $to->endOfDay()])
            ->latest('paid_at')
            ->get();

        // Summary metrics
        $totalPayments = $payments->count();
        $totalAmount = $payments->sum('amount');
        $totalInsuranceFees = $payments->where('insurance', true)->sum('insurance_fee');

        $methodBreakdown = $payments->groupBy('payment_method')->map(function ($items, $method) {
            return [
                'count' => $items->count(),
                'amount' => $items->sum('amount'),
            ];
        });

        $insuranceCount = $payments->where('insurance', true)->count();

        return view('reports.payments', compact(
            'payments',
            'from',
            'to',
            'totalPayments',
            'totalAmount',
            'totalInsuranceFees',
            'methodBreakdown',
            'insuranceCount',
        ));
    }

    public function exportExcel(Request $request)
    {
        $from = $request->date('from') ?? now()->startOfDay();
        $to = $request->date('to') ?? now()->endOfDay();

        $payments = Payment::with(['booking.trip.route', 'booking.user'])
            ->whereBetween('paid_at', [$from->startOfDay(), $to->endOfDay()])
            ->latest('paid_at')
            ->get();

        $filename = 'payment-report-'.$from->format('Y-m-d').'-to-'.$to->format('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fputs($file, "\xEF\xBB\xBF");

            // Header row
            fputcsv($file, [
                'Date',
                'Ticket #',
                'Passenger',
                'Route',
                'Seat',
                'Amount',
                'Payment Method',
                'Insurance',
                'Insurance Fee',
                'Reference',
                'Status',
                'Verified At',
                'Verified By',
            ]);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->paid_at?->format('Y-m-d H:i') ?? '',
                    $payment->booking?->ticket_number ?? '',
                    $payment->booking?->passenger_name ?? '',
                    $payment->booking?->trip?->route?->name ?? '',
                    $payment->booking?->seat_number ?? '',
                    number_format($payment->amount, 2),
                    str_replace('_', ' ', ucfirst($payment->payment_method)),
                    $payment->insurance ? 'Yes' : 'No',
                    $payment->insurance ? number_format($payment->insurance_fee, 2) : '0.00',
                    $payment->transaction_reference ?? '',
                    ucfirst($payment->status),
                    $payment->verified_at?->format('Y-m-d H:i') ?? '',
                    $payment->verifiedBy?->name ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
