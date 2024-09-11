<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>

<body>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th colspan="18" style="border:1px solid black;text-align:center;vertical-align:center">PT. CITRA BORNEO INDAH </th>
            </tr>
            <tr>
                <th style="border:1px solid black;text-align:center;vertical-align:center" rowspan="3" colspan="2"></th>
                <th style="border:1px solid black;text-align:left;vertical-align:center" colspan="16">RESEARCH AND DEVELOPMENT - LABORATORIUM ANALITIK</th>

            </tr>
            <tr>
                <th style="border:1px solid black;text-align:left;vertical-align:center" colspan="16"></th>
            </tr>
            <tr>
                <th style="border:1px solid black;text-align:left;vertical-align:center" colspan="16"></th>
            </tr>
        </thead>
    </table>


    <table>
        <thead>
            <tr>
                <th rowspan="2" style="border:1px solid black;text-align:center;vertical-align:center">NO</th>
                <th rowspan="2" style="border:1px solid black;text-align:center;vertical-align:center">tanggal sample</th>
                <th rowspan="2" style="border:1px solid black;text-align:center;vertical-align:center">Sampel</th>
                <th rowspan="2" style="border:1px solid black;text-align:center;vertical-align:center">Jenis Sampel</th>
                <th rowspan="2" style="border:1px solid black;text-align:center;vertical-align:center">Asal Sampel</th>
                <th rowspan="2" style="border:1px solid black;text-align:center;vertical-align:center">No Kupa</th>
                <th rowspan="2" style="border:1px solid black;text-align:center;vertical-align:center">No Lab</th>
                <th style="border:1px solid black;text-align:center;vertical-align:center" colspan="2">Client</th>

                <th rowspan="2" style="border:1px solid black;text-align:center;vertical-align:center">No Surat</th>
                <th rowspan="2" style="border:1px solid black;text-align:center;vertical-align:center">Parameter Analisa</th>
                <th rowspan="2" style="border:1px solid black;text-align:center;vertical-align:center">Kode Sampel</th>
                <th rowspan="2" style="border:1px solid black;text-align:center;vertical-align:center">Estimasi KUPA</th>
                <th rowspan="2" style="border:1px solid black;text-align:center;vertical-align:center">Tanggal Selesai Analisa</th>
                <th colspan="3" style="border:1px solid black;text-align:center;vertical-align:center">Keterangan</th>
                <th rowspan="2" style="border:1px solid black;text-align:center;vertical-align:center">Jumlah Parameter</th>
                <th rowspan="2" style="border:1px solid black;text-align:center;vertical-align:center">Jumlah Sampel</th>
            </tr>

            <tr>
                <th style="border:1px solid black;text-align:center;vertical-align:center">Nama Pengirim</th>
                <th style="border:1px solid black;text-align:center;vertical-align:center">Departemen</th>
                <th style="border:1px solid black;text-align:center;vertical-align:center">Tanggal Rilis Sertifikat</th>
                <th style="border:1px solid black;text-align:center;vertical-align:center">No. Sertifikat</th>
                <th style="border:1px solid black;text-align:center;vertical-align:center">Rupiah (Rp)</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data as $item)
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
                <td style="border:1px solid black;">{{$inc++}}</td>
                <td style="border:1px solid black;">{{$item2['tanggal_terima']}}</td>
                <td style="border:1px solid black;">{{$item2['jenis_sample']}}</td>
                <td style="border:1px solid black; white-space: nowrap;">
                    {{$item2['jenissample_komuditas']}}
                </td>

                <td style="border:1px solid black;">{{$item2['asal_sampel']}}</td>
                <td style="border:1px solid black;">{{$item2['nomor_kupa']}}</td>
                <td style="border:1px solid black;">{{$item2['nomor_lab']}}</td>
                <td style="border:1px solid black;">{{$item2['nama_pengirim']}}</td>
                <td style="border:1px solid black;">{{$item2['departemen']}}</td>
                <td style="border:1px solid black;">{{$item2['nomor_surat']}}</td>
                <td style="border:1px solid black;">{{$item2['Parameter_Analisa']}}</td>
                <td style="border:1px solid black;">{{$item2['kode_sampel']}}</td>
                <td style="border:1px solid black;">{{$item2['estimasi']}}</td>
                <td style="border:1px solid black;">{{$item2['Tanggal_Selesai_Analisa']}}</td>
                <td style="border:1px solid black;">{{$item2['Tanggal_Rilis_Sertifikat']}}</td>
                <td style="border:1px solid black;">{{$item2['No_sertifikat']}}</td>
                @if ($item2['total'] !== 'null')
                <td style="border:1px solid black;text-align:center;vertical-align:center" rowspan="{{$rowspan}}">{{$item2['total']}}</td>
                @endif
                <td style="border:1px solid black;text-align:center;vertical-align:center">{{$jum_samptotal}}</td>
                @if ($item2['jumlah_sampel'] !== 'null')
                <td style="border:1px solid black;text-align:center;vertical-align:center" rowspan="{{$rowspan}}">{{$item2['jumlah_sampel']}}</td>
                @endif

            </tr>

            @endforeach
            @endif
            @endforeach
            @endforeach


        </tbody>
    </table>
</body>

</html>