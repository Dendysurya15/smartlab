<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identitas Sampel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid black;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
        }

        .text-center {
            text-align: center;
        }

        .border-right-none {
            border-right: none !important;
            border-bottom: none !important;
            border-top: none !important;
        }

        .border-left-none {
            border-left: none !important;

        }

        .border-top-none {
            border-top: none !important;
        }

        .border-bottom-none {
            border-bottom: none !important;
        }

        .border-left-right-none {
            border-left: none !important;
            border-right: none !important;
            border-bottom: none !important;
            border-top: none !important;
        }
    </style>
</head>

<body>
    @foreach ($data as $index => $item)
    @php
    $pt = defaultPTname($item['tanggal_terima']);
    $noDoc = $item['no_doc_indentitas'] ?? 'FR-7.4-1.2-1';
    @endphp

    <!-- Header Table -->
    <table>
        <tr>
            <th rowspan="4" colspan="3" style="width: 15%; border-right: 1px solid black;">
                @if (defaultIconPT($item['tanggal_terima']))
                <img src="{{ asset('images/Logo_CBI_2.png') }}" style="height:60px;margin-left:50px">
                @else
                <img src="{{ asset('images/logocorp.png') }}" style="height:60px;margin-left:50px">
                @endif
            </th>
            <th colspan="22" class="text-center" style="font-weight:bold;font-size:14px;height:40px;">
                {{ $pt['nama'] }}
            </th>
        </tr>
        <tr>
            <th colspan="22" class="text-center" style="font-size:14px;font-weight:bold;height:40px;">
                {{ $pt['nama_lab'] }}
            </th>
        </tr>
        <tr>
            <th colspan="22" class="text-center">Formulir</th>
        </tr>
        <tr>
            <th colspan="22" class="text-center">
                Identitas Sampel {{ $item['jenis_sampel'] ?? '-' }}
            </th>
        </tr>
        <tr>
            <th colspan="3" class="border-left-none">No.Dokumen</th>
            <th colspan="8" class="text-center">Revisi</th>
            <th colspan="10" class="text-center">Berlaku Efektif</th>
            <th colspan="4" class="text-center">Halaman</th>
        </tr>
        <tr>
            <th colspan="3" class="border-left-none">{{ $noDoc }}</th>
            <th colspan="8" class="text-center">{{ $pt['revisi'] }}</th>
            <th colspan="10" class="text-center">{{ $pt['tanggal_berlaku'] }}</th>
            <th colspan="4" class="text-center">1 dari 1</th>
        </tr>
        <tr>
            <th colspan="3" class="border-right-none">No Order</th>
            <th colspan="8" class="border-left-right-none">: {{ $item['no_order'] }}</th>
            <th colspan="10" class="border-left-right-none"></th>
            <th colspan="2" class="border-left-right-none">Tanggal Penyelesaian</th>
            <th colspan="2" class="border-left-right-none">: {{ $item['tanggal_penyelesaian'] ?? '-' }}</th>
        </tr>
        <tr>
            <th colspan="3" class="border-right-none">Tanggal Terima</th>
            <th colspan="8" class="border-left-right-none">: {{ $item['tanggal_terima'] }}</th>
            <th colspan="10" class="border-left-right-none"></th>
            <th colspan="2" class="border-left-right-none">Kondisi fisik sampel</th>
            <th colspan="2" class="border-left-right-none">: {{ $item['kondisi_sampel'] ?? '-' }}</th>
        </tr>
        <tr>
            <th colspan="3" class="border-right-none">Jumlah Sampel</th>
            <th colspan="8" class="border-left-right-none">: {{ $item['jumlah_sampel'] }}</th>
            <th colspan="10" class="border-left-right-none"></th>
            <th colspan="2" class="border-left-right-none">Jenis Sampel</th>
            <th colspan="2" class="border-left-right-none">: {{ $item['jenis_pupuk'] ?? '-' }}</th>
        </tr>
    </table>


    <!-- Parameter Analysis Table -->
    <table>
        <thead>
            <tr>
                <th rowspan="2" class="text-center" style="width: 5%;">No</th>
                <th rowspan="2" class="text-center" style="width: 10%;">NO.Lab</th>
                <th colspan="{{ count($item['namaparams']) }}" class="text-center">Parameter Analisis</th>
                <th rowspan="2" class="text-center" style="width: 12%;">Tanggal Preparasi</th>
                <th rowspan="2" class="text-center" style="width: 8%;">Ket.</th>
                <th rowspan="2" class="text-center" style="width: 8%;">Paraf</th>
            </tr>
            <tr>
                @foreach ($item['namaparams'] as $param)
                <th class="text-center">{{ $param }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
            $chunkSize = 40; // Jumlah row per halaman
            $dataChunks = array_chunk($item['data'], $chunkSize, true);
            $totalChunks = count($dataChunks);
            @endphp

            @foreach($dataChunks as $chunkIndex => $dataChunk)
            @foreach($dataChunk as $key => $sampel)
            <tr>
                <td class="text-center">{{ $sampel['id'] }}</td>
                <td class="text-center">{{ $sampel['nomor_lab'] }}</td>
                @foreach ($item['namaparams'] as $param)
                <td class="text-center">
                    @if (in_array($param, $sampel['parameter_sampel']))
                    √
                    @endif
                </td>
                @endforeach
                @if($loop->first)
                <td rowspan="{{ count($dataChunk) }}"></td>
                <td rowspan="{{ count($dataChunk) }}"></td>
                <td rowspan="{{ count($dataChunk) }}"></td>
                @endif
            </tr>
            @endforeach

            @if($chunkIndex < $totalChunks - 1)
                </tbody>
    </table>
    <div style="page-break-after: always;"></div>

    <!-- Repeat header for next page -->
    <table>
        <thead>
            <tr>
                <th rowspan="2" class="text-center" style="width: 5%;">No</th>
                <th rowspan="2" class="text-center" style="width: 10%;">NO.Lab</th>
                <th colspan="{{ count($item['namaparams']) }}" class="text-center">Parameter Analisis</th>
                <th rowspan="2" class="text-center" style="width: 12%;">Tanggal Preparasi</th>
                <th rowspan="2" class="text-center" style="width: 8%;">Ket.</th>
                <th rowspan="2" class="text-center" style="width: 8%;">Paraf</th>
            </tr>
            <tr>
                @foreach ($item['namaparams'] as $param)
                <th class="text-center">{{ $param }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @endif
            @endforeach
        </tbody>
    </table>

    <!-- Signature Table - Langsung di bawah tanpa page break -->
    <table style="margin-top: 25px;">
        <thead>
            <tr>
                <th style="width: 15%;">Diserah terimakan oleh</th>
                <th style="width: 15%;">Diperiksa oleh</th>
                <th style="width: 15%;">Diterima Oleh</th>
                <th style="width: 15%;">Diverifikasi Oleh</th>
                <th style="width: 40%;" colspan="2">Catatan Khusus</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center border-bottom-none">&nbsp;</td>
                <td class="text-center border-bottom-none">&nbsp;</td>
                <td class="text-center border-bottom-none">&nbsp;</td>
                <td class="text-center border-bottom-none">&nbsp;</td>
                <td colspan="2" rowspan="5" style="vertical-align:top;">
                    Ket = ( √ ) : Telah dilakukan Preparasi<br>
                    {{ $item['catatan'] ?? '' }}
                </td>
            </tr>
            <tr>
                <td class="text-center border-top-none border-bottom-none">
                    @if ($item['status'] == 1)
                    <span style="color: blue;font-size: 20px;">APPROVED</span><br>
                    <span style="font-size: 15px;">{{ $item['status_timestamp'] }}</span>
                    @endif
                </td>
                <td class="text-center border-top-none border-bottom-none">&nbsp;</td>
                <td class="text-center border-top-none border-bottom-none">&nbsp;</td>
                <td class="text-center border-top-none border-bottom-none">&nbsp;</td>
            </tr>
            <tr>
                <td class="text-center border-top-none border-bottom-none">&nbsp;</td>
                <td class="text-center border-top-none border-bottom-none">&nbsp;</td>
                <td class="text-center border-top-none border-bottom-none">&nbsp;</td>
                <td class="text-center border-top-none border-bottom-none">&nbsp;</td>
            </tr>
            <tr>
                <td class="text-center border-bottom-none">{{ $item['PenerimaSampel'] ?? '' }}</td>
                <td class="text-center border-bottom-none">{{ $item['Penyelia'] ?? '' }}</td>
                <td class="text-center border-bottom-none">{{ $item['Preparasi'] ?? '' }}</td>
                <td class="text-center border-bottom-none">{{ $item['Staff'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="text-center">Petugas Penerima Sampel</td>
                <td class="text-center">Penyelia</td>
                <td class="text-center">Petugas Preparasi</td>
                <td class="text-center">Staff Kimia dan Lingkungan</td>
            </tr>
        </tbody>
    </table>

    @if (!$loop->last)
    <div style="page-break-after: always;"></div>
    @endif
    @endforeach
</body>

</html>