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
            page-break-after: always;
        }

        .card-header {
            background-color: #f4f4f4;
            padding: 16px;
            font-size: 1.25em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }

        .card-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card-body img {
            max-width: 100%;
            /* Ensure the image does not exceed the card width */
            max-height: 100vh;
            /* Ensure the image does not exceed the viewport height */
            height: auto;
            display: block;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    @foreach($data as $item)
    @foreach($item['foto'] as $foto)
    <div class="card">
        <div class="card-header">Dokumentasi Sampel {{$item['jenis_sampel']}} No Lab : {{$item['no_lab']}}</div>
        <div class="card-body">
            @if($foto)
            <img src="{{ asset('storage/' . $foto) }}" alt="Image">
            @else
            <p>Foto Tidak Tersedia</p>
            @endif
        </div>
    </div>
    @endforeach
    @endforeach
</body>

</html>