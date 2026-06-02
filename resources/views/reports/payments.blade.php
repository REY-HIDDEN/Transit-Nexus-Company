@extends('layouts.app')

@section('title', 'Payment Reports')
@section('subtitle', 'Daily payment summary with print & export')

@section('actions')
    <a href="{{ route('reports.payments.export', ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}" class="btn btn-success btn-icon no-print">
        <i class="bi bi-file-earmark-excel"></i><span>export excel</span>
    </a>
    <button type="button" class="btn btn-outline-secondary btn-icon no-print" onclick="window.print()">
        <i class="bi bi-printer"></i><span>print</span>
    </button>
@endsection

@section('content')
    {{-- Date Filter --}}
    <section class="panel p-3 mb-4 no-print">
        <form class="row g-3 align-items-end" method="get">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">from date</label>
                <input type="date" name="from" class="form-control" value="{{ $from->format('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">to date</label>
                <input type="date" name="to" class="form-control" value="{{ $to->format('Y-m-d') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary btn-icon flex-fill">
                    <i class="bi bi-search"></i><span>generate report</span>
                </button>
                <a href="{{ route('reports.payments') }}" class="btn btn-outline-secondary btn-icon">
                    <i class="bi bi-arrow-counterclockwise"></i><span>today</span>
                </a>
            </div>
        </form>
    </section>

    {{-- Summary Metrics --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="metric-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-secondary small text-uppercase" style="letter-spacing: 0.04em; font-size: 0.72rem;">total payments</div>
                        <div class="fs-3 fw-bold mt-1">{{ number_format($totalPayments) }}</div>
                    </div>
                    <span class="metric-icon"><i class="bi bi-receipt"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-secondary small text-uppercase" style="letter-spacing: 0.04em; font-size: 0.72rem;">total revenue</div>
                        <div class="fs-3 fw-bold mt-1" style="color: #059669;">{{ number_format($totalAmount, 2) }}</div>
                    </div>
                    <span class="metric-icon"><i class="bi bi-cash-stack"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-secondary small text-uppercase" style="letter-spacing: 0.04em; font-size: 0.72rem;">insurance revenue</div>
                        <div class="fs-3 fw-bold mt-1" style="color: #d97706;">{{ number_format($totalInsuranceFees, 2) }}</div>
                    </div>
                    <span class="metric-icon"><i class="bi bi-shield-check"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-secondary small text-uppercase" style="letter-spacing: 0.04em; font-size: 0.72rem;">insurances sold</div>
                        <div class="fs-3 fw-bold mt-1">{{ number_format($insuranceCount) }}</div>
                    </div>
                    <span class="metric-icon"><i class="bi bi-shield"></i></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Method Breakdown --}}
    @if ($methodBreakdown->isNotEmpty())
        <div class="row g-4 mb-4">
            @foreach ($methodBreakdown as $method => $data)
                <div class="col-md-4">
                    <div class="panel p-3 d-flex align-items-center gap-3">
                        <div class="fs-2">
                            @if ($method === 'cash') 💵
                            @elseif ($method === 'mobile_money') 📱
                            @else 💳
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ str_replace('_', ' ', ucfirst($method)) }}</div>
                            <div class="small text-secondary">{{ $data['count'] }} payments</div>
                        </div>
                        <div class="fw-bold fs-5" style="color: var(--tn-blue);">{{ number_format($data['amount'], 2) }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Report Period --}}
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <div class="text-secondary small">
            <i class="bi bi-calendar-range me-1"></i>
            {{ $from->format('M d, Y') }} &mdash; {{ $to->format('M d, Y') }}
        </div>
        <div class="text-secondary small">
            Generated: {{ now()->format('M d, Y H:i') }}
        </div>
    </div>

    {{-- Report Period (visible only in print) --}}
    <div style="display: none;" class="print-only text-center mb-3">
        <h4 class="fw-bold mb-1">Transit Nexus &mdash; Payment Report</h4>
        <div class="text-secondary small">{{ $from->format('M d, Y') }} &mdash; {{ $to->format('M d, Y') }}</div>
    </div>

    {{-- Payments Table --}}
    <section class="panel">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                <tr>
                    <th>date</th>
                    <th>ticket</th>
                    <th>passenger</th>
                    <th>route</th>
                    <th>seat</th>
                    <th class="text-end">amount</th>
                    <th>method</th>
                    <th>insurance</th>
                    <th>ref</th>
                    <th>verified</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($payments as $payment)
                    <tr>
                        <td class="small">{{ $payment->paid_at?->format('M d, Y H:i') ?? '-' }}</td>
                        <td class="fw-semibold" style="font-family: monospace; font-size: 0.85rem;">{{ $payment->booking?->ticket_number ?? '-' }}</td>
                        <td>
                            <div class="fw-semibold small">{{ $payment->booking?->passenger_name ?? '-' }}</div>
                            <div class="text-secondary" style="font-size: 0.75rem;">{{ $payment->booking?->phone_number ?? '' }}</div>
                        </td>
                        <td class="small">{{ $payment->booking?->trip?->route?->name ?? '-' }}</td>
                        <td class="text-center fw-semibold">{{ $payment->booking?->seat_number ?? '-' }}</td>
                        <td class="text-end fw-bold" style="color: #059669;">{{ number_format($payment->amount, 2) }}</td>
                        <td>
                            <span class="badge" style="background: #e0e7ff; color: #4338ca; border: none; font-size: 0.7rem;">
                                {{ str_replace('_', ' ', ucfirst($payment->payment_method)) }}
                            </span>
                        </td>
                        <td>
                            @if ($payment->insurance)
                                <span class="badge" style="background: #fef3c7; color: #92400e; border: none; font-size: 0.7rem;">
                                    +{{ number_format($payment->insurance_fee, 2) }}
                                </span>
                            @else
                                <span class="text-secondary" style="font-size: 0.75rem;">&mdash;</span>
                            @endif
                        </td>
                        <td>
                            @if ($payment->transaction_reference)
                                <span class="small" style="font-family: monospace; font-size: 0.75rem;">{{ $payment->transaction_reference }}</span>
                            @else
                                <span class="text-secondary" style="font-size: 0.75rem;">&mdash;</span>
                            @endif
                        </td>
                        <td>
                            @if ($payment->verified_at)
                                <span class="badge" style="background: #d1fae5; color: #065f46; border: none; font-size: 0.7rem;">
                                    <i class="bi bi-shield-check"></i> verified
                                </span>
                            @else
                                <span class="badge" style="background: #fef3c7; color: #92400e; border: none; font-size: 0.7rem;">
                                    pending
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <i class="bi bi-cash-stack text-secondary" style="font-size: 2.5rem; opacity: 0.3;"></i>
                            <p class="text-secondary mt-2 mb-0">No payments found for this period.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top d-flex justify-content-between align-items-center">
            <div class="text-secondary small">
                Showing {{ $payments->count() }} payment(s)
                @if ($payments->isNotEmpty())
                    &mdash; Total: <span class="fw-bold" style="color: #059669;">{{ number_format($totalAmount, 2) }}</span>
                @endif
            </div>
        </div>
    </section>

    <style>
        @media print {
            .no-print, .sidebar, .topbar { display: none !important; }
            .print-only { display: block !important; }
            .app-shell { display: block; }
            .page { padding: 0 !important; }
            .panel { box-shadow: none !important; border: 1px solid #ddd !important; }
            body::before { display: none !important; }
            .metric-card { break-inside: avoid; }
            .table td, .table th { padding: 0.4rem 0.6rem !important; font-size: 0.8rem !important; }
        }
        .print-only { display: none; }
    </style>
@endsection
