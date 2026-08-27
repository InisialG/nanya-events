<x-app-layout>
    <!-- Catalog Event Cards Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8 flex-grow flex flex-col justify-center">
        
        <!-- Header & Search Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-200">
            <div>
                <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900">Katalog Event</h1>
                <p class="text-xs text-slate-500 mt-1">Pilih acara favorit Anda dan booking kursi langsung dari denah venue interaktif.</p>
            </div>

            {{-- Search Form --}}
            {{-- <form action="{{ url('/events') }}" method="GET" class="w-full md:w-80 lg:w-96 flex gap-2 bg-white p-1.5 rounded-2xl border border-slate-300 shadow-sm">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul event..." class="w-full bg-transparent px-3 py-1.5 text-xs sm:text-sm text-slate-900 placeholder-slate-400 outline-none">
                <button type="submit" class="px-4 py-2 bg-[#F37032] hover:bg-[#e05f24] text-white font-bold text-xs rounded-xl transition-all shadow-md flex items-center gap-1.5 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Cari
                </button>
            </form> --}}
        </div>

        @if($events->isEmpty())
            <div class="bg-white p-12 rounded-3xl text-center border border-slate-200 shadow-sm">
                <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5z"></path></svg>
                <h3 class="text-lg font-bold text-slate-800">Belum Ada Event Aktif</h3>
                <p class="text-xs text-slate-500 mt-1">Silakan periksa kembali beberapa saat lagi.</p>
            </div>
        @else
            <!-- Horizontal Compact Event Cards Grid (2 Columns) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($events as $event)
                    <div class="bg-white rounded-3xl overflow-hidden group border border-slate-200 hover:border-[#F37032] hover:shadow-xl hover:shadow-[#F37032]/10 hover:-translate-y-1 transition-all duration-300 flex flex-col sm:flex-row shadow-sm">
                        
                        <!-- Left: Poster Image (Rasio 3:4) -->
                        <div class="w-full sm:w-44 md:w-48 shrink-0 relative aspect-[3/4] overflow-hidden bg-slate-100 border-b sm:border-b-0 sm:border-r border-slate-200">
                            @if($event->poster_path)
                                <img src="{{ str_starts_with($event->poster_path, 'http') ? $event->poster_path : asset('storage/' . $event->poster_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 flex flex-col items-center justify-center p-6 text-center relative">
                                    <div class="w-12 h-12 rounded-2xl bg-[#F37032]/10 border border-[#F37032]/30 flex items-center justify-center text-[#F37032] mb-2 relative z-10">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5z"></path></svg>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-500 relative z-10">Pentas Seni Showcase</span>
                                </div>
                            @endif

                            <!-- Category Badge -->
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/90 backdrop-blur-md border border-slate-200 text-[#F37032] shadow-sm">
                                    {{ $event->event_category }}
                                </span>
                            </div>
                        </div>

                        <!-- Right: Details Content -->
                        <div class="p-5 flex-grow flex flex-col justify-between space-y-3">
                            <div class="space-y-2.5">
                                <!-- Venue Name -->
                                <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                    <svg class="w-3.5 h-3.5 text-[#F37032] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    <span class="font-medium truncate text-slate-600">{{ $event->venue?->name ?? 'Venue Utama' }}</span>
                                </div>

                                <!-- Title -->
                                <h3 class="font-heading font-extrabold text-lg sm:text-xl text-slate-900 group-hover:text-[#F37032] transition-colors line-clamp-2 leading-snug">
                                    <a href="{{ url('/events/' . $event->slug) }}">{{ $event->title }}</a>
                                </h3>

                                <!-- Date & Time Box -->
                                @php
                                    $firstSession = $event->eventSessions->first();
                                @endphp
                                <div class="p-3 rounded-2xl bg-orange-50/60 border border-orange-100 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-[#F37032]/15 border border-[#F37032]/30 text-[#F37032] flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-slate-500 uppercase tracking-wider block font-semibold">Tanggal & Waktu Kegiatan</span>
                                        @if($firstSession)
                                            <span class="font-heading font-bold text-xs sm:text-sm text-[#F37032] block">
                                                {{ \Carbon\Carbon::parse($firstSession->session_date)->translatedFormat('l, d F Y') }}
                                            </span>
                                            <span class="text-xs text-slate-700 block font-medium">
                                                Pukul {{ \Carbon\Carbon::parse($firstSession->start_time)->format('H:i') }} @if($firstSession->end_time) - {{ \Carbon\Carbon::parse($firstSession->end_time)->format('H:i') }} @endif WIB
                                            </span>
                                        @else
                                            <span class="font-heading font-bold text-xs sm:text-sm text-[#F37032] block">
                                                Sabtu, 26 September 2026
                                            </span>
                                            <span class="text-xs text-slate-700 block font-medium">
                                                Pukul 15:00 - 17:30 WIB
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Status Event Badge (Di bawah Card Tanggal) -->
                                <div class="pt-0.5 flex items-center gap-2">
                                    <span class="text-[11px] text-slate-500 font-medium">Status Event:</span>
                                    @if($event->status === 'coming_soon')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-sky-50 border border-sky-200 text-sky-700 inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                            Coming Soon
                                        </span>
                                    @elseif($event->status === 'registration' || $event->status === 'published')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-50 border border-emerald-200 text-emerald-700 inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Registrasi Dibuka
                                        </span>
                                    @elseif($event->status === 'ongoing')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-50 border border-amber-200 text-amber-700 inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Sedang Berlangsung
                                        </span>
                                    @elseif($event->status === 'finished')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 border border-slate-200 text-slate-500 inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Event Selesai
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- CTA Button Status Connected -->
                            @if($event->status === 'coming_soon')
                                <a href="{{ url('/events/' . $event->slug) }}" class="w-full py-3 px-4 rounded-2xl bg-sky-50 border border-sky-200 text-sky-700 font-bold text-xs text-center hover:bg-sky-100 transition-all flex items-center justify-center gap-2">
                                    <span>Lihat Detail (Coming Soon)</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            @elseif($event->status === 'finished')
                                <div class="w-full py-3 px-4 rounded-2xl bg-slate-100 border border-slate-200 text-slate-400 font-bold text-xs text-center flex items-center justify-center gap-2 cursor-not-allowed">
                                    <span>Event Telah Selesai</span>
                                </div>
                            @else
                                <a href="{{ url('/events/' . $event->slug) }}" class="w-full py-3 px-4 rounded-2xl bg-[#F37032] hover:bg-[#e05f24] text-white font-extrabold text-xs text-center shadow-md shadow-[#F37032]/20 transition-all flex items-center justify-center gap-2 group-hover:scale-[1.02]">
                                    <span>Lihat Event</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

            @if($events->hasPages())
                <div class="mt-4">
                    {{ $events->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
