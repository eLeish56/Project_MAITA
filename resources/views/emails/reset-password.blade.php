<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            width: 100%;
            background-color: #f5f5f5;
            padding: 20px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #366092 0%, #2d4d77 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .logo {
            display: inline-block;
            margin-bottom: 10px;
            font-size: 32px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #333;
        }
        .greeting strong {
            color: #366092;
        }
        .message {
            font-size: 14px;
            color: #555;
            margin-bottom: 25px;
            line-height: 1.8;
        }
        .message p {
            margin: 12px 0;
        }
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 40px;
            background: linear-gradient(135deg, #366092 0%, #2d4d77 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 15px;
            transition: background 0.3s ease;
        }
        .button:hover {
            background: linear-gradient(135deg, #2d4d77 0%, #1f3a57 100%);
        }
        .info-box {
            background-color: #f0f4f8;
            border-left: 4px solid #366092;
            padding: 15px;
            margin: 25px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #555;
        }
        .info-box strong {
            display: block;
            margin-bottom: 5px;
            color: #366092;
        }
        .link-text {
            word-break: break-all;
            color: #366092;
            text-decoration: none;
            font-size: 12px;
            font-family: monospace;
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 4px;
            display: block;
            margin: 15px 0;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
            font-size: 13px;
            color: #856404;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 25px 30px;
            border-top: 1px solid #e9ecef;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        .footer-content {
            margin-bottom: 15px;
            line-height: 1.7;
        }
        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 15px 0;
        }
        .signature {
            font-weight: 600;
            color: #366092;
            margin-top: 10px;
        }
        .contact-info {
            font-size: 11px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <div class="logo">🔐</div>
                <h1>Permintaan Reset Password</h1>
                <p>SMK Muhammadiyah 1 Palembang</p>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="greeting">
                    Halo <strong>{{ $notifiable->name }}</strong>,
                </div>

                <div class="message">
                    <p>Kami menerima permintaan untuk mereset password akun Anda pada sistem Teaching Factory SMK Muhammadiyah 1 Palembang.</p>
                    
                    <p>Untuk melanjutkan proses reset password, silakan klik tombol di bawah ini:</p>
                </div>

                <!-- Button -->
                <div class="button-container">
                    <a href="{{ route('password.reset', $token) }}" class="button">
                        Reset Password
                    </a>
                </div>

                <!-- Alternative Link -->
                <div class="info-box">
                    <strong>Jika tombol di atas tidak berfungsi, salin dan paste link berikut:</strong>
                    <span class="link-text">{{ route('password.reset', $token) }}</span>
                </div>

                <!-- Warning -->
                <div class="warning">
                    <strong>⚠️ Perhatian Keamanan:</strong>
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        <li>Link ini hanya berlaku untuk <strong>60 menit</strong> ke depan</li>
                        <li>Jangan bagikan link ini kepada siapa pun</li>
                        <li>Jika Anda tidak melakukan permintaan ini, abaikan email ini atau hubungi administrator</li>
                    </ul>
                </div>

                <div class="message">
                    <p>Pertanyaan? Hubungi tim support kami untuk bantuan lebih lanjut.</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-content">
                    <div class="signature">
                        Dengan Hormat,<br>
                        Tim Layanan Pelanggan
                    </div>
                    <div class="divider"></div>
                    <strong>SMK Muhammadiyah 1 Palembang</strong><br>
                    Jl. Balayudha, RT.16/RW.4, Ario Kemuning<br>
                    Kec. Kemuning, Kota Palembang<br>
                    Sumatera Selatan 30128
                </div>
                <div class="contact-info">
                    📞 Telepon: (0711) 414662<br>
                    📧 Email: {{ config('mail.from.address') }}<br>
                    🌐 Website: {{ config('app.url') }}
                </div>
                <div class="divider"></div>
                <p style="margin: 10px 0 0 0; color: #999; font-size: 11px;">
                    Email ini dikirim secara otomatis. Jangan balas email ini.<br>
                    © {{ date('Y') }} SMK Muhammadiyah 1 Palembang. Semua hak dilindungi.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
