<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monotoring Export</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>


    <table style="border: 1px solid black;">

        <thead>
            <tr>
                <td rowspan="4" colspan="2" style="border-left:1px solid black;"></td>
                <td colspan="15" style="border-left:1px solid black;border-right: 1px solid black;height: 40px;font-weight:bold;font-size:14px">
                    PT . CITRA BORNEO INDAH</td>
            </tr>
            <tr>
                <td colspan="15" style="border-left:1px solid black;border-right: 1px solid black;height: 40px;font-size:14px;font-weight:bold">
                    RESEARCH AND DEVELOPMENT - LABORATORIUM ANALITIK</td>
            </tr>
            <tr>

                <td colspan="15" style="border: 1px solid black;">Formulir</td>
            </tr>
            <tr>

                <td colspan="15" style="border: 1px solid black;">MONITORING PENERIMAAN SAMPEL

                </td>
            </tr>
            <tr>
                <th colspan="2" style="border: 1px solid black;font-weight:bold">
                    No.Dokumen
                </th>
                <th colspan="5" style="border: 1px solid black;text-align:center;">
                    Revisi
                </th>
                <th colspan="5" style="border: 1px solid black;text-align:center;">
                    Berlaku Efektif
                </th>
                <th colspan="5" style="border: 1px solid black;text-align:center;">
                    Halaman
                </th>
            </tr>
            <tr>
                <th colspan="2" style="border: 1px solid black;">
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
                <th colspan="2" style="border-left:1px solid black;border-top:1px solid black ;">
                    Tanggal Penerimaan
                </th>
                <th style="border-top:1px solid black;">
                    :
                </th>

                <th colspan="11" style="border-top: 1px solid black;"></th>
                <th colspan="2" style="border-top: 1px solid black;">Jenis Sampel</th>
                <th style="border-top: 1px solid black;border-right: 1px solid black;">:</th>
            </tr>
            <tr>
                <th colspan="2" style="border-left: 1px solid black;border-bottom: 1px solid black;">
                    No. Kaji Ulang
                </th>
                <th style="border-bottom: 1px solid black;">
                    :
                </th>

                <th colspan="11" style="border-bottom: 1px solid black;"></th>
                <th colspan="2" style="border-bottom: 1px solid black;">Nama Pelanggan</th>
                <th style="border-bottom: 1px solid black;border-right:1px solid black">:</th>
            </tr>


            <tr>
                <th rowspan="2" style="border: 1px solid black;">
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
                    Biaya Analisa + ppn 11% (Rp)
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
                <td>{{ $items['no'] }}</td>
                <td>{{ $items['tgl_trma'] }}</td>
                <td>{{ $items['jenis_sample'] }}</td>
                <td>{{ $items['asal_sampel'] }}</td>
                <td>{{ $items['memo_pengantar'] }}</td>
                <td>{{ $items['nama_pengirim'] }}</td>
                <td>{{ $items['departemen'] }}</td>
                <td>{{ $items['nomor_kupa'] }}</td>
                <td>{{ $items['kode_sampel'] }}</td>
                <td>{{ $items['jumlah_parameter'] }}</td>
                <td>{{ $items['jumlah_sampel'] }}</td>

                <td>{{ $items['parameter_anal'] }}</td>
                <td>{{ $items['harga_normal'] }}</td>
                <td>{{ $items['estimasi'] }}</td>
                <td>{{ $items['tanggal_serif'] }}</td>
                <td>{{ $items['no_serif'] }}</td>
                <td>{{ $items['tanggal_kirimserif'] }}</td>
            </tr>

            @endforeach

            <tr>
                <td>{{ $total['no'] }}</td>
                <td>{{ $total['tgl_trma'] }}</td>
                <td>{{ $total['jenis_sample'] }}</td>
                <td>{{ $total['asal_sampel'] }}</td>
                <td>{{ $total['memo_pengantar'] }}</td>
                <td>{{ $total['nama_pengirim'] }}</td>
                <td>{{ $total['departemen'] }}</td>
                <td>{{ $total['nomor_kupa'] }}</td>
                <td>{{ $total['kode_sampel'] }}</td>
                <td>{{ $total['jumlah_parameter'] }}</td>
                <td>{{ $total['jumlah_sampel'] }}</td>

                <td>{{ $total['parameter_anal'] }}</td>
                <td>{{ $total['harga_normal'] }}</td>
                <td>{{ $total['estimasi'] }}</td>
                <td>{{ $total['tanggal_serif'] }}</td>
                <td>{{ $total['no_serif'] }}</td>
                <td>{{ $total['tanggal_kirimserif'] }}</td>
            </tr>
            <tr>
                <td>{{ $totalppn['no'] }}</td>
                <td>{{ $totalppn['tgl_trma'] }}</td>
                <td>{{ $totalppn['jenis_sample'] }}</td>
                <td>{{ $totalppn['asal_sampel'] }}</td>
                <td>{{ $totalppn['memo_pengantar'] }}</td>
                <td>{{ $totalppn['nama_pengirim'] }}</td>
                <td>{{ $totalppn['departemen'] }}</td>
                <td>{{ $totalppn['nomor_kupa'] }}</td>
                <td>{{ $totalppn['kode_sampel'] }}</td>
                <td>{{ $totalppn['jumlah_parameter'] }}</td>
                <td>{{ $totalppn['jumlah_sampel'] }}</td>

                <td>{{ $totalppn['parameter_anal'] }}</td>
                <td>{{ $totalppn['harga_normal'] }}</td>
                <td>{{ $totalppn['estimasi'] }}</td>
                <td>{{ $totalppn['tanggal_serif'] }}</td>
                <td>{{ $totalppn['no_serif'] }}</td>
                <td>{{ $totalppn['tanggal_kirimserif'] }}</td>
            </tr>
            <tr>
                <td>{{ $totalfinal['no'] }}</td>
                <td>{{ $totalfinal['tgl_trma'] }}</td>
                <td>{{ $totalfinal['jenis_sample'] }}</td>
                <td>{{ $totalfinal['asal_sampel'] }}</td>
                <td>{{ $totalfinal['memo_pengantar'] }}</td>
                <td>{{ $totalfinal['nama_pengirim'] }}</td>
                <td>{{ $totalfinal['departemen'] }}</td>
                <td>{{ $totalfinal['nomor_kupa'] }}</td>
                <td>{{ $totalfinal['kode_sampel'] }}</td>
                <td>{{ $totalfinal['jumlah_parameter'] }}</td>
                <td>{{ $totalfinal['jumlah_sampel'] }}</td>

                <td>{{ $totalfinal['parameter_anal'] }}</td>
                <td>{{ $totalfinal['harga_normal'] }}</td>
                <td>{{ $totalfinal['estimasi'] }}</td>
                <td>{{ $totalfinal['tanggal_serif'] }}</td>
                <td>{{ $totalfinal['no_serif'] }}</td>
                <td>{{ $totalfinal['tanggal_kirimserif'] }}</td>
            </tr>
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>