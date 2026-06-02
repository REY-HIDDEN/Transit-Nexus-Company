@extends('layouts.public')

@section('title', __('messages.payment') . ' | ' . __('messages.title'))

@section('content')
    <section class="section-pad">
        <div class="container">
            <div class="row g-4 justify-content-center">
                {{-- Booking Summary --}}
                <div class="col-lg-5">
                    <div class="content-panel p-4" style="background: linear-gradient(135deg, #f8fafc, #f1f5f9);">
                        <div class="d-flex align-items-center gap-2 text-secondary small text-uppercase mb-2" style="letter-spacing: 0.05em;">
                            <i class="bi bi-receipt"></i> booking summary
                        </div>
                        <h1 class="h4 fw-bold mb-3" style="letter-spacing: -0.02em;">{{ $booking->trip?->route?->name }}</h1>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">ticket</div>
                                <div class="fw-semibold" style="font-family: monospace;">{{ $booking->ticket_number }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">passenger</div>
                                <div class="fw-semibold">{{ $booking->passenger_name }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">date</div>
                                <div class="fw-semibold"><i class="bi bi-calendar3 text-secondary me-1"></i>{{ $booking->trip?->departure_date?->format('M d, Y') }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">time</div>
                                <div class="fw-semibold"><i class="bi bi-clock text-secondary me-1"></i>{{ $booking->trip?->departure_time ? substr($booking->trip->departure_time, 0, 5) : '' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">seat</div>
                                <div class="fw-bold fs-5" style="color: var(--tn-blue);">{{ $booking->seat_number }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">bus</div>
                                <div class="fw-semibold"><i class="bi bi-bus-front text-secondary me-1"></i>{{ $booking->trip?->bus?->plate_number }}</div>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">ticket price</div>
                                <div class="fw-bold fs-5" style="color: var(--tn-blue);">{{ number_format($booking->total_amount, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Form --}}
                <div class="col-lg-7">
                    <form method="post" action="{{ route('payment.store', $booking) }}" class="content-panel p-4" id="paymentForm">
                        @csrf
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px; background: var(--tn-gradient); color: white; font-size: 0.85rem;">
                                <i class="bi bi-credit-card"></i>
                            </span>
                            <h2 class="h4 fw-bold mb-0" style="letter-spacing: -0.02em;">payment method</h2>
                        </div>

                        {{-- Payment Methods --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <input type="radio" name="payment_method" id="method_cash" value="cash" class="btn-check" {{ old('payment_method') === 'cash' ? 'checked' : '' }} checked>
                                <label for="method_cash" class="btn payment-method-card w-100" style="border: 2px solid var(--tn-line); border-radius: 12px; padding: 1.25rem 1rem; text-align: center; cursor: pointer; transition: all 0.2s ease;">
                                    <div class="fs-2 mb-2" style="color: var(--tn-gold);">💵</div>
                                    <div class="fw-bold small">Cash</div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="radio" name="payment_method" id="method_mobile" value="mobile_money" class="btn-check" {{ old('payment_method') === 'mobile_money' ? 'checked' : '' }}>
                                <label for="method_mobile" class="btn payment-method-card w-100" style="border: 2px solid var(--tn-line); border-radius: 12px; padding: 1.25rem 1rem; text-align: center; cursor: pointer; transition: all 0.2s ease;">
                                    <div class="fs-2 mb-2">📱</div>
                                    <div class="fw-bold small">Mobile Money</div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="radio" name="payment_method" id="method_card" value="credit_card" class="btn-check" {{ old('payment_method') === 'credit_card' ? 'checked' : '' }}>
                                <label for="method_card" class="btn payment-method-card w-100" style="border: 2px solid var(--tn-line); border-radius: 12px; padding: 1.25rem 1rem; text-align: center; cursor: pointer; transition: all 0.2s ease;">
                                    <div class="fs-2 mb-2">💳</div>
                                    <div class="fw-bold small">Credit Card</div>
                                </label>
                            </div>
                        </div>

                        {{-- Transaction Reference (shown for mobile money and credit card) --}}
                        <div class="mb-3" id="referenceField" style="display: none;">
                            <label for="transaction_reference" class="form-label">transaction reference <span class="text-danger">*</span></label>
                            <input type="text" id="transaction_reference" name="transaction_reference" class="form-control" value="{{ old('transaction_reference') }}" placeholder="e.g. MTN-1234567890">
                            <div class="text-secondary small mt-1">Enter the reference number from your payment receipt.</div>
                            @error('transaction_reference') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Insurance Option --}}
                        <div class="mb-4 p-3" style="background: #fefce8; border: 1.5px solid #fde68a; border-radius: 12px;">
                            <div class="d-flex align-items-start gap-3">
                                <div class="form-check" style="padding-left: 0;">
                                    <input type="hidden" name="insurance" value="0">
                                    <input type="checkbox" name="insurance" id="insurance" value="1" class="form-check-input" style="width: 20px; height: 20px; border-radius: 4px; border: 2px solid #d97706; cursor: pointer;" {{ old('insurance') ? 'checked' : '' }}>
                                </div>
                                <div class="flex-grow-1">
                                    <label for="insurance" class="fw-bold" style="cursor: pointer; color: #92400e;">add travel insurance</label>
                                    <div class="small" style="color: #a16207;">
                                        Protect your trip with travel insurance.
                                        <span class="fw-bold" id="insuranceFeeLabel">+{{ number_format($insuranceFee, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="mb-3">
                            <label for="notes" class="form-label">notes <span class="text-secondary fw-normal">(optional)</span></label>
                            <textarea id="notes" name="notes" class="form-control" rows="2" placeholder="Any additional notes...">{{ old('notes') }}</textarea>
                        </div>

                        {{-- Total Summary --}}
                        <div class="p-3 mb-3" style="background: #f0f9ff; border: 1.5px solid #bae6fd; border-radius: 12px;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">total to pay</div>
                                    <div id="totalDisplay" class="fw-bold fs-4" style="color: var(--tn-blue);">{{ number_format($booking->total_amount, 2) }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-secondary small" style="font-size: 0.7rem;">
                                        ticket: <span id="ticketPriceDisplay">{{ number_format($booking->total_amount, 2) }}</span>
                                        <br>
                                        insurance: <span id="insuranceDisplay">0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('account') }}" class="btn btn-outline-secondary btn-icon">
                                <i class="bi bi-arrow-left"></i><span>back to tickets</span>
                            </a>
                            <button type="submit" class="btn btn-primary btn-icon" style="background: linear-gradient(135deg, #059669, #047857); border: none; box-shadow: 0 2px 8px rgba(5, 150, 105, 0.25);">
                                <i class="bi bi-check2-circle"></i><span>confirm payment</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <style>
        .payment-method-card {
            transition: all 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important;
        }

        .payment-method-card:hover {
            border-color: var(--tn-blue) !important;
            background: rgba(37, 99, 235, 0.04) !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.1) !important;
        }

        .btn-check:checked + .payment-method-card {
            border-color: var(--tn-blue) !important;
            background: rgba(37, 99, 235, 0.08) !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
            transform: translateY(-2px);
        }

        .btn-check:checked + .payment-method-card .fw-bold {
            color: var(--tn-blue) !important;
        }

        html.dark .payment-method-card:hover {
            background: rgba(96, 165, 250, 0.08) !important;
        }

        html.dark .btn-check:checked + .payment-method-card {
            background: rgba(96, 165, 250, 0.12) !important;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.2) !important;
        }
    </style>

    <script>
    (function() {
        const form = document.getElementById('paymentForm');
        const referenceField = document.getElementById('referenceField');
        const insuranceCheck = document.getElementById('insurance');
        const totalDisplay = document.getElementById('totalDisplay');
        const insuranceDisplay = document.getElementById('insuranceDisplay');
        const basePrice = {{ $booking->total_amount }};
        const insuranceFee = {{ $insuranceFee }};

        function updateVisibility() {
            const selected = form.querySelector('input[name="payment_method"]:checked');
            if (selected) {
                const showRef = selected.value === 'mobile_money' || selected.value === 'credit_card';
                referenceField.style.display = showRef ? 'block' : 'none';
            }
        }

        function updateTotal() {
            const hasInsurance = insuranceCheck.checked;
            const total = basePrice + (hasInsurance ? insuranceFee : 0);
            totalDisplay.textContent = total.toFixed(2);
            insuranceDisplay.textContent = hasInsurance ? insuranceFee.toFixed(2) : '0.00';
        }

        // Listen for radio changes
        form.querySelectorAll('input[name="payment_method"]').forEach(function(radio) {
            radio.addEventListener('change', updateVisibility);
        });

        insuranceCheck.addEventListener('change', updateTotal);

        // Initial state
        updateVisibility();
        updateTotal();
    })();
    </script>
@endsection
