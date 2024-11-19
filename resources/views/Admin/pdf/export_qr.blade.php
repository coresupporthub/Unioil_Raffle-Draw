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
        body{
            text-align: center;
        }
        table {
            width: 2in;
            border-collapse: collapse;
            page-break-before: auto;
            page-break-after: auto;
            margin: auto;
        }
        tbody tr {
            page-break-inside: avoid;
        }
        td {
            background-image: url('{{ $entry == 'Single Entry QR Code' ? public_path('unioil_images/entry_coupon_1.jpg') : public_path('unioil_images/entry_coupon_2.jpg') }}');
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
            border: 1px solid #ccc;
            position: relative;
            width:2in;
            height: 1.5in;
        }
        td img {
            position: absolute;
            bottom: 13mm;
            right: 1.2mm;
            width: 18mm;
            height: 18mm;
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
