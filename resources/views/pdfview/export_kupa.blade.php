<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export KUPA</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        .page-container {
            position: relative;
            width: 100%;
            min-height: 100%;
        }

        .main-table-container {
            width: 100%;
        }

        .footer-table-container {
            width: 100%;
            margin-top: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            padding: 4px 6px;
            font-size: 11px;
        }

        .border-full {
            border: 1px solid black;
        }

        .border-top {
            border-top: 1px solid black;
        }

        .border-bottom {
            border-bottom: 1px solid black;
        }

        .border-left {
            border-left: 1px solid black;
        }

        .border-right {
            border-right: 1px solid black;
        }

        .border-no-left {
            border-top: 1px solid black;
            border-right: 1px solid black;
            border-bottom: 1px solid black;
            border-left: none;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .v-top {
            vertical-align: top;
        }

        .v-center {
            vertical-align: middle;
        }

        .font-bold {
            font-weight: bold;
        }

        .page-break {
            page-break-before: always;
        }

        .page-info {
            text-align: right;
            font-size: 10px;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    @foreach ($data as $keysx => $valuex)
    @foreach ($valuex['pages'] as $pageIndex => $pageData)
    {{-- Page break untuk setiap halaman kecuali yang pertama --}}
    @if ($pageIndex > 0 || !$loop->parent->first)
    <div class="page-break"></div>
    @endif

    <div class="page-container">
        {{-- Info halaman jika lebih dari 1 page --}}
        @if (count($valuex['pages']) > 1)
        <div class="page-info">
            Halaman {{ $pageData['page_number'] }} dari {{ $pageData['total_pages'] }}
        </div>
        @endif

        <div class="main-table-container">
            <table>
                {{-- ==================== HEADER ==================== --}}
                <thead>
                    <tr>
                        <td style="width: 5px;"></td>
                        <td rowspan="4" colspan="2">
                            <div>
                                @if (defaultIconPT($valuex['tanggal_penerimaan']))
                                <img src="{{ asset('images/Logo_CBI_2.png') }}" style="height:60px;margin-left:50px">
                                @else
                                <img src="{{ asset('images/logocorp.png') }}" style="height:60px;margin-left:50px">
                                @endif
                            </div>
                        </td>
                        <td colspan="16" class="text-center border-left border-right font-bold" style="height: 40px; font-size:14px">
                            @php
                            $pt = defaultPTname($valuex['tanggal_penerimaan']);
                            @endphp
                            {{ $pt['nama'] }}
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 5px;"></th>
                        <td colspan="16" class="text-center border-left border-right font-bold" style="height: 40px; font-size:14px">
                            {{ $pt['nama_lab'] }}
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 5px;"></th>
                        <td colspan="16" class="border-full text-center">Formulir</td>
                    </tr>
                    <tr>
                        <th style="width: 5px;"></th>
                        <td colspan="16" class="border-full text-center">
                            @if($valuex['formulir'] != null)
                            {{ $valuex['formulir'] }}
                            @else
                            Kaji Ulang Permintaan, Tender dan Kontrak Sampel {{ $valuex['jenis_kupa'] ?? '' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 5px;"></th>
                        <th colspan="2" class="border-no-left">No.Dokumen</th>
                        <th colspan="5" class="border-full text-center">Revisi</th>
                        <th colspan="6" class="border-full text-center">Berlaku Efektif</th>
                        <th colspan="5" class="border-full text-center">Halaman</th>
                    </tr>
                    <tr>
                        <th style="width: 5px;"></th>
                        <th colspan="2" class="border-no-left">
                            {{ $valuex['doc'] ?? 'FR.7.1-12' }}
                        </th>
                        <th colspan="5" class="border-full text-center">{{ $pt['revisi'] }}</th>
                        <th colspan="6" class="border-full text-center">{{ $pt['tanggal_berlaku'] }}</th>
                        <th colspan="5" class="border-full text-center">1 dari 1</th>
                    </tr>
                    <tr>
                        <th style="width: 5px;"></th>
                        <th colspan="2">Tanggal Penerimaan</th>
                        <th colspan="5" class="text-left">: {{ $valuex['tanggal_penerimaan'] ?? '' }}</th>
                        <th colspan="6"></th>
                        <th colspan="2" class="text-left">Jenis Sampel</th>
                        <th colspan="3" class="text-left">: {{ $valuex['jenis_kupa'] ?? '' }}</th>
                    </tr>
                    <tr>
                        <th style="width: 5px;"></th>
                        <th colspan="2">No. Kaji Ulang</th>
                        <th colspan="5" class="text-left">: {{ $valuex['no_kupa'] ?? '' }}</th>
                        <th colspan="6"></th>
                        <th colspan="2" class="text-left">Nama Pelanggan</th>
                        <th colspan="3" class="text-left">: {{ $valuex['nama_pengirim'] ?? '' }}</th>
                    </tr>
                    <tr>
                        <th style="width: 5px;"></th>
                        <th colspan="2"></th>
                        <th colspan="5"></th>
                        <th colspan="6"></th>
                        <th colspan="2" class="text-left">Departemen</th>
                        <th colspan="3" class="text-left">: {{ $valuex['departemen'] ?? '' }}</th>
                    </tr>

                    {{-- Column Headers --}}
                    <tr>
                        <th style="width: 5px;"></th>
                        <th rowspan="2" class="border-no-left text-center">No. Surat Pelanggan</th>
                        <th rowspan="2" class="border-full text-center">Kemasan Sampel</th>
                        <th rowspan="2" class="border-full text-center">Jumlah Sampel</th>
                        <th rowspan="2" class="border-full text-center">Nomor Laboratorium</th>
                        <th rowspan="2" colspan="2" class="border-full text-center">Parameter Analisis</th>
                        <th rowspan="2" class="border-full text-center">Metode Analisis</th>
                        <th rowspan="2" class="border-full text-center">Satuan</th>
                        <th colspan="3" class="border-full text-center">Sumber Daya Laboratorium</th>
                        <th colspan="3" class="border-full text-center">Biaya Analisa (Rp)</th>
                        <th class="border-full text-center">Konfirmasi</th>
                        <th colspan="2" class="border-full text-center">Kondisi Sampel</th>
                        <th rowspan="2" class="border-full text-center">Tanggal Penyelesaian Analisa</th>
                    </tr>
                    <tr>
                        <th style="width: 5px;"></th>
                        <th class="border-full text-center" style="font-size:9px">Personel (Tersedia dan Kompeten)</th>
                        <th class="border-full text-center" style="font-size:9px">Alat (Tersedia dan Baik)</th>
                        <th class="border-full text-center" style="font-size:9px">Bahan Kimia (Tersedia dan Baik)</th>
                        <th class="border-full text-center">Jumlah Sampel</th>
                        <th class="border-full text-center">Harga Per Sampel</th>
                        <th class="border-full text-center">Sub Total</th>
                        <th class="border-full text-center" style="font-size:9px">Langsung / Telepon / Email</th>
                        <th class="border-full text-center">Normal</th>
                        <th class="border-full text-center">Abnormal</th>
                    </tr>
                </thead>

                {{-- ==================== BODY ==================== --}}
                <tbody>
                    @foreach ($pageData['data'] as $rowIndex => $items)
                    <tr>
                        <td style="width: 5px;"></td>

                        {{-- Kolom dengan ROWSPAN FULL PAGE: No Surat, Kemasan, Jum Sampel, Nomor Lab --}}
                        @if ($items['show_main_cols'])
                        <td rowspan="{{ $pageData['total_row'] }}" class="border-no-left v-top text-left">{{ $items['no_surat'] }}</td>
                        <td rowspan="{{ $pageData['total_row'] }}" class="border-no-left v-top text-left">{{ $items['kemasan'] }}</td>
                        <td rowspan="{{ $pageData['total_row'] }}" class="border-no-left v-top text-center">{{ $items['jum_sampel'] }}</td>
                        <td rowspan="{{ $pageData['total_row'] }}" class="border-full v-center text-left">
                            <span style="width: 100%; display: block;">{{ $valuex['labkiri'] }}</span>
                            @if ($valuex['labkanan'])
                            <span style="width: 100%; display: block; border-top: 1px solid black; margin-top: 2px; padding-top: 2px;">{{ $valuex['labkanan'] }}</span>
                            @endif
                        </td>
                        @endif

                        {{-- Parameter Analisis (selalu tampil) --}}
                        <td class="border-full v-center text-left">{{ $items['Parameter_Analisis'] }}</td>
                        <td class="border-full v-center text-center">{{ $items['mark'] }}</td>
                        <td class="border-full v-center text-left">{{ $items['Metode_Analisis'] }}</td>
                        <td class="border-full v-center text-center">{{ $items['satuan'] }}</td>

                        {{-- Kolom dengan ROWSPAN GROUP: Personel, Alat, Bahan, Biaya, Konfirmasi, Kondisi --}}
                        @if ($items['show_group_cols'])
                        <td rowspan="{{ $items['cols'] }}" class="border-full v-center text-center">{{ $items['Personel'] }}</td>
                        <td rowspan="{{ $items['cols'] }}" class="border-full v-center text-center">{{ $items['alat'] }}</td>
                        <td rowspan="{{ $items['cols'] }}" class="border-full v-center text-center">{{ $items['bahan'] }}</td>
                        <td rowspan="{{ $items['cols'] }}" class="border-full v-center text-center">{{ $items['jum_data'] ?: '' }}</td>
                        <td rowspan="{{ $items['cols'] }}" class="border-full v-center text-center">
                            @if ($items['jum_harga'])
                            @money($items['jum_harga'], 'IDR', 'True')
                            @endif
                        </td>
                        <td rowspan="{{ $items['cols'] }}" class="border-full v-center text-center">
                            @if ($items['jum_sub_total'])
                            @money($items['jum_sub_total'], 'IDR', 'True')
                            @endif
                        </td>
                        <td rowspan="{{ $items['cols'] }}" class="border-full v-center text-center">{{ $items['Konfirmasi'] }}</td>
                        @if ($items['kondisi_sampel'] === 'Normal')
                        <td rowspan="{{ $items['cols'] }}" class="border-full v-center text-center">✔</td>
                        <td rowspan="{{ $items['cols'] }}" class="border-full v-center text-center"></td>
                        @else
                        <td rowspan="{{ $items['cols'] }}" class="border-full v-center text-center"></td>
                        <td rowspan="{{ $items['cols'] }}" class="border-full v-center text-center">{{ $items['kondisi_sampel'] }}</td>
                        @endif
                        @endif

                        {{-- Estimasi (ROWSPAN FULL PAGE) --}}
                        @if ($items['show_main_cols'])
                        <td rowspan="{{ $pageData['total_row'] }}" class="border-full v-top text-center">{{ $items['estimasi'] }}</td>
                        @endif
                    </tr>
                    @endforeach

                    {{-- ==================== TOTAL (hanya di halaman terakhir) ==================== --}}
                    @if ($pageData['is_last_page'])
                    @foreach ($valuex['result_total'] as $totalRow)
                    <tr>
                        @foreach ($totalRow as $colIndex => $item)
                        @if ($colIndex == 0)
                        <td>{{ $item }}</td>
                        @elseif ($colIndex == 5)
                        <td colspan="4" class="border-no-left v-center text-center font-bold">{{ $item }}</td>
                        @else
                        <td class="border-no-left v-center text-center">{{ $item }}</td>
                        @endif
                        @endforeach
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        {{-- ==================== FOOTER (hanya di halaman terakhir) ==================== --}}
        @if ($pageData['is_last_page'])
        <div class="footer-table-container">
            <table style="width:100%">
                <thead>
                    <tr>
                        <th class="border-full" style="width:15%;">Dibuat Oleh</th>
                        <th class="border-full" style="width:15%;">Diketahui Oleh</th>
                        <th class="border-full" style="width:15%;">Disetujui Oleh</th>
                        <th class="border-full" style="width:40%;">Catatan Khusus</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center border-left border-right">&nbsp;</td>
                        <td class="text-center border-left border-right">&nbsp;</td>
                        <td class="text-center border-left border-right">&nbsp;</td>
                        <td rowspan="6" class="text-left v-top border-full">{{ $valuex['catatan'] }}</td>
                    </tr>
                    <tr>
                        <td class="text-center border-left border-right">
                            @if ($valuex['approval'] == 'Draft' || $valuex['approval'] == 'Waiting Admin Approval')
                            <span>CREATED</span><br>
                            <span>{{ $valuex['memo_created'] }}</span>
                            @else
                            <span style="color: blue; font-size: 16px; font-weight: bold;">APPROVED</span><br>
                            <span>{{ $valuex['verifikasi_admin_timestamp'] }}</span>
                            @endif
                        </td>
                        <td class="text-center border-left border-right">
                            @if ($valuex['isVerifiedByHead'])
                            <span style="color: blue; font-size: 16px; font-weight: bold;">APPROVED</span><br>
                            <span>{{ $valuex['verifikasi_head_timestamp'] }}</span>
                            @endif
                        </td>
                        <td class="text-center border-left border-right">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="text-center border-left border-right">&nbsp;</td>
                        <td class="text-center border-left border-right">&nbsp;</td>
                        <td class="text-center border-left border-right">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="text-center border-left border-right">&nbsp;</td>
                        <td class="text-center border-left border-right">&nbsp;</td>
                        <td class="text-center border-left border-right">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="text-center border-left border-right border-bottom">{{ $valuex['petugas_penerima_sampel'] }}</td>
                        <td class="text-center border-left border-right border-bottom">Budi Umbara</td>
                        <td class="text-center border-left border-right border-bottom">{{ $valuex['nama_pengirim'] }}</td>
                    </tr>
                    <tr>
                        <td class="text-center border-full">Petugas Penerima Sampel</td>
                        <td class="text-center border-full">Manager Laboratorium</td>
                        <td class="text-center border-full">Pelanggan</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
    </div>
    @endforeach
    @endforeach

</body>

</html>