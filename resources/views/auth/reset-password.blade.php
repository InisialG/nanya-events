<x-app-layout>
    <div class="max-w-md mx-auto my-12 px-4">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 relative overflow-hidden">
            
            <div class="text-center mb-8">
                <div class="w-12 h-12 bg-orange-50 border border-orange-200 rounded-2xl flex items-center justify-center mx-auto mb-4 text-[#F37032]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="font-heading font-bold text-2xl text-slate-900 mb-2">
                    Buat Password Baru
                </h1>
                <p class="text-xs text-slate-500">
                    Silakan masukkan alamat email dan password baru Anda di bawah ini.
                </p>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $email) }}" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-sm focus:border-[#F37032] focus:ring-1 focus:ring-[#F37032] outline-none" placeholder="nama@email.com">
                        @error('email')
                            <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Password Baru</label>
                        <input type="password" name="password" required autofocus class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-sm focus:border-[#F37032] focus:ring-1 focus:ring-[#F37032] outline-none" placeholder="Minimal 8 karakter">
                        @error('password')
                            <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-sm focus:border-[#F37032] focus:ring-1 focus:ring-[#F37032] outline-none" placeholder="Ulangi password baru">
                        @error('password_confirmation')
                            <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-[#F37032] hover:bg-[#e05f24] text-white font-extrabold text-sm shadow-md shadow-[#F37032]/20 transition-all mt-4 flex items-center justify-center gap-2">
                        <span>Simpan Password Baru</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
