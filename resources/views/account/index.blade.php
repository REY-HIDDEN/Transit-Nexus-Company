@extends('layouts.public')

@section('title', __('messages.my_tickets') . ' | ' . __('messages.title'))

@section('content')
    <section class="section-pad">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4 reveal">
                <div>
                    <h1 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">my account</h1>
                    <div class="text-secondary">{{ auth()->user()->name }}</div>
                </div>
            </div>

            {{-- Tabs --}}
            <ul class="nav nav-tabs mb-4" style="border-bottom: 1px solid var(--tn-line);">
                <li class="nav-item">
                    <a class="nav-link {{ $tab === 'tickets' ? 'active' : '' }}" href="{{ route('account', ['tab' => 'tickets']) }}" style="border: none; font-weight: 600; color: {{ $tab === 'tickets' ? 'var(--tn-blue)' : 'var(--tn-muted)' }};">
                        <i class="bi bi-ticket-perforated me-1"></i>my tickets
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab === 'trips' ? 'active' : '' }}" href="{{ route('account', ['tab' => 'trips']) }}" style="border: none; font-weight: 600; color: {{ $tab === 'trips' ? 'var(--tn-blue)' : 'var(--tn-muted)' }};">
                        <i class="bi bi-calendar2-week me-1"></i>trips
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab === 'routes' ? 'active' : '' }}" href="{{ route('account', ['tab' => 'routes']) }}" style="border: none; font-weight: 600; color: {{ $tab === 'routes' ? 'var(--tn-blue)' : 'var(--tn-muted)' }};">
                        <i class="bi bi-signpost-2 me-1"></i>routes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab === 'profile' ? 'active' : '' }}" href="{{ route('account', ['tab' => 'profile']) }}" style="border: none; font-weight: 600; color: {{ $tab === 'profile' ? 'var(--tn-blue)' : 'var(--tn-muted)' }};">
                        <i class="bi bi-person me-1"></i>profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab === 'password' ? 'active' : '' }}" href="{{ route('account', ['tab' => 'password']) }}" style="border: none; font-weight: 600; color: {{ $tab === 'password' ? 'var(--tn-blue)' : 'var(--tn-muted)' }};">
                        <i class="bi bi-lock me-1"></i>password
                    </a>
                </li>
            </ul>

            {{-- Tickets Tab --}}
            @if ($tab === 'tickets')
                <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
                    <div></div>
                    <a href="{{ route('home') }}#trips" class="btn btn-primary btn-icon">
                        <i class="bi bi-search"></i><span>find route</span>
                    </a>
                </div>

                <section class="content-panel">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                            <tr>
                                <th>ticket</th>
                                <th>route</th>
                                <th>departure</th>
                                <th class="text-end">seat</th>
                                <th>payment</th>
                                <th>status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($bookings as $booking)
                                <tr>
                                    <td class="fw-semibold" style="font-family: monospace;">{{ $booking->ticket_number }}</td>
                                    <td class="fw-semibold">{{ $booking->trip?->route?->name }}</td>
                                    <td>
                                        <i class="bi bi-calendar3 text-secondary me-1"></i>{{ $booking->trip?->departure_date?->format('M d, Y') }}
                                        <i class="bi bi-clock text-secondary ms-1 me-1"></i>{{ $booking->trip?->departure_time ? substr($booking->trip->departure_time, 0, 5) : '' }}
                                    </td>
                                    <td class="text-end fw-bold">{{ $booking->seat_number }}</td>
                                    <td>
                                        @php $ps = $booking->payment_status === 'paid' ? ['#d1fae5', '#065f46'] : ['#fef3c7', '#92400e']; @endphp
                                        <span class="badge" style="background: {{ $ps[0] }}; color: {{ $ps[1] }}; border: none;">{{ ucfirst($booking->payment_status) }}</span>
                                    </td>
                                    <td>
                                        @php $bs = $booking->booking_status; $bsBg = $bs === 'confirmed' ? '#d1fae5' : ($bs === 'pending' ? '#fef3c7' : '#fee2e2'); $bsFg = $bs === 'confirmed' ? '#065f46' : ($bs === 'pending' ? '#92400e' : '#991b1b'); @endphp
                                        <span class="badge" style="background: {{ $bsBg }}; color: {{ $bsFg }}; border: none;">{{ ucfirst($booking->booking_status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-ticket text-secondary" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="text-secondary mt-2 mb-0">no tickets found</p>
                                        <a href="{{ route('home') }}#trips" class="btn btn-primary btn-icon mt-3">
                                            <i class="bi bi-search"></i><span>book a trip</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 border-top">
                        {{ $bookings->links() }}
                    </div>
                </section>

            {{-- Trips Tab --}}
            @elseif ($tab === 'trips')
                <section class="content-panel">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="bi bi-calendar2-week me-1"></i>all trips</h5>
                        <a href="{{ route('home') }}#trips" class="btn btn-primary btn-icon btn-sm">
                            <i class="bi bi-search"></i><span>book a trip</span>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                            <tr>
                                <th>route</th>
                                <th>bus</th>
                                <th>agency</th>
                                <th>date</th>
                                <th>departure</th>
                                <th>arrival</th>
                                <th class="text-end">seats left</th>
                                <th>status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($trips as $trip)
                                <tr>
                                    <td class="fw-semibold">{{ $trip->route?->name }}</td>
                                    <td>{{ $trip->bus?->plate_number }}</td>
                                    <td>{{ $trip->bus?->agency ?? '-' }}</td>
                                    <td>{{ $trip->departure_date?->format('M d, Y') }}</td>
                                    <td>{{ $trip->departure_time ? substr($trip->departure_time, 0, 5) : '' }}</td>
                                    <td>{{ $trip->arrival_time ? substr($trip->arrival_time, 0, 5) : '' }}</td>
                                    <td class="text-end">{{ $trip->remainingSeats() }}</td>
                                    <td><span class="badge badge-soft">{{ ucfirst($trip->status) }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="bi bi-calendar-x text-secondary" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="text-secondary mt-2 mb-0">no upcoming trips available</p>
                                        <a href="{{ route('home') }}#trips" class="btn btn-primary btn-icon mt-3">
                                            <i class="bi bi-search"></i><span>find trips</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

            {{-- Routes Tab --}}
            @elseif ($tab === 'routes')
                <section class="content-panel">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="bi bi-signpost-2 me-1"></i>all routes</h5>
                        <a href="{{ route('home') }}#trips" class="btn btn-primary btn-icon btn-sm">
                            <i class="bi bi-search"></i><span>book a trip</span>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                            <tr>
                                <th>route</th>
                                <th>origin</th>
                                <th>destination</th>
                                <th class="text-end">distance</th>
                                <th class="text-end">price</th>
                                <th class="text-end">trips</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($routes as $route)
                                <tr>
                                    <td class="fw-semibold">{{ $route->name }}</td>
                                    <td>{{ $route->origin }}</td>
                                    <td>{{ $route->destination }}</td>
                                    <td class="text-end">{{ number_format($route->distance, 2) }}</td>
                                    <td class="text-end fw-semibold">{{ number_format($route->ticket_price, 2) }}</td>
                                    <td class="text-end">{{ $route->trips_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-signpost-2 text-secondary" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="text-secondary mt-2 mb-0">no routes available</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

            {{-- Profile Tab --}}
            @elseif ($tab === 'profile')
                <div class="row g-4">
                    {{-- Avatar Card --}}
                    <div class="col-md-4">
                        <section class="content-panel p-4 text-center">
                            @php $avatarUrl = auth()->user()->avatar_url; @endphp
                            <div class="mb-3">
                                @if ($avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="avatar" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid var(--tn-line);">
                                @else
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; background: var(--tn-gradient); color: white; font-size: 3rem; font-weight: 700;">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <h5 class="fw-bold mb-0">{{ auth()->user()->name }}</h5>
                            <div class="text-secondary small">{{ auth()->user()->email }}</div>

                            <hr style="border-color: var(--tn-line);">

                            <form method="post" action="{{ route('account.avatar.update') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3 text-start">
                                    <label class="form-label small fw-semibold">change avatar</label>
                                    <input type="file" name="avatar" class="form-control form-control-sm" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" required>
                                    @error('avatar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm w-100 btn-icon justify-content-center">
                                    <i class="bi bi-upload"></i><span>upload</span>
                                </button>
                            </form>
                        </section>
                    </div>

                    {{-- Profile Form --}}
                    <div class="col-md-8">
                        <section class="content-panel p-4">
                            <h5 class="fw-bold mb-3"><i class="bi bi-person me-1"></i>edit profile</h5>
                            <form method="post" action="{{ route('account.profile.update') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                                    @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                                    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}">
                                    @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <button type="submit" class="btn btn-primary btn-icon">
                                    <i class="bi bi-check-lg"></i><span>save changes</span>
                                </button>
                            </form>
                        </section>
                    </div>
                </div>

            {{-- Password Tab --}}
            @elseif ($tab === 'password')
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <section class="content-panel p-4">
                            <h5 class="fw-bold mb-3"><i class="bi bi-lock me-1"></i>change password</h5>
                            <form method="post" action="{{ route('account.password.update') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">current password</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                    @error('current_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">new password</label>
                                    <input type="password" name="password" class="form-control" required>
                                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">confirm new password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-icon">
                                    <i class="bi bi-check-lg"></i><span>update password</span>
                                </button>
                            </form>
                        </section>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
