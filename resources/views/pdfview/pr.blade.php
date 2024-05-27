<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


</head>

<body>

    <style>
        .textcenter {
            text-align: center;
            vertical-align: center
        }

        .border {
            border: 1px solid black;
        }

        .border-text {
            border: 1px solid black;
            text-align: center;
            vertical-align: middle
        }

        .page_break {
            page-break-before: always;
        }
    </style>
    @foreach ($data as $index => $listitems)
    <table style="border: 1px solid black;width:100%">
        <thead>
            <tr>
                <td></td>
                <td rowspan="4" colspan="2">
                    <div>
                        <img src="{{ asset('images/Logo_CBI_2.png') }}" style="height:60px;margin-left:50px">
                    </div>
                </td>
                <td colspan="16" style="border-left:1px solid black;border-right: 1px solid black;height: 40px;font-weight:bold;font-size:14px;text-align:center">
                    PT . CITRA BORNEO INDAH</td>
            </tr>
            <tr>
                <th></th>
                <td colspan="16" style="border-left:1px solid black;border-right: 1px solid black;height: 40px;font-size:14px;font-weight:bold;text-align:center">
                    RESEARCH AND DEVELOPMENT - LABORATORIUM ANALITIK</td>
            </tr>
            <tr>
                <th></th>
                <td colspan="16" style="border: 1px solid black;">Formulir</td>
            </tr>
            <tr>
                <th></th>
                <td colspan="16" style="border: 1px solid black;">Kaji Ulang Permintaa, Tender dan Kontrak Sampel
                    {{$listitems['jenis_kupa'] ?? 0}}
                </td>
            </tr>
            <tr>
                <th></th>
                <th colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none;">
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
                <th colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none;">
                    FR.7.1-12
                </th>
                <th colspan="5" style="border: 1px solid black;text-align:center;">
                    02
                </th>
                <th colspan="6" style="border: 1px solid black;text-align:center;">
                    1-jul-21
                </th>
                <th colspan="5" style="border: 1px solid black;text-align:center;">
                    1 dari 1
                </th>
            </tr>
            <tr>
                <th></th>
                <th colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none;">
                    No Order
                </th>
                <th colspan="2" style="border-top:1px solid black;">
                    {{$listitems['no_order']}}
                </th>

                <th colspan="10" style="border-top: 1px solid black;"></th>
                <th colspan="3" style="border-top: 1px solid black;">Tanggal Penyelesaian</th>
                <th style="border-top: 1px solid black;border-right: 1px solid black;">: {{$listitems['tanggal_penyelesaian'] ?? 0}}</th>
            </tr>
            <tr>
                <th></th>
                <th colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none;">
                    Tanggal Terima :
                </th>
                <th>
                    {{$listitems['tanggal_terima']}}
                </th>

                <th colspan="11" style="border-bottom: 1px solid blacck;"></th>
                <th colspan="3">Kondisi fisik sampel</th>
                <th>: {{$listitems['kondisi_sampel'] ?? 0}}</th>
            </tr>
            <tr>
                <th></th>
                <th colspan="2" style="border-right:1px solid black;text-align:left">
                    Jumlah Sampel :
                </th>
                <th style="border-bottom: 1px solid black;text-align:left">
                    {{$listitems['jumlah_sampel']}}
                </th>
                <th colspan="15" style="border-bottom: 1px solid black;">

                </th>
            </tr>

            <tr>
                <th></th>
                <th rowspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none;text-align:center;">
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


                @foreach ($listitems['namaparams'] as $items)
                <th style="border: 1px solid black;text-align:center;">
                    {{$items}}
                </th>
                @endforeach
                @for ($i = 0; $i < $listitems['total_namaparams']; $i++) <th style="border: 1px solid black;text-align:center;">
                    -
                    </th>
                    @endfor

            </tr>
        </thead>
        <tbody>
            @foreach($listitems['data'] as $item)

            <tr>
                <td></td>
                <td style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none;text-align:center;"> {{$item['id']}} </td>
                <td style="border: 1px solid black;text-align:center;"> {{$item['nomor_lab']}} </td>
                @foreach ($listitems['namaparams'] as $param)
                <td style="border: 1px solid black;text-align:center;">
                    @if (in_array($param, $item['parameter_sampel']))
                    √
                    @endif
                </td>
                @endforeach
                @for ($i = 0; $i < ($listitems['total_namaparams'] + 3); $i++) <th style="border: 1px solid black;text-align:center;">

                    </th>
                    @endfor
            </tr>
            @endforeach

        </tbody>

    </table>
    <table style="width: 70%;padding-top:50px">
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


                <td style="text-align: center;border:1px solid black" colspan="3" rowspan="5">{{$listitems['PenerimaSampel']}}</td>


                <td style="text-align: center;border:1px solid black" colspan="2" rowspan="5">{{$listitems['Penyelia']}}</td>


                <td style="text-align: center;border:1px solid black" colspan="2" rowspan="5">{{$listitems['Preparasi']}}</td>


                <td style="text-align: center;border:1px solid black" colspan="2" rowspan="5">{{$listitems['Staff']}}</td>


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

    <div class="page_break"></div>
    @endforeach

</body>

</html>