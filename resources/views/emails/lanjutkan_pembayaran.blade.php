<!DOCTYPE html>
<html>
<head>
    <title>Continue Payment</title>
</head>
<body>
    <h1>Hai, {{ $pembeliNama }}</h1>
    <p>Jika ingin melanjutkan pembayaran klik link berikut ya: </p>
    <a href="{{ $paymentUrl }}">Klik</a>
</body>
</html>