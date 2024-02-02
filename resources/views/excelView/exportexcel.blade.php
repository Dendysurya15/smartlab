<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>


    <table>

        <thead>

            <tr>
                <th></th>
                <td rowspan="4" colspan="2" style="border-left:1px solid black;"></td>
                <td colspan="18"
                    style="border-left:1px solid black;border-right: 1px solid black;height: 40px;font-weight:bold;font-size:14px">
                    PT . CITRA BORNEO INDAH</td>
            </tr>
            <tr>
                <th></th>
                <td colspan="18"
                    style="border-left:1px solid black;border-right: 1px solid black;height: 40px;font-size:14px;font-weight:bold">
                    RESEARCH AND DEVELOPMENT - LABORATORIUM ANALITIK</td>
            </tr>
            <tr>
                <th></th>
                <td colspan="18" style="border: 1px solid black;">Formulir</td>
            </tr>
            <tr>
                <th></th>
                <td colspan="18" style="border: 1px solid black;">Kaji Ulang Permintaa, Tender dan Kontrak Sampel
                    {{$jenis_kupa}} </td>
            </tr>
            <tr>
                <th></th>
                <th colspan="2" style="border: 1px solid black;font-weight:bold">
                    No.Dokumen
                </th>
                <th colspan="5" style="border: 1px solid black;text-align:center;">
                    Revisi
                </th>
                <th colspan="8" style="border: 1px solid black;text-align:center;">
                    Berlaku Efektif
                </th>
                <th colspan="5" style="border: 1px solid black;text-align:center;">
                    Halaman
                </th>
            </tr>
            <tr>
                <th></th>
                <th colspan="2" style="border: 1px solid black;">
                    FR.7.1-12
                </th>
                <th colspan="5" style="border: 1px solid black;text-align:center;">
                    02
                </th>
                <th colspan="8" style="border: 1px solid black;text-align:center;">
                    1-jul-21
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
                    : {{$tanggal_penerimaan}}
                </th>

                <th colspan="12" style="border-top: 1px solid black;"></th>
                <th colspan="3" style="border-top: 1px solid black;">Jenis Sampel</th>
                <th style="border-top: 1px solid black;border-right: 1px solid black;">: {{$jenis_kupa}}</th>
            </tr>
            <tr>
                <th></th>
                <th colspan="2" style="border-left: 1px solid black;border-bottom: 1px solid black;">
                    No. Kaji Ulang
                </th>
                <th style="border-bottom: 1px solid black;">
                    : {{$no_kupa}}
                </th>

                <th colspan="13" style="border-bottom: 1px solid blacck;"></th>
                <th colspan="3" style="border-bottom: 1px solid black;">Nama Pelanggan</th>
                <th style="border-bottom: 1px solid black;border-right:1px solid black">: {{$nama_pengirim}}</th>
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

                <th colspan="5" style="border: 1px solid black;text-align:center;">
                    Biaya Analisa (Rp)
                </th>
                <th style="border: 1px solid black;text-align:center;">
                    Konfirmasi
                </th>
                <th colspan="2" style="border: 1px solid black;text-align:center;">
                    Kondisi Sample
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
                    ppn 11%
                </th>
                <th style="border: 1px solid black;text-align:center;">
                    Total
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

            @foreach ($kupa as $data)
            <tr style="border-left:1px solid black;border-right:1px solid black">
                <td></td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_no_surat']}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_kemasan']}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_jum_sampel']}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_no_lab']}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_param']}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">
                    {{ $data['col_mark'] == 1 ? '✓' : '' }}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_metode']}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_satuan']}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_personel']}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_alat']}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_bahan']}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_jum_sampel_2']}}
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_harga']}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_sub_total']}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_ppn']}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_total']}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_langsung']}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_normal']}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_abnormal']}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;">{{$data['col_tanggal']}}</td>
            </tr>
            @endforeach


            <tr>
                <td></td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td colspan="4"
                    style="font-weight: bold;border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">
                    Total</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td
                    style="font-weight: bold;border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                    {{$final_total}}</td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
                <td style="border-left: 1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                </td>
            </tr>

            <tr>
                <td colspan="20"></td> <!-- Replace "20" with the total number of columns in your table -->
            </tr>

            <tr>
                <td></td>
                <td colspan="2" style="border: 1px solid black;">Dibuat Oleh,</td>
                <td colspan="3" style="border: 1px solid black;">Diketahui Oleh,</td>
                <td colspan="3" style="border: 1px solid black;">Disetujui Oleh,</td>
                <td colspan="12"
                    style="border-top: 1px solid black;border-right:1px solid black; text-decoration: underline;">
                    Catatan Khusus : </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2" rowspan="5" style="border: 1px solid black;">
                    (............................................)</td>
                <td colspan="3" rowspan="5" style="border:1px solid black">
                    (............................................)</td>
                <td colspan="3" rowspan="5" style="border:1px solid black">
                    (............................................)</td>
                <td colspan="12" style="border-right: 1px solid black;"></td>
            </tr>

            <tr>
                <td></td>
                <td colspan="12" style="border-right: 1px solid black;"></td>
            </tr>

            <tr>
                <td></td>
                <td colspan="12" style="border-right: 1px solid black;"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="12" style="border-right: 1px solid black;"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="12" style="border-right: 1px solid black;"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2" style="border: 1px solid black;">Petugas Penerima Sampel</td>
                <td colspan="3" style="border: 1px solid black;">Manager Laboratorium</td>
                <td colspan="3" style="border: 1px solid black;">Pelanggan</td>
                <td colspan="12" style="border-bottom: 1px solid black;border-right: 1px solid black;"></td>
            </tr>

        </tbody>
    </table>



    <!-- <table style="margin-top: 20px">
        <thead>
            <tr>
                <th style="border: 1px solid black;">Di buat oleh</th>
                <th style="border: 1px solid black;">DIketahi Oleh</th>
                <th style="border: 1px solid black;">Di setujui Oleh</th>
                <th style="border: 1px solid black;">Catatan</th>
            </tr>
        </thead>
        <tbody>
            <tr style="height: 180px;">
                <td style="text-align:left;border-left: 1px solid black;width: 350px;"></td>
                <td style="text-align:left;border-left: 1px solid black;width: 350px;"></td>
                <td style="text-align:left;border-left: 1px solid black;width: 350px;"></td>
                <td style="text-align:left;border-left: 1px solid black;width: 900px;"></td>
            </tr>

            <tr>
                <td style="text-align:left;border-bottom:1px solid black;border-left:1px solid black;width: 350px;">(Erna Wati)</td>
                <td style="text-align:left;border-bottom:1px solid black;border-left:1px solid black;width: 350px;">(Budi Umbara)</td>
                <td style="text-align:left;border-bottom:1px solid black;border-left:1px solid black;width: 350px;">(Lujian Kurniawan)</td>
                <td style="text-align:left;border-left:1px solid black;border-right:1px solid black;width: 900px;"> </td>
            </tr>
            <tr>
                <td style="text-align:left;border-bottom:1px solid black;border-left:1px solid black;width: 350px;">Petugas Penerima Sampel </td>
                <td style="text-align:left;border-bottom:1px solid black;border-left:1px solid black;width: 350px;">Manager Laboratorium</td>
                <td style="text-align:left;border-bottom:1px solid black;border-left:1px solid black;width: 350px;">Pelanggan</td>
                <td style="text-align:left;border-bottom:1px solid black;border-right:1px solid black;border-left:1px solid black;width: 900px;">Sampel basah </td>
            </tr>
        </tbody>
    </table> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>