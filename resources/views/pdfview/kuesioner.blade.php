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
            color: #000000;
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

        .container {
            background-image: url('{{ asset("images/logotext-removebg-preview.png") }}');
            background-repeat: no-repeat;
            background-position: left;
            background-size: contain;
            /* atau: cover, 100% auto, dll */
            background-attachment: fixed;
            /* opsional: agar gambar tidak ikut scroll */
            opacity: 0.1;
            position: relative;
            height: 100vh;
            /* ini penting agar kontainer 1 full page tinggi */
        }

        .watermark {
            position: fixed;
            top: 65%;
            right: 0;
            transform: rotate(90deg) translateY(-50%);
            transform-origin: right top;
            font-size: 14px;
            background: transparent;
            padding: 10px;
            writing-mode: vertical-rl;
            text-orientation: mixed;
            z-index: 999;
            color: #893D41;
            font-weight: bold;
            opacity: 0.8;
            /* transparansi rendah */
            pointer-events: none;
            /* biar gak ganggu klik elemen lain */
        }
    </style>
</head>

<body>
    @foreach ($data as $keyx => $valuex)

    @php
    $data_diri = $valuex['Data diri']['text'];
    $pelayanan = $valuex['Pelayanan']['radio'];
    $signature = $valuex['signature'];
    $datetime = $valuex['datetime'];
    $laporan = $valuex['Laporan']['radio'] ?? $valuex['Laporan hasil uji (Report of Analysis)']['radio'] ;
    $kritiksaran = $valuex['Kritik dan saran']['text'][0]['jawaban'];
    @endphp

    <div class="watermark">
        Dilarang memperbanyak dokumen ini tanpa seizin Laboratorium Pengujian PT Sulung Research Station
    </div>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <table style="width: 100%;border-bottom: 1px solid black">
                <tr>
                    <td style="width: 25%;">
                        <img src="{{asset('images/logocorp.png')}}" alt="Company Logo" class="logo">
                    </td>
                    <td style="width: auto;">
                        <div class="company-info">
                            <h2>PT. Sulung Research Station</h2>
                            <p>LABORATORIUM PENGUJIAN</p>
                            <p>JL.Sulung Kenambui km.30.4, Desa Runtu, Kec. Arut Selatan Kab. Kotawaringin Barat, 71171</p>
                            <p>Email cs.labcbi@citraborneo.co.id, Telp (0532) 21277 line 5691</p>
                        </div>
                    </td>
                </tr>
            </table>
        </header>

        <!-- Introduction -->
        <table style="width: 100%;">
            <tr>
                <td>Yth. Bapak/Ibu Pelanggan setia Laboratorium Pengujian PT Sulung Research Station</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Laboratorium Pengujian PT Sulung Research Station, yang telah menerapkan Sistem Manajemen Mutu Layanan Laboratoriom ISO 1725:2017</td>
            </tr>
            <tr>
                <td>memiliki komitmen untuk menempatkan kepuasan Bapak/Ibu para Pelanggan kami yang terhormat di tingkat tertinggi. Memujudkan komitmen kami</td>
            </tr>
            <tr>

                <td>tersebut, PT SRS sangat mengharapkan peran serta Bapak/Ibu untuk memberikan penilaian terhadap jasa.</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Partisipasi Bapak/Ibu sangat berharga bagi kami untuk selalu mengedepankan kepuasan pelanggan. Atas kesetian dalam mengisi questionnaire di bawah ini.</td>
            </tr>
            <tr>
                <td>Kami sampaikan terima kasih sebelumnya.</td>
            </tr>
        </table>

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

        <table style="width: 100%;">
            <tr>
                <td>
                    Beri tanda (√) dalam pengisian tabel di bawah ini:
                </td>
            </tr>
        </table>

        <table style="width: 95%;" class="info-table">
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
                <td class="border" rowspan="6">1</td>
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
                <td class="border" rowspan="9">2</td>
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

        <table style="width: 95%; margin-top: 56px;">
            <tr>
                <td>
                    Kritik dan Saran: {{$kritiksaran}}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="underline"></span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="underline"></span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="underline"></span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="underline"></span>
                </td>
            </tr>
        </table>

        <!-- ttd  -->


        <footer style="position: fixed; bottom: 0; width: 100%; background-color: transparent;">
            <table style="width: 100%;margin-top: 20px;">
                <tr>
                    <td style="width: 70%;"></td>
                    <td style="text-align:left;vertical-align:top;border-left:none;border-right:none">Hormat Kami,{{$datetime}}</td>
                </tr>
                <tr>
                    <td style="width: 70%;"></td>
                    <td style="border: 1px solid black;text-align:left;vertical-align:top;border-left:none;border-right:none;border-bottom:none">
                        <img src="{{ asset('storage/' . $signature) }}" alt="Image">
                    </td>
                </tr>
                <tr>
                    <td style="width: 70%;"></td>
                    <td style="text-align:center;border-bottom:1px solid black"><span style="font-size: 10px;"></span></td>
                </tr>
                <tr>
                    <td style="width: 70%;"></td>
                    <td style="text-align:center">(<span>{{$nama_pelanggan}}</span>)</td>
                </tr>
            </table>

            <table style="width: 100%; font-size: 8px; margin-top: 40px;">
                <tr>
                    <td>
                        Note: Note : Mohon berkenan ketersediaan Bapak/Ibu jika sudah mengisi dapat mengirim kembali via email ke cs.labcbi@citraborneo.co.id
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%;">No Dokumen: FR-5.4-2.1</td>
                    <td style="width: 40%; text-align: center;">Halaman 1 dari 1</td>
                    <td style="width: 30%; text-align: right;">Revisi 00 / Berlaku :01 Mei 2025</td>
                </tr>
            </table>
        </footer>


    </div>

    @if(!$loop->last)
    <div style="page-break-after: always;"></div>
    @endif
    @endforeach


</body>

</html>