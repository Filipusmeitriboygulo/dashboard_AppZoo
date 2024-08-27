<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tiket </title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
        }

        .ticket-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px;
        }

        .ticket {
            display: flex;
            justify-content: space-between;
            align-items: stretch;
            width: 15cm;
            height: 12cm;
            background-color: #fff;
            border: 2px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .stub {
            background-color: #15F5BA;
            color: white;
            padding: 20px;
            width: 15cm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .stub .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .stub .barcode {
            text-align: center;
        }

        .ticket-info {
            padding: 20px;
            width: 14cm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .ticket-info h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .ticket-info p {
            font-size: 14px;
            margin: 5px 0;
        }

        .ticket-info .details {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .ticket-info .details .section {
            width: 45%;
        }

        .ticket-info .details .section h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .ticket-info .details .section p {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="ticket-container">
        <div class="ticket">
            <div class="stub">
                <div class="logo">
                    <h2>B-Zoo : Kebun Binatang Medan</h2>
                </div>
            </div>
            <div class="ticket-info">
                <h1>Tiket Masuk</h1>
                <p>No.Tiket       : {{ $pembeli->id_pesanan }}</p>
                <p>Nama Pengunjung: {{ $pembeli->nama }}</p>
                <p>Tanggal        : {{ $pembeli->pesanan->tanggal_pesanan }}</p>
                <p>Jumlah Tiket   : {{ $pembeli->pesanan->jumlah_tiket }}</p>
                <p>Total Harga    : Rp {{ number_format($pembeli->pesanan->jumlah_tiket * $pembeli->pesanan->harga, 0, ',', '.') }} 
                <div class="details">
                    <div class="section">
                        <h2>Waktu Masuk</h2>
                        <p>09:00 - 17:00</p>
                    </div>
                    <div class="section">
                        <h2>Lokasi</h2>
                        <p>B-Zoo, Kota Medan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>
