<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\SeatAvailability;
use App\Models\Ticket;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RepairMissingTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:repair-missing-tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perbaiki pesanan yang berstatus Lunas tapi tiket belum digenerate';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mencari pesanan berstatus Lunas (paid) tanpa tiket...');

        $orders = Order::with(['payment', 'user', 'eventSession.event.venue'])
            ->where('status', 'paid')
            ->doesntHave('tickets')
            ->get();

        if ($orders->isEmpty()) {
            $this->info('Tidak ditemukan pesanan bermasalah. Semua aman!');
            return;
        }

        $this->warn('Ditemukan ' . $orders->count() . ' pesanan bermasalah.');
        $repairedCount = 0;

        foreach ($orders as $order) {
            $this->line("Memproses Order: {$order->order_code} (User: {$order->user?->name})");

            try {
                DB::transaction(function () use ($order) {
                    // 1. Cari kursi yang seharusnya dikunci oleh order ini
                    // Karena order_id di seat_availabilities mungkin sudah null jika expired,
                    // Kita cari melalui histori pembayaran atau asumsi kursi apa yang dipesan.
                    // Tunggu, jika order_id sudah null, kita kesulitan tau kursi yang mana.
                    // Mari kita cek history di order_id yang tertinggal, atau Payment.
                    
                    // Coba cari kursi yang order_id-nya masih nyantol di order ini
                    $seatAvails = SeatAvailability::where('order_id', $order->id)->get();

                    // Jika kosong, ini bahaya. Admin mungkin mengubah order yang sudah cancelled.
                    // Namun kita cek apakah kita bisa menemukannya? Jika tidak, kita laporkan.
                    if ($seatAvails->isEmpty()) {
                        Log::warning("RepairMissingTickets: Order {$order->order_code} tidak memiliki kursi yang ditautkan!");
                        throw new \Exception("Tidak ada kursi yang ditautkan ke order_id {$order->id}. Manual intervention required.");
                    }

                    foreach ($seatAvails as $seat) {
                        $seat->update([
                            'status' => 'sold',
                            'locked_until' => null,
                        ]);

                        // Generate E-Ticket per seat
                        $ticketCode = 'TKT-' . date('Ymd') . '-' . strtoupper(Str::random(6));
                        $qrHash = hash('sha256', $ticketCode . '-' . $seat->id . '-' . Str::random(12));

                        Ticket::create([
                            'ticket_code' => $ticketCode,
                            'qr_code_hash' => $qrHash,
                            'order_id' => $order->id,
                            'seat_availability_id' => $seat->id,
                            'status' => 'valid',
                        ]);
                    }
                });

                // OTOMATIS KIRIM EMAIL E-TIKET KE PENONTON
                $order->unsetRelation('tickets');
                $order->load(['tickets.seatAvailability.seatMaster.seatCategory']);

                if ($order->user && $order->user->email) {
                    $resendApiKey = env('RESEND_API_KEY');

                    if (!empty($resendApiKey)) {
                        \Illuminate\Support\Facades\Http::withToken($resendApiKey)->post('https://api.resend.com/emails', [
                            'from' => env('RESEND_FROM_ADDRESS', 'Nanya Events <onboarding@resend.dev>'),
                            'to' => [$order->user->email],
                            'subject' => '🎫 E-Tiket Resmi Nanya Events — Order #' . $order->order_code,
                            'html' => view('emails.ticket-approved', ['order' => $order])->render(),
                        ]);
                    } else {
                        \Illuminate\Support\Facades\Mail::to($order->user->email)->send(new \App\Mail\TicketApprovedMail($order));
                    }
                    $this->info("Email E-Tiket berhasil dikirim ke {$order->user->email}");
                }

                $repairedCount++;
            } catch (\Exception $e) {
                $this->error("Gagal memproses Order {$order->order_code}: " . $e->getMessage());
            }
        }

        $this->info("Proses selesai. {$repairedCount} dari {$orders->count()} pesanan berhasil diperbaiki.");
    }
}
