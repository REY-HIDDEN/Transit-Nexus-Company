<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Receipt</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f1f5f9; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 0 auto; padding: 2rem 1rem; }
        .card { background: #ffffff; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.06); overflow: hidden; }
        .header { background: linear-gradient(135deg, #059669, #047857); padding: 1.75rem 2rem; color: white; text-align: center; }
        .header h1 { margin: 0; font-size: 1.4rem; font-weight: 700; }
        .header p { margin: 0.35rem 0 0; opacity: 0.85; font-size: 0.9rem; }
        .check-icon { display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; border-radius: 50%; background: rgba(255,255,255,0.2); margin-bottom: 0.75rem; font-size: 1.5rem; }
        .body { padding: 2rem; }
        .receipt-header { text-align: center; margin-bottom: 1.5rem; }
        .receipt-header .amount { font-size: 2.2rem; font-weight: 800; color: #059669; letter-spacing: -0.02em; }
        .receipt-header .paid-label { display: inline-block; background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; }
        .details { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin: 1.5rem 0; }
        .detail-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; color: #64748b; margin-bottom: 0.2rem; }
        .detail-value { font-weight: 600; color: #0b1a33; }
        .ticket-number { font-size: 1.3rem; font-weight: 800; letter-spacing: -0.02em; color: #1e40af; font-family: monospace; }
        .divider { border: none; border-top: 1px dashed #e2e8f0; margin: 1.5rem 0; }
        .footer-text { color: #64748b; font-size: 0.85rem; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0; text-align: center; }
        .btn { display: inline-block; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; text-decoration: none; padding: 0.65rem 1.5rem; border-radius: 8px; font-weight: 600; }
        @media (max-width: 480px) { .details { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <div class="check-icon">&#10003;</div>
                <h1>Payment Received</h1>
                <p>Your payment has been processed successfully</p>
            </div>
            <div class="body">
                <div class="receipt-header">
                    <div class="amount">{{ number_format($payment->amount, 2) }}</div>
                    <span class="paid-label">Paid via {{ str_replace('_', ' ', ucfirst($payment->payment_method)) }}</span>
                </div>

                <div class="ticket-number">{{ $payment->booking?->ticket_number }}</div>

                <div class="details">
                    <div>
                        <div class="detail-label">Passenger</div>
                        <div class="detail-value">{{ $payment->booking?->passenger_name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="detail-label">Route</div>
                        <div class="detail-value">{{ $payment->booking?->trip?->route?->name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="detail-label">Seat</div>
                        <div class="detail-value">{{ $payment->booking?->seat_number ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="detail-label">Departure</div>
                        <div class="detail-value">{{ $payment->booking?->trip?->departure_date?->format('M d, Y') ?? 'N/A' }} {{ $payment->booking?->trip?->departure_time ? '@' . substr($payment->booking->trip->departure_time, 0, 5) : '' }}</div>
                    </div>
                    <div>
                        <div class="detail-label">Payment Method</div>
                        <div class="detail-value">{{ str_replace('_', ' ', ucfirst($payment->payment_method)) }}</div>
                    </div>
                    <div>
                        <div class="detail-label">Ticket Price</div>
                        <div class="detail-value">{{ number_format($payment->booking?->total_amount ?? 0, 2) }}</div>
                    </div>
                    @if ($payment->insurance)
                    <div>
                        <div class="detail-label">Insurance</div>
                        <div class="detail-value">+{{ number_format($payment->insurance_fee, 2) }}</div>
                    </div>
                    <div>
                        <div class="detail-label">Total Paid</div>
                        <div class="detail-value fw-bold" style="color: #059669;">{{ number_format($payment->amount, 2) }}</div>
                    </div>
                    @endif
                    @if ($payment->transaction_reference)
                    <div>
                        <div class="detail-label">Reference</div>
                        <div class="detail-value" style="font-family: monospace;">{{ $payment->transaction_reference }}</div>
                    </div>
                    @endif
                    <div>
                        <div class="detail-label">Paid At</div>
                        <div class="detail-value">{{ $payment->paid_at?->format('M d, Y H:i') ?? 'N/A' }}</div>
                    </div>
                </div>

                <hr class="divider">

                <div style="text-align: center;">
                    <a href="{{ url('/account') }}" class="btn">View My Tickets</a>
                </div>

                <div class="footer-text">
                    Thank you for choosing Transit Nexus. Safe travels!
                </div>
            </div>
        </div>
    </div>
</body>
</html>
