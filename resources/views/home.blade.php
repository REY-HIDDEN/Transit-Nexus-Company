@extends('layouts.public')

@section('title', __('messages.book_tickets') . ' | Transit Nexus')

@section('content')
    <section class="hero">
        <div class="hero-floater hero-floater-1"></div>
        <div class="hero-floater hero-floater-2"></div>
        <div class="hero-floater hero-floater-3"></div>
        <div class="container">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 text-uppercase small fw-semibold mb-3 reveal" style="letter-spacing: 0.08em; color: rgba(255,255,255,0.7);">
                    <i class="bi bi-bus-front"></i> {{ __('messages.musanze_transport') }}
                </div>
                <h1 class="fw-bold mb-3 reveal-left" style="transition-delay: 0.1s;">
                    Transit <span style="background: linear-gradient(135deg, #60a5fa, #34d399); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Nexus</span>
                </h1>
                <p class="lead mb-4 reveal-left" style="font-size: 1.15rem; color: rgba(255,255,255,0.85); max-width: 90%; transition-delay: 0.2s;">
                    {{ __('messages.hero_subtitle') }}
                </p>
                <form class="search-panel p-3 shadow-lg reveal" style="transition-delay: 0.35s;" method="get" action="{{ route('home') }}#trips">
                    <div class="row g-2 align-items-center">
                        <div class="col-md">
                            <input type="search" name="search" value="{{ $search }}"
                                   class="form-control form-control-lg"
                                   placeholder="{{ __('messages.search_placeholder') }}">
                        </div>
                        <div class="col-md-auto">
                            <button class="btn btn-primary btn-lg btn-icon w-100">
                                <i class="bi bi-search"></i><span>{{ __('messages.find_route') }}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section id="trips" class="section-pad">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4 reveal">
                <div>
                    <h2 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">{{ __('messages.available_trips') }}</h2>
                    <div class="text-secondary">{{ __('messages.choose_destination') }}</div>
                </div>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-primary btn-icon">
                        <i class="bi bi-person-plus"></i><span>{{ __('messages.create_account') }}</span>
                    </a>
                @endguest
            </div>
            <div class="row g-4 reveal-stagger">
                @forelse ($trips as $trip)
                    <div class="col-md-6 col-xl-4">
                        <article class="trip-card p-3 d-flex flex-column position-relative">
                            <div class="card-glow"></div>
                            <div class="d-flex justify-content-between gap-3 mb-3">
                                <div>
                                    <div class="text-secondary small text-uppercase" style="letter-spacing: 0.05em; font-size: 0.7rem;">{{ __('messages.route') }}</div>
                                    <h3 class="h5 mb-0 fw-bold">{{ $trip->route?->name }}</h3>
                                </div>
                                <span class="badge badge-soft align-self-start">{{ ucfirst($trip->status) }}</span>
                            </div>

                            <div class="d-flex gap-3 small mb-3 p-2" style="background: #f8fafc; border-radius: 10px;">
                                <div class="flex-fill">
                                    <div class="text-secondary" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.04em;">{{ __('messages.departure') }}</div>
                                    <div class="fw-semibold">{{ $trip->departure_date?->format('M d, Y') }}</div>
                                    <div>{{ substr($trip->departure_time, 0, 5) }}</div>
                                </div>
                                <div class="flex-fill">
                                    <div class="text-secondary" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.04em;">{{ __('messages.bus') }}</div>
                                    <div class="fw-semibold">{{ $trip->bus?->plate_number }}</div>
                                    <div>
                                        <span class="fw-semibold" style="color: #059669;">{{ $trip->remainingSeats() }}</span>
                                        {{ __('messages.seats_left') }}
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-auto">
                                <div>
                                    <div class="text-secondary small" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.04em;">{{ __('messages.price') }}</div>
                                    <div class="fw-bold fs-5" style="color: var(--tn-blue);">{{ number_format($trip->route?->ticket_price ?? 0, 2) }}</div>
                                </div>
                                <a href="{{ route('public.bookings.create', $trip) }}" class="btn btn-primary btn-icon">
                                    <i class="bi bi-ticket-perforated"></i><span>{{ __('messages.book') }}</span>
                                </a>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="content-panel p-5 text-center">
                            <i class="bi bi-bus-front text-secondary" style="font-size: 3.5rem; opacity: 0.25;"></i>
                            <p class="text-secondary mt-3 mb-0 fs-5">{{ __('messages.no_trips') }}</p>
                            <a href="{{ route('home') }}" class="btn btn-outline-primary btn-icon mt-3">
                                <i class="bi bi-arrow-left"></i><span>{{ __('messages.view_all_trips') }}</span>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
