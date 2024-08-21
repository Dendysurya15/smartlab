<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

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

        table {
            page-break-inside: avoid !important;
            page-break-before: auto !important;
        }
    </style>
    @foreach ($data as $item)
    <div style="border: 1px solid black;">
        <div style="margin: 10px;">


            <div style="border: 1px solid black;text-align:center">
                <h2 class="text-center">PT. CITRA BORNEO INDAH
                </h2>
            </div>


            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="vertical-align: middle; padding-left: 0; width: 10%;border:0;">
                        <div>
                            <img src="{{ asset('images/Logo_CBI_2.png') }}" style="height:60px">
                        </div>
                    </td>
                    <td style="width:30%;border:0;">

                        <p style="text-align: left;">RESEARCH & DEVELOPMENT - LABORATORIUM ANALITIK</p>
                        <p style="text-align: left;">{{$item['jenis']}}</p>

                    </td>
                    <td style=" width: 20%;border:0;">
                    </td>
                    <td style="vertical-align: middle; text-align: right;width:40%;border:0;">
                        <div class="right-container">
                            <div class="text-container">


                            </div>
                        </div>
                    </td>
                </tr>
            </table>


            <table style="width: 100%; border-collapse: collapse;padding:10px">
                <thead>
                    <tr>
                        <th rowspan="2" class="border-text">NO</th>
                        <th rowspan="2" class="border-text">tanggal sample</th>
                        <th rowspan="2" class="border-text">Jenis Sampel</th>
                        <th rowspan="2" class="border-text">Asal Sampel</th>
                        <th rowspan="2" class="border-text">No Kupa</th>
                        <th rowspan="2" class="border-text">No Lab</th>
                        <th class="border-text" colspan="2">Client</th>

                        <th rowspan="2" class="border-text">No Surat</th>
                        <th rowspan="2" class="border-text">Parameter Analisa</th>
                        <th rowspan="2" class="border-text">Tujuan</th>
                        <th rowspan="2" class="border-text">Kode Sampel</th>
                        <th rowspan="2" class="border-text">Estimasi KUPA</th>
                        <th rowspan="2" class="border-text">Tanggal Selesai Analisa</th>
                        <th colspan="3" class="border-text">Keterangan</th>
                        <th rowspan="2" class="border-text">Jumlah Parameter</th>
                        <th rowspan="2" class="border-text">Jumlah Sampel</th>
                        <th rowspan="2" class="border-text">Catatan</th>
                    </tr>

                    <tr>
                        <th class="border-text">Nama Pengirim</th>
                        <th class="border-text">Departemen</th>
                        <th class="border-text">Tanggal Rilis Sertifikat</th>
                        <th class="border-text">No. Sertifikat</th>
                        <th class="border-text">Rupiah (Rp)</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($item as $item1)
                    @if (is_array($item1))
                    @php
                    $inc = 1;
                    @endphp
                    @php
                    $rowspan = count($item1);
                    @endphp
                    @foreach ($item1 as $item2)
                    @php
                    $jum_kodesamp = explode(',',$item2['kode_sampel']);
                    $jum_samptotal = count($jum_kodesamp);
                    @endphp
                    <tr>
                        <td class="border-text">{{$inc++}}</td>
                        <td class="border-text">{{$item2['tanggal_terima']}}</td>
                        <td class="border-text">{{$item2['jenis_sample']}}</td>
                        <td class="border-text">{{$item2['asal_sampel']}}</td>
                        <td class="border-text">{{$item2['nomor_kupa']}}</td>
                        <td class="border-text">{{$item2['nomor_lab']}}</td>
                        <td class="border-text">{{$item2['nama_pengirim']}}</td>
                        <td class="border-text">{{$item2['departemen']}}</td>
                        <td class="border-text">{{$item2['nomor_surat']}}</td>
                        <td class="border-text">{{$item2['Parameter_Analisa']}}</td>
                        <td class="border-text">{{$item2['tujuan']}}</td>
                        <td class="border-text">{{$item2['kode_sampel']}}</td>
                        <td class="border-text">{{$item2['estimasi']}}</td>
                        <td class="border-text">{{$item2['Tanggal_Selesai_Analisa']}}</td>
                        <td class="border-text">{{$item2['Tanggal_Rilis_Sertifikat']}}</td>
                        <td class="border-text">{{$item2['No_sertifikat']}}</td>
                        @if ($item2['total'] !== 'null')
                        <td class="border-text" rowspan="{{$rowspan}}">{{$item2['total']}}</td>
                        @endif
                        <td class="border-text">{{$jum_samptotal}}</td>
                        @if ($item2['jumlah_sampel'] !== 'null')
                        <td class="border-text" rowspan="{{$rowspan}}">{{$item2['jumlah_sampel']}}</td>
                        @endif
                        @if ($item2['catatan'] !== 'null')
                        <td class="border-text" rowspan="{{$rowspan}}">{{$item2['catatan']}}</td>
                        @endif
                    </tr>

                    @endforeach
                    @endif
                    @endforeach


                </tbody>
            </table>

        </div>
    </div>
    <div style="page-break-after: always;"></div>
    @endforeach


</body>

</html>