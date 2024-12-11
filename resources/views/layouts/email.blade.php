<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab CBI Sample Progress Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 20px;
        }

        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #333333;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .content-table td {
            padding: 8px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            width: 140px;
            color: #555555;
        }

        .separator {
            width: 20px;
            text-align: center;
            color: #555555;
        }

        .value {
            color: #333333;
        }

        .progress-status {
            background-color: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            border-left: 4px solid #0066cc;
        }

        .tracking-info {
            background-color: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .link {
            color: #0066cc;
            text-decoration: none;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
            font-style: italic;
            color: #666666;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ $message->embed(public_path('images/logocorp.png')) }}"
                alt="Citra Borneo Indah Group"
                class="logo">
        </div>

        <div class="greeting">
            <p>Selamat Pagi</p>
            <p>Yth. Pelanggan Setia Lab CBI,</p>
            <p>Progress Sampel anda telah Terupdate dengan:</p>
        </div>

        <table class="content-table">
            <tr>
                <td class="label">No. Surat</td>
                <td class="separator">:</td>
                <td class="value">{{$nomor_surat ?? '-'}}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Registrasi</td>
                <td class="separator">:</td>
                <td class="value">{{$tanggal_registrasi ?? '-'}}</td>
            </tr>
            <tr>
                <td class="label">Estimasi KUPA</td>
                <td class="separator">:</td>
                <td class="value">{{$estimasi_kup ?? '-'}}</td>
            </tr>
            <tr>
                <td class="label">Departemen</td>
                <td class="separator">:</td>
                <td class="value">{{$departement ?? '-'}}</td>
            </tr>
            <tr>
                <td class="label">Jenis Sampel</td>
                <td class="separator">:</td>
                <td class="value">{{$jenis_sampel ?? '-'}}</td>
            </tr>
            <tr>
                <td class="label">Jumlah Sampel</td>
                <td class="separator">:</td>
                <td class="value">{{$jumlah_sampel ?? '-'}}</td>
            </tr>
        </table>

        <div class="progress-status">
            Progress saat ini: <strong>{{$progress ?? '-'}}</strong>
        </div>

        <div class="tracking-info">
            <p>Progress anda dapat dilihat di website:
                <a href="https://smartlab.srs-ssms.com/" class="link">https://smartlab.srs-ssms.com/</a>
            </p>
            <p>Dengan kode tracking sampel: <strong>{{$kode_tracking_sampel ?? '-'}}</strong></p>
        </div>

        <div class="footer">
            Terima kasih telah mempercayakan sampel anda untuk dianalisa di Lab kami.
        </div>
    </div>
</body>

</html>