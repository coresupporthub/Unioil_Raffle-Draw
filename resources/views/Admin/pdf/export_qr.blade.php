<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $file_title }}</title>
    <style>
         @page {
            margin: 1mm;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            page-break-before: auto;
            page-break-after: auto;
        }
        tbody tr {
            page-break-inside: avoid;
        }
        td {
            background-image: url('{{ public_path('unioil_images/coupon_bw.jpg') }}');
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
            border: 1px solid #ccc;
            position: relative;
            width:2.70in;
            height: 1.70in;
        }
        td img {
            position: absolute;
            bottom: 7.2mm;
            right: 2.1mm;
            width: 100px;
            height: 115px;
        }

    </style>
</head>
<body>
    @foreach ($qrCodeChunkBy24 as $qrCodeBy8)
    <table >

        @foreach ($qrCodeBy8 as $qrCodeBy3)
        <tr>
            @foreach ($qrCodeBy3 as $qrCode)
            <td style="text-align: center;">
                <img src="{{ $qrCode['image_base64'] }}">
            </td>
            @endforeach

        </tr>
        @endforeach

    </table>

    @endforeach


</body>
</html>
