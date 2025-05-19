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
                <th></th>
                <td rowspan="4" colspan="2" style="border-left:1px solid black;"></td>
                <td colspan="16" style="border-left:1px solid black;border-right: 1px solid black;height: 40px;font-weight:bold;font-size:14px">
                    @php
                    $pt = defaultPTname($tanggal_penerimaan);
                    @endphp
                    {{$pt['nama']}}
                </td>
            </tr>
            <tr>
                <th></th>
                <td colspan="16" style="border-left:1px solid black;border-right: 1px solid black;height: 40px;font-size:14px;font-weight:bold">
                    {{$pt['nama_lab']}}
                </td>
            </tr>
            <tr>
                <th></th>
                <td colspan="16" style="border: 1px solid black;">Formulir</td>
            </tr>
            <tr>
                <th></th>
                <td colspan="16" style="border: 1px solid black;">
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
                <th colspan="2" style="border: 1px solid black;">
                    @if($doc != null)
                    {{$doc}}
                    @else
                    FR.7.1-12
                    @endif
                </th>
                <th colspan="5" style="border: 1px solid black;text-align:center;">
                    {{$pt['revisi']}}
                </th>
                <th colspan="6" style="border: 1px solid black;text-align:center;">
                    {{$pt['tanggal_berlaku']}}
                </th>
                <th colspan="5" style="border: 1px solid black;text-align:center;">
                    1 dari 1
                </th>
            </tr>
            <tr>
                <td colspan="20"></td> <!-- Replace "20" with the total number of columns in your table -->
            </tr>
            <tr>
                <td colspan="20"></td> <!-- Replace "20" with the total number of columns in your table -->
            </tr>
            <tr>
                <th></th>
                <th colspan="2" style="border-left:1px solid black;border-top:1px solid black ;">
                    Tanggal Penerimaan
                </th>
                <th colspan="2" style="border-top:1px solid black;">
                    : {{$tanggal_penerimaan ?? 0}}
                </th>

                <th colspan="10" style="border-top: 1px solid black;"></th>
                <th colspan="3" style="border-top: 1px solid black;">Jenis Sampel</th>
                <th style="border-top: 1px solid black;border-right: 1px solid black;">: {{$jenis_kupa ?? 0}}</th>
            </tr>
            <tr>
                <th></th>
                <th colspan="2" style="border-left: 1px solid black;border-bottom: 1px solid black;">
                    No. Kaji Ulang
                </th>
                <th style="border-bottom: 1px solid black;">
                    : {{$no_kupa ?? 0}}
                </th>

                <th colspan="11" style="border-bottom: 1px solid blacck;"></th>
                <th colspan="3" style="border-bottom: 1px solid black;">Nama Pelanggan</th>
                <th style="border-bottom: 1px solid black;border-right:1px solid black">: {{$nama_pengirim ?? 0}}</th>
            </tr>
            <tr>
                <th></th>
                <th colspan="2" style="border-left: 1px solid black;border-bottom: 1px solid black;">

                </th>
                <th style="border-bottom: 1px solid black;">

                </th>

                <th colspan="11" style="border-bottom: 1px solid blacck;"></th>
                <th colspan="3" style="border-bottom: 1px solid black;">Departemen</th>
                <th style="border-bottom: 1px solid black;border-right:1px solid black">: {{$departemen ?? 0}}</th>
            </tr>
            <tr>
                <td colspan="20"></td> <!-- Replace "20" with the total number of columns in your table -->
            </tr>

            <tr>
                <th></th>
                <th rowspan="2" style="border: 1px solid black;">
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
                <td rowspan="{{ $total_row }}" style="border: 1px solid black; vertical-align: top; text-align: left">{{ $items['no_surat'] }}</td>
                <td rowspan="{{ $total_row }}" style="border: 1px solid black; vertical-align: top; text-align: left">{{ $items['kemasan'] }}</td>
                <td rowspan="{{ $total_row }}" style="border: 1px solid black; vertical-align: top; text-align: center">{{ $items['jum_sampel'] }}</td>
                @endif

                @if ($total_row == 1)
                @if ($key == 0)
                <td>
                    {{ $labkiri }}<br>
                    {{ $labkanan }}
                </td>
                @endif
                @else
                @if ($key == 1)
                <td rowspan="{{ $total_row - 1 }}" style="border: 1px solid black; vertical-align: top; text-align: left">{{ $items['nolab'] }}</td>
                @else
                @if ($key == 0)
                <td>{{ $items['nolab'] }}</td>
                @endif
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
                <td style="border: 1px solid black; vertical-align: center; text-align: center" colspan="4">{{ $item }}</td>
                @else
                <td style="border: 1px solid black; vertical-align: center; text-align: center">{{ $item }}</td>
                @endif
                @endif
                @endforeach
            </tr>
            @endforeach




            <tr>
                <td colspan="20"></td> <!-- Replace "20" with the total number of columns in your table -->
            </tr>

            <tr>
                <td></td>
                <td colspan="2" style="border: 1px solid black;">Dibuat Oleh,</td>
                <td colspan="3" style="border: 1px solid black;">Diketahui Oleh,</td>
                <td colspan="3" style="border: 1px solid black;">Disetujui Oleh,</td>
                <td colspan="10" style="border-top: 1px solid black;border-right:1px solid black; text-decoration: underline;">
                    Catatan Khusus : </td>
            </tr>

            <tr>
                <td></td>
                <td colspan="2" rowspan="4" style="border-left:1px solid black;text-align:center">

                    @if ($approval == 'Draft' || $approval == 'Waiting Admin Approval')
                    CREATED <br style="border: none;">
                    {{$memo_created}}
                    @else
                    APPROVED <br style="border: none;">
                    {{$verifikasi_admin_timestamp}}
                    @endif

                </td>
                <td colspan="3" rowspan="4" style="border-left:1px solid black;text-align:center">
                    @if ($isVerifiedByHead == True)
                    @php
                    echo strtoupper($approval)
                    @endphp <br>


                    {{$verifikasi_head_timestamp}}

                    @endif

                </td>
                <td colspan="3" rowspan="4" style="border-left:1px solid black;">

                </td>
                <td colspan="10" rowspan="6" style="border: 1px solid black;">
                    {{$catatan}}
                </td>
            </tr>
            <tr>
                <td></td>
                <!-- <td colspan="10" style="border-right: 1px solid black;">aa</td> -->
            </tr>
            <tr>
                <td></td>
                <!-- <td colspan="10" style="border-right: 1px solid black;">cc</td> -->
            </tr>
            <tr>
                <td></td>
                <!-- <td colspan="10" style="border-right: 1px solid black;">dd</td> -->
            </tr>
            <tr>
                <td></td>
                <td colspan="2" style="border-left:1px solid black;"> {{$petugas_penerima_sampel ?? '-'}}</td>
                <td colspan="3" style="border-left:1px solid black;"> Budi Umbara</td>
                <td colspan="3" style="border-left:1px solid black;"> {{$nama_pengirim ?? '-'}}</td>
                <!-- <td colspan="10" style="border-bottom: 1px solid black;border-right: 1px solid black;">gg</td> -->
            </tr>
            <tr>
                <td></td>
                <td colspan="2" style="border: 1px solid black;">Petugas Penerima Sampel</td>
                <td colspan="3" style="border: 1px solid black;">Manager Laboratorium</td>
                <td colspan="3" style="border: 1px solid black;">Pelanggan</td>
                <!-- <td colspan="10" style="border-bottom: 1px solid black;border-right: 1px solid black;">gg</td> -->
            </tr>
        </tbody>
    </table>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>