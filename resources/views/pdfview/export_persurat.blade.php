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
        table {
            page-break-inside: avoid !important;
            page-break-before: auto !important;
        }
    </style>



    <table style="border: 1px solid black;">
        <thead>

            <tr>
                <td></td>
                <td rowspan="4" colspan="2">
                    <div>
                        @if (defaultIconPT($data_kupa['tanggal_penerimaan']))
                        <img src="{{ asset('images/Logo_CBI_2.png') }}" style="height:60px;margin-left:50px">
                        @else
                        <img src="{{ asset('images/logocorp.png') }}" style="height:60px;margin-left:50px">
                        @endif
                    </div>
                </td>
                <td colspan="16" style="text-align:center; border-left:1px solid black;border-right: 1px solid black;height: 40px;font-weight:bold;font-size:14px">
                    @php
                    $pt = defaultPTname($data_kupa['tanggal_penerimaan']);
                    @endphp
                    {{$pt['nama']}}
                </td>
            </tr>
            <tr>
                <th></th>
                <td colspan="16" style="text-align:center; border-left:1px solid black;border-right: 1px solid black;height: 40px;font-size:14px;font-weight:bold">
                    RESEARCH AND DEVELOPMENT - LABORATORIUM ANALITIK</td>
            </tr>
            <tr>
                <th></th>
                <td colspan="16" style="border: 1px solid black;text-align:center">Formulir</td>
            </tr>
            <tr>
                <th></th>
                <td colspan="16" style="border: 1px solid black;text-align:center">
                    @if($data_kupa['formulir'] != null)
                    {{$data_kupa['formulir']}}
                    @else
                    Kaji Ulang Permintaa, Tender dan Kontrak Sampel
                    {{$data_kupa['jenis_kupa'] ?? 0}}
                    @endif

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
                    @if($data_kupa['doc'] != null)
                    {{$data_kupa['doc']}}
                    @else
                    FR.7.1-12
                    @endif

                </th>
                <th colspan="5" style="border: 1px solid black;text-align:center;">
                    {{$pt['revisi']}}
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
                <th colspan="2">
                    Tanggal Penerimaan
                </th>
                <th colspan="5" style="text-align:left;">
                    : {{$data_kupa['tanggal_penerimaan'] ?? 0}}
                </th>
                <th colspan="6" style="text-align:center;">

                </th>
                <th colspan="2" style="text-align:left;">
                    Jenis Sampel
                </th>
                <th colspan="3" style="text-align:left;">
                    : {{$data_kupa['jenis_kupa'] ?? 0}}
                </th>
            </tr>
            <tr>
                <th></th>
                <th colspan="2">
                    No. Kaji Ulang
                </th>
                <th colspan="5" style="text-align:left;">
                    : {{$data_kupa['no_kupa'] ?? 0}}
                </th>
                <th colspan="6" style="text-align:center;">

                </th>
                <th colspan="2" style="text-align:left;">
                    Nama Pelanggan
                </th>
                <th colspan="3" style="text-align:left;">
                    : {{$data_kupa['nama_pengirim'] ?? 0}}
                </th>
            </tr>
            <tr>
                <th></th>
                <th colspan="2">

                </th>
                <th colspan="5" style="text-align:left;">

                </th>
                <th colspan="6" style="text-align:center;">

                </th>
                <th colspan="2" style="text-align:left;">
                    Departemen
                </th>
                <th colspan="3" style="text-align:left;">
                    : {{$data_kupa['departemen'] ?? 0}}
                </th>
            </tr>
            <tr>
                <th></th>
                <th rowspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none;">
                    No. Surat Pelanggan
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Kemasan Sampel
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Jumlah Sampel
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Nomor Laboratorium
                </th>
                <th rowspan="2" colspan="2" style="border: 1px solid black;text-align:center;">
                    Parameter Analisis
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Metode Analisis
                </th>
                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Satuan
                </th>
                <th colspan="3" style="border: 1px solid black;text-align:center;">
                    Sumber Daya Laboratorium
                </th>

                <th colspan="3" style="border: 1px solid black;text-align:center;">
                    Biaya Analisa (Rp)
                </th>
                <th style="border: 1px solid black;text-align:center;">
                    Konfirmasi
                </th>
                <th colspan="2" style="border: 1px solid black;text-align:center;">
                    Kondisi Sampel
                </th>

                <th rowspan="2" style="border: 1px solid black;text-align:center;">
                    Tanggal Penyelesaian Analisa
                </th>

            </tr>
            <tr>
                <th></th>

                <th style="border: 1px solid black;text-align:center;">
                    Personel (Tersedia dan Kompeten)
                </th>

                <th style="border: 1px solid black;text-align:center;">
                    Alat (Tersedia dan Baik)
                </th>
                <th style="border: 1px solid black;text-align:center;">
                    Bahan Kimia (Tersedia dan Baik)
                </th>
                <th style="border: 1px solid black;text-align:center;">
                    Jumlah Sampel
                </th>
                <th style="border: 1px solid black;text-align:center;">
                    Harga Per Sampel
                </th>
                <th style="border: 1px solid black;text-align:center;">
                    Sub Total
                </th>
                <th style="border: 1px solid black;text-align:center;">
                    Langsung / Telepon / Email
                </th>
                <th style="border: 1px solid black;text-align:center;">
                    Normal
                </th>
                <th style="border: 1px solid black;text-align:center;">
                    Abnormal
                </th>


            </tr>

        </thead>
        <tbody>

            @foreach ($data_kupa['data'] as $key => $items)
            <tr>
                <td></td>
                @if ($key == 0)
                <td rowspan="{{ $data_kupa['total_row'] }}" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none; vertical-align: top; text-align: left">{{ $items['no_surat'] }}</td>
                <td rowspan="{{ $data_kupa['total_row'] }}" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none; vertical-align: top; text-align: left">{{ $items['kemasan'] }}</td>
                <td rowspan="{{ $data_kupa['total_row'] }}" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none; vertical-align: top; text-align: center">{{ $items['jum_sampel'] }}</td>
                @endif

                @if ($key == 1)
                <td rowspan="{{ $data_kupa['total_row'] - 1 }}" style="border: 1px solid black; vertical-align: top; text-align: left">{{ $items['nolab'] }}</td>
                @else
                @if ($key == 0)
                <td>{{ $items['nolab'] }}</td>
                @endif
                @endif

                <td style="border: 1px solid black; vertical-align: center; text-align: left">{{ $items['Parameter_Analisis'] }}</td>
                <td style="border: 1px solid black; vertical-align: center; text-align: center">{{ $items['mark'] }}</td>
                <td style="border: 1px solid black; vertical-align: center; text-align: left">{{ $items['Metode_Analisis'] }}</td>
                <td style="border: 1px solid black; vertical-align: center; text-align: center">{{ $items['satuan'] }}</td>

                @if ($items['cols'] != 0)
                <td style="border: 1px solid black; vertical-align: center; text-align: center" rowspan="{{ $items['cols'] }}">{{ $items['Personel'] }}</td>
                <td style="border: 1px solid black; vertical-align: center; text-align: center" rowspan="{{ $items['cols'] }}">{{ $items['alat'] }}</td>
                <td style="border: 1px solid black; vertical-align: center; text-align: center" rowspan="{{ $items['cols'] }}">{{ $items['bahan'] }}</td>
                @endif

                @if ($items['jum_data'] != 0)
                <td style="border: 1px solid black; vertical-align: center; text-align: center" rowspan="{{ $items['cols'] }}">{{ $items['jum_data'] }}</td>
                @endif

                @if ($items['jum_harga'] != 0)
                <td style="border: 1px solid black; vertical-align: center; text-align: center" rowspan="{{ $items['cols'] }}">@money($items['jum_harga'], 'IDR','True')</td>
                @endif

                @if ($items['jum_sub_total'] != 0)
                <td style="border: 1px solid black; vertical-align: center; text-align: center" rowspan="{{ $items['cols'] }}">@money($items['jum_sub_total'], 'IDR','True')</td>
                @endif

                @if ($items['cols'] != 0)
                <td style="border: 1px solid black; vertical-align: center; text-align: center" rowspan="{{ $items['cols'] }}">{{ $items['Konfirmasi'] }}</td>
                @if ($items['kondisi_sampel'] === 'Normal')
                <td style="border: 1px solid black; vertical-align: center; text-align: center" rowspan="{{ $items['cols'] }}">✔</td>
                <td style="border: 1px solid black; vertical-align: center; text-align: center" rowspan="{{ $items['cols'] }}"></td>
                @else
                <td style="border: 1px solid black; vertical-align: center; text-align: center" rowspan="{{ $items['cols'] }}"></td>
                <td style="border: 1px solid black; vertical-align: center; text-align: center" rowspan="{{ $items['cols'] }}">{{ $items['kondisi_sampel'] }}</td>
                @endif
                @endif

                @if ($key == 0)
                <td rowspan="{{ $data_kupa['total_row'] }}" style="border: 1px solid black; vertical-align: top; text-align: center">{{ $items['estimasi'] }}</td>
                @endif
            </tr>
            @endforeach

            @foreach ($data_kupa['result_total'] as $data)
            <tr>
                @foreach ($data as $index => $item)
                @if ($index == 0)
                <td>{{ $item }}</td>
                @else
                @if ($index == 5)
                <td style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none; vertical-align: center; text-align: center" colspan="4">{{ $item }}</td>
                @else
                <td style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none; vertical-align: center; text-align: center">{{ $item }}</td>
                @endif
                @endif
                @endforeach
            </tr>
            @endforeach

        </tbody>

    </table>
    <table style="width:100%;padding-top:5%;">
        <thead>
            <tr>
                <th style="border: 1px solid black; width:15%;">Dibuat Oleh</th>
                <th style="border: 1px solid black; width:15%;">Diketahui Oleh</th>
                <th style="border: 1px solid black; width:15%;">Disetujui Oleh</th>
                <th style="border: 1px solid black; width:40%;">Catatan Khusus</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center; border:1px solid black; border-bottom:none;">&NonBreakingSpace;</td>
                <td style="text-align: center; border:1px solid black; border-bottom:none;">&NonBreakingSpace;</td>
                <td style="text-align: center; border:1px solid black; border-bottom:none;">&NonBreakingSpace;</td>
                <td rowspan="6" style="text-align: left; vertical-align:top; border:1px solid black;">{{$data_kupa['catatan']}}</td>
            </tr>
            <tr>
                <td style="text-align: center; border: 1px solid black; border-bottom: none; border-top: none;">

                    @if ($data_kupa['approval'] == 'Draft' || $data_kupa['approval'] == 'Waiting Admin Approval')
                    <span style="color: blue;font-size: 30px;">CREATED</span><br>
                    <span style="font-size: 15px;">{{$data_kupa['memo_created']}}</span>
                    @else
                    <span style="color: blue;font-size: 30px;">APPROVED</span><br>
                    <span style="font-size: 15px;">{{$data_kupa['verifikasi_admin_timestamp']}}</span>
                    @endif

                </td>

                <td style="text-align: center; border:1px solid black; border-bottom:none; border-top:none;"> @if ($data_kupa['isVerifiedByHead'] == True)
                    @php
                    echo strtoupper($data_kupa['approval'])
                    @endphp <br>


                    {{$data_kupa['verifikasi_head_timestamp']}}

                    @endif
                </td>
                <td style="text-align: center; border:1px solid black; border-bottom:none; border-top:none;">&NonBreakingSpace;</td>
            </tr>
            <tr>
                <td style="text-align: center; border:1px solid black; border-bottom:none; border-top:none;">&NonBreakingSpace;</td>
                <td style="text-align: center; border:1px solid black; border-bottom:none; border-top:none;">&NonBreakingSpace;</td>
                <td style="text-align: center; border:1px solid black; border-bottom:none; border-top:none;">&NonBreakingSpace;</td>
            </tr>
            <tr>
                <td style="text-align: center; border:1px solid black; border-bottom:none; border-top:none;">&NonBreakingSpace;</td>
                <td style="text-align: center; border:1px solid black; border-bottom:none; border-top:none;">&NonBreakingSpace;</td>
                <td style="text-align: center; border:1px solid black; border-bottom:none; border-top:none;">&NonBreakingSpace;</td>
            </tr>
            <tr>
                <td style="text-align: center; border:1px solid black; border-bottom:none;">{{$data_kupa['petugas_penerima_sampel']}}</td>
                <td style="text-align: center; border:1px solid black; border-bottom:none;">Budi Umbara</td>
                <td style="text-align: center; border:1px solid black; border-bottom:none;">{{$data_kupa['nama_pengirim']}}</td>
            </tr>
            <tr>
                <td style="text-align: center; border:1px solid black;">Petugas Penerima Sampel</td>
                <td style="text-align: center; border:1px solid black;">Manager Laboratorium</td>
                <td style="text-align: center; border:1px solid black;">Pelanggan</td>
            </tr>
        </tbody>
    </table>



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

        table {
            page-break-inside: avoid !important;
            page-break-before: auto !important;
        }
    </style>
    <div style="page-break-before:always"></div>
    @foreach ($data_identitas['data'] as $index => $listitems)
    <table style="border: 1px solid black;width:100%">
        <thead>
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
                    {{$pt['nama']}}
            </tr>
            <tr>
                <th></th>
                <td colspan="22" style="border-left:1px solid black;border-right: 1px solid black;height: 40px;font-size:14px;font-weight:bold;text-align:center">
                    RESEARCH AND DEVELOPMENT - LABORATORIUM ANALITIK</td>
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
                    1-jul-21
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
                <th colspan="3" style="text-align:left;">
                    : {{$listitems['jumlah_sampel']}}
                </th>
                <th colspan="9" style="text-align:center;">

                </th>
                <th colspan="10" style="text-align:right;">

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
    <table style="border: 1px solid black;width:100%;padding-top:5%">
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
                <td colspan="8" rowspan="5" style="text-align: left;vertical-align:top;border:1px solid black">Ket = ( √ ) : Telah dilakukan Preparasi</td>
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
    @endforeach

    <div style="page-break-before:always"></div>
    <style>
        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 1080px;
            margin: 20px auto;
            font-family: Arial, sans-serif;
            page-break-after: always;
        }

        .card-header {
            background-color: #f4f4f4;
            padding: 16px;
            font-size: 1.25em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }

        .card-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card-body img {
            width: 90%;
            height: 80%;
            display: block;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
    @foreach($data_dokument as $item)
    @foreach($item['foto'] as $foto)
    <div class="card">
        <div class="card-header">Dokumentasi Sampel {{$item['jenis_sampel']}} No Lab : {{$item['no_lab']}}</div>
        <div class="card-body">
            @if($foto)
            <img src="{{ asset('storage/' . $foto) }}" alt="Image">
            @else
            <p>Foto Tidak Tersedia</p>
            @endif
        </div>
    </div>
    @endforeach
    @endforeach
</body>

</html>