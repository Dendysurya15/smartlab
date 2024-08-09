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
            width: 100px;
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


        .info-table,
        .rating-table {
            width: 100%;
            margin-top: 10%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 5px;
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
    </style>
</head>

<body>
    @foreach ($data as $key => $value)
    <div class="container">
        <!-- Header -->
        <header class="header">
            <table style="width: 100%;border-bottom: 1px solid black">
                <tr>
                    <td style="width: 30%;">
                        <img src="{{asset('images/CBI-logo.png')}}" alt="Company Logo" class="logo">
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
        </section>

        <table class="info-table">
            <tr>
                <td style="text-align: center;border:1px solid black">NO</td>
                <td colspan="2" style="text-align: center;border:1px solid black">Aspek Penilian</td>
                <td style="text-align: center;border:1px solid black">Tindakan Kepuasan Terhadap Layanan Kami</td>
            </tr>
            @php
            $no = 1;
            @endphp
            @foreach ($value as $key1 => $value1)
            <tr>
                <td style="border:1px solid black">{{$no++}}</td>
                <td style="text-align: center;border:1px solid black">{{$key1}}</td>
                @foreach ($value1 as $key2 => $value2)
                <td style="border:1px solid black">
                    @foreach ($value2 as $key3 => $value3)
                    - {{$value3['pertanyaan']}}<br>
                    @endforeach
                </td>
                <td style="border:1px solid black;">
                    @foreach ($value2 as $key3 => $value3)
                    - <span style="margin-top:25px;">{{$value3['jawaban']}}</span> <br>
                    @endforeach
                </td>
                @endforeach
            </tr>
            @endforeach

        </table>

        <!-- ttd  -->


        <footer style="position: fixed; bottom: 0; width: 100%; background-color: white;">
            <table style="width: 100%;margin-top: 20px;">
                <tr>
                    <td style="width: 70%;"></td>
                    <td style="height: 100px;border:1px solid black;text-align:left;vertical-align:top;border-left:none;border-right:none">Hormat Kami</td>
                </tr>
                <tr>
                    <td></td>
                    <td>(<span style="text-decoration: underline;text-align:end">Nama Pelanggan</span>)</td>
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