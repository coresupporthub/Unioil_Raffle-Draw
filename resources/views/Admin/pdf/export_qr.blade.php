<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @foreach ($qrCodeChunk as $qrCode)
    <div>
        @foreach ($qrCode as $qr)
        <img src="{{ public_path('qr-codes/'. $qr['image']) }}" alt="Logo" style="width: 140px; height: auto;">
        @endforeach
    </div>
    @endforeach
</body>
</html>
