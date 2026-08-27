<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Order Header Status -->
        @if($order->status === 'cancelled')
        <div class="bg-rose-50 p-6 rounded-3xl mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-rose-200 shadow-sm">
            <div>
                <span class="text-xs text-rose-600 font-bold uppercase tracking-wider block">Status Pesanan</span>
                <h1 class="font-heading font-extrabold text-2xl text-slate-900">Pesanan Dibatalkan</h1>
                <span class="text-xs text-slate-600">Kode Order: <strong class="text-slate-900">{{ $order->order_code }}</strong></span>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-[11px] text-slate-500 block">Batas Waktu Pembayaran & Upload</span>
                <span class="font-bold text-rose-600 text-sm">
                    Waktu Habis (Expired)
                </span>
            </div>
        </div>
        @else
        <div class="bg-orange-50 p-6 rounded-3xl mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-orange-200 shadow-sm">
            <div>
                <span class="text-xs text-[#F37032] font-bold uppercase tracking-wider block">Status Pesanan</span>
                <h1 class="font-heading font-extrabold text-2xl text-slate-900">
                    {{ $order->status === 'waiting_verification' ? 'Menunggu Verifikasi Admin' : 'Menunggu Transfer Bank' }}
                </h1>
                <span class="text-xs text-slate-600">Kode Order: <strong class="text-slate-900">{{ $order->order_code }}</strong></span>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-[11px] text-slate-500 block">Batas Waktu Pembayaran & Upload</span>
                <span class="font-bold text-[#F37032] text-sm">
                    {{ \Carbon\Carbon::parse($order->expired_at)->translatedFormat('d M Y, H:i') }} WIB
                </span>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left: Transfer Instructions & Bank Card -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Highlight Nominal Transfer -->
                <div class="bg-white p-8 rounded-3xl text-center border-2 border-orange-200 shadow-sm">
                    <span class="text-xs text-slate-500 uppercase tracking-wider block mb-1">Total Nominal yang Harus Ditransfer</span>
                    
                    <div class="my-4 inline-flex items-center gap-3 px-6 py-3 rounded-2xl bg-orange-50/80 border border-orange-200 shadow-sm">
                        <span class="font-heading font-extrabold text-3xl sm:text-4xl text-[#F37032]" id="nominalText">
                            Rp {{ number_format($order->final_amount, 0, ',', '.') }}
                        </span>
                    </div>

                    <p class="text-xs text-slate-600 max-w-md mx-auto">
                        Silakan melakukan transfer sesuai nominal di atas ke rekening bank tujuan, lalu unggah bukti transfer.
                    </p>
                </div>

                <!-- Bank Details Box -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                    <h3 class="font-heading font-bold text-lg text-slate-900 mb-4">Rekening Tujuan Transfer</h3>

                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">Bank Tujuan</span>
                            <span class="font-bold text-sm text-[#F37032]">{{ $order->bankAccount->bank_name }}</span>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-slate-200">
                            <span class="text-xs text-slate-500">Nomor Rekening</span>
                            <span class="font-bold text-lg text-slate-900 tracking-wider select-all">{{ $order->bankAccount->account_number }}</span>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-slate-200">
                            <span class="text-xs text-slate-500">Atas Nama</span>
                            <span class="font-medium text-sm text-slate-800">{{ $order->bankAccount->account_holder }}</span>
                        </div>
                    </div>
                </div>

                <!-- Booked Seats Detail Box -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm mt-6">
                    <h3 class="font-heading font-bold text-lg text-slate-900 mb-4">Detail Kursi Pesanan Anda</h3>
                    <div class="space-y-3">
                        @if($order->status === 'cancelled' || $order->expired_at < now())
                            <div class="p-5 rounded-2xl bg-rose-50 border border-rose-200 text-center">
                                <svg class="w-8 h-8 text-rose-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-xs text-rose-700 font-semibold">Informasi kursi tidak tersedia karena seluruh kursi telah dilepas kembali (dibatalkan) akibat batas waktu pembayaran telah habis.</p>
                            </div>
                        @else
                            @foreach($order->seatAvailabilities as $seat)
                                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-200">
                                    <div>
                                        <span class="block text-xs text-slate-500 font-bold mb-1 uppercase tracking-wider">{{ $seat->seatMaster->seatCategory->name ?? 'Kategori' }}</span>
                                        <span class="block font-heading font-bold text-lg text-slate-900">
                                            Kode Kursi: {{ $seat->seatMaster->seat_code }}
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-sm font-bold text-[#F37032]">
                                            Rp {{ number_format($seat->seatMaster->seatCategory->price ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>

            <!-- Right: Upload Bukti Transfer Form -->
            <div class="lg:col-span-5">
                <div class="bg-white p-6 sm:p-8 rounded-3xl sticky top-28 border border-slate-200 shadow-sm">
                    <h3 class="font-heading font-bold text-xl text-slate-900 mb-2">Upload Bukti Transfer</h3>
                    <p class="text-xs text-slate-500 mb-6">Unggah struk / foto / PDF bukti transfer setelah selesai membayar.</p>

                    @if($order->status === 'cancelled')
                        <div class="p-6 rounded-2xl bg-rose-50 border border-rose-200 text-center">
                            <svg class="w-12 h-12 text-rose-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h4 class="font-bold text-slate-900 text-base">Waktu Pembayaran Habis</h4>
                            <p class="text-xs text-slate-600 mt-1">Anda sudah melewati batas waktu pembayaran. Pesanan ini otomatis dibatalkan dan kursi dilepas kembali.</p>
                            <a href="{{ route('events.index') }}" class="inline-block mt-4 px-6 py-2 rounded-xl bg-rose-600 text-white font-bold text-xs hover:bg-rose-700 transition-colors">← Cari Tiket Lain</a>
                        </div>
                    @elseif($order->status === 'waiting_verification')
                        <div class="p-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-center">
                            <svg class="w-12 h-12 text-emerald-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h4 class="font-bold text-slate-900 text-base">Bukti Berhasil Diunggah</h4>
                            <p class="text-xs text-slate-600 mt-1">Pembayaran Anda sedang dalam proses verifikasi oleh Admin.</p>
                            <a href="{{ route('checkout.success', $order->order_code) }}" class="inline-block mt-4 text-xs font-bold text-[#F37032] hover:underline">Lihat Status Verifikasi →</a>
                        </div>
                    @else
                        <div id="upload-form-container">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">File Bukti Transfer (JPG/PNG, Max 10MB)</label>
                                    <input type="file" id="proof_file_input" required accept="image/*" class="w-full text-xs text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#F37032] file:text-white hover:file:bg-[#e05f24] cursor-pointer">
                                    <div id="file-preview" class="mt-2 hidden">
                                        <img id="preview-img" class="w-full max-h-48 object-contain rounded-xl border border-slate-200" alt="Preview">
                                    </div>
                                    <span id="proof_error" class="text-rose-600 text-xs mt-1 block font-semibold hidden"></span>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Bank Pengirim</label>
                                    <input type="text" id="sender_bank_input" required placeholder="contoh: BCA / Mandiri / GoPay" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs outline-none focus:border-[#F37032]">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Pengirim di Struk</label>
                                    <input type="text" id="sender_name_input" required placeholder="contoh: Budi Santoso" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs outline-none focus:border-[#F37032]">
                                </div>
                                <div id="upload-progress" class="hidden">
                                    <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                                        <div id="progress-bar" class="bg-[#F37032] h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                    <p id="progress-text" class="text-xs text-slate-500 mt-1 text-center">Mengunggah...</p>
                                </div>
                                <button type="button" id="btn-upload" onclick="startDirectUpload()" class="w-full py-4 px-6 rounded-2xl bg-[#F37032] hover:bg-[#e05f24] text-white font-extrabold text-sm text-center shadow-md shadow-[#F37032]/20 transition-all mt-4">
                                    Unggah Bukti Pembayaran
                                </button>
                            </div>
                        </div>
                        <script>
                        document.getElementById('proof_file_input').addEventListener('change', function(e) {
                            var file = e.target.files[0];
                            if (file) {
                                var reader = new FileReader();
                                reader.onload = function(ev) {
                                    document.getElementById('preview-img').src = ev.target.result;
                                    document.getElementById('file-preview').classList.remove('hidden');
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                        function showError(msg) {
                            var el = document.getElementById('proof_error');
                            el.textContent = msg;
                            el.classList.remove('hidden');
                        }
                        function updateProgress(pct, text) {
                            document.getElementById('progress-bar').style.width = pct + '%';
                            document.getElementById('progress-text').textContent = text;
                        }
                        function startDirectUpload() {
                            var fileInput = document.getElementById('proof_file_input');
                            var senderBank = document.getElementById('sender_bank_input').value.trim();
                            var senderName = document.getElementById('sender_name_input').value.trim();
                            var btn = document.getElementById('btn-upload');
                            document.getElementById('proof_error').classList.add('hidden');
                            if (!fileInput.files[0]) { showError('File bukti transfer wajib dipilih.'); return; }
                            if (!senderBank) { showError('Nama bank pengirim wajib diisi.'); return; }
                            if (!senderName) { showError('Nama pengirim wajib diisi.'); return; }
                            var file = fileInput.files[0];
                            if (file.size > 10485760) { showError('Ukuran file maksimal 10MB.'); return; }
                            if (!file.type.startsWith('image/')) { showError('File harus berupa gambar.'); return; }
                            btn.disabled = true;
                            btn.textContent = 'Memproses...';
                            document.getElementById('upload-progress').classList.remove('hidden');
                            updateProgress(10, 'Mendapatkan izin upload...');
                            fetch('{{ route("checkout.cloudinary-signature", $order->order_code) }}')
                            .then(function(r) { return r.json(); })
                            .then(function(sig) {
                                if (!sig.success) throw new Error(sig.error || 'Gagal mendapatkan signature');
                                updateProgress(20, 'Mengunggah ke cloud...');
                                var fd = new FormData();
                                fd.append('file', file);
                                fd.append('api_key', sig.api_key);
                                fd.append('timestamp', sig.timestamp);
                                fd.append('signature', sig.signature);
                                fd.append('folder', sig.folder);
                                var cloudUrl = 'https://api.cloudinary.com/v1_1/' + sig.cloud_name + '/image/upload';
                                return new Promise(function(resolve, reject) {
                                    var xhr = new XMLHttpRequest();
                                    xhr.open('POST', cloudUrl);
                                    xhr.upload.onprogress = function(e) {
                                        if (e.lengthComputable) {
                                            var pct = 20 + Math.round((e.loaded / e.total) * 60);
                                            updateProgress(pct, 'Mengunggah... ' + Math.round(e.loaded/e.total*100) + '%');
                                        }
                                    };
                                    xhr.onload = function() {
                                        if (xhr.status >= 200 && xhr.status < 300) {
                                            resolve(JSON.parse(xhr.responseText));
                                        } else {
                                            try { var err = JSON.parse(xhr.responseText); reject(new Error(err.error.message)); }
                                            catch(ex) { reject(new Error('Upload gagal (status ' + xhr.status + ')')); }
                                        }
                                    };
                                    xhr.onerror = function() { reject(new Error('Koneksi gagal. Periksa internet.')); };
                                    xhr.send(fd);
                                });
                            })
                            .then(function(cloudRes) {
                                if (!cloudRes.secure_url) throw new Error('Cloudinary tidak mengembalikan URL');
                                updateProgress(85, 'Menyimpan data...');
                                return fetch('{{ route("checkout.save-proof-url", $order->order_code) }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                                    body: JSON.stringify({ cloudinary_url: cloudRes.secure_url, sender_bank: senderBank, sender_name: senderName })
                                }).then(function(r) { return r.json(); });
                            })
                            .then(function(saveRes) {
                                if (!saveRes.success) throw new Error(saveRes.message || 'Gagal menyimpan');
                                updateProgress(100, '✅ Berhasil! Mengalihkan...');
                                window.location.href = saveRes.redirect_url;
                            })
                            .catch(function(err) {
                                showError(err.message);
                                btn.disabled = false;
                                btn.textContent = 'Unggah Bukti Pembayaran';
                                document.getElementById('upload-progress').classList.add('hidden');
                            });
                        }
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
