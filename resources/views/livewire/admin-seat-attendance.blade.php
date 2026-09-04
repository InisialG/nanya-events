<div class="max-w-[1600px] mx-auto px-2 sm:px-4 lg:px-6 py-6" wire:poll.2s>
    
    <!-- Top Bar Header Card -->
    <div class="bg-white p-6 rounded-3xl mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6 border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-600 block">Live Monitoring Kehadiran</span>
            </div>
            <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900">Denah & Status Kehadiran Kursi</h1>
            <p class="text-xs text-slate-500 mt-1">
                Pantau kursi yang sudah dipindai (Hadir) vs Belum Hadir secara real-time.
            </p>
        </div>

        <!-- Session Picker & Nav Buttons -->
        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <!-- Select Session Dropdown -->
            <div class="flex items-center gap-2 bg-slate-50 p-2 rounded-2xl border border-slate-200">
                <svg class="w-4 h-4 text-[#F37032] shrink-0 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <select 
                    wire:change="selectSession($event.target.value)" 
                    class="bg-transparent border-none text-xs font-bold text-slate-900 focus:ring-0 cursor-pointer pr-8"
                >
                    @foreach ($sessions as $s)
                        <option value="{{ $s->id }}" @if($s->id == $sessionId) selected @endif>
                            {{ $s->event->title }} — {{ \Carbon\Carbon::parse($s->session_date)->translatedFormat('d M Y') }} ({{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} WIB)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Quick Action Links -->
            <a href="{{ route('scan-ticket.index') }}" class="px-4 py-2.5 rounded-2xl bg-[#F37032] hover:bg-[#e05f24] text-white text-xs font-extrabold shadow-md shadow-[#F37032]/20 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                <span>Scanner QR</span>
            </a>

            @if (Auth::check() && Auth::user()->isSuperAdmin())
                <button 
                    wire:click="resetAllSeatsInSession" 
                    wire:confirm="Apakah Anda yakin ingin mengosongkan SELURUH STATUS KURSI pada sesi ini menjadi Tersedia (Kosong) kembali?"
                    type="button" 
                    class="px-4 py-2.5 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-extrabold border border-rose-200 transition-all flex items-center gap-1.5 cursor-pointer"
                >
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>Reset Semua Kursi</span>
                </button>
            @endif

            <a href="{{ url('/admin') }}" class="px-4 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold border border-slate-200 transition-all">
                Panel Admin
            </a>
        </div>
    </div>

    <!-- Live KPI Summary Stats (5 Cards) -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <!-- 1. Hadir (Dipindai) -->
        <div class="bg-white p-5 rounded-3xl border-2 border-emerald-500/40 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-700">Hadir (Dipindai)</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-sm">✓</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="font-heading font-black text-3xl sm:text-4xl text-emerald-600">{{ $totalAttended }}</span>
                <span class="text-xs font-bold text-emerald-700">/ {{ $totalSold }} Terjual</span>
            </div>
            <div class="mt-3 w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                <div class="bg-emerald-500 h-full transition-all duration-500" style="width: {{ $attendancePercentage }}%"></div>
            </div>
            <span class="text-[10px] text-slate-500 font-bold block mt-1.5 text-right">{{ $attendancePercentage }}% Tingkat Kehadiran</span>
        </div>

        <!-- 2. Belum Hadir (Lunas) -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-blue-600">Belum Hadir (Lunas)</span>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">⏳</span>
            </div>
            <span class="font-heading font-black text-3xl sm:text-4xl text-blue-600 block">{{ $totalPending }}</span>
            <span class="text-[10px] text-slate-400 mt-1 block">Tiket lunas, penonton belum tiba di gate</span>
        </div>

        <!-- 3. Dikunci -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-amber-600">Dikunci</span>
                <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">🔒</span>
            </div>
            <span class="font-heading font-black text-3xl sm:text-4xl text-amber-600 block">{{ $totalLocked }}</span>
            <span class="text-[10px] text-slate-400 mt-1 block">Proses pemesanan (Locked)</span>
        </div>

        <!-- 4. Tersedia (Belum Dipesan) -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Kursi Tersedia</span>
                <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-sm">🛋️</span>
            </div>
            <span class="font-heading font-black text-3xl sm:text-4xl text-slate-700 block">{{ $totalAvailable }}</span>
            <span class="text-[10px] text-slate-400 mt-1 block">Belum dipesan / masih kosong</span>
        </div>

        <!-- 5. Total Kapasitas -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#F37032]">Total Kapasitas</span>
                <span class="w-8 h-8 rounded-xl bg-orange-50 text-[#F37032] flex items-center justify-center font-bold text-sm">🏛️</span>
            </div>
            <span class="font-heading font-black text-3xl sm:text-4xl text-[#F37032] block">{{ $totalCapacity }}</span>
            <span class="text-[10px] text-slate-400 mt-1 block">Total kursi aktif dalam denah venue</span>
        </div>
    </div>

    <!-- Status Legend Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 mb-6 flex flex-wrap items-center justify-between gap-4 text-xs shadow-sm">
        <span class="font-bold text-slate-700 uppercase tracking-wider text-[11px]">Legenda Status Live:</span>
        <div class="flex flex-wrap items-center gap-4">
            <!-- HADIR -->
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded-md bg-emerald-500 text-white flex items-center justify-center font-bold text-[9px] shadow-sm">✓</span>
                <span class="font-extrabold text-emerald-800">HADIR (Dipindai)</span>
            </div>
            <!-- BELUM HADIR -->
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded-md bg-blue-600 shadow-sm"></span>
                <span class="font-extrabold text-blue-800">Belum Hadir (Lunas)</span>
            </div>
            <!-- DIKUNCI -->
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded-md bg-amber-500 shadow-sm"></span>
                <span class="font-bold text-amber-700">Proses Order (Locked)</span>
            </div>
            <!-- TERSEDIA -->
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded-md bg-slate-200 border border-slate-300"></span>
                <span class="font-bold text-slate-600">Tersedia (Kosong)</span>
            </div>
        </div>
    </div>

    <!-- Interactive Grid Seat Map Container (Fit 100% Width) -->
    @if ($activeSession && $activeSession->event->venue)
        @php $venue = $activeSession->event->venue; @endphp
        <div class="bg-white p-3 sm:p-5 rounded-3xl border border-slate-200 shadow-sm overflow-x-auto touch-auto relative">
            
            <!-- Mobile Hint Banner -->
            <div class="sm:hidden text-center mb-3">
                <span class="text-[11px] text-slate-600 bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200 inline-flex items-center gap-1.5 font-medium">
                    👈 Geser denah ke samping untuk melihat seluruh kursi 👉
                </span>
            </div>

            <div class="inline-block min-w-full align-middle">
                <div class="w-max mx-auto flex flex-col items-center gap-2 sm:gap-2.5 p-3 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200">
                    
                    <!-- Official STAGE Box -->
                    <div class="w-full max-w-xs mx-auto mb-6 sm:mb-8 text-center">
                        <div class="py-2.5 px-8 bg-slate-800 rounded-xl border border-slate-700 shadow-md flex items-center justify-center mx-auto w-48">
                            <span class="font-heading font-black text-xs sm:text-sm tracking-[0.25em] uppercase text-white">STAGE</span>
                        </div>
                    </div>

                    <!-- Global Grid Container untuk Lorong Vertikal Lurus -->
                    <div class="grid grid-cols-[1fr_auto_1fr] gap-x-2 sm:gap-x-4 gap-y-1 sm:gap-y-1 w-full min-w-max mt-4">
                        <!-- Headers Zona -->
                        <div class="text-right text-[10px] sm:text-xs font-bold text-slate-400 tracking-widest uppercase mb-2">Zona Kiri</div>
                        <div class="text-center text-[10px] sm:text-xs font-bold text-slate-400 tracking-widest uppercase px-4 sm:px-8 mb-2">Zona Tengah</div>
                        <div class="text-left text-[10px] sm:text-xs font-bold text-slate-400 tracking-widest uppercase mb-2">Zona Kanan</div>

                        @foreach($groupedSeatsByRow as $rowLetter => $zones)
                            <!-- ZONA KIRI -->
                            <div class="flex items-center gap-0.5 sm:gap-1 justify-end border-b border-slate-100 pb-1.5 min-h-[36px]">
                                @if(!empty($zones['L']))
                                    <span class="w-4 sm:w-5 text-[9px] sm:text-[10px] font-bold text-slate-500 text-center uppercase shrink-0 select-none">{{ $rowLetter }}</span>
                                    <div class="flex items-center gap-0.5 sm:gap-1 flex-nowrap justify-end">
                                        @foreach($zones['L'] as $seatAvail)
                                            @php
                                                $seatMaster = $seatAvail->seatMaster;
                                                $ticket = $seatAvail->ticket;

                                                $isAttended = $ticket && $ticket->status === 'used';
                                                $isPending = $ticket && $ticket->status === 'valid';
                                                $isLocked = ($seatAvail->status === 'locked');
                                                $isSold = ($seatAvail->status === 'sold');
                                            @endphp

                                            <button 
                                                type="button"
                                                wire:click="showSeatDetail({{ $seatAvail->id }})"
                                                title="Kursi {{ $seatMaster->seat_code }} @if($isAttended) (HADIR) @elseif($isPending) (Belum Hadir) @elseif($isSold) (Terjual) @elseif($isLocked) (Dikunci) @else (Tersedia) @endif"
                                                class="w-5 h-5 sm:w-5 sm:h-5 md:w-6 md:h-6 shrink-0 rounded text-[8px] sm:text-[9px] font-black flex items-center justify-center transition-all duration-150 cursor-pointer hover:scale-110 hover:shadow-md active:scale-95 shadow-xs relative group
                                                    @if($isAttended) bg-emerald-500 text-white ring-2 ring-emerald-400 shadow-md shadow-emerald-500/20 @elseif($isPending) bg-blue-600 text-white ring-2 ring-blue-400 shadow-md shadow-blue-500/20 @elseif($isLocked) bg-amber-500 text-white @elseif($isSold) bg-slate-700 text-white @else bg-slate-200 text-slate-700 hover:bg-slate-300 border border-slate-300 @endif"
                                            >
                                                @if ($isAttended)
                                                    <span>✓</span>
                                                @else
                                                    {{ (int)$seatMaster->col_num }}
                                                @endif

                                                <!-- Tooltip on Hover -->
                                                <span class="absolute -top-10 left-1/2 -translate-x-1/2 px-2.5 py-1 bg-slate-900 text-white text-[10px] rounded-md font-bold whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-30 border border-slate-700 shadow-xl">
                                                    {{ $seatMaster->seat_code }} • @if($isAttended) HADIR ✅ @elseif($isPending) BELUM HADIR ⏳ @elseif($isSold) TERJUAL 🎟️ @elseif($isLocked) DIKUNCI 🔒 @else TERSEDIA 🛋️ @endif
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- ZONA TENGAH -->
                            <div class="flex items-center gap-0.5 sm:gap-1 justify-center border-b border-slate-100 border-l border-r border-slate-200 px-2 sm:px-4 pb-1.5 min-h-[36px]">
                                @if(!empty($zones['C']))
                                    <span class="w-4 sm:w-5 text-[8px] font-bold text-slate-300 text-center uppercase shrink-0 select-none">{{ $rowLetter }}</span>
                                    <div class="flex items-center gap-0.5 sm:gap-1 flex-nowrap justify-center">
                                        @foreach($zones['C'] as $seatAvail)
                                            @php
                                                $seatMaster = $seatAvail->seatMaster;
                                                $ticket = $seatAvail->ticket;

                                                $isAttended = $ticket && $ticket->status === 'used';
                                                $isPending = $ticket && $ticket->status === 'valid';
                                                $isLocked = ($seatAvail->status === 'locked');
                                                $isSold = ($seatAvail->status === 'sold');
                                            @endphp

                                            <button 
                                                type="button"
                                                wire:click="showSeatDetail({{ $seatAvail->id }})"
                                                title="Kursi {{ $seatMaster->seat_code }} @if($isAttended) (HADIR) @elseif($isPending) (Belum Hadir) @elseif($isSold) (Terjual) @elseif($isLocked) (Dikunci) @else (Tersedia) @endif"
                                                class="w-5 h-5 sm:w-5 sm:h-5 md:w-6 md:h-6 shrink-0 rounded text-[8px] sm:text-[9px] font-black flex items-center justify-center transition-all duration-150 cursor-pointer hover:scale-110 hover:shadow-md active:scale-95 shadow-xs relative group
                                                    @if($isAttended) bg-emerald-500 text-white ring-2 ring-emerald-400 shadow-md shadow-emerald-500/20 @elseif($isPending) bg-blue-600 text-white ring-2 ring-blue-400 shadow-md shadow-blue-500/20 @elseif($isLocked) bg-amber-500 text-white @elseif($isSold) bg-slate-700 text-white @else bg-slate-200 text-slate-700 hover:bg-slate-300 border border-slate-300 @endif"
                                            >
                                                @if ($isAttended)
                                                    <span>✓</span>
                                                @else
                                                    {{ (int)$seatMaster->col_num }}
                                                @endif

                                                <!-- Tooltip on Hover -->
                                                <span class="absolute -top-10 left-1/2 -translate-x-1/2 px-2.5 py-1 bg-slate-900 text-white text-[10px] rounded-md font-bold whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-30 border border-slate-700 shadow-xl">
                                                    {{ $seatMaster->seat_code }} • @if($isAttended) HADIR ✅ @elseif($isPending) BELUM HADIR ⏳ @elseif($isSold) TERJUAL 🎟️ @elseif($isLocked) DIKUNCI 🔒 @else TERSEDIA 🛋️ @endif
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>
                                    <span class="w-4 sm:w-5 text-[8px] font-bold text-slate-300 text-center uppercase shrink-0 select-none">{{ $rowLetter }}</span>
                                @endif
                            </div>

                            <!-- ZONA KANAN -->
                            <div class="flex items-center gap-0.5 sm:gap-1 justify-start border-b border-slate-100 pb-1.5 min-h-[36px]">
                                @if(!empty($zones['R']))
                                    <div class="flex items-center gap-0.5 sm:gap-1 flex-nowrap justify-start">
                                        @foreach($zones['R'] as $seatAvail)
                                            @php
                                                $seatMaster = $seatAvail->seatMaster;
                                                $ticket = $seatAvail->ticket;

                                                $isAttended = $ticket && $ticket->status === 'used';
                                                $isPending = $ticket && $ticket->status === 'valid';
                                                $isLocked = ($seatAvail->status === 'locked');
                                                $isSold = ($seatAvail->status === 'sold');
                                            @endphp

                                            <button 
                                                type="button"
                                                wire:click="showSeatDetail({{ $seatAvail->id }})"
                                                title="Kursi {{ $seatMaster->seat_code }} @if($isAttended) (HADIR) @elseif($isPending) (Belum Hadir) @elseif($isSold) (Terjual) @elseif($isLocked) (Dikunci) @else (Tersedia) @endif"
                                                class="w-5 h-5 sm:w-5 sm:h-5 md:w-6 md:h-6 shrink-0 rounded text-[8px] sm:text-[9px] font-black flex items-center justify-center transition-all duration-150 cursor-pointer hover:scale-110 hover:shadow-md active:scale-95 shadow-xs relative group
                                                    @if($isAttended) bg-emerald-500 text-white ring-2 ring-emerald-400 shadow-md shadow-emerald-500/20 @elseif($isPending) bg-blue-600 text-white ring-2 ring-blue-400 shadow-md shadow-blue-500/20 @elseif($isLocked) bg-amber-500 text-white @elseif($isSold) bg-slate-700 text-white @else bg-slate-200 text-slate-700 hover:bg-slate-300 border border-slate-300 @endif"
                                            >
                                                @if ($isAttended)
                                                    <span>✓</span>
                                                @else
                                                    {{ (int)$seatMaster->col_num }}
                                                @endif

                                                <!-- Tooltip on Hover -->
                                                <span class="absolute -top-10 left-1/2 -translate-x-1/2 px-2.5 py-1 bg-slate-900 text-white text-[10px] rounded-md font-bold whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-30 border border-slate-700 shadow-xl">
                                                    {{ $seatMaster->seat_code }} • @if($isAttended) HADIR ✅ @elseif($isPending) BELUM HADIR ⏳ @elseif($isSold) TERJUAL 🎟️ @elseif($isLocked) DIKUNCI 🔒 @else TERSEDIA 🛋️ @endif
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>
                                    <span class="w-4 sm:w-5 text-[9px] sm:text-[10px] font-bold text-slate-500 text-center uppercase shrink-0 select-none">{{ $rowLetter }}</span>
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
    @endif

    <!-- QUICK DETAIL MODAL / POPUP -->
    @if ($selectedSeatDetails)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm" wire:click.self="closeSeatDetail">
            <div class="bg-white max-w-md w-full rounded-3xl p-6 shadow-2xl border border-slate-200 space-y-5 animate-in fade-in zoom-in duration-200">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-heading font-black text-xl text-white shadow-md"
                            style="background-color: {{ $selectedSeatDetails['category_color'] }}">
                            {{ $selectedSeatDetails['seat_code'] }}
                        </div>
                        <div>
                            <h3 class="font-heading font-extrabold text-lg text-slate-900">Kursi {{ $selectedSeatDetails['seat_code'] }}</h3>
                            <span class="text-xs text-slate-500">{{ $selectedSeatDetails['category_name'] }} • Rp {{ number_format($selectedSeatDetails['price'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <button wire:click="closeSeatDetail" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center font-bold transition-colors">
                        ✕
                    </button>
                </div>

                <!-- Status Badge Banner -->
                <div>
                    @if ($selectedSeatDetails['is_attended'])
                        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold">✓</span>
                                <div>
                                    <span class="font-extrabold text-sm block">HADIR (SUDAH DIPINDAI)</span>
                                    <span class="text-[11px] text-emerald-700">Penonton telah memasuki venue.</span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black bg-emerald-500 text-white uppercase">Valid</span>
                        </div>
                    @elseif ($selectedSeatDetails['is_pending'])
                        <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold">⏳</span>
                                <div>
                                    <span class="font-extrabold text-sm block">BELUM HADIR (TIKET LUNAS)</span>
                                    <span class="text-[11px] text-blue-700">Tiket lunas, belum dipindai di gate.</span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black bg-blue-600 text-white uppercase">Lunas</span>
                        </div>
                    @else
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-slate-700 flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-xl bg-slate-200 text-slate-600 flex items-center justify-center font-bold">🛋️</span>
                            <div>
                                <span class="font-extrabold text-sm block">KURSI TERSEDIA</span>
                                <span class="text-[11px] text-slate-500">Belum dibeli / belum dipesan penonton.</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Attendee & Ticket Info Grid -->
                @if ($selectedSeatDetails['has_ticket'])
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3 text-xs text-slate-700">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                            <span class="text-slate-500 font-bold uppercase text-[10px]">Nama Penonton:</span>
                            <strong class="text-slate-900 font-extrabold">{{ $selectedSeatDetails['user_name'] ?? '-' }}</strong>
                        </div>

                        <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                            <span class="text-slate-500 font-bold uppercase text-[10px]">Email Penonton:</span>
                            <span class="text-slate-800 font-medium">{{ $selectedSeatDetails['user_email'] ?? '-' }}</span>
                        </div>

                        <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                            <span class="text-slate-500 font-bold uppercase text-[10px]">Kode Tiket:</span>
                            <span class="font-mono font-bold text-slate-900">{{ $selectedSeatDetails['ticket_code'] }}</span>
                        </div>

                        @if ($selectedSeatDetails['is_attended'])
                            <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                                <span class="text-slate-500 font-bold uppercase text-[10px]">Waktu Dipindai:</span>
                                <strong class="text-emerald-700 font-bold">{{ $selectedSeatDetails['scanned_at'] }} WIB</strong>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 font-bold uppercase text-[10px]">Petugas Gatekeeper:</span>
                                <span class="text-slate-800 font-bold">{{ $selectedSeatDetails['scanned_by'] ?? 'Petugas Demo' }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                <button 
                    wire:click="closeSeatDetail" 
                    class="w-full py-3 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-all shadow-md"
                >
                    Tutup Detail
                </button>

            </div>
        </div>
    @endif

</div>
