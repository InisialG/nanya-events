<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-heading font-extrabold text-3xl text-slate-900">Tiket & Pesanan Saya</h1>
                <p class="text-xs text-slate-500 mt-1">Daftar transaksi dan e-tiket pertunjukan pentas seni Anda.</p>
            </div>
        </div>

        <!-- 1. PENDING ORDERS / UNPAID TRANSACTIONS SECTION -->
        @if(isset($pendingOrders) && $pendingOrders->isNotEmpty())
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#F37032] animate-ping"></span>
                    <h2 class="font-heading font-bold text-xl text-slate-900">Riwayat Pesanan Anda</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($pendingOrders as $order)
                        <div class="bg-white p-6 rounded-3xl border-2 border-orange-200 shadow-sm space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-mono text-slate-500 font-bold">{{ $order->order_code }}</span>
                                @if($order->status === 'cancelled' || ($order->status === 'pending_payment' && $order->expired_at && $order->expired_at < now()))
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-600 border border-rose-200">Pesanan Dibatalkan</span>
                                @elseif($order->status === 'pending_payment')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-[#F37032] border border-orange-200">Belum Bayar</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">Menunggu Verifikasi Admin</span>
                                @endif
                            </div>

                            <div>
                                <h3 class="font-heading font-bold text-lg text-slate-900 mb-1">{{ $order->eventSession->event->title }}</h3>
                                <p class="text-xs text-slate-500">Total Pembayaran: <strong class="text-[#F37032] font-heading font-bold text-base">Rp {{ number_format($order->final_amount, 0, ',', '.') }}</strong></p>
                            </div>

                            @if($order->status === 'cancelled' || ($order->status === 'pending_payment' && $order->expired_at && $order->expired_at < now()))
                            <a href="{{ route('checkout.instructions', $order->order_code) }}" class="w-full py-3.5 px-5 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs text-center shadow-md shadow-rose-600/20 transition-all flex items-center justify-center gap-2">
                                <span>Lihat Detail Pembatalan</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                            @else
                            <a href="{{ route('checkout.instructions', $order->order_code) }}" class="w-full py-3.5 px-5 rounded-2xl bg-[#F37032] hover:bg-[#e05f24] text-white font-extrabold text-xs text-center shadow-md shadow-[#F37032]/20 transition-all flex items-center justify-center gap-2">
                                <span>Lanjutkan Pembayaran / Upload Bukti Transfer</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 2. APPROVED E-TICKETS SECTION -->
        <div class="space-y-4" x-data="{ 
            selectedTickets: [],
            allTicketCodes: [{{ $tickets->pluck('ticket_code')->map(fn($c) => "'".$c."'")->implode(',') }}],
            toggleSelectAll() {
                if (this.selectedTickets.length === this.allTicketCodes.length) {
                    this.selectedTickets = [];
                } else {
                    this.selectedTickets = [...this.allTicketCodes];
                }
            },
            submitBulkPrint() {
                if (this.selectedTickets.length === 0) return;
                const codesParam = this.selectedTickets.join(',');
                window.location.href = '{{ route("my-tickets.print-multiple") }}?codes=' + encodeURIComponent(codesParam);
            }
        }">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h2 class="font-heading font-bold text-xl text-slate-900">E-Tiket Aktif</h2>

                @if($tickets->isNotEmpty())
                    <!-- Control Bar untuk Pilih Banyak Tiket & Download Sekaligus -->
                    <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-2xl border border-slate-200 shadow-sm">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" 
                                   @change="toggleSelectAll()" 
                                   :checked="selectedTickets.length === allTicketCodes.length && allTicketCodes.length > 0"
                                   class="w-4 h-4 text-[#F37032] rounded border-slate-300 focus:ring-[#F37032]">
                            <span class="text-xs font-bold text-slate-700">Pilih Semua</span>
                        </label>

                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-orange-50 text-[#F37032] border border-orange-200"
                              x-text="selectedTickets.length + ' Dipilih'"></span>

                        <button type="button"
                                @click="submitBulkPrint()"
                                :disabled="selectedTickets.length === 0"
                                :class="selectedTickets.length > 0 ? 'bg-[#F37032] hover:bg-[#e05f24] text-white shadow-sm cursor-pointer' : 'bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200'"
                                class="px-4 py-1.5 rounded-xl font-bold text-xs transition-all flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span x-text="selectedTickets.length > 0 ? 'Unduh Terpilih (' + selectedTickets.length + ')' : 'Unduh Sekaligus'"></span>
                        </button>
                    </div>
                @endif
            </div>

            @if($tickets->isEmpty())
                <div class="bg-white p-12 rounded-3xl text-center border border-slate-200 shadow-sm">
                    <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5z"></path></svg>
                    <h3 class="text-lg font-bold text-slate-800">Belum Ada E-Tiket Terbit</h3>
                    <p class="text-xs text-slate-500 mt-1 mb-6">Pesan tiket event favorit Anda dan lakukan transfer untuk mendapatkan E-Tiket.</p>
                    <a href="{{ url('/events') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[#F37032] hover:bg-[#e05f24] text-white transition-all inline-block shadow-md shadow-[#F37032]/20">
                        Jelajahi Katalog Event
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($tickets as $ticket)
                        @php
                            $event = $ticket->order->eventSession->event;
                            $session = $ticket->order->eventSession;
                            $master = $ticket->seatAvailability->seatMaster;
                            $cat = $master->seatCategory;
                        @endphp
                        <div class="bg-white rounded-3xl p-6 border transition-all duration-300 flex flex-col justify-between relative"
                             :class="selectedTickets.includes('{{ $ticket->ticket_code }}') ? 'border-2 border-[#F37032] shadow-md ring-1 ring-[#F37032]/30' : 'border border-slate-200 hover:border-[#F37032] shadow-sm'">
                            <div>
                                <!-- Header Status Badge & Checkbox -->
                                <div class="flex items-center justify-between mb-4">
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" 
                                               value="{{ $ticket->ticket_code }}" 
                                               x-model="selectedTickets" 
                                               class="w-4 h-4 text-[#F37032] rounded border-slate-300 focus:ring-[#F37032]">
                                        <span class="text-[10px] font-mono text-slate-600 font-bold">{{ $ticket->ticket_code }}</span>
                                    </label>
                                    
                                    @if($ticket->status === 'valid')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 border border-emerald-200 text-emerald-700">Siap Digunakan</span>
                                    @elseif($ticket->status === 'used')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 border border-slate-200 text-slate-500">Sudah Digunakan</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-50 border border-rose-200 text-rose-700">Batal</span>
                                    @endif
                                </div>

                                <!-- Event Title -->
                                <h3 class="font-heading font-bold text-lg text-slate-900 mb-2 line-clamp-1">{{ $event->title }}</h3>
                                <p class="text-xs text-slate-500 mb-4">{{ $event->venue->name }}</p>

                                <!-- Seat Details Box -->
                                <div class="p-4 rounded-2xl bg-orange-50/60 border border-orange-100 flex items-center justify-between mb-6">
                                    <div>
                                        <span class="text-[10px] text-slate-500 uppercase tracking-wider block font-semibold">Nomor Kursi</span>
                                        <span class="font-heading font-extrabold text-xl text-[#F37032]">{{ $master->seat_code }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] text-slate-500 uppercase tracking-wider block font-semibold">Kategori</span>
                                        <span class="font-bold text-xs text-slate-900">{{ $cat?->name ?? 'Reguler' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('my-tickets.show', $ticket->ticket_code) }}" class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-[#F37032] text-white font-bold text-xs text-center transition-all flex items-center justify-center gap-2 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Lihat Tiket Ini
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
