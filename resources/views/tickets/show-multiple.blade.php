<x-app-layout>
    <!-- Include html2canvas CDN for PNG Image Export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        @media print {
            @page {
                size: portrait;
                margin: 8mm;
            }
            html, body {
                background: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            header, footer, nav, .no-print {
                display: none !important;
            }
            .printable-wrapper {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }
            .ticket-card-stub {
                box-shadow: none !important;
                border: 2px solid #F37032 !important;
                page-break-after: always !important;
                break-after: page !important;
            }
        }
    </style>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 printable-wrapper">
        
        <!-- Top Action Card Header -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm mb-8 no-print flex flex-col sm:flex-row items-center justify-between gap-4">
            <a href="{{ route('my-tickets.index') }}" class="text-xs font-extrabold text-[#F37032] hover:underline flex items-center gap-1.5 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                <span>Kembali ke Daftar Tiket Saya</span>
            </a>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <span class="text-xs font-extrabold px-3 py-2 rounded-xl bg-orange-50 text-[#F37032] border border-orange-200">
                    {{ count($tickets) }} Tiket Terpilih
                </span>

                <button 
                    onclick="downloadMultiplePNG(event)" 
                    type="button"
                    class="px-4 py-2 rounded-xl bg-[#F37032] hover:bg-[#e05f24] text-white font-extrabold text-xs shadow-md shadow-[#F37032]/20 transition-all flex items-center gap-1.5 shrink-0 cursor-pointer"
                    title="Simpan seluruh tiket terpilih langsung sebagai gambar PNG ke Galeri/Downloads"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Unduh Semua Gambar (.PNG)</span>
                </button>

                <button 
                    onclick="window.print()" 
                    type="button"
                    class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-sm transition-all flex items-center gap-1.5 shrink-0 cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 00-2-v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Cetak</span>
                </button>
            </div>
        </div>

        <!-- Loop Tickets Section Container -->
        <div id="multiple-tickets-target" class="space-y-8 flex flex-col items-center">
            @foreach($tickets as $index => $ticket)
                <!-- Custom Flat Sharp E-Pass Ticket Card (No Rounded Border & No Side Circles) -->
                <div data-code="{{ $ticket->ticket_code }}" class="ticket-card-stub bg-white border-2 border-[#F37032] relative w-full max-w-md flex flex-col justify-between box-border shadow-md">
                    
                    <!-- 1. Header Banner -->
                    <div class="bg-[#F37032] py-3.5 px-4 flex items-center justify-center relative shrink-0">
                        <!-- Official Nanyang Zhi Hui Logo Centered & Prominent -->
                        <img src="{{ asset('img/Nanyang Logo with text white (1).png') }}" crossorigin="anonymous" alt="Logo Nanyang Zhi Hui" class="h-16 sm:h-20 w-auto object-contain mx-auto my-0.5">
                    </div>

                    <!-- 2. Card Body Info -->
                    <div class="p-6 space-y-5 bg-white flex-grow">
                        <!-- Centered Event Title in White Section -->
                        <div class="pb-3 border-b border-slate-100 text-center">
                            <h2 class="font-heading font-black text-xl sm:text-2xl text-slate-900 leading-tight">{{ $ticket->order->eventSession->event->title }}</h2>
                        </div>

                        <!-- Location & Date Grid (Neat Card Modules) -->
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200/80">
                                <span class="text-[9px] text-slate-400 uppercase tracking-wider block font-bold">Venue / Lokasi</span>
                                <span class="font-extrabold text-slate-900 text-xs sm:text-sm block mt-1 leading-snug">{{ $ticket->order->eventSession->event->venue->name }}</span>
                            </div>

                            <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200/80">
                                <span class="text-[9px] text-slate-400 uppercase tracking-wider block font-bold">Tanggal & Jam</span>
                                <span class="font-extrabold text-slate-900 text-xs sm:text-sm block mt-1">
                                    {{ \Carbon\Carbon::parse($ticket->order->eventSession->session_date)->translatedFormat('d M Y') }}
                                </span>
                                <span class="text-[11px] text-[#F37032] font-black block mt-0.5">Jam {{ \Carbon\Carbon::parse($ticket->order->eventSession->start_time)->format('H:i') }} WIB</span>
                            </div>
                        </div>

                        <!-- Seat & Category Highlight Box -->
                        <div class="p-4 rounded-lg bg-orange-50/80 border border-orange-200/80 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-bold">Nomor Kursi</span>
                                <span class="font-heading font-black text-3xl text-[#F37032] tracking-tight">{{ $ticket->seatAvailability->seatMaster->seat_code }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-bold">Kategori Kursi</span>
                                <span class="font-extrabold text-sm text-slate-900 px-3 py-1 rounded bg-white border border-slate-200 inline-block mt-0.5">
                                    {{ $ticket->seatAvailability->seatMaster->seatCategory?->name ?? 'Reguler' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Clean Flat Dashed Line Divider -->
                    <div class="border-b-2 border-dashed border-slate-300 mx-6 my-1"></div>

                    <!-- 4. QR Code & Verification Section -->
                    <div class="p-6 bg-white text-center space-y-3">
                        <div class="w-44 h-44 mx-auto bg-white p-3 rounded-lg shadow-sm border-2 border-slate-200 flex items-center justify-center">
                            <!-- QR Code Render via QuickChart API with CORS -->
                            <img src="https://quickchart.io/qr?text={{ urlencode($ticket->qr_code_hash) }}&size=250&margin=1" crossorigin="anonymous" alt="QR Code E-Tiket" class="w-full h-full object-contain">
                        </div>
                        
                        <div>
                            <span class="font-mono text-sm text-slate-800 font-extrabold tracking-wider block">{{ $ticket->ticket_code }}</span>
                            <span class="text-[10px] text-slate-400 font-semibold block mt-1">Tunjukkan QR Code ini kepada petugas di pintu masuk venue.</span>
                        </div>
                    </div>

                    <!-- 5. Ticket Owner Bottom Footer -->
                    <div class="bg-slate-50 p-3 border-t border-slate-200 text-center shrink-0">
                        <span class="text-[10px] text-slate-500 font-semibold">Pemilik Tiket: <strong class="text-slate-900 font-bold">{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})</span>
                    </div>

                </div>
            @endforeach
        </div>

    </div>

    <script>
        async function downloadMultiplePNG(evt) {
            const cards = document.querySelectorAll('.ticket-card-stub');
            if (!cards || cards.length === 0) return;

            const btn = evt ? evt.currentTarget : document.querySelector('button[onclick*="downloadMultiplePNG"]');
            const originalText = btn ? btn.innerHTML : '';
            if (btn) {
                btn.innerHTML = '<span>Mengunduh Gambar...</span>';
                btn.disabled = true;
            }

            try {
                for (let i = 0; i < cards.length; i++) {
                    const card = cards[i];
                    const ticketCode = card.getAttribute('data-code') || ('ticket-' + (i + 1));

                    const canvas = await html2canvas(card, {
                        scale: 3, // Ultra HD Retina scale
                        useCORS: true,
                        logging: false,
                        backgroundColor: '#ffffff'
                    });

                    const link = document.createElement('a');
                    link.download = 'E-Ticket-' + ticketCode + '.png';
                    link.href = canvas.toDataURL('image/png', 1.0);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    // Short pause between download triggers so browser processes each file cleanly
                    await new Promise(resolve => setTimeout(resolve, 400));
                }
            } catch (err) {
                console.error('PNG Download Error:', err);
                alert('Gagal mengunduh gambar PNG: ' + err.message);
            } finally {
                if (btn) {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            }
        }
    </script>
</x-app-layout>
