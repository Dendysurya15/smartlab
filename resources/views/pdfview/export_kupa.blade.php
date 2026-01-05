<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>KUPA Export</title>

    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            line-height: 1.3;
            color: #000;
            padding: 5mm;
            /* Padding tambahan untuk keamanan print */
        }

        /* Page Setup for DomPDF */
        @page {
            size: A4 landscape;
            margin: 15mm 15mm 15mm 15mm;
            /* top right bottom left - margin lebih lebar untuk print */
        }

        /* Container dengan padding */
        .document-container {
            width: 100%;
            position: relative;
            padding: 2mm;
        }

        /* Force page break between documents */
        .page-break {
            page-break-before: always;
        }

        /* ============ HEADER STYLES ============ */
        .header-section {
            width: 100%;
            margin-bottom: 8px;
        }

        .company-header {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        .company-header td {
            padding: 3px 5px;
            vertical-align: middle;
        }

        .logo-cell {
            width: 100px;
            text-align: center;
            border-right: 1px solid #000;
        }

        .logo-cell img {
            max-height: 50px;
            max-width: 90px;
        }

        .company-name {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            border-bottom: 1px solid #000;
        }

        .lab-name {
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            padding: 4px;
            border-bottom: 1px solid #000;
        }

        .form-label {
            font-size: 10px;
            text-align: center;
            padding: 3px;
            border-bottom: 1px solid #000;
        }

        .form-title {
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            padding: 4px;
        }

        /* Document Info Row */
        .doc-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: -1px;
        }

        .doc-info-table td,
        .doc-info-table th {
            border: 1px solid #000;
            padding: 3px 5px;
            text-align: center;
            font-size: 9px;
        }

        .doc-info-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        /* Meta Info */
        .meta-info {
            width: 100%;
            margin: 8px 0;
            border-collapse: collapse;
        }

        .meta-info td {
            padding: 2px 5px;
            font-size: 9px;
            vertical-align: top;
        }

        .meta-label {
            font-weight: bold;
            width: 130px;
        }

        /* ============ DATA TABLE STYLES ============ */
        .data-table-wrapper {
            width: 100%;
            margin-bottom: 10px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 3px 4px;
            font-size: 8px;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
            overflow: hidden;
        }

        .data-table thead th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 8px;
        }

        /* CRITICAL: Make thead repeat on each page */
        .data-table thead {
            display: table-header-group;
        }

        .data-table tbody {
            display: table-row-group;
        }

        /* Prevent row breaks */
        .data-table tr {
            page-break-inside: avoid;
        }

        /* Column widths - total should be 100% */
        .col-nosurat {
            width: 7%;
        }

        .col-kemasan {
            width: 5%;
        }

        .col-jumlah {
            width: 3%;
        }

        .col-nolab {
            width: 5%;
        }

        .col-parameter {
            width: 8%;
        }

        .col-mark {
            width: 3%;
        }

        .col-metode {
            width: 9%;
        }

        .col-satuan {
            width: 4%;
        }

        .col-personel {
            width: 5%;
        }

        .col-alat {
            width: 5%;
        }

        .col-bahan {
            width: 5%;
        }

        .col-jmlsampel {
            width: 4%;
        }

        .col-harga {
            width: 7%;
        }

        .col-subtotal {
            width: 7%;
        }

        .col-konfirmasi {
            width: 6%;
        }

        .col-normal {
            width: 4%;
        }

        .col-abnormal {
            width: 4%;
        }

        .col-tanggal {
            width: 6%;
        }

        /* Text alignment helpers */
        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .v-top {
            vertical-align: top !important;
        }

        .v-middle {
            vertical-align: middle !important;
        }

        /* Total rows styling */
        .total-row td {
            font-weight: bold;
            background-color: #fafafa;
        }

        .total-label {
            text-align: right !important;
            padding-right: 10px !important;
        }

        /* ============ FOOTER/SIGNATURE STYLES ============ */
        .footer-section {
            width: 100%;
            margin-top: 15px;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table th,
        .signature-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9px;
            text-align: center;
            vertical-align: middle;
        }

        .signature-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .signature-cell {
            height: 50px;
            vertical-align: middle;
        }

        .catatan-cell {
            vertical-align: top;
            text-align: left;
            font-size: 8px;
        }

        .approved-stamp {
            color: #0066cc;
            font-size: 12px;
            font-weight: bold;
        }

        .created-stamp {
            font-size: 10px;
        }

        .position-label {
            background-color: #f5f5f5;
            font-size: 8px;
        }

        /* Checkmark styling */
        .checkmark {
            color: #000;
            font-size: 12px;
        }

        /* Lab number cell with divider */
        .lab-number-cell {
            position: relative;
        }

        .lab-divider {
            border-top: 1px solid #000;
            margin: 2px 0;
        }

        /* Hide duplicate values visually but keep cell structure */
        .cell-continuation {
            border-top: none !important;
        }

        /* ============ PRINT MEDIA QUERY ============ */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }

            .document-container {
                padding: 0;
            }

            /* Pastikan header table repeat di setiap halaman */
            .data-table thead {
                display: table-header-group;
            }

            /* Cegah row terpotong */
            .data-table tr {
                page-break-inside: avoid;
            }

            /* Cegah footer terpotong */
            .footer-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    @foreach ($data as $keysx => $valuex)
    @php
    $pt = defaultPTname($valuex['tanggal_penerimaan']);
    $totalRows = count($valuex['data']);
    @endphp

    <div class="document-container {{ !$loop->first ? 'page-break' : '' }}">

        {{-- ========== HEADER SECTION ========== --}}
        <div class="header-section">
            <table class="company-header">
                <tr>
                    <td class="logo-cell" rowspan="4">
                        @if (defaultIconPT($valuex['tanggal_penerimaan']))
                        <img src="{{ asset('images/Logo_CBI_2.png') }}" alt="Logo">
                        @else
                        <img src="{{ asset('images/logocorp.png') }}" alt="Logo">
                        @endif
                    </td>
                    <td class="company-name">{{ $pt['nama'] }}</td>
                </tr>
                <tr>
                    <td class="lab-name">{{ $pt['nama_lab'] }}</td>
                </tr>
                <tr>
                    <td class="form-label">Formulir</td>
                </tr>
                <tr>
                    <td class="form-title">
                        @if($valuex['formulir'] != null)
                        {{ $valuex['formulir'] }}
                        @else
                        Kaji Ulang Permintaan, Tender dan Kontrak Sampel {{ $valuex['jenis_kupa'] ?? '' }}
                        @endif
                    </td>
                </tr>
            </table>

            {{-- Document Info --}}
            <table class="doc-info-table">
                <tr>
                    <th style="width: 15%;">No.Dokumen</th>
                    <th style="width: 15%;">Revisi</th>
                    <th style="width: 20%;">Berlaku Efektif</th>
                    <th style="width: 15%;">Halaman</th>
                </tr>
                <tr>
                    <td>{{ $valuex['doc'] ?? 'FR.7.1-12' }}</td>
                    <td>{{ $pt['revisi'] }}</td>
                    <td>{{ $pt['tanggal_berlaku'] }}</td>
                    <td>1 dari 1</td>
                </tr>
            </table>

            {{-- Meta Information --}}
            <table class="meta-info">
                <tr>
                    <td class="meta-label">Tanggal Penerimaan</td>
                    <td style="width: 25%;">: {{ $valuex['tanggal_penerimaan'] ?? '-' }}</td>
                    <td class="meta-label">Jenis Sampel</td>
                    <td>: {{ $valuex['jenis_kupa'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="meta-label">No. Kaji Ulang</td>
                    <td>: {{ $valuex['no_kupa'] ?? '-' }}</td>
                    <td class="meta-label">Nama Pelanggan</td>
                    <td>: {{ $valuex['nama_pengirim'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="meta-label"></td>
                    <td></td>
                    <td class="meta-label">Departemen</td>
                    <td>: {{ $valuex['departemen'] ?? '-' }}</td>
                </tr>
            </table>
        </div>

        {{-- ========== DATA TABLE ========== --}}
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th rowspan="2" class="col-nosurat">No. Surat<br>Pelanggan</th>
                        <th rowspan="2" class="col-kemasan">Kemasan<br>Sampel</th>
                        <th rowspan="2" class="col-jumlah">Jml<br>Sampel</th>
                        <th rowspan="2" class="col-nolab">Nomor<br>Lab</th>
                        <th rowspan="2" class="col-parameter">Parameter<br>Analisis</th>
                        <th rowspan="2" class="col-mark">&nbsp;</th>
                        <th rowspan="2" class="col-metode">Metode<br>Analisis</th>
                        <th rowspan="2" class="col-satuan">Satuan</th>
                        <th colspan="3">Sumber Daya Laboratorium</th>
                        <th colspan="3">Biaya Analisa (Rp)</th>
                        <th rowspan="2" class="col-konfirmasi">Konfirmasi</th>
                        <th colspan="2">Kondisi Sampel</th>
                        <th rowspan="2" class="col-tanggal">Tgl<br>Selesai</th>
                    </tr>
                    <tr>
                        <th class="col-personel">Personel</th>
                        <th class="col-alat">Alat</th>
                        <th class="col-bahan">Bahan<br>Kimia</th>
                        <th class="col-jmlsampel">Jml</th>
                        <th class="col-harga">Harga/<br>Sampel</th>
                        <th class="col-subtotal">Sub Total</th>
                        <th class="col-normal">Normal</th>
                        <th class="col-abnormal">Abnormal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($valuex['data'] as $key => $items)
                    <tr>
                        {{-- PENTING: Kolom 1-4 SELALU dirender di setiap baris untuk menghindari masalah rowspan + page break --}}
                        {{-- Baris pertama: tampilkan nilai, baris berikutnya: cell kosong --}}
                        @if ($key == 0)
                        <td class="v-top text-left">{{ $items['no_surat'] }}</td>
                        <td class="v-top text-left">{{ $items['kemasan'] }}</td>
                        <td class="v-top text-center">{{ $items['jum_sampel'] }}</td>
                        <td class="v-middle text-left">
                            {{ $valuex['labkiri'] }}
                            <div class="lab-divider"></div>
                            {{ $valuex['labkanan'] }}
                        </td>
                        @else
                        {{-- Baris berikutnya: cell kosong dengan border-top none untuk efek visual merged --}}
                        <td class="cell-continuation"></td>
                        <td class="cell-continuation"></td>
                        <td class="cell-continuation"></td>
                        <td class="cell-continuation"></td>
                        @endif

                        {{-- Parameter info - always shown --}}
                        <td class="text-left">{{ $items['Parameter_Analisis'] }}</td>
                        <td class="text-center">{{ $items['mark'] }}</td>
                        <td class="text-left">{{ $items['Metode_Analisis'] }}</td>
                        <td class="text-center">{{ $items['satuan'] }}</td>

                        {{-- Resource columns with rowspan --}}
                        @if ($items['cols'] != 0)
                        <td rowspan="{{ $items['cols'] }}">{{ $items['Personel'] }}</td>
                        <td rowspan="{{ $items['cols'] }}">{{ $items['alat'] }}</td>
                        <td rowspan="{{ $items['cols'] }}">{{ $items['bahan'] }}</td>

                        {{-- Cost columns - always render cells for consistent borders --}}
                        <td rowspan="{{ $items['cols'] }}">{{ $items['jum_data'] ?: '' }}</td>
                        <td rowspan="{{ $items['cols'] }}" class="text-right">
                            @if($items['jum_harga'] != 0)
                            @money($items['jum_harga'], 'IDR','True')
                            @endif
                        </td>
                        <td rowspan="{{ $items['cols'] }}" class="text-right">
                            @if($items['jum_sub_total'] != 0)
                            @money($items['jum_sub_total'], 'IDR','True')
                            @endif
                        </td>

                        {{-- Confirmation & Condition --}}
                        <td rowspan="{{ $items['cols'] }}">{{ $items['Konfirmasi'] }}</td>
                        @if ($items['kondisi_sampel'] === 'Normal')
                        <td rowspan="{{ $items['cols'] }}"><span class="checkmark">✔</span></td>
                        <td rowspan="{{ $items['cols'] }}"></td>
                        @else
                        <td rowspan="{{ $items['cols'] }}"></td>
                        <td rowspan="{{ $items['cols'] }}">{{ $items['kondisi_sampel'] }}</td>
                        @endif
                        @endif

                        {{-- Completion date - only first row, but NO rowspan to avoid page break issues --}}
                        @if ($key == 0)
                        <td class="v-top">{{ $items['estimasi'] }}</td>
                        @else
                        <td class="cell-continuation"></td>
                        @endif
                    </tr>
                    @endforeach

                    {{-- TOTAL ROWS --}}
                    @foreach ($valuex['result_total'] as $datas)
                    <tr class="total-row">
                        @foreach ($datas as $index => $item)
                        @if ($index == 0)
                        {{-- Skip empty first column --}}
                        @elseif ($index == 5)
                        <td colspan="4" class="total-label">{{ $item }}</td>
                        @else
                        <td>{{ $item }}</td>
                        @endif
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ========== FOOTER/SIGNATURE SECTION ========== --}}
        <div class="footer-section">
            <table class="signature-table">
                <tr>
                    <th style="width: 20%;">Dibuat Oleh</th>
                    <th style="width: 20%;">Diketahui Oleh</th>
                    <th style="width: 20%;">Disetujui Oleh</th>
                    <th style="width: 40%;">Catatan Khusus</th>
                </tr>
                <tr>
                    <td class="signature-cell">
                        @if ($valuex['approval'] == 'Draft' || $valuex['approval'] == 'Waiting Admin Approval')
                        <div class="created-stamp">CREATED</div>
                        <div style="font-size: 8px;">{{ $valuex['memo_created'] }}</div>
                        @else
                        <div class="approved-stamp">APPROVED</div>
                        <div style="font-size: 8px;">{{ $valuex['verifikasi_admin_timestamp'] }}</div>
                        @endif
                    </td>
                    <td class="signature-cell">
                        @if ($valuex['isVerifiedByHead'] == True)
                        <div class="approved-stamp">APPROVED</div>
                        <div style="font-size: 8px;">{{ $valuex['verifikasi_head_timestamp'] }}</div>
                        @endif
                    </td>
                    <td class="signature-cell">&nbsp;</td>
                    <td rowspan="3" class="catatan-cell">{{ $valuex['catatan'] }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">{{ $valuex['petugas_penerima_sampel'] }}</td>
                    <td style="font-weight: bold;">Budi Umbara</td>
                    <td style="font-weight: bold;">{{ $valuex['nama_pengirim'] }}</td>
                </tr>
                <tr>
                    <td class="position-label">Petugas Penerima Sampel</td>
                    <td class="position-label">Manager Laboratorium</td>
                    <td class="position-label">Pelanggan</td>
                </tr>
            </table>
        </div>

    </div>
    @endforeach

</body>

</html>