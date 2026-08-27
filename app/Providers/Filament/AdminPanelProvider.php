<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->favicon(asset('img/LogoNanyangIcon.png') . '?v=3')
            ->brandLogo(fn () => asset('img/Nanyang Logo with text white (1).png'))
            ->brandLogoHeight('4rem')
            ->maxContentWidth('full')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->navigationItems([
                NavigationItem::make('Lihat Situs Utama')
                    ->url('/')
                    ->icon('heroicon-o-arrow-left-start-on-rectangle')
                    ->sort(-1),
                NavigationItem::make('Scanner QR Tiket')
                    ->url('/scan-ticket')
                    ->icon('heroicon-o-qr-code')
                    ->sort(100),
                NavigationItem::make('Denah & Kehadiran Kursi')
                    ->url('/admin/seat-attendance')
                    ->icon('heroicon-o-user-group')
                    ->sort(101),
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Lihat Situs Utama')
                    ->url('/')
                    ->icon('heroicon-o-home'),
                MenuItem::make()
                    ->label('Scanner QR Tiket')
                    ->url('/scan-ticket')
                    ->icon('heroicon-o-qr-code'),
                MenuItem::make()
                    ->label('Denah & Kehadiran Kursi')
                    ->url('/admin/seat-attendance')
                    ->icon('heroicon-o-user-group'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
