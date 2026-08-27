<x-app-layout>
    <!-- Include HTML5-QRCode Library for Web Camera Scanner -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="scannerApp()" x-init="initScanner()">
        
        <!-- Header Banner Card -->
        <div class="bg-white p-6 rounded-3xl mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-slate-200 shadow-sm">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#F37032] block mb-1">Gatekeeper Entry Control</span>
                <h1 class="font-heading font-extrabold text-2xl text-slate-900">Scanner QR Code Tiket</h1>
                <p class="text-xs text-slate-500 mt-0.5">Petugas Pemindai: <strong class="text-slate-900 font-bold">{{ Auth::user()->name }}</strong></p>
            </div>

            <div class="flex items-center gap-3 shrink-0 self-start sm:self-auto">
                <a href="{{ route('admin.seat-attendance') }}" class="px-4 py-2.5 rounded-2xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-extrabold border border-emerald-200 transition-all flex items-center gap-1.5 shadow-xs">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Monitor Kehadiran</span>
                </a>

                <div class="px-4 py-2.5 rounded-2xl bg-orange-50 border border-orange-200 text-right">
                    <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider">Total Dipindai</span>
                    <span class="font-heading font-black text-2xl text-[#F37032]" x-text="todayCount">{{ $todayScannedCount }}</span>
                </div>
            </div>
        </div>

        <!-- Main Scanner Card -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-md space-y-8">
            
            <!-- Live Web Camera Scanner Container -->
            <div class="space-y-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#F37032] animate-pulse"></span>
                        <span>Live Kamera Scanner Tiket</span>
                    </label>

                    <button 
                        @click="toggleCamera()" 
                        type="button" 
                        class="px-4 py-2.5 rounded-xl text-xs font-extrabold transition-all flex items-center gap-2 cursor-pointer"
                        :class="isCameraActive ? 'bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100' : 'bg-[#F37032] text-white shadow-md shadow-[#F37032]/20 hover:bg-[#e05f24]'"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                        <span x-text="isCameraActive ? 'Matikan Kamera' : 'Buka Kamera Scanner'"></span>
                    </button>
                </div>

                <div x-show="isCameraActive" x-transition:enter="transition ease-out duration-300">
                    <div id="reader" class="w-full max-w-md mx-auto rounded-2xl overflow-hidden border-2 border-[#F37032]/50 bg-slate-900 min-h-[260px] shadow-lg"></div>
                    <span class="text-[11px] text-slate-500 mt-2.5 text-center block font-medium">Arahkan QR Code E-Tiket penonton tepat ke depan lensa kamera.</span>
                </div>
            </div>

            <!-- Divider -->
            <div class="relative flex items-center justify-center my-4">
                <div class="border-t border-slate-200 w-full"></div>
                <span class="bg-slate-100 px-4 py-1 rounded-full text-[10px] font-extrabold text-slate-500 uppercase tracking-widest absolute border border-slate-200">atau input manual</span>
            </div>

            <!-- Manual Input Form -->
            <form @submit.prevent="submitScan">
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-800 mb-2">
                    Masukkan Kode Tiket / Tempel QR Hash Manual
                </label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <input 
                        type="text" 
                        x-model="qrCodeInput" 
                        x-ref="searchInput"
                        placeholder="Contoh: TKT-20260815-XYZ1 atau tempel QR Hash..." 
                        class="w-full px-5 py-3.5 rounded-2xl bg-slate-50 border-2 border-slate-200 text-slate-900 font-mono text-sm outline-none focus:border-[#F37032] focus:bg-white shadow-xs transition-all"
                    >
                    <button 
                        type="submit" 
                        :disabled="isLoading || !qrCodeInput.trim()"
                        class="px-7 py-3.5 bg-[#F37032] hover:bg-[#e05f24] disabled:opacity-40 text-white font-extrabold text-xs rounded-2xl transition-all shadow-md shadow-[#F37032]/20 flex items-center justify-center gap-2 shrink-0 cursor-pointer"
                    >
                        <span x-show="!isLoading">Periksa Tiket</span>
                        <span x-show="isLoading" style="display: none;">Memproses...</span>
                    </button>
                </div>
            </form>

            <!-- Result Alert Modal Overlay Popup -->
            <div 
                x-show="showModal" 
                x-cloak 
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-md"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                <div 
                    class="bg-white w-full max-w-md rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-100 relative overflow-hidden text-center space-y-6"
                    @click.away="closeModalAndResume()"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                >
                    <!-- 1. SUCCESS STATE MODAL -->
                    <template x-if="result && result.status === 'success'">
                        <div class="space-y-5">
                            <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-inner animate-bounce">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>

                            <div>
                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-black uppercase tracking-wider inline-block mb-2">Akses Masuk Diizinkan ✅</span>
                                <h2 class="font-heading font-black text-2xl text-emerald-950" x-text="result.message"></h2>
                            </div>

                            <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200 text-left grid grid-cols-2 gap-3 text-xs text-slate-700">
                                <div class="col-span-2 border-b border-emerald-200/60 pb-2">
                                    <span class="text-[10px] text-slate-400 block uppercase font-bold">Penonton Terdaftar</span>
                                    <strong class="text-slate-900 text-base font-bold" x-text="result.ticket.user_name"></strong>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-400 block uppercase font-bold">Nomor Kursi</span>
                                    <strong class="text-[#F37032] text-2xl font-heading font-black" x-text="result.ticket.seat_code"></strong>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-400 block uppercase font-bold">Kategori Kursi</span>
                                    <strong class="text-slate-900 font-bold" x-text="result.ticket.category"></strong>
                                </div>
                                <div class="col-span-2 pt-1 border-t border-emerald-200/60">
                                    <span class="text-[10px] text-slate-400 block uppercase font-bold">Kode Tiket</span>
                                    <strong class="text-slate-700 font-mono font-bold" x-text="result.ticket.ticket_code"></strong>
                                </div>
                            </div>

                            <button 
                                type="button" 
                                @click="closeModalAndResume()" 
                                class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-sm rounded-2xl shadow-lg shadow-emerald-600/30 transition-all transform active:scale-95 cursor-pointer flex items-center justify-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                <span>OK / Pindai Tiket Berikutnya 📷</span>
                            </button>
                        </div>
                    </template>

                    <!-- 2. ALREADY USED STATE MODAL -->
                    <template x-if="result && result.status === 'already_used'">
                        <div class="space-y-5">
                            <div class="w-20 h-20 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto shadow-inner">
                                <svg class="w-11 h-11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>

                            <div>
                                <span class="px-3 py-1 rounded-full bg-rose-100 text-rose-700 text-[11px] font-black uppercase tracking-wider inline-block mb-2">Akses Ditolak — Tiket Sudah Terpakai 🚫</span>
                                <h2 class="font-heading font-black text-xl text-amber-950" x-text="result.message"></h2>
                            </div>

                            <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200 text-left grid grid-cols-2 gap-3 text-xs text-slate-700">
                                <div>
                                    <span class="text-[10px] text-slate-400 block uppercase font-bold">Penonton Terdaftar</span>
                                    <strong class="text-slate-900 font-bold" x-text="result.ticket.user_name"></strong>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-400 block uppercase font-bold">Nomor Kursi</span>
                                    <strong class="text-amber-600 text-xl font-heading font-black" x-text="result.ticket.seat_code"></strong>
                                </div>
                            </div>

                            <div class="pt-2 flex flex-col gap-3">
                                <button 
                                    type="button" 
                                    @click="resetTicket(result.ticket.ticket_code)" 
                                    class="w-full py-2.5 text-xs font-bold text-amber-900 bg-amber-200/80 hover:bg-amber-300 rounded-xl transition-all cursor-pointer flex items-center justify-center gap-1.5"
                                >
                                    <svg class="w-3.5 h-3.5 text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    <span>Reset Status Tiket Ini (Dev/Testing Mode)</span>
                                </button>

                                <button 
                                    type="button" 
                                    @click="closeModalAndResume()" 
                                    class="w-full py-4 bg-amber-500 hover:bg-amber-600 text-white font-black text-sm rounded-2xl shadow-lg shadow-amber-500/30 transition-all transform active:scale-95 cursor-pointer flex items-center justify-center gap-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                    <span>OK / Pindai Tiket Berikutnya 📷</span>
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- 3. ERROR / INVALID STATE MODAL -->
                    <template x-if="result && result.status === 'error'">
                        <div class="space-y-5">
                            <div class="w-20 h-20 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto shadow-inner">
                                <svg class="w-11 h-11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </div>

                            <div>
                                <span class="px-3 py-1 rounded-full bg-rose-100 text-rose-700 text-[11px] font-black uppercase tracking-wider inline-block mb-2">Error Verifikasi ❌</span>
                                <h2 class="font-heading font-black text-xl text-rose-950" x-text="result.message || '❌ TIKET TIDAK DITEMUKAN / KODE QR INVALID!'"></h2>
                                <p class="text-xs text-rose-600 mt-1">Pastikan kode QR berasal dari tiket resmi Nanya Events.</p>
                            </div>

                            <button 
                                type="button" 
                                @click="closeModalAndResume()" 
                                class="w-full py-4 bg-rose-600 hover:bg-rose-700 text-white font-black text-sm rounded-2xl shadow-lg shadow-rose-600/30 transition-all transform active:scale-95 cursor-pointer flex items-center justify-center gap-2"
                            >
                                <span>Tutup & Coba Pindai Lagi 📷</span>
                            </button>
                        </div>
                    </template>

                </div>
            </div>

        </div>
    </div>

    <script>
        function scannerApp() {
            return {
                qrCodeInput: '',
                isLoading: false,
                result: null,
                showModal: false,
                todayCount: {{ $todayScannedCount }},
                isCameraActive: false,
                html5QrCode: null,
                isProcessingScan: false,

                initScanner() {
                    this.$nextTick(() => {
                        if (this.$refs.searchInput) this.$refs.searchInput.focus();
                    });
                },

                toggleCamera() {
                    if (this.isCameraActive) {
                        this.stopCamera();
                    } else {
                        this.startCamera();
                    }
                },

                startCamera() {
                    if (this.isCameraActive) return;
                    
                    this.isCameraActive = true;
                    this.$nextTick(() => {
                        this.html5QrCode = new Html5Qrcode("reader");
                        const config = { fps: 10, qrbox: { width: 220, height: 220 } };

                        this.html5QrCode.start(
                            { facingMode: "environment" },
                            config,
                            (decodedText, decodedResult) => {
                                // Jika sedang memproses atau modal terbuka, hentikan tembakan berikutnya
                                if (this.isProcessingScan || this.showModal) return;

                                this.isProcessingScan = true;
                                this.qrCodeInput = decodedText;

                                // Hentikan kamera segera saat QR terdeteksi pertama kali
                                this.stopCamera().then(() => {
                                    this.submitScan();
                                });
                            },
                            (errorMessage) => {
                                // Ignore frame scan errors
                            }
                        ).catch((err) => {
                            console.error("Camera access failed:", err);
                            alert("Gagal mengaktifkan kamera. Pastikan Anda mengizinkan akses kamera pada browser.");
                            this.isCameraActive = false;
                        });
                    });
                },

                async stopCamera() {
                    if (this.html5QrCode && this.isCameraActive) {
                        try {
                            await this.html5QrCode.stop();
                            this.html5QrCode.clear();
                        } catch (err) {
                            console.error("Failed to stop camera:", err);
                        } finally {
                            this.isCameraActive = false;
                        }
                    } else {
                        this.isCameraActive = false;
                    }
                },

                async submitScan() {
                    if (!this.qrCodeInput.trim()) return;
                    
                    this.isLoading = true;

                    try {
                        const response = await fetch('{{ route("scan-ticket.validate") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ qr_code: this.qrCodeInput.trim() })
                        });

                        const data = await response.json();
                        this.result = data;

                        if (data.status === 'success') {
                            this.todayCount = data.today_scanned_count;
                        }

                        // TAMPILKAN MODAL POPUP RESULTS
                        this.showModal = true;
                        this.qrCodeInput = '';
                    } catch (e) {
                        this.result = {
                            status: 'error',
                            message: '❌ Terjadi kesalahan jaringan saat memverifikasi tiket.'
                        };
                        this.showModal = true;
                    } finally {
                        this.isLoading = false;
                        this.isProcessingScan = false;
                    }
                },

                closeModalAndResume() {
                    this.showModal = false;
                    this.result = null;
                    this.qrCodeInput = '';

                    // Nyalakan kembali kamera scanner secara otomatis
                    this.startCamera();
                },

                async resetTicket(ticketCode) {
                    if (!confirm(`Reset status tiket ${ticketCode} menjadi Tersedia / Belum Dipindai?`)) return;

                    try {
                        const response = await fetch('{{ route("scan-ticket.reset") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ ticket_code: ticketCode })
                        });

                        const data = await response.json();
                        if (data.status === 'success') {
                            this.todayCount = data.today_scanned_count;
                            this.result = {
                                status: 'success',
                                message: data.message,
                                ticket: {
                                    user_name: 'Status Reset (Siap Dipindai)',
                                    seat_code: 'VALID',
                                    category: 'Testing',
                                    ticket_code: ticketCode
                                }
                            };
                        } else {
                            alert(data.message || 'Gagal mereset tiket.');
                        }
                    } catch(e) {
                        alert('Gagal menghubungi server untuk reset tiket.');
                    }
                }
            }
        }
    </script>
</x-app-layout>
