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
    </style>
    @foreach ($data as $index => $listitems)

    <table style="border: 1px solid black;width:100%">
        <tr>
            <td></td>
            <td rowspan="4" colspan="3">
                <div>
                    @if (defaultIconPT($listitems['tanggal_terima']))
                    <img src="{{ asset('images/Logo_CBI_2.png') }}" style="height:60px;margin-left:50px">
                    @else
                    <img src="{{ asset('images/logocorp.png') }}" style="height:60px;margin-left:50px">
                    @endif
                </div>
            </td>
            <td colspan="22" style="border-left:1px solid black;border-right: 1px solid black;height: 40px;font-weight:bold;font-size:14px;text-align:center">
                @php
                $pt = defaultPTname($listitems['tanggal_terima']);
                @endphp
                {{$pt['nama']}}
            </td>
        </tr>
        <tr>
            <th></th>
            <td colspan="22" style="border-left:1px solid black;border-right: 1px solid black;height: 40px;font-size:14px;font-weight:bold;text-align:center">
                {{$pt['nama_lab']}}
            </td>
        </tr>
        <tr>
            <th></th>
            <td colspan="22" style="border: 1px solid black;text-align:center">Formulir</td>
        </tr>
        <tr>
            <th></th>
            <td colspan="22" style="border: 1px solid black;text-align:center">
                Identitas Sampel {{$listitems['jenis_sampel'] ?? 0}}
            </td>
        </tr>
        <tr>
            <th></th>
            <th colspan="3" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none;">
                No.Dokumen
            </th>
            <th colspan="8" style="border: 1px solid black;text-align:center;">
                Revisi
            </th>
            <th colspan="10" style="border: 1px solid black;text-align:center;">
                Berlaku Efektif
            </th>
            <th colspan="4" style="border: 1px solid black;text-align:center;">
                Halaman
            </th>
        </tr>
        <tr>
            <th></th>
            <th colspan="3" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none;">
                @if($listitems['no_doc_indentitas'] != null)
                {{$listitems['no_doc_indentitas']}}
                @else
                FR-7.4-1.2-1
                @endif

            </th>
            <th colspan="8" style="border: 1px solid black;text-align:center;">
                {{$pt['revisi']}}
            </th>
            <th colspan="10" style="border: 1px solid black;text-align:center;">
                {{$pt['tanggal_berlaku']}}
            </th>
            <th colspan="4" style="border: 1px solid black;text-align:center;">
                1 dari 1
            </th>
        </tr>
        <tr>
            <th></th>
            <th colspan="3">
                No Order
            </th>
            <th colspan="8" style="text-align:left;">
                : {{$listitems['no_order']}}
            </th>
            <th colspan="10" style="text-align:center;">

            </th>
            <th colspan="2" style="text-align:left;">
                Tanggal Penyelesaian
            </th>
            <th colspan="2" style="text-align:left;">
                :{{$listitems['tanggal_penyelesaian'] ?? 0}}
            </th>
        </tr>
        <tr>
            <th></th>
            <th colspan="3">
                Tanggal Terima
            </th>
            <th colspan="8" style="text-align:left;">
                : {{$listitems['tanggal_terima']}}
            </th>
            <th colspan="10" style="text-align:center;">

            </th>
            <th colspan="2" style="text-align:left;">
                Kondisi fisik sampel
            </th>
            <th colspan="2" style="text-align:left;">
                : {{$listitems['kondisi_sampel'] ?? 0}}
            </th>
        </tr>
        <tr>
            <th></th>
            <th colspan="3">
                Jumlah Sampel
            </th>
            <th colspan="8" style="text-align:left;">
                : {{$listitems['jumlah_sampel']}}
            </th>
            <th colspan="10" style="text-align:center;">

            </th>
            <th colspan="2" style="text-align:left;">
                Jenis Sampel
            </th>
            <th colspan="2" style="text-align:left;">
                : {{$listitems['jenis_pupuk'] ?? '-'}}
            </th>
        </tr>
    </table>
    <table style="border: 1px solid black;width:100%">
        <thead>
            <tr>
                <th></th>
                <th rowspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none;text-align:center;">
                    No
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    NO.Lab
                </th>
                <th colspan="20" style="border: 1px solid black;text-align:center;">
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

    @if(count($listitems['data']) > 40)
    <div style="page-break-after: always;"></div>
    @else
    <div style="margin-top: 25px;"></div>
    @endif

    <table style="border: 1px solid black;width:100%">
        <thead>
            <tr>
                <th style="border: 1px solid black; width:15%;" style="border: 1px solid black; width:15%;" style="border: 1px solid black; width:15%;">Diserah terimakan oleh</th>
                <th style="border: 1px solid black; width:15%;" style="border: 1px solid black; width:15%;" style="border: 1px solid black; width:15%;">Diperiksa oleh</th>
                <th style="border: 1px solid black; width:15%;" style="border: 1px solid black; width:15%;" style="border: 1px solid black; width:15%;">Diterima Oleh</th>
                <th style="border: 1px solid black; width:15%;" style="border: 1px solid black; width:15%;" style="border: 1px solid black; width:15%;">Diverifikasi Oleh</th>
                <th style="border: 1px solid black; width:40%;" colspan="8">Catatan Khusus</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;border:1px solid black;border-bottom:none">&NonBreakingSpace;</td>
                <td style="text-align: center;border:1px solid black;border-bottom:none">&NonBreakingSpace;</td>
                <td style="text-align: center;border:1px solid black;border-bottom:none">&NonBreakingSpace;</td>
                <td style="text-align: center;border:1px solid black;border-bottom:none">&NonBreakingSpace;</td>
                <td colspan="8" rowspan="5" style="text-align: left;vertical-align:top;border:1px solid black">
                    Ket = ( √ ) : Telah dilakukan Preparasi
                    <br>
                    {{$listitems['catatan']}}
                </td>
            </tr>
            <tr>
                <td style="text-align: center;border:1px solid black;border-bottom:none;border-top:none">
                    @if ($listitems['status'] == 1)
                    <span style="color: blue;font-size: 20px;">APPROVED</span><br>
                    <span style="font-size: 15px;">{{$listitems['status_timestamp']}}</span>
                    @endif
                </td>
                <td style="text-align: center;border:1px solid black;border-bottom:none;border-top:none">&NonBreakingSpace;</td>
                <td style="text-align: center;border:1px solid black;border-bottom:none;border-top:none">&NonBreakingSpace;</td>
                <td style="text-align: center;border:1px solid black;border-bottom:none;border-top:none">&NonBreakingSpace;</td>

            </tr>
            <tr>
                <td style="text-align: center;border:1px solid black;border-bottom:none;border-top:none">&NonBreakingSpace;</td>
                <td style="text-align: center;border:1px solid black;border-bottom:none;border-top:none">&NonBreakingSpace;</td>
                <td style="text-align: center;border:1px solid black;border-bottom:none;border-top:none">&NonBreakingSpace;</td>
                <td style="text-align: center;border:1px solid black;border-bottom:none;border-top:none">&NonBreakingSpace;</td>

            </tr>

            <tr>
                <td style="text-align: center;border:1px solid black;border-bottom:none">{{$listitems['PenerimaSampel']}}</td>


                <td style="text-align: center;border:1px solid black;border-bottom:none">{{$listitems['Penyelia']}}</td>


                <td style="text-align: center;border:1px solid black;border-bottom:none">{{$listitems['Preparasi']}}</td>


                <td style="text-align: center;border:1px solid black;border-bottom:none">{{$listitems['Staff']}}</td>



            </tr>

            <tr>
                <td style="text-align: center;border:1px solid black">Petugas Penerima Sampel</td>
                <td style="text-align: center;border:1px solid black">Penyelia</td>
                <td style="text-align: center;border:1px solid black">Petugas Preparasi</td>
                <td style="text-align: center;border:1px solid black">Staff Kimia dan Lingkungan</td>

            </tr>


        </tbody>
    </table>

    @if (!$loop->last)
    <div style="page-break-after: always;"></div>
    @endif

    @endforeach

</body>

</html>