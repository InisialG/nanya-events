<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventCatalogController;
use App\Http\Controllers\MyTicketsController;
use App\Http\Controllers\TicketScannerController;
use App\Livewire\SeatSelection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Katalog & Detail Event Publik
Route::get('/', [EventCatalogController::class, 'index'])->name('home');
Route::get('/events', [EventCatalogController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventCatalogController::class, 'show'])->name('events.show');

// Autentikasi Penonton (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [LoginController::class, 'register'])->name('register');

    // Reset Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

    // OAuth Google
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
});

// Autentikasi Required (Penonton Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Denah Kursi Interaktif (Livewire)
    Route::get('/events/{slug}/sessions/{sessionId}/seats', SeatSelection::class)->name('events.seats');

    // Checkout & Pembayaran Manual Transfer Bank
    Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/checkout/instructions/{orderCode}', [CheckoutController::class, 'showPaymentInstructions'])->name('checkout.instructions');
    Route::post('/checkout/upload-proof/{orderCode}', [CheckoutController::class, 'uploadProof'])->name('checkout.upload-proof');
    Route::get('/checkout/cloudinary-signature/{orderCode}', [CheckoutController::class, 'cloudinarySignature'])->name('checkout.cloudinary-signature');
    Route::post('/checkout/save-proof-url/{orderCode}', [CheckoutController::class, 'saveProofUrl'])->name('checkout.save-proof-url');
    Route::get('/checkout/success/{orderCode}', [CheckoutController::class, 'showSuccess'])->name('checkout.success');

    // Tiket Saya Penonton
    Route::get('/my-tickets', [MyTicketsController::class, 'index'])->name('my-tickets.index');
    Route::get('/my-tickets/print-multiple', [MyTicketsController::class, 'printMultiple'])->name('my-tickets.print-multiple');
    Route::get('/my-tickets/{ticketCode}', [MyTicketsController::class, 'show'])->name('my-tickets.show');

    // Endpoint Signature Direct Cloudinary Upload
    Route::get('/api/cloudinary-signature', [CheckoutController::class, 'generalCloudinarySignature'])->name('api.cloudinary-signature');

    // Petugas Scanner QR Tiket & Live Seat Monitor (Gatekeeper Entrance)
    Route::get('/scan-ticket', [TicketScannerController::class, 'index'])->name('scan-ticket.index');
    Route::post('/scan-ticket/validate', [TicketScannerController::class, 'validateTicket'])->name('scan-ticket.validate');
    Route::post('/scan-ticket/reset', [TicketScannerController::class, 'resetTicketStatus'])->name('scan-ticket.reset');
    Route::get('/admin/seat-attendance', \App\Livewire\AdminSeatAttendance::class)->name('admin.seat-attendance');
});

// Endpoint Simulasi k6 Load Testing
Route::post('/api/checkout-simulation', function (Illuminate\Http\Request $request) {
    $sessionId = $request->input('event_session_id');
    $seatId = $request->input('seat_id');

    try {
        DB::transaction(function () use ($sessionId, $seatId) {
            $seatAvail = \App\Models\SeatAvailability::where('id', $seatId)
                ->where('event_session_id', $sessionId)
                ->lockForUpdate()
                ->first();

            if (!$seatAvail) {
                abort(404, 'Seat not found');
            }

            if ($seatAvail->status !== 'available' && !($seatAvail->status === 'locked' && $seatAvail->locked_until < now())) {
                abort(409, 'Seat taken');
            }

            $seatAvail->update([
                'status' => 'locked',
                'locked_until' => now()->addMinutes(10),
            ]);
        });
        
        return response()->json(['message' => 'Success'], 200);
    } catch (\Exception $e) {
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        }
        return response()->json(['message' => 'Server Error: ' . $e->getMessage()], 500);
    }
});
