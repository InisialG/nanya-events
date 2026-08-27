<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="font-heading font-extrabold text-3xl text-slate-900 mb-2">Ringkasan Pemesanan Tiket</h1>
        <p class="text-xs text-slate-500 mb-8">Periksa rincian kursi terpilih dan pilih rekening bank tujuan transfer.</p>

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left: Event Info & Selected Seats Breakdown -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <!-- Event Info Box -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#F37032] block mb-1">Event Pentas Seni</span>
                        <h2 class="font-heading font-bold text-xl text-slate-900 mb-2">{{ $event->title }}</h2>
                        <div class="space-y-1 text-xs text-slate-600">
                            <p><strong>Lokasi / Venue:</strong> {{ $event->venue->name }}</p>
                            <p><strong>Sesi Pertunjukan:</strong> {{ \Carbon\Carbon::parse($session->session_date)->translatedFormat('l, d F Y') }} (Jam {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} WIB)</p>
                        </div>
                    </div>

                    <!-- Selected Seats Breakdown -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                        <h3 class="font-heading font-bold text-base text-slate-900 mb-4">Rincian Kursi Terpilih ({{ count($seats) }} Kursi)</h3>
                        <div class="space-y-3">
                            @foreach ($seats as $seat)
                                @php
                                    $master = $seat->seatMaster;
                                    $cat = $master->seatCategory;
                                @endphp
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="w-3.5 h-3.5 rounded-md shadow-sm border border-slate-300" style="background-color: {{ $cat?->color_code ?? '#00D4E6' }}"></span>
                                        <div>
                                            <span class="font-bold text-sm text-slate-900 block">Kursi {{ $master->seat_code }}</span>
                                            <span class="text-[10px] text-slate-500">{{ $cat?->name ?? 'Reguler' }}</span>
                                        </div>
                                    </div>
                                    <span class="font-bold text-sm text-[#F37032]">Rp {{ number_format($cat?->price ?? 0, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <!-- Right: Select Bank & Price Breakdown -->
                <div class="lg:col-span-5 space-y-6">
                    
                    <!-- Select Bank Account Card -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                        <h3 class="font-heading font-bold text-base text-slate-900 mb-1">Pilih Rekening Bank Tujuan</h3>
                        <p class="text-xs text-slate-500 mb-4">Transfer manual via ATM / M-Banking / Internet Banking.</p>

                        <div class="space-y-3">
                            @foreach ($bankAccounts as $bank)
                                <label class="p-3.5 rounded-2xl border border-slate-200 bg-slate-50 hover:border-[#F37032] cursor-pointer transition-all flex items-center justify-between group">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="bank_account_id" value="{{ $bank->id }}" required class="w-4 h-4 text-[#F37032] bg-white border-slate-300 focus:ring-[#F37032]">
                                        <div>
                                            <span class="font-bold text-xs sm:text-sm text-slate-900 block">{{ $bank->bank_name }} — {{ $bank->account_number }}</span>
                                            <span class="text-[11px] text-slate-500">a/n {{ $bank->account_holder }}</span>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-orange-50 text-[#F37032] border border-orange-200">{{ $bank->bank_name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('bank_account_id')
                            <span class="text-rose-600 text-xs mt-2 block font-semibold">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Price Breakdown & Submit Button Card -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                        <h3 class="font-heading font-bold text-lg text-slate-900 mb-6">Rincian Pembayaran</h3>

                        <div class="space-y-3 text-xs mb-6">
                            <div class="flex items-center justify-between text-slate-500">
                                <span>Total Harga Tiket</span>
                                <span class="font-semibold text-slate-800">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-200 mb-8">
                            <span class="text-[10px] text-slate-500 uppercase tracking-wider block mb-1">Total Nominal yang Harus Ditransfer</span>
                            <span class="font-heading font-extrabold text-3xl text-[#F37032] block">
                                Rp {{ number_format($finalAmount, 0, ',', '.') }}
                            </span>
                        </div>

                        <button type="submit" class="w-full py-4 px-6 rounded-2xl bg-[#F37032] hover:bg-[#e05f24] text-white font-extrabold text-sm text-center shadow-md shadow-[#F37032]/20 transition-all flex items-center justify-center gap-2">
                            Buat Pesanan & Lanjut Pembayaran
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</x-app-layout>
