<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name', 'Events Nanyang Zhi Hui') . ' — Platform Tiket Pentas Seni' }}</title>
    <link rel="shortcut icon" href="{{ asset('img/LogoNanyangIcon.png') }}?v=3">
    <link rel="icon" type="image/png" href="{{ asset('img/LogoNanyangIcon.png') }}?v=3">
    
    <!-- Google Fonts Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        nanya: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            500: '#F37032',
                            600: '#e05f24',
                            700: '#c24b17',
                        }
                    }
                }
            }
        }
    </script>
    
    @livewireStyles
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #0f172a; -webkit-tap-highlight-color: transparent; }
        .font-heading { font-family: 'Outfit', sans-serif; }
        .light-card { background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05); }
        .touch-manipulation { touch-action: manipulation; }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased bg-white text-slate-900" x-data="{ mobileMenuOpen: false }">

    <!-- Header Navigation Bar (Vibrant Orange #F37032 Navbar) -->
    <header class="sticky top-0 z-50 bg-[#F37032] border-b border-[#e05f24] text-white shadow-md shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between py-1">
            
            <!-- Logo Image -->
            <a href="{{ url('/') }}" class="flex items-center group">
                <img src="{{ asset('img/Nanyang Logo with text white (1).png') }}" alt="Nanya Events Logo" class="h-12 sm:h-16 max-h-16 w-auto object-contain group-hover:scale-105 transition-transform">
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center gap-6">
                <a href="{{ url('/') }}" class="text-sm font-bold transition-all px-3 py-1.5 rounded-xl {{ request()->is('/') || request()->is('events') ? 'bg-white/20 text-white shadow-sm border border-white/30' : 'text-white/90 hover:text-white hover:bg-white/10' }}">Katalog Event</a>
                @auth
                    <a href="{{ route('my-tickets.index') }}" class="text-sm font-bold transition-all px-3 py-1.5 rounded-xl {{ request()->is('my-tickets*') ? 'bg-white/20 text-white shadow-sm border border-white/30' : 'text-white/90 hover:text-white hover:bg-white/10' }}">Tiket Saya</a>
                @endauth
            </nav>

            <!-- Desktop User Auth Buttons -->
            <div class="hidden md:flex items-center gap-4">
                @auth
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-white/90">Halo, <strong class="text-white font-extrabold">{{ Auth::user()->name }}</strong></span>
                        @if(Auth::user()->hasAnyRole(['Super Admin', 'Admin']))
                            <a href="{{ url('/admin') }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-white/20 text-white border border-white/40 hover:bg-white/30 transition-all flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                                Admin Panel
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-900/80 text-white hover:bg-slate-900 transition-colors">
                                Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl text-sm font-extrabold bg-slate-900 text-white hover:bg-slate-950 shadow-md hover:scale-[1.02] transition-all">
                        Masuk / Daftar
                    </a>
                @endauth
            </div>

            <!-- Mobile Hamburger Button Toggler -->
            <button 
                @click="mobileMenuOpen = !mobileMenuOpen"
                type="button" 
                class="md:hidden p-2.5 rounded-xl bg-white/20 text-white hover:bg-white/30 transition-colors focus:outline-none border border-white/30"
                aria-label="Toggle Navigation Menu"
            >
                <svg class="w-6 h-6" x-show="!mobileMenuOpen" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                <svg class="w-6 h-6" x-show="mobileMenuOpen" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div 
            x-show="mobileMenuOpen" 
            x-cloak 
            transition:enter="transition ease-out duration-200"
            transition:enter-start="opacity-0 -translate-y-4"
            transition:enter-end="opacity-100 translate-y-0"
            transition:leave="transition ease-in duration-150"
            transition:leave-start="opacity-100 translate-y-0"
            transition:leave-end="opacity-0 -translate-y-4"
            class="md:hidden bg-[#F37032] border-b border-[#e05f24] px-4 pt-3 pb-6 space-y-3 text-white"
        >
            <a href="{{ url('/') }}" @click="mobileMenuOpen = false" class="block px-4 py-3 rounded-xl text-base font-bold transition-colors {{ request()->is('/') || request()->is('events') ? 'bg-white/20 text-white border border-white/30' : 'text-white/90 hover:bg-white/10' }}">
                Katalog Event
            </a>

            @auth
                <a href="{{ route('my-tickets.index') }}" @click="mobileMenuOpen = false" class="block px-4 py-3 rounded-xl text-base font-bold transition-colors {{ request()->is('my-tickets*') ? 'bg-white/20 text-white border border-white/30' : 'text-white/90 hover:bg-white/10' }}">
                    Tiket Saya
                </a>

                @if(Auth::user()->hasAnyRole(['Super Admin', 'Admin']))
                    <a href="{{ url('/admin') }}" class="block px-4 py-3 rounded-xl text-base font-bold bg-white/20 text-white border border-white/30">
                        ⚡ Buka Admin Panel
                    </a>
                @endif

                <div class="pt-3 border-t border-white/20 flex items-center justify-between px-2">
                    <span class="text-xs text-white/80">Akun: <strong class="text-white font-extrabold">{{ Auth::user()->name }}</strong></span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-900/80 text-white">
                            Keluar
                        </button>
                    </form>
                </div>
            @else
                <div class="pt-2">
                    <a href="{{ route('login') }}" class="block w-full py-3 rounded-xl text-center text-base font-bold bg-slate-900 text-white shadow-lg">
                        Masuk / Daftar Akun
                    </a>
                </div>
            @endauth
        </div>
    </header>

    @auth
        @php
            $activePendingOrder = \App\Models\Order::where('user_id', Auth::id())
                ->where('status', 'pending_payment')
                ->latest()
                ->first();
        @endphp

        @if($activePendingOrder)
            <div class="bg-orange-50 border-b border-[#F37032]/30 py-2.5 px-4 text-xs">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left">
                    <div class="flex items-center gap-2 text-[#F37032] font-semibold">
                        <span class="w-2 h-2 rounded-full bg-[#F37032] animate-ping shrink-0"></span>
                        <span>Anda memiliki pesanan aktif <strong class="font-mono text-slate-900">#{{ $activePendingOrder->order_code }}</strong> yang belum selesai.</span>
                    </div>
                    <a href="{{ route('checkout.instructions', $activePendingOrder->order_code) }}" class="px-3.5 py-1 rounded-lg bg-[#F37032] hover:bg-[#e05f24] text-white font-bold text-[11px] transition-all shrink-0 shadow-md">
                        Lanjutkan Pembayaran / Upload Struk →
                    </a>
                </div>
            </div>
        @endif
    @endauth

    <!-- Main Content Area -->
    <main class="flex-grow">
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 mt-6">
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center justify-between shadow-sm">
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 mt-6">
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm flex items-center justify-between shadow-sm">
                    <span class="font-semibold">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Footer Area -->
    <footer class="border-t border-slate-200 bg-white mt-auto py-4 pb-20 lg:pb-4 shrink-0 shadow-inner">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-slate-600 text-xs">
            <p>&copy; {{ date('Y') }} <strong class="text-slate-900">Yayasan Pendidikan Nanyang Indonesia</strong>. All Rights Reserved.</p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
