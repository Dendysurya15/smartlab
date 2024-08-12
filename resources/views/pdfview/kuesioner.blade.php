<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questionnaire Form</title>
    <style>
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            width: 250px;
            height: auto;
        }

        .company-info {
            text-align: center;
            margin-right: 100px;
        }


        .company-info h2,
        .company-info p {
            margin: 0;
        }

        .introduction {
            margin-top: 5%;
        }

        .introduction p {

            margin: 0;
        }


        .info-table {
            width: 100%;
            /* margin-top: 10%; */
            border-collapse: collapse;
        }

        .rating-table {
            width: 100%;
            /* margin-top: 10%; */
            border-collapse: collapse;
        }

        .rating-table th,
        .rating-table td {
            border: 1px solid #000;
            text-align: center;
            padding: 10px;
        }

        .rating-options {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .feedback-section {
            margin-top: 20px;
        }

        textarea {
            width: 100%;
            padding: 10px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
        }

        .border {
            border: 1px solid black;
            text-align: center;
        }

        .border2 {
            border: 1px solid black;
            text-align: left;
        }

        .underline {
            display: block;
            width: 100%;
            border-bottom: 1px solid black;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    @foreach ($data as $keyx => $valuex)

    @php
    $data_diri = $valuex['Data diri']['text'];
    $pelayanan = $valuex['Pelayanan']['radio'];
    $laporan = $valuex['Laporan']['radio'];
    $kritiksaran = $valuex['Kritik dan saran']['text'][0]['jawaban'];
    @endphp
    <div class="container">
        <!-- Header -->
        <header class="header">
            <table style="width: 100%;border-bottom: 1px solid black">
                <tr>
                    <td style="width: 30%;">
                        <img src="{{asset('images/Logo_CBI_2.png')}}" alt="Company Logo" class="logo">
                    </td>
                    <td style="width: auto;">
                        <div class="company-info">
                            <h2>PT. CITRA BORNEO INDAH</h2>
                            <p>LABORATORIUM PENGUJIAN & KALIBRASI</p>
                            <p>Jl. S Parman No. 4, Pangkalan Bun, Kalimantan Tengah, Indonesia</p>
                            <p>Phone: +62 (0532) 21277</p>
                        </div>
                    </td>
                </tr>
            </table>
        </header>

        <!-- Introduction -->
        <section class="introduction">
            <p>Kepada Yth. Bapak/Ibu Pelanggan setia Laboratorium Pengujian & Kalibrasi PT.CBI</p>
            <p>Laboratorium Pengujian & Kalibrasi PT Citra Borneo Indah (PT CBI), yang telah menerapkan Sistem Manajemen Mutu Layanan Laboratoriom ISO 1725:2017</p>
            <p>memiliki komitmen untuk menempatkan kepuasan Bapak/Ibu para Pelanggan kami yang terhormat di tingkat tertinggi. Memujudkan komitmen kami</p>
            <p>tersebut, PT CBI sangat mengharapkan peran serta Bapak/Ibu untuk memberikan penilaian terhadap jasa analisa laboratorium kami.</p>
            <br>
            <p>Partisipasi Bapak/Ibu sangat berharga bagi kami untuk selalu mengedepankan kepuasan pelanggan. Atas kesetian dalam mengisi questionnaire di bawah ini.</p>
            <p>Kami sampaikan terima kasih sebelumnya.</p>
            <br>
            @php
            $nama_pelanggan = $data_diri[0]['jawaban'] ?? '-';
            @endphp
            @foreach ($data_diri as $key => $value)

            <table style="width: 100%; margin: 0;">
                <tr>
                    <td style="width:20%; margin: 0;">
                        <p>{{$value['pertanyaan']}} </p>
                    </td>
                    <td style="text-align: right;width:5%">
                        :
                    </td>
                    <td>
                        <p>
                            {{$value['jawaban']}}
                        </p>
                    </td>
                    <td>

                    </td>
                </tr>
            </table>

            @endforeach
        </section>
        <section style="padding-top: 10%;">
            <p>Beri tanda (√) dalam pengisian tabel di bawah ini:</p>
        </section>
        <table style="width: 100%;" class="info-table">
            <tr>
                <td class=" border" style="width: 5%;" rowspan="3">NO</td>
                <td class="border" style="width:60%;" rowspan="3">Aspek Penilian</td>
                <td class="border" style="width: auto;" colspan="4">Tindakan Kepuasan Terhadap Layanan Kami</td>
            </tr>
            <tr>
                <td class="border">Tidak Puas</td>
                <td class="border">Kurang Puas</td>
                <td class="border">Puas</td>
                <td class="border">Sangat Puas</td>
            </tr>
            <tr>
                <td class="border">1</td>
                <td class="border">2</td>
                <td class="border">3</td>
                <td class="border">4</td>
            </tr>

            <tr>
                <td class="border" rowspan="8">1</td>
                <td class="border2" colspan="5" style="font-weight:bold">Laporan Hasil Uji (Report of Analysis)</td>

            </tr>
            @foreach ($laporan as $key => $value)
            <tr>
                <td class="border2">{{$value['pertanyaan']}}</td>

                @if($value['jawaban'] ==='1')
                <td class="border">√</td>
                <td class="border">-</td>
                <td class="border">-</td>
                <td class="border">-</td>
                @elseif($value['jawaban'] ==='2')
                <td class="border">-</td>
                <td class="border">√</td>
                <td class="border">-</td>
                <td class="border">-</td>
                @elseif($value['jawaban'] ==='3')
                <td class="border">-</td>
                <td class="border">-</td>
                <td class="border">√</td>
                <td class="border">-</td>
                @elseif($value['jawaban'] ==='4')
                <td class="border">-</td>
                <td class="border">-</td>
                <td class="border">-</td>
                <td class="border">√</td>
                @else
                <td class="border">-</td>
                <td class="border">-</td>
                <td class="border">-</td>
                <td class="border">-</td>
                @endif

            </tr>
            @endforeach
            <tr>
                <td class="border" rowspan="6">2</td>
                <td class="border2" colspan="5" style="font-weight:bold;">Pelayanan</td>

            </tr>
            @foreach ($pelayanan as $key => $value)
            <tr>
                <td class="border2">{{$value['pertanyaan']}}</td>

                @if($value['jawaban'] ==='1')
                <td class="border">√</td>
                <td class="border">-</td>
                <td class="border">-</td>
                <td class="border">-</td>
                @elseif($value['jawaban'] ==='2')
                <td class="border">-</td>
                <td class="border">√</td>
                <td class="border">-</td>
                <td class="border">-</td>
                @elseif($value['jawaban'] ==='3')
                <td class="border">-</td>
                <td class="border">-</td>
                <td class="border">√</td>
                <td class="border">-</td>
                @elseif($value['jawaban'] ==='4')
                <td class="border">-</td>
                <td class="border">-</td>
                <td class="border">-</td>
                <td class="border">√</td>
                @else
                <td class="border">-</td>
                <td class="border">-</td>
                <td class="border">-</td>
                <td class="border">-</td>
                @endif

            </tr>
            @endforeach

        </table>

        <section style="padding-top: 10px;">
            <p>Kritik dan Saran: {{$kritiksaran}} <span class="underline"></span></p>
            <p><span class="underline"></span></p>
            <p><span class="underline"></span></p>
            <p><span class="underline"></span></p>
            <p><span class="underline"></span></p>
            <p><span class="underline"></span></p>
            <p><span class="underline"></span></p>

        </section>


        <!-- ttd  -->


        <footer style="position: fixed; bottom: 0; width: 100%; background-color: white;">
            <table style="width: 100%;margin-top: 20px;">
                <tr>
                    <td style="width: 70%;"></td>
                    <td style="height: 100px;border:1px solid black;text-align:left;vertical-align:top;border-left:none;border-right:none">Hormat Kami</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align:center">(<span style="">{{$nama_pelanggan}}</span>)</td>
                </tr>
            </table>
            <h1 style="font-size: 10px; margin-top: 20px;">
                Note: Mohon berkenan ketersediaan Bapak/Ibu jika sudah mengisi dapat mengirim kembali via email ke cs.labcbi@citraborneo.co.id
            </h1>
            <table style="width: 100%; font-size: 8px; margin-top: 20px;">
                <tr>
                    <td style="width: 30%;">No Dokumen: FR-5.4-2.1</td>
                    <td style="width: 40%; text-align: center;">Halaman 1 dari 1</td>
                    <td style="width: 30%; text-align: right;">Revisi / Berlaku :02 / 01 October 2022</td>
                </tr>
            </table>
        </footer>


    </div>

    <!-- Force page break after each iteration -->
    <div style="page-break-after: always;"></div>
    @endforeach


</body>

</html>