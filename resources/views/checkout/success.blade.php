<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 my-12 text-center">
        <div class="bg-white p-8 sm:p-10 rounded-3xl shadow-sm border border-slate-200">
            <div class="w-20 h-20 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>

            <h1 class="font-heading font-extrabold text-3xl text-slate-900 mb-2">Pesanan & Bukti Pembayaran Diterima!</h1>
            <p class="text-slate-600 text-sm max-w-md mx-auto mb-8">
                Terima kasih. Bukti transfer Anda untuk pesanan <strong class="text-slate-900">{{ $order->order_code }}</strong> telah berhasil kami terima dan sedang berada dalam proses verifikasi Admin.
            </p>

            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 text-left space-y-3 text-xs mb-8">
                <div class="flex justify-between">
                    <span class="text-slate-500">Status Pesanan</span>
                    @if($order->status === 'paid')
                        <span class="font-bold text-emerald-600">Lunas (E-Tiket Diterbitkan)</span>
                    @elseif($order->status === 'waiting_verification')
                        <span class="font-bold text-amber-600">Menunggu Verifikasi Admin</span>
                    @elseif($order->status === 'rejected')
                        <span class="font-bold text-rose-600">Ditolak Admin</span>
                    @else
                        <span class="font-bold text-slate-700">{{ $order->status }}</span>
                    @endif
                </div>

                <div class="flex justify-between pt-2 border-t border-slate-200">
                    <span class="text-slate-500">Event</span>
                    <span class="font-semibold text-slate-900">{{ $order->eventSession->event->title }}</span>
                </div>

                <div class="flex justify-between pt-2 border-t border-slate-200">
                    <span class="text-slate-500">Total Pembayaran</span>
                    <span class="font-bold text-[#F37032]">Rp {{ number_format($order->final_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-sm transition-colors border border-slate-200">
                    Kembali ke Beranda
                </a>
                <a href="{{ route('checkout.instructions', $order->order_code) }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-[#F37032] hover:bg-[#e05f24] text-white font-bold text-sm transition-colors shadow-md shadow-[#F37032]/20">
                    Lihat Instruksi & Detail Order
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
