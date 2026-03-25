<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi Kampanye Baru - {{ $donation->trx_id }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .message {
            color: #4b5563;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .donation-card {
            background-color: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .donation-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .donation-row:last-child {
            border-bottom: none;
        }
        .donation-label {
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
        }
        .donation-label:after {
            content: ":";
        }
        .donation-value {
            color: #1f2937;
            font-size: 14px;
            font-weight: 600;
            text-align: right;
        }
        .amount {
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
        }
        .status-badge {
            display: inline-block;
            background-color: #fef3c7;
            color: #92400e;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
            color: white;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: bold;
            margin: 5px;
        }
        .button-approve {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .button-reject {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        .footer {
            background-color: #f3f4f6;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 13px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-wakaf_pembangunan { background-color: #fef3c7; color: #92400e; }
        .badge-wakaf_produktif { background-color: #ccfbf1; color: #115e59; }
        .badge-donasi_pendidikan { background-color: #dbeafe; color: #1e40af; }
        .campaign-info {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border: 2px solid #86efac;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .campaign-info-title {
            color: #166534;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .campaign-info-link {
            color: #15803d;
            font-size: 14px;
            text-decoration: none;
        }
        .campaign-info-link:hover {
            text-decoration: underline;
        }
        .proof-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-top: 10px;
            border: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <h1>🔔 Donasi Kampanye Baru Masuk!</h1>
            <p>Ada donasi untuk kampanye yang menunggu verifikasi</p>
        </div>

        {{-- Content --}}
        <div class="content">
            <div class="greeting">Assalamu'alaikum Warahmatullahi Wabarakatuh,</div>

            <div class="message">
                Alhamdulillah, ada donasi baru untuk kampanye di website <strong>{{ config('app.name') }}</strong>.
                Donasi ini masih dalam status <span class="status-badge">Pending</span> dan menunggu verifikasi dari admin.
            </div>

            {{-- Campaign Info --}}
            <div class="campaign-info">
                <div class="campaign-info-title">📢 Kampanye: {{ $donation->campaign->title ?? 'N/A' }}</div>
                <a href="{{ route('campaigns.detail', $donation->campaign->slug ?? '#') }}" class="campaign-info-link">
                    Lihat Detail Kampanye →
                </a>
            </div>

            {{-- Donation Details Card --}}
            <div class="donation-card">
                <div class="donation-row">
                    <span class="donation-label">Kode Transaksi</span>
                    <span class="donation-value" style="font-family: monospace;">{{ $donation->trx_id }}</span>
                </div>

                <div class="donation-row">
                    <span class="donation-label">Nama Donatur</span>
                    <span class="donation-value"> : 
                        {{ $donation->donatur_name }}
                        @if($donation->is_anonymous)
                            <span class="badge" style="background-color: #e5e7eb; color: #374151; margin-left: 8px;">Anonim</span>
                        @endif
                    </span>
                </div>

                <div class="donation-row">
                    <span class="donation-label">Nomor WhatsApp</span>
                    <span class="donation-value"> : {{ $donation->phone ?? '-' }}</span>
                </div>

                <div class="donation-row">
                    <span class="donation-label">Bank Pembayaran</span>
                    <span class="donation-value"> : {{ $donation->paymentMethod->bank_name ?? '-' }}</span>
                </div>

                <div class="amount">
                    Rp {{ number_format($donation->amount, 0, ',', '.') }}
                </div>

                <div class="donation-row">
                    <span class="donation-label">Tanggal</span>
                    <span class="donation-value">{{ $donation->created_at->format('d F Y, H:i') }} WIB</span>
                </div>

                @if($donation->proof_of_transfer)
                <div style="margin-top: 20px;">
                    <span class="donation-label" style="display: block; margin-bottom: 10px;">Bukti Transfer:</span>
                    <img src="{{ asset('storage/' . $donation->proof_of_transfer) }}" alt="Bukti Transfer" class="proof-image">
                </div>
                @endif
            </div>

            <p style="color: #4b5563; line-height: 1.6; margin-bottom: 25px;">
                Silahkan segera verifikasi donasi ini dengan memeriksa bukti transfer yang telah diupload oleh donatur.
                Anda dapat menyetujui atau menolak donasi ini.
            </p>

            {{-- Action Buttons --}}
            <div class="button-container">
                <a href="{{ route('admin.campaign-donation') }}" class="button button-approve">
                    ✓ Verifikasi Donasi
                </a>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p style="margin: 0 0 10px 0;">
                <strong>{{ config('app.name') }}</strong>
            </p>
            <p style="margin: 0;">
                Email notifikasi otomatis dari sistem. Jangan membalas email ini.
            </p>
        </div>
    </div>
</body>
</html>
