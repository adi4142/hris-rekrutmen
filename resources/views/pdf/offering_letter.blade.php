<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Offering Letter - {{ $application->jobApplicant->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 13px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 30px;
        }
        .header {
            border-bottom: 2px solid #444;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-logo {
            float: left;
            width: 80px;
            height: 80px;
            background-color: #eee;
            text-align: center;
            line-height: 80px;
            font-weight: bold;
            color: #888;
            border-radius: 8px;
        }
        .company-info {
            margin-left: 100px;
        }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0;
            text-transform: uppercase;
        }
        .company-details {
            font-size: 11px;
            color: #7f8c8d;
            margin: 5px 0 0 0;
        }
        .letter-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 20px;
            margin-bottom: 5px;
        }
        .letter-no {
            text-align: center;
            font-size: 12px;
            margin-bottom: 30px;
        }
        .letter-date {
            text-align: right;
            margin-bottom: 20px;
        }
        .receiver-info {
            margin-bottom: 30px;
        }
        .receiver-info p {
            margin: 2px 0;
        }
        .content {
            text-align: justify;
            margin-bottom: 20px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .details-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .details-table td.label {
            width: 30%;
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .signature-section {
            margin-top: 50px;
            width: 100%;
        }
        .signature-box {
            float: right;
            width: 250px;
            text-align: center;
        }
        .signature-img {
            height: 80px;
            margin-bottom: 10px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 2px;
        }
        .signature-title {
            font-size: 11px;
            color: #7f8c8d;
        }
        .footer {
            position: fixed;
            bottom: 30px;
            left: 30px;
            right: 30px;
            font-size: 10px;
            color: #bdc3c7;
            text-align: center;
            border-top: 1px solid #ecf0f1;
            padding-top: 10px;
        }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-logo">
            <!-- logo placeholder -->
            LOGO
        </div>
        <div class="company-info">
            <h1 class="company-name">PT Vneu Teknologi Indonesia</h1>
            <p class="company-details">
                Jl. Kebayoran Lama No. 123, Jakarta Selatan<br>
                Telp: 021-30015000 | Email: hrd@vneu.co.id<br>
                Website: www.vneu.co.id
            </p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="letter-title">OFFERING LETTER</div>
    <div class="letter-no">Nomor: {{ $application->offering_letter_no }}</div>

    <div class="letter-date">Jakarta, {{ now()->translatedFormat('d F Y') }}</div>

    <div class="receiver-info">
        <p>Kepada Yth,</p>
        <p><strong>{{ $application->jobApplicant->name }}</strong></p>
        <p>{{ $application->jobApplicant->email }}</p>
        <p>{{ $application->jobApplicant->phone ?? '-' }}</p>
    </div>

    <div class="content">
        <p>Perihal: <strong>Penawaran Hubungan Kerja</strong></p>
        
        <p>Dengan hormat,</p>
        
        <p>Berdasarkan hasil seleksi dan wawancara yang telah Anda lalui, kami dengan senang hati menyampaikan penawaran kerja (Offering Letter) kepada Anda untuk bergabung dengan <strong>PT Vneu Teknologi Indonesia</strong>.</p>
        
        <p>Berikut adalah rincian penawaran kami:</p>
        
        <table class="details-table">
            <tr>
                <td class="label">Posisi</td>
                <td>{{ $application->jobVacancie->title }}</td>
            </tr>
            <tr>
                <td class="label">Departemen</td>
                <td>{{ $application->jobVacancie->departement->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Mulai</td>
                <td>{{ \Carbon\Carbon::parse($application->offering_start_date)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Nominal Gaji</td>
                <td>Rp {{ number_format($application->offering_salary, 0, ',', '.') }} per bulan</td>
            </tr>
            <tr>
                <td class="label">Jam Kerja</td>
                <td>{{ $application->offering_working_hours }}</td>
            </tr>
            <tr>
                <td class="label">Jatah Cuti</td>
                <td>{{ $application->offering_leave_quota }}</td>
            </tr>
            <tr>
                <td class="label">Deskripsi Pekerjaan</td>
                <td>{!! nl2br(e($application->offering_job_desc)) !!}</td>
            </tr>
        </table>

        <p>Penawaran ini bersifat rahasia dan berlaku hingga <strong>{{ now()->addDays(7)->translatedFormat('d F Y') }}</strong>. Mohon memberikan konfirmasi penerimaan atau penolakan paling lambat pada tanggal tersebut.</p>
        
        <p>Demikian penawaran ini kami sampaikan. Besar harapan kami Anda dapat bergabung dan berkontribusi di perusahaan kami.</p>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p>Hormat Kami,</p>
            <p><strong>PT Vneu Teknologi Indonesia</strong></p>
            <div class="signature-img">
                @if(isset($settings['hr_signature_path']) && $settings['hr_signature_path'])
                    <img src="{{ public_path($settings['hr_signature_path']) }}" style="height: 100%;">
                @else
                    <div style="height: 60px;"></div>
                @endif
            </div>
            <p class="signature-name">{{ $settings['hr_name'] ?? 'HR Manager' }}</p>
            <p class="signature-title">{{ $settings['hr_position'] ?? 'HR Department' }}</p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} PT Vneu Teknologi Indonesia.
    </div>
</body>
</html>
