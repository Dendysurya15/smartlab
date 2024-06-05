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
                <td colspan="16" style="border: 1px solid black;">
                    @if($formulir != null)
                    {{$formulir}}
                    @else
                    MONITORING PENERIMAAN SAMPEL
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
                    @if($nodoc != null)
                    {{$nodoc}}
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
                <th colspan="2" style="border-left:1px solid black;border-top:1px solid black ;">
                    Tanggal Penerimaan
                </th>
                <th colspan="2" style="border-top:1px solid black;">
                    : {{ $tanggalterima }}
                </th>

                <th colspan="12" style="border-top: 1px solid black;"></th>
                <th style="border-top: 1px solid black;">Jenis Sampel</th>
                <th style="border-top: 1px solid black;border-right: 1px solid black;">
                    : {{ $jenis_sample}}
                </th>
            </tr>
            <tr>
                <th></th>
                <th colspan="2" style="border-left: 1px solid black;border-bottom: 1px solid black;">
                    No. Kaji Ulang
                </th>
                <th colspan="2" style="border-bottom: 1px solid black;">
                    : {{ $nomor_kupa }}
                </th>

                <th colspan="12" style="border-bottom: 1px solid black;"></th>
                <th style="border-bottom: 1px solid black;">Nama Pelanggan</th>
                <th style="border-bottom: 1px solid black;border-right:1px solid black">
                    : {{ $nama_pengirim }}
                </th>
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
            @foreach ($data as $item)
            @php
            $kodasampel = explode('$', $item['kode_sampel']);
            @endphp
            <tr>
                <td style="border: 1px solid black">{{ $item['col'] }}</td>
                <td style="border: 1px solid black; vertical-align: top;">{{ $item['id'] }}</td>
                <td style="border: 1px solid black;vertical-align: top; text-align: right">{{ $item['tanggalterima'] }}</td>
                <td style="border: 1px solid black;vertical-align: top; text-align: right">{{ $item['jenis_sample'] }}</td>
                <td style="border: 1px solid black;vertical-align: top; text-align: right">{{ $item['asal_sampel'] }}</td>
                <td style="border: 1px solid black;vertical-align: top; text-align: right">{{ $item['memo_pengantar'] }}</td>
                <td style="border: 1px solid black;vertical-align: top; text-align: right">{{ $item['nama_pengirim'] }}</td>
                <td style="border: 1px solid black;vertical-align: top; text-align: right">{{ $item['departemen'] }}</td>
                <td style="border: 1px solid black;vertical-align: top; text-align: right">{{ $item['nomor_kupa'] }}</td>

                <td style="border: 1px solid black;vertical-align: top; text-align: right">
                    @foreach ($kodasampel as $kode)
                    {{ $kode }}<br> @endforeach
                </td>

                <td style="border: 1px solid black;vertical-align: top; text-align: right">
                    {{ $item['jumlah_parameter'] }}
                </td>
                <td style="border: 1px solid black;vertical-align: top; text-align: right">
                    @foreach($item['jumlah_sampel'] as $item1)
                    {{$item1 }}<br>
                    @endforeach
                </td>
                <td style="border: 1px solid black;vertical-align: top; text-align: right">
                    @foreach($item['parameter_analisis'] as $item3)
                    {{$item3 }}<br>
                    @endforeach
                </td>
                <td style="border: 1px solid black;vertical-align: top; text-align: right">
                    @foreach($item['biaya_analisa'] as $item4)
                    {{$item4 }}<br>
                    @endforeach
                </td>
                <td style="border: 1px solid black;vertical-align: top; text-align: right">
                    @foreach($item['sub_total_per_parameter'] as $item5)
                    {{$item5 }}<br>
                    @endforeach
                </td>
                <td style="border: 1px solid black;vertical-align: top; text-align: right">{{ $item['estimasi'] }}</td>
                <td style="border: 1px solid black;vertical-align: top; text-align: right">{{ $item['tanggal_serif'] }}</td>
                <td style="border: 1px solid black;vertical-align: top; text-align: right">{{ $item['no_serif'] }}</td>
                <td style="border: 1px solid black;vertical-align: top; text-align: right">{{ $item['tanggal_kirim_sertif'] }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 2px solid black">Sub Total:</td>
                <td style="border: 2px solid black">{{ $item['sub_total_akhir'] }}</td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 2px solid black">PPN 11%:</td>
                <td style="border: 2px solid black">{{ $item['harga_total_dengan_ppn'] }}</td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 2px solid black">Diskon {{$item['text_disc']}} %:</td>
                <td style="border: 2px solid black">{{ $item['diskon'] }}</td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 2px solid black">Total Harga:</td>
                <td style="border: 2px solid black">{{ $item['total'] }}</td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
            </tr>
            @endforeach


        </tbody>

    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>