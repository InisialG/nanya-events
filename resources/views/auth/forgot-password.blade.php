<x-app-layout>
    <div class="max-w-md mx-auto my-12 px-4">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 relative overflow-hidden">
            
            <div class="text-center mb-8">
                <div class="w-12 h-12 bg-orange-50 border border-orange-200 rounded-2xl flex items-center justify-center mx-auto mb-4 text-[#F37032]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h1 class="font-heading font-bold text-2xl text-slate-900 mb-2">
                    Lupa Password?
                </h1>
                <p class="text-xs text-slate-500">
                    Masukkan email terdaftar Anda. Kami akan mengirimkan tautan untuk mengatur ulang password akun Anda.
                </p>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-semibold">{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-sm focus:border-[#F37032] focus:ring-1 focus:ring-[#F37032] outline-none" placeholder="nama@email.com">
                        @error('email')
                            <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-[#F37032] hover:bg-[#e05f24] text-white font-extrabold text-sm shadow-md shadow-[#F37032]/20 transition-all mt-4 flex items-center justify-center gap-2">
                        <span>Kirim Tautan Reset</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>

            <div class="text-center mt-6 pt-4 border-t border-slate-200">
                <a href="{{ route('login') }}" class="text-xs text-slate-600 hover:text-[#F37032] transition-colors flex items-center justify-center gap-1.5 font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Halaman Masuk</span>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
