<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>

    @php
    foreach ($data as $items){
    $tanggal_terima = $items['tanggal_terima'];
    $kondisi_sampel = $items['kondisi_sampel'];
    $tanggal_penyelesaian = $items['tanggal_penyelesaian'];
    $no_order = $items['no_order'];
    $jumlah_sampel = $items['jumlah_sampel'];
    $jenis_sampel = $items['jenis_sampel'];

    }

    @endphp
    <table style="border: 1px solid black;">

        <thead>

            <tr>
                <td></td>
                <td rowspan="4" style="border-left:1px solid black;"></td>
                <td colspan="17" style="text-align:center;border-left:1px solid black;border-right: 1px solid black;height: 40px;font-weight:bold;font-size:14px">
                    PT . CITRA BORNEO INDAH</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="17" style="text-align:center;border-left:1px solid black;border-right: 1px solid black;height: 40px;font-size:14px;font-weight:bold">
                    RESEARCH AND DEVELOPMENT - LABORATORIUM ANALITIK</td>
                <td style="border-left:1px solid black;"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="17" style="text-align:center;border: 1px solid black;">Formulir</td>
                <td style="border-left:1px solid black;"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="17" style="text-align:center;border: 1px solid black;">Identitas Sampel {{$jenis_sampel}}</td>
                <td style="border-left:1px solid black;"></td>
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
                    No Order
                </th>
                <th style="border-top:1px solid black;text-align:left">
                    {{$no_order}}
                </th>

                <th colspan="12" style="border-top: 1px solid black;"></th>
                <th colspan="2" style="border-top: 1px solid black;">Tanggal Penyelesaian</th>
                <th style="border-top: 1px solid black;border-right: 1px solid black;">{{$tanggal_penyelesaian}}</th>
            </tr>
            <tr>
                <th></th>
                <th colspan="2" style="border-left: 1px solid black;border-bottom: 1px solid black;">
                    Tanggal Terima :
                </th>
                <th style="border-bottom: 1px solid black;">
                    {{$tanggal_terima}}
                </th>

                <th colspan="12" style="border-bottom: 1px solid black;"></th>
                <th colspan="2" style="border-bottom: 1px solid black;">Kondisi fisik sampel</th>
                <th style="border-bottom: 1px solid black;border-right:1px solid black">{{$kondisi_sampel}}</th>
            </tr>
            <tr>
                <th></th>
                <th colspan="2" style="border-left: 1px solid black;border-bottom: 1px solid black;text-align:left">
                    Jumlah Sampel :
                </th>
                <th style="border-bottom: 1px solid black;text-align:left">
                    {{$jumlah_sampel}}
                </th>
                <th colspan="15" style="border-bottom: 1px solid black;">

                </th>
            </tr>

            <tr>
                <th></th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    No
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    NO.Lab
                </th>
                <th colspan="13" style="border: 1px solid black;text-align:center;">
                    Parameter Analisis
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Tanggal Preparasi
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Ket.
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Paraf
                </th>
            </tr>
            <tr>
                <th>

                </th>


                @foreach ($namaparams as $items)
                <th style="border: 1px solid black;text-align:center;">
                    {{$items}}
                </th>
                @endforeach
                @for ($i = 0; $i < $total_namaparams; $i++) <th style="border: 1px solid black;text-align:center;">
                    -
                    </th>
                    @endfor

            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td></td>
                <td style="border: 1px solid black;text-align:center;"> {{$item['id']}} </td>
                <td style="border: 1px solid black;text-align:center;"> {{$item['nomor_lab']}} </td>
                @foreach ($namaparams as $param)
                <td style="border: 1px solid black;text-align:center;">
                    @if (in_array($param, $item['parameter_sampel']))
                    √
                    @endif
                </td>
                @endforeach
                @for ($i = 0; $i < ($total_namaparams + 3); $i++) <th style="border: 1px solid black;text-align:center;">

                    </th>
                    @endfor
            </tr>
            @endforeach

        </tbody>
    </table>

    <tr></tr>
    <tr></tr>
    <tr></tr>


    <table>
        <thead>
            <tr>
                <th></th>
                <th style="border: 1px solid black;" colspan="3">Diserah terimakan oleh</th>
                <th style="border: 1px solid black;" colspan="2">Diperiksa oleh</th>
                <th style="border: 1px solid black;" colspan="2">Diterima Oleh</th>
                <th style="border: 1px solid black;" colspan="2">Diverifikasi Oleh</th>
                <th style="border: 1px solid black;" colspan="4">Catatan Khusus</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td style="text-align: center;border:1px solid black" colspan="3" rowspan="5">Ernawati</td>
                <td style="text-align: center;border:1px solid black" colspan="2" rowspan="5">Wulan Permata Ardean</td>
                <td style="text-align: center;border:1px solid black" colspan="2" rowspan="5">Syahid Shadiqin</td>
                <td style="text-align: center;border:1px solid black" colspan="2" rowspan="5">Riski Fitri Kurnia</td>
                <td colspan="4" rowspan="6" style="vertical-align: top;text-align:left;border:1px solid black">Ket = ( √ ) : Telah dilakukan Preparasi</td>
            </tr>
            <tr>
                <td></td>


            </tr>
            <tr>
                <td></td>

            </tr>
            <tr>
                <td></td>

            </tr>
            <tr>
                <td></td>

            </tr>
            <tr>
                <td></td>
                <td style="text-align: center;border:1px solid black" colspan="3">Petugas Penerima Sampel</td>
                <td style="text-align: center;border:1px solid black" colspan="2">Penyelia</td>
                <td style="text-align: center;border:1px solid black" colspan="2">Petugas Preparasi</td>
                <td style="text-align: center;border:1px solid black" colspan="2">Staff Kimia dan Lingkungan</td>
            </tr>
        </tbody>
    </table>


</body>

</html>