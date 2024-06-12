<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasi</title>
    <style>
        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 1080px;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }

        .card-header {
            background-color: #f4f4f4;
            padding: 16px;
            font-size: 1.25em;
            font-weight: bold;
            text-align: center;
            /* Add margin-bottom to create space */
            margin-bottom: 10px;
        }

        .card-body {
            padding: 16px;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            /* Align content at the top */
        }

        .card-body img {
            width: 45%;
            height: auto;
            display: block;
            margin-bottom: 10px;
            border-radius: 8px;
            margin: 10px;
        }
    </style>
</head>

<body>
    @foreach($data as $item)

    <div class="card">
        <div class="card-header">Dokumentasi Sampel {{$item['jenis_sampel']}} No Lab : {{$item['no_lab']}}</div>
        <div class="card-body">
            @foreach($item['foto'] as $foto)
            @if($foto)
            <img src="{{ asset('storage/' . $foto) }}" alt="Image 1">
            @else
            <p>Foto Tidak Tersedia</p>
            @endif
            @endforeach
        </div>
    </div>
    <div style="page-break-after: always;"></div>
    @endforeach

</body>

</html>