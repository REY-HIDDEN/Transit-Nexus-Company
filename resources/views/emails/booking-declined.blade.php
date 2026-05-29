<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Declined</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f1f5f9; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 0 auto; padding: 2rem 1rem; }
        .card { background: #ffffff; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.06); overflow: hidden; }
        .header { background: linear-gradient(135deg, #dc2626, #991b1b); padding: 1.75rem 2rem; color: white; }
        .header h1 { margin: 0; font-size: 1.4rem; font-weight: 700; }
        .header p { margin: 0.35rem 0 0; opacity: 0.85; font-size: 0.9rem; }
        .body { padding: 2rem; }
        .ticket-number { font-size: 1.6rem; font-weight: 800; letter-spacing: -0.02em; color: #dc2626; margin: 0 0 0.25rem; font-family: monospace; }
        .badge { display: inline-block; background: #fee2e2; color: #991b1b; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; }
        .details { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin: 1.5rem 0; }
        .detail-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; color: #64748b; margin-bottom: 0.2rem; }
        .detail-value { font-weight: 600; color: #0b1a33; }
        .footer-text { color: #64748b; font-size: 0.85rem; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0; }
        .btn { display: inline-block; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; text-decoration: none; padding: 0.65rem 1.5rem; border-radius: 8px; font-weight: 600; margin-top: 0.5rem; }
        @media (max-width: 480px) { .details { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <h1>Booking Declined</h1>
                <p>Your booking request could not be processed</p>
            </div>
            <div class="body">
                <p style="color: #0b1a33;">Hello <strong>{{ $booking->passenger_name }}</strong>,</p>
                <p style="color: #475569;">Unfortunately, your booking has been declined. Please review the details below and try booking another trip.</p>

                <div class="ticket-number">{{ $booking->ticket_number }}</div>
                <span class="badge">Declined</span>

                <div class="details">
                    <div>
                        <div class="detail-label">Route</div>
                        <div class="detail-value">{{ $booking->trip?->route?->name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="detail-label">Seat</div>
                        <div class="detail-value">{{ $booking->seat_number }}</div>
                    </div>
                    <div>
                        <div class="detail-label">Departure Date</div>
                        <div class="detail-value">{{ $booking->trip?->departure_date?->format('M d, Y') ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="detail-label">Departure Time</div>
                        <div class="detail-value">{{ $booking->trip?->departure_time ? substr($booking->trip->departure_time, 0, 5) : 'N/A' }}</div>
                    </div>
                </div>

                <a href="{{ url('/') }}" class="btn">Browse Other Trips</a>

                <div class="footer-text">
                    If you believe this is a mistake, please contact Transit Nexus support. We appreciate your interest!
                </div>
            </div>
        </div>
    </div>
</body>
</html>
