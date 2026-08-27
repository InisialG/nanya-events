<x-app-layout>
    <div class="max-w-md mx-auto my-12 px-4">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 relative overflow-hidden" x-data="{ mode: 'login' }">
            
            <div class="text-center mb-8">
                <h1 class="font-heading font-bold text-2xl text-slate-900 mb-2" x-text="mode === 'login' ? 'Masuk ke Akun Anda' : 'Buat Akun Penonton'">
                    Masuk ke Akun Anda
                </h1>
                <p class="text-xs text-slate-500">
                    Wajib masuk untuk dapat memesan tiket dan memilih kursi venue.
                </p>
            </div>

            @if (session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 text-rose-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-semibold leading-relaxed">{{ session('error') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-semibold leading-relaxed">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Tombol Socialite Google OAuth -->
            <a href="{{ route('auth.google') }}" class="w-full mb-6 py-3 px-4 rounded-2xl bg-slate-50 text-slate-800 font-semibold text-sm flex items-center justify-center gap-3 hover:bg-slate-100 border border-slate-200 transition-all shadow-sm">
                <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                Masuk dengan Google
            </a>

            <div class="relative flex py-2 items-center mb-6">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink mx-4 text-xs text-slate-400 uppercase font-semibold">atau email</span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>

            <!-- Form Login -->
            <form method="POST" action="{{ route('login') }}" x-show="mode === 'login'">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email</label>
                        <input type="email" name="email" required value="{{ old('email') }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-sm focus:border-[#F37032] focus:ring-1 focus:ring-[#F37032] outline-none" placeholder="nama@email.com">
                        @error('email')
                            <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-xs font-semibold text-slate-700">Password</label>
                            <a href="{{ route('password.request') }}" class="text-xs text-[#F37032] hover:underline font-semibold">Lupa Password?</a>
                        </div>
                        <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-sm focus:border-[#F37032] focus:ring-1 focus:ring-[#F37032] outline-none" placeholder="••••••••">
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-[#F37032] hover:bg-[#e05f24] text-white font-extrabold text-sm shadow-md shadow-[#F37032]/20 transition-all mt-4">
                        Masuk Sekarang
                    </button>
                </div>
            </form>

            <!-- Form Register -->
            <form method="POST" action="{{ route('register') }}" x-show="mode === 'register'" style="display: none;">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-sm focus:border-[#F37032] outline-none" placeholder="Budi Santoso">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-sm focus:border-[#F37032] outline-none" placeholder="nama@email.com">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-sm focus:border-[#F37032] outline-none" placeholder="Minimal 8 karakter">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-sm focus:border-[#F37032] outline-none" placeholder="Ulangi password">
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-[#F37032] hover:bg-[#e05f24] text-white font-extrabold text-sm shadow-md shadow-[#F37032]/20 transition-all mt-4">
                        Daftar Akun Baru
                    </button>
                </div>
            </form>

            <div class="text-center mt-6 pt-4 border-t border-slate-200">
                <template x-if="mode === 'login'">
                    <p class="text-xs text-slate-600">Belum punya akun? <button @click="mode = 'register'" class="text-[#F37032] font-bold hover:underline">Daftar sekarang</button></p>
                </template>
                <template x-if="mode === 'register'">
                    <p class="text-xs text-slate-600">Sudah punya akun? <button @click="mode = 'login'" class="text-[#F37032] font-bold hover:underline">Masuk ke akun</button></p>
                </template>
            </div>

        </div>
    </div>
</x-app-layout>
