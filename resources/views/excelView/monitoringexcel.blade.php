<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Export</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>


    <table style="border: 1px solid black;">

        <thead>

            <tr>
                <td></td>
                <td rowspan="4" colspan="2" style="border-left:1px solid black;"></td>
                <td colspan="16" style="border-left:1px solid black;border-right: 1px solid black;height: 40px;font-weight:bold;font-size:14px">
                    PT . CITRA BORNEO INDAH</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="16" style="border-left:1px solid black;border-right: 1px solid black;height: 40px;font-size:14px;font-weight:bold">
                    RESEARCH AND DEVELOPMENT - LABORATORIUM ANALITIK</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="16" style="border: 1px solid black;">Formulir</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="16" style="border: 1px solid black;">MONITORING PENERIMAAN SAMPEL

                </td>
            </tr>
            <tr>
                <th></th>
                <th colspan="2" style="border: 1px solid black;font-weight:bold">
                    No.Dokumen
                </th>
                <th colspan="5" style="border: 1px solid black;text-align:center;">
                    Revisi
                </th>
                <th colspan="6" style="border: 1px solid black;text-align:center;">
                    Berlaku Efektif
                </th>
                <th colspan="5" style="border: 1px solid black;text-align:center;">
                    Halaman
                </th>
            </tr>
            <tr>
                <th></th>
                <th colspan="3" style="border: 1px solid black;">
                    FR.7.1-12
                </th>
                <th colspan="5" style="border: 1px solid black;text-align:center;">
                    02
                </th>
                <th colspan="5" style="border: 1px solid black;text-align:center;">
                    1-jul-21
                </th>
                <th colspan="5" style="border: 1px solid black;text-align:center;">
                    1 dari 1
                </th>
            </tr>
            <tr>
                <td colspan="16"></td> <!-- Replace "18" with the total number of columns in your table -->
            </tr>
            <tr>
                <td colspan="16"></td> <!-- Replace "20" with the total number of columns in your table -->
            </tr>
            <tr>
                <th></th>
                <th colspan="2" style="border-left:1px solid black;border-top:1px solid black ;">
                    Tanggal Penerimaan
                </th>
                <th style="border-top:1px solid black;">
                    :
                </th>

                <th colspan="12" style="border-top: 1px solid black;"></th>
                <th colspan="2" style="border-top: 1px solid black;">Jenis Sampel</th>
                <th style="border-top: 1px solid black;border-right: 1px solid black;">:</th>
            </tr>
            <tr>
                <th></th>
                <th colspan="2" style="border-left: 1px solid black;border-bottom: 1px solid black;">
                    No. Kaji Ulang
                </th>
                <th style="border-bottom: 1px solid black;">
                    :
                </th>

                <th colspan="12" style="border-bottom: 1px solid black;"></th>
                <th colspan="2" style="border-bottom: 1px solid black;">Nama Pelanggan</th>
                <th style="border-bottom: 1px solid black;border-right:1px solid black">:</th>
            </tr>


            <tr>
                <th></th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    No
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Tanggal Penerimaan
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Jenis Sampel
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Asal Sampel (Internal/Eskternal)
                </th>
                <th colspan="3" style="border: 1px solid black;text-align:center;">
                    Pelanggan
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    No. KUPA
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Kode sampel
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Jumlah Parameter
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Jumlah Sampel
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Parameter Analisa
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Biaya Analisa
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Sub Total (Rp)
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Estimasi Tanggal Penyelesaian Analisa
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Tanggal Rilis Sertifikat
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    No. Sertifikat Hasil Analisa
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Tanggal Pengiriman Sertifikat
                </th>
            </tr>
            <tr>
                <th></th>
                <th style="border: 1px solid black;text-align:center;">
                    No.Memo Pengantar Sampel
                </th>
                <th style="border: 1px solid black;text-align:center;">
                    Nama Pengirim
                </th>
                <th style="border: 1px solid black;text-align:center;">
                    Departemen
                </th>
            </tr>

        </thead>
        <tbody>

            @foreach ($data as $items)
            <tr>
                <td></td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{ $items['no'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{ $items['tgl_trma'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{ $items['jenis_sample'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{ $items['asal_sampel'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{ $items['memo_pengantar'] }}
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{ $items['nama_pengirim'] }}
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{ $items['departemen'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{ $items['nomor_kupa'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{ $items['kode_sampel'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{ $items['jumlah_parameter'] }}
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{ $items['jumlah_sampel'] }}
                </td>

                <td style="border-left: 1px solid black;border-right:1px solid black;">{{ $items['parameter_analisis']
                    }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{ $items['harga_normal'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{
                    $items['sub_total_per_parameter'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{ $items['estimasi'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{ $items['tanggal_serif'] }}
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{ $items['no_serif'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{ $items['tanggal_kirim_sertif']
                    }}</td>
            </tr>

            @endforeach

            <tr>
                <td></td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $total['no'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $total['tgl_trma'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $total['jenis_sample'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $total['asal_sampel'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $total['memo_pengantar'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $total['nama_pengirim'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $total['departemen'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $total['nomor_kupa'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $total['kode_sampel'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $total['jumlah_parameter'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $total['jumlah_sampel'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $total['parameter_analisis'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $total['harga_normal'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $total['estimasi'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $total['tanggal_serif'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $total['no_serif'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $total['tanggal_kirim_sertif'] }}</td>
            </tr>
            <tr>
                <td></td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalppn['no'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalppn['tgl_trma'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalppn['jenis_sample'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalppn['asal_sampel'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalppn['memo_pengantar'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalppn['nama_pengirim'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalppn['departemen'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalppn['nomor_kupa'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalppn['kode_sampel'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalppn['jumlah_parameter'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalppn['jumlah_sampel'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalppn['parameter_analisis'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalppn['harga_normal'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalppn['estimasi'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalppn['tanggal_serif'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalppn['no_serif'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalppn['tanggal_kirim_sertif'] }}</td>
            </tr>
            <tr>
                <td></td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $diskon['no'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $diskon['tgl_trma'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $diskon['jenis_sample'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $diskon['asal_sampel'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $diskon['memo_pengantar'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $diskon['nama_pengirim'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $diskon['departemen'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $diskon['nomor_kupa'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $diskon['kode_sampel'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $diskon['jumlah_parameter'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $diskon['jumlah_sampel'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $diskon['parameter_analisis'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $diskon['harga_normal'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $diskon['estimasi'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $diskon['tanggal_serif'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $diskon['no_serif'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $diskon['tanggal_kirim_sertif'] }}</td>
            </tr>
            <tr>
                <td></td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalfinal['no'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalfinal['tgl_trma'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalfinal['jenis_sample'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalfinal['asal_sampel'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalfinal['memo_pengantar'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalfinal['nama_pengirim'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalfinal['departemen'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalfinal['nomor_kupa'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalfinal['kode_sampel'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalfinal['jumlah_parameter'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalfinal['jumlah_sampel'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalfinal['parameter_analisis'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalfinal['harga_normal'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalfinal['estimasi'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalfinal['tanggal_serif'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalfinal['no_serif'] }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">{{
                    $totalfinal['tanggal_kirim_sertif'] }}</td>
            </tr>
        </tbody>
    </table>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>