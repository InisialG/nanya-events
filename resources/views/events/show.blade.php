<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12" x-data="{ selectedSessionId: {{ $event->eventSessions->first()?->id ?? 0 }} }">
        
        <!-- Breadcrumb Navigation -->
        <div class="flex items-center gap-2 text-xs text-slate-500 mb-6">
            <a href="{{ url('/') }}" class="hover:text-[#F37032] transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Katalog Event
            </a>
            <span>/</span>
            <span class="text-[#F37032] font-semibold bg-orange-50 px-2.5 py-0.5 rounded-md border border-orange-200">{{ $event->event_category }}</span>
            <span>/</span>
            <span class="text-slate-800 font-medium truncate">{{ $event->title }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            
            <!-- Left Column: Poster & Event Brand Identity Card -->
            <div class="lg:col-span-5">
                <div class="bg-white p-4 sm:p-5 rounded-3xl sticky top-28 border border-slate-200 shadow-lg relative overflow-hidden group">
                    
                    <!-- Poster Frame -->
                    <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-slate-100 shadow-md relative border border-slate-200">
                        @if($event->poster_path)
                            <img src="{{ str_starts_with($event->poster_path, 'http') ? $event->poster_path : asset('storage/' . $event->poster_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-orange-50 via-slate-100 to-slate-200 flex flex-col items-center justify-center p-8 text-center">
                                <div class="w-20 h-20 rounded-2xl bg-[#F37032]/10 border border-[#F37032]/30 flex items-center justify-center text-[#F37032] mb-4 shadow-md">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5z"></path></svg>
                                </div>
                                <span class="font-heading font-extrabold text-slate-900 text-xl">{{ $event->title }}</span>
                            </div>
                        @endif

                        <div class="absolute top-4 left-4">
                            <span class="px-4 py-1.5 rounded-full text-xs font-extrabold bg-white/90 backdrop-blur-md border border-slate-200 text-[#F37032] shadow-md">
                                {{ $event->event_category }}
                            </span>
                        </div>
                    </div>

                    <!-- Organizer Brand Footer Card -->
                    <a href="https://new.nanyang.sch.id/" target="_blank" rel="noopener noreferrer" class="mt-4 p-3.5 rounded-2xl bg-slate-50 hover:bg-slate-100 transition-colors border border-slate-200 flex items-center justify-between gap-3 cursor-pointer group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white p-1 shrink-0 shadow-sm border border-slate-200 flex items-center justify-center overflow-hidden">
                                <img src="{{ asset('img/LogoNanyangIcon.png') }}" alt="Logo Penyelenggara" class="w-full h-full object-contain">
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-500 uppercase tracking-wider block font-bold">Penyelenggara Resmi</span>
                                <span class="text-xs font-bold text-slate-900 flex items-center gap-1 group-hover:text-nanyang-600 transition-colors">
                                    Nanyang Zhi Hui School
                                    <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </span>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>

                </div>
            </div>

            <!-- Right Column: Event Details & Schedule Selector -->
            <div class="lg:col-span-7 flex flex-col justify-between">
                <div>
                    <!-- Title -->
                    <h1 class="font-heading font-black text-3xl sm:text-4xl text-slate-900 mb-4 leading-tight">
                        {{ $event->title }}
                    </h1>

                    <!-- Key Metadata Badges -->
                    <div class="flex flex-wrap items-center gap-3 mb-8">
                        <div class="flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-xs text-slate-700 shadow-sm">
                            <div class="w-6 h-6 rounded-lg bg-[#F37032]/10 border border-[#F37032]/30 flex items-center justify-center text-[#F37032] shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <span class="font-semibold text-slate-800">{{ $event->venue?->name ?? 'Venue Utama' }}</span>
                        </div>

                        <div class="flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-xs text-slate-700 shadow-sm">
                            <div class="w-6 h-6 rounded-lg bg-purple-50 border border-purple-200 flex items-center justify-center text-purple-600 shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span>Batas Verifikasi: <strong class="text-purple-700">{{ $event->payment_verification_timeout_hours }} Jam</strong></span>
                        </div>

                        <!-- Status Event Badge -->
                        @if($event->status === 'coming_soon')
                            <div class="flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-sky-50 border border-sky-200 text-xs text-sky-800 shadow-sm font-bold">
                                <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                                <span>Status: Coming Soon</span>
                            </div>
                        @elseif($event->status === 'registration' || $event->status === 'published')
                            <div class="flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 shadow-sm font-bold">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Status: Registrasi Dibuka</span>
                            </div>
                        @elseif($event->status === 'ongoing')
                            <div class="flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-800 shadow-sm font-bold">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                <span>Status: Sedang Berlangsung</span>
                            </div>
                        @elseif($event->status === 'finished')
                            <div class="flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-100 border border-slate-200 text-xs text-slate-600 shadow-sm font-bold">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                <span>Status: Event Selesai</span>
                            </div>
                        @endif
                    </div>

                    <!-- Price Range Card with Category Badges (Sleek & Compact) -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200 shadow-sm mb-8">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider block mb-0.5">Rentang Harga Tiket</span>
                                <div class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2">
                                    <span class="text-[#F37032]">Rp {{ number_format($event->min_price, 0, ',', '.') }}</span>
                                    <span class="text-slate-400 font-normal text-xs">s/d</span>
                                    <span class="text-[#F37032]">Rp {{ number_format($event->max_price, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Category Pricing Badges -->
                            <div class="flex flex-wrap items-center gap-2">
                                @if($event->venue && $event->venue->seatCategories)
                                    @foreach($event->venue->seatCategories->sortBy('price') as $category)
                                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold border bg-slate-50 shadow-sm" style="color: {{ $category->color_code }}; border-color: {{ $category->color_code }}60;">
                                            {{ strtoupper($category->name) }} Rp {{ number_format($category->price / 1000, 0, ',', '.') }}k
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Section: Pilih Sesi Pertunjukan (Dikommentari / Dihilangkan) --}}
                    {{-- <div class="mb-8">
                        <h3 class="font-heading font-bold text-sm text-slate-800 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#F37032]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Pilih Jadwal Sesi Pertunjukan
                        </h3>

                        @if($event->eventSessions->isEmpty())
                            <div class="p-4 rounded-xl bg-slate-100 border border-slate-200 text-xs text-slate-500">
                                Belum ada jadwal sesi pertunjukan yang tersedia untuk event ini.
                            </div>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($event->eventSessions as $session)
                                    <div @click="selectedSessionId = {{ $session->id }}" 
                                         :class="selectedSessionId === {{ $session->id }} ? 'border-[#F37032] bg-orange-50/60 shadow-md ring-1 ring-[#F37032]/40' : 'border-slate-200 bg-white hover:border-slate-300'"
                                         class="p-4 rounded-xl border cursor-pointer transition-all flex items-center justify-between group">
                                        <div>
                                            <span class="block text-xs font-bold text-slate-900 mb-0.5 group-hover:text-[#F37032] transition-colors">
                                                {{ \Carbon\Carbon::parse($session->session_date)->translatedFormat('l, d F Y') }}
                                            </span>
                                            <span class="text-[11px] text-[#F37032] font-semibold flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Pukul {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} WIB
                                            </span>
                                        </div>

                                        <div :class="selectedSessionId === {{ $session->id }} ? 'bg-[#F37032] text-white scale-105' : 'border border-slate-300 text-transparent'" class="w-5 h-5 rounded-full flex items-center justify-center transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div> --}}

                    <!-- CTA Booking Button (Refined & Connected to Event Status) -->
                    @php
                        $isAdmin = Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin());
                        $isDisabled = in_array($event->status, ['coming_soon', 'ongoing', 'finished', 'draft']);
                    @endphp

                    @if($isDisabled && !$isAdmin)
                        @if($event->status === 'coming_soon')
                            <div class="w-full py-4 px-6 rounded-2xl bg-sky-50 border border-sky-200 text-sky-800 font-bold text-sm text-center flex items-center justify-center gap-2 shadow-sm">
                                <svg class="w-5 h-5 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Pendaftaran / Registrasi Kursi Belum Dibuka (Coming Soon)</span>
                            </div>
                        @elseif($event->status === 'ongoing')
                            <div class="w-full py-4 px-6 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 font-bold text-sm text-center flex items-center justify-center gap-2 shadow-sm cursor-not-allowed">
                                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Pendaftaran Ditutup (Pertunjukan Sedang Berlangsung)</span>
                            </div>
                        @elseif($event->status === 'finished')
                            <div class="w-full py-4 px-6 rounded-2xl bg-slate-100 border border-slate-200 text-slate-500 font-bold text-sm text-center flex items-center justify-center gap-2 shadow-sm cursor-not-allowed">
                                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Pertunjukan / Event Telah Selesai</span>
                            </div>
                        @else
                            <div class="w-full py-4 px-6 rounded-2xl bg-slate-100 border border-slate-200 text-slate-500 font-bold text-sm text-center flex items-center justify-center gap-2 shadow-sm cursor-not-allowed">
                                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Event Tidak Tersedia</span>
                            </div>
                        @endif
                    @else
                        @auth
                            <template x-if="selectedSessionId > 0">
                                <a :href="'/events/{{ $event->slug }}/sessions/' + selectedSessionId + '/seats'" 
                                   class="w-full py-3.5 px-6 rounded-xl bg-[#F37032] hover:bg-[#e05f24] text-white font-bold text-sm text-center shadow-md shadow-[#F37032]/20 hover:shadow-lg transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5z"></path></svg>
                                    <span>
                                        Pilih Kursi dari Denah Venue
                                        @if($isDisabled && $isAdmin)
                                            <span class="text-[10px] ml-1 bg-white/25 px-2 py-0.5 rounded-full">(Akses Admin)</span>
                                        @endif
                                    </span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </template>
                        @else
                            <a href="{{ route('login') }}" class="w-full py-3.5 px-6 rounded-xl bg-[#F37032] hover:bg-[#e05f24] text-white font-bold text-sm text-center shadow-md shadow-[#F37032]/20 transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                <span>Masuk / Daftar untuk Memilih Kursi</span>
                            </a>
                        @endauth
                    @endif
                </div>
            </div>

        </div>

        <!-- SECTION DESKRIPSI EVENT & KETENTUAN -->
        <div class="mt-14 pt-10 border-t border-slate-200">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-2xl bg-orange-50 border border-orange-200 flex items-center justify-center text-[#F37032] shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h2 class="font-heading font-extrabold text-2xl text-slate-900">Deskripsi Event & Ketentuan Pertunjukan</h2>
                    <p class="text-xs text-slate-500">Informasi lengkap seputar pertunjukan seni dan aturan tata tertib venue.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left: Deskripsi & Overview Card -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- About Show Card -->
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden">
                        <div class="flex items-center gap-2 text-xs font-bold text-[#F37032] uppercase tracking-wider mb-3">
                            <span class="w-2 h-2 rounded-full bg-[#F37032]"></span>
                            <span>Tentang Acara</span>
                        </div>

                        <blockquote class="text-base sm:text-lg text-slate-800 font-serif italic leading-relaxed mb-4 border-l-4 border-[#F37032] pl-4 py-1">
                            "Celebrating a cherished tradition and honoring the rich cultural tapestry of the Mid-Autumn Festival through music, dance, and verse."
                        </blockquote>

                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Pentas seni pertunjukan spektakuler persembahan Nanyang Zhi Hui Modern Indonesian School yang menggabungkan harmoni tari tradisional, musik teater, dan seni vokal bernuansa budaya luhur.
                        </p>
                    </div>

                    <!-- Penyelenggara Profile Card -->
                    <!-- Penyelenggara Profile Card -->
                    <a href="https://new.nanyang.sch.id/" target="_blank" rel="noopener noreferrer" class="bg-white hover:bg-slate-50 transition-colors cursor-pointer p-6 rounded-3xl border border-slate-200 flex items-start gap-4 shadow-sm group relative block">
                        <div class="w-14 h-14 rounded-2xl bg-white p-1 shrink-0 shadow-sm border border-slate-200 flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('img/LogoNanyangIcon.png') }}" alt="Logo Nanyang Zhi Hui" class="w-full h-full object-contain">
                        </div>
                        <div class="pr-6">
                            <span class="text-[10px] text-slate-500 uppercase tracking-wider block font-bold">Penyelenggara Acara</span>
                            <h4 class="font-heading font-extrabold text-base text-slate-900 group-hover:text-nanyang-600 transition-colors flex items-center gap-1.5 mt-0.5">
                                <span>Nanyang Zhi Hui Modern Indonesian School</span>
                                <svg class="w-4 h-4 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </h4>
                            <p class="text-xs text-slate-500 mt-1">Institusi pendidikan berkualitas terkemuka yang aktif menyelenggarakan kegiatan seni dan kebudayaan berkelas dunia.</p>
                        </div>
                        <div class="absolute top-6 right-6">
                            <svg class="w-5 h-5 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </div>
                    </a>
                </div>

                <!-- Right: Syarat & Ketentuan Grid Cards -->
                <div class="lg:col-span-5 space-y-4">
                    <h3 class="font-heading font-bold text-base text-slate-900 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#F37032]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Syarat & Ketentuan Masuk Venue
                    </h3>

                    <!-- Rule 1: Waktu Kehadiran -->
                    <div class="p-4 rounded-2xl bg-white border border-slate-200 flex items-start gap-3.5 shadow-sm">
                        <div class="w-9 h-9 rounded-xl bg-orange-50 border border-orange-200 flex items-center justify-center text-[#F37032] shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h5 class="font-bold text-xs text-slate-900 mb-0.5">Waktu Kehadiran</h5>
                            <p class="text-xs text-slate-600">Penonton wajib hadir minimal <strong>30 menit</strong> sebelum sesi pertunjukan dimulai untuk proses pemeriksaan gatepass.</p>
                        </div>
                    </div>

                    <!-- Rule 2: Validasi E-Tiket -->
                    <div class="p-4 rounded-2xl bg-white border border-slate-200 flex items-start gap-3.5 shadow-sm">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <div>
                            <h5 class="font-bold text-xs text-slate-900 mb-0.5">Pemeriksaan QR Code E-Tiket</h5>
                            <p class="text-xs text-slate-600">E-tiket ber-QR code sah wajib ditunjukkan melalui smartphone kepada petugas scanner di pintu masuk venue.</p>
                        </div>
                    </div>

                    <!-- Rule 3: Larangan Makanan/Minuman -->
                    <div class="p-4 rounded-2xl bg-white border border-slate-200 flex items-start gap-3.5 shadow-sm">
                        <div class="w-9 h-9 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        </div>
                        <div>
                            <h5 class="font-bold text-xs text-slate-900 mb-0.5">Tata Tertib F&B</h5>
                            <p class="text-xs text-slate-600">Dilarang membawa makanan dan minuman dari luar ke dalam area Auditorium pertunjukan demi menjaga kebersihan.</p>
                        </div>
                    </div>

                    <!-- Rule 4: Tiket Non-Refundable -->
                    <div class="p-4 rounded-2xl bg-white border border-slate-200 flex items-start gap-3.5 shadow-sm">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center text-blue-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h5 class="font-bold text-xs text-slate-900 mb-0.5">Ketentuan Tiket</h5>
                            <p class="text-xs text-slate-600">1 E-Tiket berlaku untuk 1 orang penonton. Seluruh tiket yang telah dipesan bersifat final dan non-refundable.</p>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</x-app-layout>
