<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        table {
            margin-top: 10px;
            margin-bottom: 20px;
        }



        a {
            color: #0066cc;
            text-decoration: none;
        }

        .italic {
            font-style: italic;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td colspan="3">Selamat Pagi</td>
        </tr>
        <tr>
            <td colspan="3">Yth. Pelanggan Setia Lab CBI,</td>
        </tr>
        <tr>
            <td colspan="3">Progress Sampel anda telah Terupdate dengan:</td>
        </tr>
        <tr>
            <td class="bold">No. Surat</td>
            <td>:</td>
            <td>{{$nomor_surat ?? '-'}}</td>
        </tr>
        <tr>
            <td class="bold">Departemen</td>
            <td>:</td>
            <td>{{$departement ?? '-'}}</td>
        </tr>
        <tr>
            <td class="bold">Jenis Sampel</td>
            <td>:</td>
            <td>{{$jenis_sampel ?? '-'}}</td>
        </tr>
        <tr>
            <td class="bold">Jumlah Sampel</td>
            <td>:</td>
            <td>{{$jumlah_sampel ?? '-'}}</td>
        </tr>
        <tr>
            <td colspan="3">Progress saat ini: <span class="bold">{{$progress ?? '-'}}</span></td>
        </tr>
        <tr>
            <td colspan="3">
                Progress anda dapat dilihat di website:<a href="https://smartlab.srs-ssms.com/">https://smartlab.srs-ssms.com/</a>

            </td>
        </tr>
        <tr>
            <td colspan="3">Dengan kode tracking sampel: <span class="bold">{{$kode_tracking_sampel ?? '-'}}</span></td>
        </tr>
        <tr>
            <td colspan="3"><span class="italic">Terima kasih telah mempercayakan sampel anda untuk dianalisa di Lab kami.</span></td>
        </tr>
    </table>
    <p></p>
</body>

</html>