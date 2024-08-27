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
                        <img src="{{ asset('images/Logo_CBI_2.png') }}" style="height:60px;margin-left:50px">
                    </div>
                </td>
                <td colspan="16" style="text-align:center; border-left:1px solid black;border-right: 1px solid black;height: 40px;font-weight:bold;font-size:14px">
                    PT . CITRA BORNEO INDAH</td>
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
                    @if($formulir != null)
                    {{$formulir}}
                    @else
                    Kaji Ulang Permintaa, Tender dan Kontrak Sampel
                    {{$jenis_kupa ?? 0}}
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
                    @if($doc != null)
                    {{$doc}}
                    @else
                    FR.7.1-12
                    @endif

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
                <th colspan="2">
                    Tanggal Penerimaan
                </th>
                <th colspan="5" style="text-align:left;">
                    : {{$tanggal_penerimaan ?? 0}}
                </th>
                <th colspan="6" style="text-align:center;">

                </th>
                <th colspan="2" style="text-align:left;">
                    Jenis Sampel
                </th>
                <th colspan="3" style="text-align:left;">
                    : {{$jenis_kupa ?? 0}}
                </th>
            </tr>
            <tr>
                <th></th>
                <th colspan="2">
                    No. Kaji Ulang
                </th>
                <th colspan="5" style="text-align:left;">
                    : {{$no_kupa ?? 0}}
                </th>
                <th colspan="6" style="text-align:center;">

                </th>
                <th colspan="2" style="text-align:left;">
                    Nama Pelanggan
                </th>
                <th colspan="3" style="text-align:left;">
                    : {{$nama_pengirim ?? 0}}
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
                    : {{$departemen ?? 0}}
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
                {{-- <th style="border: 1px solid black;text-align:center;">
                    ppn 11%
                </th>
                <th style="border: 1px solid black;text-align:center;">
                    Total
                </th> --}}
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

            @foreach ($data as $key => $items)
            <tr>
                <td></td>
                @if ($key == 0)
                <td rowspan="{{ $total_row }}" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none; vertical-align: top; text-align: left">{{ $items['no_surat'] }}</td>
                <td rowspan="{{ $total_row }}" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none; vertical-align: top; text-align: left">{{ $items['kemasan'] }}</td>
                <td rowspan="{{ $total_row }}" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none; vertical-align: top; text-align: center">{{ $items['jum_sampel'] }}</td>
                @endif

                @if ($key == 0)
                <td rowspan="{{ $total_row  }}" style="border: 1px solid black; vertical-align: center; text-align: left;">
                    <span style="width: 100%;">{{ $labkiri }}</span><br>
                    <span style="width: 100%;display:block;border-top: 1px solid black;">{{ $labkanan }}</span>
                </td>
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
                <td rowspan="{{ $total_row }}" style="border: 1px solid black; vertical-align: top; text-align: center">{{ $items['estimasi'] }}</td>
                @endif
            </tr>
            @endforeach

            @foreach ($result_total as $data)
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
                <td rowspan="6" style="text-align: left; vertical-align:top; border:1px solid black;">{{$catatan}}</td>
            </tr>
            <tr>
                <td style="text-align: center; border: 1px solid black; border-bottom: none; border-top: none;">

                    @if ($approval == 'Draft' || $approval == 'Waiting Admin Approval')
                    <span>CREATED</span><br>
                    <span>{{$memo_created}}</span>
                    @else
                    <span style="color: blue;font-size: 20px;">APPROVED</span><br>
                    <span>{{$verifikasi_admin_timestamp}}</span>
                    @endif

                </td>

                <td style="text-align: center; border:1px solid black; border-bottom:none; border-top:none;">
                    @if ($isVerifiedByHead == True)
                    <span style="color: blue;font-size: 20px;">APPROVED</span><br>
                    <span>{{$verifikasi_head_timestamp}}</span>
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
                <td style="text-align: center; border:1px solid black; border-bottom:none;">{{$petugas_penerima_sampel}}</td>
                <td style="text-align: center; border:1px solid black; border-bottom:none;">Budi Umbara</td>
                <td style="text-align: center; border:1px solid black; border-bottom:none;">{{$nama_pengirim}}</td>
            </tr>
            <tr>
                <td style="text-align: center; border:1px solid black;">Petugas Penerima Sampel</td>
                <td style="text-align: center; border:1px solid black;">Manager Laboratorium</td>
                <td style="text-align: center; border:1px solid black;">Pelanggan</td>
            </tr>
        </tbody>
    </table>


</body>

</html>