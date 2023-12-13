<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>


    <table>

        <thead>


            <tr>
                <th rowspan="5" style="border: 1px solid black;">

                </th>
            </tr>
            <tr>
                <th colspan="18" style="border: 1px solid black;text-align:center;">
                    PT. CITRA BORNEO INDAH
                </th>
            </tr>
            <tr>
                <th colspan="18" style="border: 1px solid black;text-align:center;">
                    RESEARCH AND DEVELOPMENT - LABORATORIUM ANALITIK
                </th>
            </tr>
            <tr>
                <th colspan="18" style="border: 1px solid black;text-align:center;">
                    Formulir
                </th>
            </tr>
            <tr>
                <th colspan="18" style="border: 1px solid black;text-align:center;">
                    Kaji Ulang Permintaan,Tender dan Kontrak Sampel {{$jenissample}}
                </th>
            </tr>
            <tr>
                <th style="text-align:center;border: 1px solid black;">
                    No: Dokumen
                </th>
                <th colspan="6" style="text-align:center;border: 1px solid black;">
                    Revisi
                </th>
                <th colspan="6" style="text-align:center;border: 1px solid black;">
                    Berlaku Efektif
                </th>
                <th colspan="6" style="text-align:center;border: 1px solid black;">
                    Halaman
                </th>
            </tr>
            <tr>
                <th style="text-align:center;border: 1px solid black;">
                    {{$no_dokumen}}
                </th>
                <th colspan="6" style="text-align:center;border: 1px solid black;">
                    02
                </th>
                <th colspan="6" style="text-align:center;border: 1px solid black;">
                    01-Jul-21
                </th>
                <th colspan="6" style="text-align:center;border: 1px solid black;">
                    1 dari 1
                </th>
            </tr>

            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>

            <tr style="border: 1px solid black;">
                <th colspan="4">
                    Tanggal Penerimaan: {{$tanggal}}
                </th>
                <th colspan="14">

                </th>
                <th colspan="2">
                    Jenis Sample : {{$jenissample}}
                </th>
            </tr>
            <tr style="border: 1px solid black;">
                <th colspan="2">
                    No. Kaji Ulang : 441
                </th>
                <th colspan="14">

                </th>
                <th colspan="2">
                    Nama Pelanggan:
                </th>
                <th colspan="2">
                    {{$pelanggan}}
                </th>
            </tr>

            <tr>
                <td>&nbsp;</td>
            </tr>


            <tr>
                <th style="border: 1px solid black;text-align:center" rowspan="2">
                    No Surat Pelanggan
                </th>
                <th style="border: 1px solid black;text-align:center" rowspan="2">
                    Kemasan Sampel
                </th>
                <th style="border: 1px solid black;text-align:center" rowspan="2">
                    Jumlah Sampel
                </th>
                <th style="border: 1px solid black;text-align:center" rowspan="2">
                    Nomor Laboratorium
                </th>
                <th style="border: 1px solid black;text-align:center" rowspan="2">
                    Parameter Analisis
                </th>
                <th style="border: 1px solid black;text-align:center" rowspan="2">
                    Metode Analisis
                </th>
                <th style="border: 1px solid black;text-align:center" rowspan="2">
                    Satuan
                </th>
                <th style="border: 1px solid black;text-align:center" colspan="3">
                    Sumber Daya Laboratorium
                </th>
                <th style="border: 1px solid black;text-align:center" colspan="5">
                    Biaya Analisa (Rp)
                </th>
                <th style="border: 1px solid black;text-align:center">
                    Konfirmasi
                </th>
                <th style="border: 1px solid black;text-align:center" colspan="2">
                    Kondisi Sample
                </th>
                <th style="border: 1px solid black;text-align:center" rowspan="2">
                    Tanggal Penyelesaian Analisa
                </th>
            </tr>
            <tr>
                <th style="border: 1px solid black;text-align:center">
                    Personel (Tersedia dan Kompeten)
                </th>
                <th style="border: 1px solid black;text-align:center">
                    Alat (Tersedia dan Baik)
                </th>
                <th style="border: 1px solid black;text-align:center">
                    Bahan Kimia (Tersedia dan Baik)
                </th>
                <th style="border: 1px solid black;text-align:center">
                    Jumlah Sampel
                </th>
                <th style="border: 1px solid black;text-align:center">
                    Harga per Sampel
                </th>
                <th style="border: 1px solid black;text-align:center">
                    Sub Total
                </th>
                <th style="border: 1px solid black;text-align:center">
                    ppn 11%
                </th>
                <th style="border: 1px solid black;text-align:center">
                    Total
                </th>
                <th style="border: 1px solid black;text-align:center">
                    Langsung / Telepon / Email
                </th>
                <th style="border: 1px solid black;text-align:center">
                    Normal
                </th>
                <th style="border: 1px solid black;text-align:center">
                    Abnormal
                </th>

            </tr>

        </thead>
        <tbody>
            @foreach ($trackdata as $data)
            <tr>
                <td style="border: 1px solid black;text-align:center;">{{$data['no_surat']}}</td>
                <td style="border: 1px solid black;text-align:center;">{{$data['kondisi_sampel']}}</td>
                <td style="border: 1px solid black;text-align:center;">{{$data['jumlah_sampel']}}</td>
                <td style="border: 1px solid black;text-align:center;">{{$data['nomor_lab']}}</td>
                <td style="border: 1px solid black;text-align:center;">
                    @if(isset($data['parameter_analisis']['parameter']) && is_array($data['parameter_analisis']['parameter']))
                    @foreach ($data['parameter_analisis']['parameter'] as $items)
                    {{$items}}<br>
                    @endforeach
                    @endif
                </td>
                <td style="border: 1px solid black;text-align:center;">
                    @if(is_array($data['metode_analisis'])) <!-- Check if it's an array -->
                    @foreach ($data['metode_analisis'] as $metode)
                    {{$metode}}<br>
                    @endforeach
                    @else
                    {{$data['metode_analisis']}} <!-- Display single value if not an array -->
                    @endif
                </td>
                <td style="border: 1px solid black;text-align:center;">%</td>
                <td style="border: 1px solid black;text-align:center;">{{$data['personel']}}</td>
                <td style="border: 1px solid black;text-align:center;">{{$data['alat']}}</td>
                <td style="border: 1px solid black;text-align:center;">{{$data['bahan']}}</td>
                <td style="border: 1px solid black;text-align:center;">
                    {{$data['parameter_analisis']['jumlah_sampel']}} <!-- Access 'jumlah_sampel' directly -->
                </td>
                <td style="border: 1px solid black;text-align:center;">
                    {{$data['parameter_analisis']['hargaori']}} <!-- Access 'jumlah_sampel' directly -->
                </td>
                <td style="border: 1px solid black;text-align:center;">
                    {{$data['parameter_analisis']['subtotal']}} <!-- Access 'jumlah_sampel' directly -->
                </td>
                <td style="border: 1px solid black;text-align:center;">
                    {{$data['parameter_analisis']['ppn']}} <!-- Access 'jumlah_sampel' directly -->
                </td>
                <td style="border: 1px solid black;text-align:center;">
                    {{$data['parameter_analisis']['total']}} <!-- Access 'jumlah_sampel' directly -->
                </td>
                <td style="border: 1px solid black;text-align:center;">{{$data['email']}}</td>
                <td style="border: 1px solid black;text-align:center;">{{$data['normal']}}</td>
                <td style="border: 1px solid black;text-align:center;">{{$data['taknormal']}}</td>
                <td style="border: 1px solid black;text-align:center;">{{$data['estimasi']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


    <table style="margin-top: 20px">
        <thead>
            <tr>
                <th style="border: 1px solid black;">Di buat oleh</th>
                <th style="border: 1px solid black;">DIketahi Oleh</th>
                <th style="border: 1px solid black;">Di setujui Oleh</th>
                <th style="border: 1px solid black;">Catatan</th>
            </tr>
        </thead>
        <tbody>
            <tr style="height: 180px;">
                <td style="text-align:left;border-left: 1px solid black;width: 350px;"></td>
                <td style="text-align:left;border-left: 1px solid black;width: 350px;"></td>
                <td style="text-align:left;border-left: 1px solid black;width: 350px;"></td>
                <td style="text-align:left;border-left: 1px solid black;width: 900px;"></td>
            </tr>

            <tr>
                <td style="text-align:left;border-bottom:1px solid black;border-left:1px solid black;width: 350px;">(Erna Wati)</td>
                <td style="text-align:left;border-bottom:1px solid black;border-left:1px solid black;width: 350px;">(Budi Umbara)</td>
                <td style="text-align:left;border-bottom:1px solid black;border-left:1px solid black;width: 350px;">(Lujian Kurniawan)</td>
                <td style="text-align:left;border-left:1px solid black;border-right:1px solid black;width: 900px;"> </td>
            </tr>
            <tr>
                <td style="text-align:left;border-bottom:1px solid black;border-left:1px solid black;width: 350px;">Petugas Penerima Sampel </td>
                <td style="text-align:left;border-bottom:1px solid black;border-left:1px solid black;width: 350px;">Manager Laboratorium</td>
                <td style="text-align:left;border-bottom:1px solid black;border-left:1px solid black;width: 350px;">Pelanggan</td>
                <td style="text-align:left;border-bottom:1px solid black;border-right:1px solid black;border-left:1px solid black;width: 900px;">Sampel basah </td>
            </tr>
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>