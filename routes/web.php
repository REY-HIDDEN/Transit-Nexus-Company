<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerBookingController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Language switcher
Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'rw'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Guest routes (login, register, password reset)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login/admin', [AuthController::class, 'showAdminLogin'])->name('login.admin');
    Route::get('/login/customer', [AuthController::class, 'showCustomerLogin'])->name('login.customer');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    // Forgot password routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Public booking routes (no auth required — guests can book and pay)
Route::get('/trips/{trip}/book', [CustomerBookingController::class, 'create'])->name('public.bookings.create');
Route::post('/trips/{trip}/book', [CustomerBookingController::class, 'store'])->name('public.bookings.store');

// Public payment routes (no auth required)
Route::get('/bookings/{booking}/pay', [PaymentController::class, 'create'])->name('payment.create');
Route::post('/bookings/{booking}/pay', [PaymentController::class, 'store'])->name('payment.store');

// Ticket lookup & receipt (no auth required — phone number based)
Route::get('/tickets/lookup', [App\Http\Controllers\TicketController::class, 'lookupForm'])->name('tickets.lookup');
Route::post('/tickets/lookup', [App\Http\Controllers\TicketController::class, 'lookup'])->name('tickets.lookup.post');
Route::get('/tickets/{booking}/receipt', [App\Http\Controllers\TicketController::class, 'receipt'])->name('tickets.receipt');

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/account', [CustomerDashboardController::class, 'index'])->name('account');
    Route::post('/account/profile', [CustomerDashboardController::class, 'updateProfile'])->name('account.profile.update');
    Route::post('/account/avatar', [CustomerDashboardController::class, 'updateAvatar'])->name('account.avatar.update');
    Route::post('/account/password', [CustomerDashboardController::class, 'updatePassword'])->name('account.password.update');

    // Session idle timeout
    Route::post('/session/ping', function () {
        return response()->json(['status' => 'ok']);
    })->name('session.ping');
});

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('buses', BusController::class)->except('show');
    Route::resource('routes', RouteController::class)->except('show');
    Route::resource('trips', TripController::class)->except('show');
    Route::resource('bookings', BookingController::class);
    Route::post('bookings/{booking}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::post('bookings/{booking}/verify-payment', [PaymentController::class, 'verify'])->name('bookings.verify-payment');
    Route::get('reports/payments', [ReportController::class, 'payments'])->name('reports.payments');
    Route::get('reports/payments/export', [ReportController::class, 'exportExcel'])->name('reports.payments.export');
});
?>
