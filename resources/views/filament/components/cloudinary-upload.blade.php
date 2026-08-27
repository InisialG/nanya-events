<div 
    x-data="{
        imageUrl: $wire.entangle('{{ $getStatePath() }}'),
        isUploading: false,
        uploadProgress: 0,
        errorMessage: '',

        async uploadFile(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.isUploading = true;
            this.uploadProgress = 10;
            this.errorMessage = '';

            try {
                const folder = 'nanya-event-posters';
                const sigResponse = await fetch('/api/cloudinary-signature?folder=' + folder);
                const sigData = await sigResponse.json();

                if (!sigData.success) {
                    throw new Error(sigData.error || 'Gagal membuat signature Cloudinary');
                }

                this.uploadProgress = 30;

                const formData = new FormData();
                formData.append('file', file);
                formData.append('api_key', sigData.api_key);
                formData.append('timestamp', sigData.timestamp);
                formData.append('signature', sigData.signature);
                formData.append('folder', folder);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', `https://api.cloudinary.com/v1_1/${sigData.cloud_name}/image/upload`);

                xhr.upload.onprogress = (e) => {
                    if (e.lengthComputable) {
                        this.uploadProgress = Math.round((e.loaded / e.total) * 100);
                    }
                };

                xhr.onload = () => {
                    this.isUploading = false;
                    if (xhr.status === 200) {
                        const data = JSON.parse(xhr.responseText);
                        if (data.secure_url) {
                            this.imageUrl = data.secure_url;
                        } else {
                            this.errorMessage = 'Gagal mendapatkan URL gambar dari Cloudinary.';
                        }
                    } else {
                        const errData = JSON.parse(xhr.responseText || '{}');
                        this.errorMessage = 'Upload gagal: ' + (errData.error?.message || 'HTTP ' + xhr.status);
                    }
                };

                xhr.onerror = () => {
                    this.isUploading = false;
                    this.errorMessage = 'Gagal menghubungi server Cloudinary. Periksa koneksi internet.';
                };

                xhr.send(formData);

            } catch (err) {
                this.isUploading = false;
                this.errorMessage = err.message || 'Terjadi kesalahan saat upload gambar.';
            }
        },

        removeImage() {
            if (confirm('Apakah Anda yakin ingin menghapus foto poster ini?')) {
                this.imageUrl = null;
            }
        }
    }"
    class="space-y-3"
>
    <label class="block text-sm font-bold text-slate-900 dark:text-slate-100">
        Poster Event (Rasio Disarankan 3:4) — <span class="text-emerald-600 dark:text-emerald-400 font-extrabold">Direct Cloudinary Upload ☁️</span>
    </label>

    <!-- Preview State if image exists -->
    <template x-if="imageUrl">
        <div class="space-y-3">
            <div class="relative w-48 rounded-2xl overflow-hidden border-2 border-emerald-500/40 shadow-lg group bg-slate-900">
                <img :src="imageUrl" alt="Poster Event" class="w-full h-64 object-cover">
                
                <div class="absolute inset-0 bg-slate-950/70 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2 p-2 text-center">
                    <a :href="imageUrl" target="_blank" class="px-3 py-1.5 rounded-lg bg-emerald-500 text-white font-bold text-xs shadow-md hover:bg-emerald-600 transition-all flex items-center gap-1">
                        🔍 Buka HD
                    </a>
                    <button type="button" @click="removeImage()" class="px-3 py-1.5 rounded-lg bg-rose-600 text-white font-bold text-xs shadow-md hover:bg-rose-700 transition-all flex items-center gap-1">
                        🗑️ Hapus Gambar
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-500 font-medium truncate max-w-md font-mono" x-text="imageUrl"></span>
                <button type="button" @click="removeImage()" class="text-xs text-rose-600 hover:text-rose-800 font-bold hover:underline">
                    Ganti Gambar
                </button>
            </div>
        </div>
    </template>

    <!-- Upload Input Box if no image or uploading -->
    <div x-show="!imageUrl || isUploading" class="space-y-2">
        <div class="flex items-center justify-center w-full">
            <label class="flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-2xl cursor-pointer bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all p-4 text-center">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-12 h-12 mb-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    <p class="mb-2 text-sm text-slate-700 dark:text-slate-200"><span class="font-bold text-emerald-600 dark:text-emerald-400">Klik untuk pilih gambar poster</span> atau geser berkas ke sini</p>
                    <p class="text-xs text-slate-500">PNG, JPG, WEBP, GIF (Maks. 10MB) • Diunggah Langsung ke Cloudinary ☁️</p>
                </div>
                <input type="file" accept="image/*" class="hidden" @change="uploadFile($event)" :disabled="isUploading">
            </label>
        </div>
    </div>

    <!-- Progress Bar -->
    <div x-show="isUploading" class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-3 overflow-hidden">
        <div class="bg-emerald-500 h-3 rounded-full transition-all duration-200" :style="`width: ${uploadProgress}%`"></div>
        <span class="text-[11px] text-slate-500 font-bold block mt-1 text-center" x-text="`Mengunggah langsung ke Cloudinary... ${uploadProgress}%`"></span>
    </div>

    <!-- Error Alert -->
    <div x-show="errorMessage" class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold flex items-center justify-between">
        <span x-text="errorMessage"></span>
        <button type="button" @click="errorMessage = ''" class="text-rose-500 hover:text-rose-800 font-bold ml-2">✕</button>
    </div>
</div>
