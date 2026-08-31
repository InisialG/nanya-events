<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10" wire:poll.2s="cleanupExpiredLocks" 
     x-data="{ 
        viewMode: 'interactive', 
        zoomModal: false, 
        zoomScale: 1, 
        selectedSeatIds: @entangle('selectedSeatIds'),
        toggle(id) {
            if (this.selectedSeatIds.includes(id)) {
                this.selectedSeatIds = this.selectedSeatIds.filter(i => i !== id);
            } else {
                this.selectedSeatIds.push(id);
            }
            $wire.toggleSeat(id);
        }
     }">
    
    <!-- Top Bar: Event & Session Info + View Mode Selector -->
    <div class="bg-white p-5 sm:p-6 rounded-3xl mb-6 sm:mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-4 border border-slate-200 shadow-sm">
        <div>
            <a href="{{ url('/events/' . $event->slug) }}" class="text-xs font-semibold text-[#F37032] hover:underline flex items-center gap-1 mb-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Detail Event
            </a>
            <h1 class="font-heading font-extrabold text-xl sm:text-2xl text-slate-900">{{ $event->title }}</h1>
            <p class="text-xs text-slate-500 mt-1">
                {{ $venue->name }} — Sesi: <strong class="text-slate-800">{{ \Carbon\Carbon::parse($eventSession->session_date)->translatedFormat('l, d F Y') }} (Pukul {{ \Carbon\Carbon::parse($eventSession->start_time)->format('H:i') }} WIB)</strong>
            </p>
        </div>

        <!-- Mode View Switcher Tab -->
        <div class="flex items-center gap-1.5 bg-slate-100 p-1.5 rounded-2xl border border-slate-200 shrink-0 self-start lg:self-center">
            <button 
                @click="viewMode = 'interactive'"
                :class="viewMode === 'interactive' ? 'bg-[#F37032] text-white font-bold shadow-md shadow-[#F37032]/20' : 'text-slate-600 hover:text-slate-900 font-medium'"
                class="px-3.5 py-2 rounded-xl text-xs flex items-center gap-1.5 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5z"></path></svg>
                <span>Denah Interaktif</span>
            </button>

            <button 
                @click="viewMode = 'split'"
                :class="viewMode === 'split' ? 'bg-purple-600 text-white font-bold shadow-md shadow-purple-600/20' : 'text-slate-600 hover:text-slate-900 font-medium'"
                class="px-3.5 py-2 rounded-xl text-xs flex items-center gap-1.5 transition-all hidden md:flex">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                <span>Bersandingan (Split)</span>
            </button>

            <button 
                @click="viewMode = 'poster'"
                :class="viewMode === 'poster' ? 'bg-amber-500 text-white font-bold shadow-md shadow-amber-500/20' : 'text-slate-600 hover:text-slate-900 font-medium'"
                class="px-3.5 py-2 rounded-xl text-xs flex items-center gap-1.5 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Poster Official</span>
            </button>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between shadow-sm">
            <span class="font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Legenda Kategori Kursi (Clean Bar) -->
    <div class="mb-6 bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200 flex flex-wrap items-center justify-between gap-4 text-xs shadow-sm">
        <div class="flex items-center gap-4 overflow-x-auto pb-1 sm:pb-0">
            <span class="text-slate-500 font-semibold uppercase tracking-wider text-[10px] border-r border-slate-200 pr-3">Kategori Kursi:</span>
            @foreach($seatCategories as $cat)
                <div class="flex items-center gap-2 shrink-0">
                    <span class="w-3.5 h-3.5 rounded-md shadow-sm border border-slate-300" style="background-color: {{ $cat->color_code }}"></span>
                    <span class="text-slate-900 font-bold">{{ $cat->name }}</span>
                    <span class="text-slate-500 font-medium">(Rp {{ number_format($cat->price, 0, ',', '.') }})</span>
                </div>
            @endforeach
        </div>

        <div class="flex items-center gap-3 shrink-0 text-[11px] border-t sm:border-t-0 sm:border-l border-slate-200 pt-2 sm:pt-0 sm:pl-4">
            <div class="flex items-center gap-1.5">
                <span class="w-3.5 h-3.5 rounded-md bg-emerald-500 ring-2 ring-emerald-500/40"></span>
                <span class="text-slate-700 font-semibold">Terpilih (Hijau)</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-3.5 h-3.5 rounded-md bg-rose-600"></span>
                <span class="text-slate-700 font-semibold">Terjual</span>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT AREA BASED ON VIEW MODE -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pb-24 lg:pb-0">
        
        <!-- LEFT AREA: DENAH / POSTER / SPLIT VIEW -->
        <div :class="viewMode === 'split' ? 'lg:col-span-8' : 'lg:col-span-8'" class="space-y-6">

            <!-- MODE 1: DENAH INTERAKTIF -->
            <div x-show="viewMode === 'interactive' || viewMode === 'split'" class="bg-white p-4 sm:p-8 rounded-3xl overflow-x-auto touch-auto border border-slate-200 shadow-sm relative">
                
                <!-- Stage & Layout Header Bar -->
                <div class="flex items-center justify-between mb-4 border-b border-slate-200 pb-3 text-xs text-slate-600">
                    <span class="font-bold flex items-center gap-1.5 text-slate-800">
                        <svg class="w-4 h-4 text-[#F37032]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5z"></path></svg>
                        Denah Interaktif Presisi
                    </span>
                    <button @click="zoomModal = true; zoomScale = 1" class="text-[#F37032] hover:text-[#e05f24] font-semibold flex items-center gap-1 hover:underline">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                        <span>Perbesar Poster HD</span>
                    </button>
                </div>

                <!-- Mobile Hint Banner -->
                <div class="sm:hidden text-center mb-4">
                    <span class="text-[11px] text-slate-600 bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200 inline-flex items-center gap-1.5 font-medium">
                        👈 Geser denah ke samping untuk melihat seluruh kursi 👉
                    </span>
                </div>

                <!-- Seat Grid Container matching Poster Stage Box -->
                <div class="inline-block min-w-full align-middle">
                    <div class="w-max mx-auto flex flex-col items-center gap-2 sm:gap-2.5 p-3 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200">
                        
                        <!-- Official STAGE Box -->
                        <div class="w-full max-w-xs mx-auto mb-6 sm:mb-8 text-center">
                            <div class="py-2.5 px-8 bg-slate-800 rounded-xl border border-slate-700 shadow-md flex items-center justify-center mx-auto w-48">
                                <span class="font-heading font-black text-xs sm:text-sm tracking-[0.25em] uppercase text-white">STAGE</span>
                            </div>
                        </div>

                        <!-- Global Grid Container untuk Lorong Vertikal Lurus -->
                        <div class="grid grid-cols-[1fr_auto_1fr] gap-x-4 sm:gap-x-8 gap-y-1 sm:gap-y-1.5 w-full min-w-max mt-4">
                            <!-- Headers Zona -->
                            <div class="text-right text-[10px] sm:text-xs font-bold text-slate-400 tracking-widest uppercase mb-2">Zona Kiri</div>
                            <div class="text-center text-[10px] sm:text-xs font-bold text-slate-400 tracking-widest uppercase px-4 sm:px-8 mb-2">Zona Tengah</div>
                            <div class="text-left text-[10px] sm:text-xs font-bold text-slate-400 tracking-widest uppercase mb-2">Zona Kanan</div>

                            @foreach($groupedSeatsByRow as $rowLetter => $zones)
                                <!-- ZONA KIRI -->
                                <div class="flex items-center gap-1 sm:gap-1.5 justify-end border-b border-slate-100 pb-1.5 min-h-[36px]" wire:key="zone-L-{{ $rowLetter }}">
                                    @if(!empty($zones['L']))
                                        <span class="w-5 sm:w-6 text-[11px] sm:text-xs font-bold text-slate-500 text-center uppercase shrink-0 select-none">{{ $rowLetter }}</span>
                                        <div class="flex items-center gap-1 sm:gap-1.5 flex-nowrap justify-end">
                                            @foreach($zones['L'] as $seatAvail)
                                                @php
                                                    $seatMaster = $seatAvail->seatMaster;
                                                    $category = $seatMaster->seatCategory;
                                                    $isSelected = in_array($seatAvail->id, $selectedSeatIds);
                                                    $isLockedOrSold = ($seatAvail->status === 'sold') || ($seatAvail->status === 'locked' && !$isSelected && $seatAvail->locked_until > now());
                                                    $bgColor = $category ? $category->color_code : '#00D4E6';
                                                @endphp
                                                <button 
                                                    wire:key="seat-{{ $seatAvail->id }}"
                                                    type="button" @click="toggle({{ $seatAvail->id }})" @if($isLockedOrSold) disabled @endif
                                                    title="Kursi {{ $seatMaster->seat_code }}"
                                                    :class="selectedSeatIds.includes({{ $seatAvail->id }}) ? 'bg-emerald-500 text-white ring-4 ring-emerald-500/40 scale-110 shadow-lg z-10' : '{{ $isLockedOrSold ? 'bg-rose-600 text-white border border-rose-500/50 cursor-not-allowed opacity-90' : 'text-slate-900 hover:scale-110 hover:shadow-md cursor-pointer' }}'"
                                                    class="w-6 h-6 sm:w-7 sm:h-7 shrink-0 rounded-md text-[9px] sm:text-[10px] font-extrabold flex items-center justify-center transition-all duration-150 touch-manipulation select-none active:scale-95 relative group"
                                                    :style="selectedSeatIds.includes({{ $seatAvail->id }}) ? '' : '{{ !$isLockedOrSold ? "background-color: {$bgColor}" : '' }}'">
                                                    {{ (int)$seatMaster->col_num }}
                                                    <span class="absolute -top-10 left-1/2 -translate-x-1/2 px-2.5 py-1 bg-slate-900 text-white text-[9px] rounded font-medium whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-20 shadow-xl">
                                                        {{ $seatMaster->seat_code }} • Rp {{ number_format($category?->price ?? 0, 0, ',', '.') }}
                                                    </span>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <!-- ZONA TENGAH -->
                                <div class="flex items-center gap-1 sm:gap-1.5 justify-center border-b border-slate-100 border-l border-r border-slate-200 px-2 sm:px-4 pb-1.5 min-h-[36px]" wire:key="zone-C-{{ $rowLetter }}">
                                    @if(!empty($zones['C']))
                                        <span class="w-5 sm:w-6 text-[10px] font-bold text-slate-300 text-center uppercase shrink-0 select-none">{{ $rowLetter }}</span>
                                        <div class="flex items-center gap-1 sm:gap-1.5 flex-nowrap justify-center">
                                            @foreach($zones['C'] as $seatAvail)
                                                @php
                                                    $seatMaster = $seatAvail->seatMaster;
                                                    $category = $seatMaster->seatCategory;
                                                    $isSelected = in_array($seatAvail->id, $selectedSeatIds);
                                                    $isLockedOrSold = ($seatAvail->status === 'sold') || ($seatAvail->status === 'locked' && !$isSelected && $seatAvail->locked_until > now());
                                                    $bgColor = $category ? $category->color_code : '#00D4E6';
                                                @endphp
                                                <button 
                                                    wire:key="seat-{{ $seatAvail->id }}"
                                                    type="button" @click="toggle({{ $seatAvail->id }})" @if($isLockedOrSold) disabled @endif
                                                    title="Kursi {{ $seatMaster->seat_code }}"
                                                    :class="selectedSeatIds.includes({{ $seatAvail->id }}) ? 'bg-emerald-500 text-white ring-4 ring-emerald-500/40 scale-110 shadow-lg z-10' : '{{ $isLockedOrSold ? 'bg-rose-600 text-white border border-rose-500/50 cursor-not-allowed opacity-90' : 'text-slate-900 hover:scale-110 hover:shadow-md cursor-pointer' }}'"
                                                    class="w-6 h-6 sm:w-7 sm:h-7 shrink-0 rounded-md text-[9px] sm:text-[10px] font-extrabold flex items-center justify-center transition-all duration-150 touch-manipulation select-none active:scale-95 relative group"
                                                    :style="selectedSeatIds.includes({{ $seatAvail->id }}) ? '' : '{{ !$isLockedOrSold ? "background-color: {$bgColor}" : '' }}'">
                                                    {{ (int)$seatMaster->col_num }}
                                                    <span class="absolute -top-10 left-1/2 -translate-x-1/2 px-2.5 py-1 bg-slate-900 text-white text-[9px] rounded font-medium whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-20 shadow-xl">
                                                        {{ $seatMaster->seat_code }} • Rp {{ number_format($category?->price ?? 0, 0, ',', '.') }}
                                                    </span>
                                                </button>
                                            @endforeach
                                        </div>
                                        <span class="w-5 sm:w-6 text-[10px] font-bold text-slate-300 text-center uppercase shrink-0 select-none">{{ $rowLetter }}</span>
                                    @endif
                                </div>

                                <!-- ZONA KANAN -->
                                <div class="flex items-center gap-1 sm:gap-1.5 justify-start border-b border-slate-100 pb-1.5 min-h-[36px]" wire:key="zone-R-{{ $rowLetter }}">
                                    @if(!empty($zones['R']))
                                        <div class="flex items-center gap-1 sm:gap-1.5 flex-nowrap justify-start">
                                            @foreach($zones['R'] as $seatAvail)
                                                @php
                                                    $seatMaster = $seatAvail->seatMaster;
                                                    $category = $seatMaster->seatCategory;
                                                    $isSelected = in_array($seatAvail->id, $selectedSeatIds);
                                                    $isLockedOrSold = ($seatAvail->status === 'sold') || ($seatAvail->status === 'locked' && !$isSelected && $seatAvail->locked_until > now());
                                                    $bgColor = $category ? $category->color_code : '#00D4E6';
                                                @endphp
                                                <button 
                                                    wire:key="seat-{{ $seatAvail->id }}"
                                                    type="button" @click="toggle({{ $seatAvail->id }})" @if($isLockedOrSold) disabled @endif
                                                    title="Kursi {{ $seatMaster->seat_code }}"
                                                    :class="selectedSeatIds.includes({{ $seatAvail->id }}) ? 'bg-emerald-500 text-white ring-4 ring-emerald-500/40 scale-110 shadow-lg z-10' : '{{ $isLockedOrSold ? 'bg-rose-600 text-white border border-rose-500/50 cursor-not-allowed opacity-90' : 'text-slate-900 hover:scale-110 hover:shadow-md cursor-pointer' }}'"
                                                    class="w-6 h-6 sm:w-7 sm:h-7 shrink-0 rounded-md text-[9px] sm:text-[10px] font-extrabold flex items-center justify-center transition-all duration-150 touch-manipulation select-none active:scale-95 relative group"
                                                    :style="selectedSeatIds.includes({{ $seatAvail->id }}) ? '' : '{{ !$isLockedOrSold ? "background-color: {$bgColor}" : '' }}'">
                                                    {{ (int)$seatMaster->col_num }}
                                                    <span class="absolute -top-10 left-1/2 -translate-x-1/2 px-2.5 py-1 bg-slate-900 text-white text-[9px] rounded font-medium whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-20 shadow-xl">
                                                        {{ $seatMaster->seat_code }} • Rp {{ number_format($category?->price ?? 0, 0, ',', '.') }}
                                                    </span>
                                                </button>
                                            @endforeach
                                        </div>
                                        <span class="w-5 sm:w-6 text-[11px] sm:text-xs font-bold text-slate-500 text-center uppercase shrink-0 select-none">{{ $rowLetter }}</span>
                                    @endif
                                </div>

                                @if($rowLetter === 'H')
                                    <!-- Walkway / Gang Tengah -->
                                    <div class="col-span-3 h-8 sm:h-12 w-full flex items-center justify-center my-1 sm:my-2 bg-slate-100/50 rounded-lg">
                                        <span class="text-[9px] sm:text-[10px] text-slate-400 tracking-[0.5em] uppercase font-bold">Jalan Lintas / Walkway</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <!-- MODE 2 & SPLIT MODE: POSTER IMAGE CARD -->
            <div x-show="viewMode === 'poster' || viewMode === 'split'" class="bg-white p-4 sm:p-6 rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between mb-4 border-b border-slate-200 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#F37032]"></span>
                        <h3 class="font-bold text-sm text-slate-900">Poster Acuan Denah Kursi Official</h3>
                    </div>
                    <button @click="zoomModal = true; zoomScale = 1" class="px-3 py-1.5 rounded-xl bg-orange-50 hover:bg-orange-100 text-[#F37032] text-xs font-semibold flex items-center gap-1.5 border border-orange-200 transition-all">
                        <svg class="w-4 h-4 text-[#F37032]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>Buka Fullscreen / Zoom</span>
                    </button>
                </div>

                <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50 p-2 text-center cursor-pointer" @click="zoomModal = true; zoomScale = 1">
                    <img src="{{ asset('img/posisi.jpeg') }}" alt="Poster Denah Official {{ $event->title }}" class="max-h-[600px] w-auto mx-auto rounded-xl object-contain shadow-md hover:scale-[1.01] transition-transform">
                </div>
            </div>

        </div>

        <!-- RIGHT AREA: SUMMARY & CHECKOUT CARD -->
        <div class="lg:col-span-4">
            <div class="bg-white p-6 sm:p-8 rounded-3xl sticky top-28 border border-slate-200 shadow-sm flex flex-col justify-between h-auto">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-heading font-bold text-xl text-slate-900">Ringkasan Kursi Terpilih</h3>
                        @if (!empty($selectedSeatIds))
                            <button wire:click="clearAllSelectedSeats" type="button" class="text-xs text-rose-600 hover:text-rose-800 font-bold hover:underline cursor-pointer">
                                Reset Pilihan
                            </button>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mb-6">Minimal pemesanan adalah 2 kursi per transaksi.</p>

                    @if (empty($selectedSeatIds))
                        <div class="p-8 text-center border-2 border-dashed border-slate-200 rounded-2xl mb-6 bg-slate-50">
                            <svg class="w-12 h-12 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5z"></path></svg>
                            <span class="text-xs text-slate-700 font-semibold block">Belum ada kursi yang dipilih.</span>
                            <span class="text-[11px] text-slate-500">Klik / sentuh kursi berwarna di denah sebelah kiri.</span>
                        </div>
                    @else
                        <div class="space-y-3 mb-6 max-h-60 overflow-y-auto pr-1">
                            @foreach ($selectedSeatIds as $seatId)
                                @php
                                    $item = $seatAvailabilities->firstWhere('id', $seatId);
                                    $master = $item?->seatMaster;
                                    $cat = $master?->seatCategory;
                                @endphp
                                @if ($master)
                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="w-3.5 h-3.5 rounded-md shadow-sm border border-emerald-400 bg-emerald-500 shrink-0"></span>
                                            <div>
                                                <span class="font-bold text-sm text-slate-900 block">Kursi {{ $master->seat_code }}</span>
                                                <span class="text-[10px] text-slate-500">{{ $cat?->name ?? 'Reguler' }}</span>
                                            </div>
                                        </div>
                                        <span class="font-bold text-sm text-[#F37032]">Rp {{ number_format($cat?->price ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <!-- Timer Countdown Badge -->
                    @if (!empty($selectedSeatIds))
                        <div class="p-3.5 rounded-xl bg-orange-50 border border-orange-200 text-[#F37032] text-xs flex items-center gap-2 mb-6 font-medium">
                            <svg class="w-4 h-4 text-[#F37032] animate-spin shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Kursi terkunci sementara (<strong>10 Menit</strong>) saat Anda memilih.</span>
                        </div>
                    @endif
                </div>

                <!-- Total & Checkout Button (Desktop) -->
                <div class="pt-6 border-t border-slate-200 hidden lg:block space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500 font-semibold flex items-center gap-1.5">
                            <span>Total Pembayaran</span>
                            <svg wire:loading wire:target="toggleSeat, clearAllSelectedSeats" class="w-3.5 h-3.5 text-[#F37032] animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </span>
                        <span class="font-heading font-extrabold text-2xl text-[#F37032]">
                            Rp {{ number_format($totalPrice, 0, ',', '.') }}
                        </span>
                    </div>

                    <button 
                        wire:click="proceedToCheckout"
                        @if (count($selectedSeatIds) < 2) disabled @endif
                        class="w-full py-4 px-6 rounded-2xl bg-[#F37032] hover:bg-[#e05f24] text-white font-extrabold text-sm text-center shadow-md shadow-[#F37032]/20 disabled:opacity-40 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2">
                        <span>{{ count($selectedSeatIds) < 2 ? 'Pilih Min. 2 Kursi' : 'Lanjut ke Pembayaran' }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>

                    <!-- SPECIAL ADMIN ONLY: VVIP COMPLIMENTARY RESERVATION BUTTON & RESET ALL SEATS -->
                    @if (Auth::check() && Auth::user()->isAdmin())
                        <div class="pt-4 border-t border-purple-200 bg-purple-50/80 p-4 rounded-2xl border space-y-3">
                            <span class="text-[11px] font-extrabold uppercase tracking-wider text-purple-900 block">Akses Admin: Kontrol Kursi</span>
                            <p class="text-[11px] text-purple-800 leading-snug">Terbitkan E-Tiket VVIP atau kosongkan seluruh status kursi sesi ini.</p>
                            
                            <button 
                                wire:click="reserveVvipSeats"
                                @if (empty($selectedSeatIds)) disabled @endif
                                class="w-full py-3 px-4 rounded-xl bg-purple-600 hover:bg-purple-700 disabled:opacity-40 disabled:cursor-not-allowed text-white font-extrabold text-xs text-center shadow-md shadow-purple-600/20 transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <span>Terbitkan Tiket VVIP (Bebas Bayar)</span>
                                <svg class="w-4 h-4 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            </button>

                            @if (Auth::user()->isSuperAdmin())
                            <button 
                                wire:click="resetAllSeatsInSession"
                                wire:confirm="Apakah Anda yakin ingin MENGOSONGKAN SELURUH KURSI pada sesi ini menjadi Tersedia kembali?"
                                type="button"
                                class="w-full py-2.5 px-4 rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 font-extrabold text-xs text-center transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <span>Reset Semua Kursi (Buka Semua)</span>
                            </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Sticky Floating Action Bar at Bottom (SPECIAL MOBILE UX) -->
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/95 backdrop-blur-2xl border-t border-slate-200 shadow-2xl lg:hidden z-40">
        <div class="max-w-md mx-auto flex items-center justify-between gap-4">
            <div>
                <span class="text-[10px] text-slate-500 uppercase tracking-wider block font-bold">
                    {{ count($selectedSeatIds) }} Kursi Terpilih
                </span>
                <span class="font-heading font-extrabold text-xl text-[#F37032] block leading-tight">
                    Rp {{ number_format($totalPrice, 0, ',', '.') }}
                </span>
            </div>

            <button 
                wire:click="proceedToCheckout"
                @if (count($selectedSeatIds) < 2) disabled @endif
                class="px-6 py-3.5 rounded-2xl bg-[#F37032] text-white font-extrabold text-sm shadow-md shadow-[#F37032]/20 disabled:opacity-40 disabled:cursor-not-allowed transition-all flex items-center gap-2 shrink-0 touch-manipulation">
                <span>{{ count($selectedSeatIds) < 2 ? 'Pilih Min. 2 Kursi' : 'Lanjut Pembayaran' }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>
    </div>

    <!-- MODAL ZOOM POSTER OVERLAY (100% RESPONSIVE & INTERACTIVE ZOOM) -->
    <div 
        x-show="zoomModal" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-6 bg-slate-950/95 backdrop-blur-xl overflow-y-auto"
        @keydown.escape.window="zoomModal = false"
    >
        <div class="relative max-w-5xl w-full h-[90vh] glass-panel border border-purple-500/40 rounded-3xl p-4 sm:p-6 shadow-2xl flex flex-col justify-between overflow-hidden my-auto" @click.away="zoomModal = false">
            
            <!-- Sticky Modal Header + Zoom Controls Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-800 gap-3 shrink-0">
                <div>
                    <h3 class="font-heading font-extrabold text-base sm:text-lg text-white flex items-center gap-2">
                        <span>Poster Acuan Denah Kursi</span>
                        <span class="text-xs font-bold text-purple-400 bg-purple-500/20 px-2 py-0.5 rounded-md border border-purple-500/30">HD View</span>
                    </h3>
                    <p class="text-[11px] text-slate-400">Klik / sentuh gambar atau gunakan tombol di samping untuk mengatur zoom.</p>
                </div>
                
                <div class="flex items-center gap-2 shrink-0">
                    <!-- Zoom Controls -->
                    <div class="flex items-center gap-1 bg-slate-900 p-1 rounded-xl border border-slate-800 text-xs">
                        <button @click="zoomScale = Math.max(0.75, zoomScale - 0.25)" title="Zoom Out" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-white font-bold flex items-center justify-center">
                            -
                        </button>
                        <span class="px-2 text-slate-300 font-mono font-bold text-[11px]" x-text="Math.round(zoomScale * 100) + '%'">100%</span>
                        <button @click="zoomScale = Math.min(2.5, zoomScale + 0.25)" title="Zoom In" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-white font-bold flex items-center justify-center">
                            +
                        </button>
                        <button @click="zoomScale = 1" title="Reset 100%" class="px-2.5 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-[10px] flex items-center justify-center">
                            Reset
                        </button>
                    </div>

                    <!-- Close Button -->
                    <button @click="zoomModal = false" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Fully Responsive Zoomable Content Container -->
            <div class="flex-grow overflow-auto rounded-2xl border border-slate-800 bg-slate-950/90 flex items-center justify-center p-2 sm:p-4 relative touch-pan-x touch-pan-y my-3">
                <img 
                    src="{{ asset('img/posisi.jpeg') }}" 
                    alt="Poster Denah Kursi {{ $event->title }}" 
                    :style="'transform: scale(' + zoomScale + ')'"
                    @click="zoomScale = (zoomScale === 1 ? 1.5 : 1)"
                    class="max-w-full max-h-full w-auto h-auto object-contain rounded-xl shadow-2xl transition-transform duration-200 cursor-zoom-in origin-center select-none"
                >
            </div>

            <!-- Sticky Modal Footer -->
            <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-xs text-slate-400 shrink-0">
                <span class="text-[11px] text-slate-400 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"></path></svg>
                    <span>Klik gambar untuk toggle zoom 150%.</span>
                </span>
                <button @click="zoomModal = false" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs transition-all">
                    Tutup (Esc)
                </button>
            </div>

        </div>
    </div>

</div>
