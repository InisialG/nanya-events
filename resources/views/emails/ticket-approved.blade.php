<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket Resmi Nanya Events</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .wrapper {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }

        .header {
            background-color: #F37032;
            padding: 30px 24px;
            text-align: center;
            color: #ffffff;
        }

        .header img {
            max-height: 48px;
            margin-bottom: 12px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .content {
            padding: 32px 24px;
        }

        .badge-success {
            display: inline-block;
            background-color: #dcfce7;
            color: #15803d;
            font-weight: 700;
            font-size: 12px;
            padding: 6px 14px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
        }

        .event-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .event-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 12px;
        }

        .meta-grid {
            display: table;
            width: 100%;
        }

        .meta-row {
            display: table-row;
        }

        .meta-cell {
            display: table-cell;
            padding-bottom: 8px;
            font-size: 13px;
            color: #475569;
        }

        .meta-cell strong {
            color: #0f172a;
        }

        .ticket-list {
            margin-bottom: 28px;
        }

        .ticket-item {
            background-color: #fff7ed;
            border: 2px dashed #fdba74;
            border-radius: 16px;
            padding: 18px;
            margin-bottom: 14px;
        }

        .seat-code {
            font-size: 24px;
            font-weight: 900;
            color: #F37032;
            margin: 0;
        }

        .btn-cta {
            display: block;
            width: 100%;
            max-width: 280px;
            margin: 28px auto 12px;
            background-color: #F37032;
            color: #ffffff !important;
            text-align: center;
            text-decoration: none;
            font-weight: 800;
            font-size: 14px;
            padding: 16px 24px;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(243, 112, 50, 0.3);
        }

        .footer {
            background-color: #f8fafc;
            padding: 20px 24px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="content">

            <h3
                style="font-size: 14px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; text-align: center;">
                Daftar E-Tiket Anda ({{ $order->tickets->count() }} Tiket)
            </h3>

            <div class="ticket-list" style="margin-bottom: 30px;">
                @foreach($order->tickets as $ticket)
                    <!-- Ticket Card Mimicking Web View -->
                    <div
                        style="background-color: #ffffff; border: 2px solid #F37032; max-width: 100%; margin: 0 auto 24px auto; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">

                        <!-- Header Banner with Logo -->
                        <div style="background-color: #F37032; padding: 14px 16px; text-align: center;">
                            <img src="https://events.nanyang.sch.id/img/logo-nanyang-white.png" alt="Nanyang Zhi Hui"
                                style="height: 50px; width: auto; margin: 0 auto; display: block;">
                        </div>

                        <!-- Card Body Info -->
                        <div style="padding: 24px;">
                            <div
                                style="padding-bottom: 12px; border-bottom: 1px solid #f1f5f9; text-align: center; margin-bottom: 20px;">
                                <h2 style="font-weight: 900; font-size: 20px; color: #0f172a; margin: 0; line-height: 1.2;">
                                    {{ $ticket->order->eventSession->event->title }}</h2>
                            </div>

                            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                                <tr>
                                    <td
                                        style="width: 48%; padding: 14px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; vertical-align: top;">
                                        <span
                                            style="font-size: 9px; color: #94a3b8; text-transform: uppercase; font-weight: 700; display: block;">Venue
                                            / Lokasi</span>
                                        <span
                                            style="font-weight: 800; color: #0f172a; font-size: 13px; display: block; margin-top: 4px;">{{ $ticket->order->eventSession->event->venue->name }}</span>
                                    </td>
                                    <td style="width: 4%;"></td>
                                    <td
                                        style="width: 48%; padding: 14px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; vertical-align: top;">
                                        <span
                                            style="font-size: 9px; color: #94a3b8; text-transform: uppercase; font-weight: 700; display: block;">Tanggal
                                            & Jam</span>
                                        <span
                                            style="font-weight: 800; color: #0f172a; font-size: 13px; display: block; margin-top: 4px;">{{ \Carbon\Carbon::parse($ticket->order->eventSession->session_date)->translatedFormat('d M Y') }}</span>
                                        <span
                                            style="font-size: 11px; color: #F37032; font-weight: 900; display: block; margin-top: 2px;">Jam
                                            {{ \Carbon\Carbon::parse($ticket->order->eventSession->start_time)->format('H:i') }}
                                            WIB</span>
                                    </td>
                                </tr>
                            </table>

                            <table
                                style="width: 100%; border-collapse: collapse; background-color: #fff7ed; border: 1px solid #fed7aa; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 16px;">
                                        <span
                                            style="font-size: 10px; color: #94a3b8; text-transform: uppercase; font-weight: 700; display: block;">Nomor
                                            Kursi</span>
                                        <span
                                            style="font-weight: 900; font-size: 28px; color: #F37032; display: block; margin-top: 2px;">{{ $ticket->seatAvailability->seatMaster->seat_code }}</span>
                                    </td>
                                    <td style="padding: 16px; text-align: right; vertical-align: top;">
                                        <span
                                            style="font-size: 10px; color: #94a3b8; text-transform: uppercase; font-weight: 700; display: block;">Kategori
                                            Kursi</span>
                                        <span
                                            style="font-weight: 800; font-size: 13px; color: #0f172a; display: inline-block; padding: 4px 10px; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 4px; margin-top: 6px;">{{ $ticket->seatAvailability->seatMaster->seatCategory?->name ?? 'Reguler' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Dashed Line -->
                        <div style="border-bottom: 2px dashed #cbd5e1; margin: 4px 24px;"></div>

                        <!-- QR Code Section -->
                        <div style="padding: 24px; background-color: #ffffff; text-align: center;">
                            <div
                                style="width: 180px; height: 180px; margin: 0 auto 16px auto; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; background-color: #ffffff;">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $ticket->qr_code_hash }}"
                                    alt="QR Code E-Tiket"
                                    style="width: 100%; height: 100%; object-fit: contain; display: block;">
                            </div>
                            <div>
                                <span
                                    style="font-family: monospace; font-size: 16px; color: #1e293b; font-weight: 900; letter-spacing: 1px; display: block;">{{ $ticket->ticket_code }}</span>
                                <span
                                    style="font-size: 10px; color: #94a3b8; font-weight: 600; display: block; margin-top: 6px;">Tunjukkan
                                    QR Code ini kepada petugas di pintu masuk venue.</span>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            style="background-color: #f8fafc; padding: 12px; border-top: 1px solid #e2e8f0; text-align: center;">
                            <span style="font-size: 11px; color: #64748b; font-weight: 600;">Pemilik Tiket: <strong
                                    style="color: #0f172a; font-weight: 700;">{{ $order->user->name }}</strong></span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div
                style="text-align: center; background-color: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <p style="margin: 0 0 4px; font-size: 13px; font-weight: 700; color: #0f172a;">💡 Petunjuk Masuk Venue:
                </p>
                <p style="margin: 0; font-size: 12px; color: #64748b;">
                    Tunjukkan QR Code E-Tiket dari HP Anda di pintu masuk venue saat hari-H pertunjukan untuk divalidasi
                    petugas scanner.
                </p>
            </div>

            <a href="{{ url('/my-tickets') }}" class="btn-cta">
                🎫 Buka & Cetak E-Tiket Saya →
            </a>
        </div>

        <div class="footer">
            &copy; 2026 <strong>Yayasan Pendidikan Nanyang Indonesia</strong>. All Rights Reserved.<br>
            Pesan ini dikirim otomatis oleh sistem Nanya Events.
        </div>
    </div>
</body>

</html>