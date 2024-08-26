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
            background: #ddd;
        }

        .ticket {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px auto;
            width: 18cm;
            /* Disesuaikan untuk ukuran kertas A4 */
            height: 8cm;
            /* Tinggi tiket disesuaikan */
            background-color: #fff;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
        }

        .stub {
            background: #ef5658;
            height: 100%;
            width: 7cm;
            /* Sesuaikan proporsi */
            color: white;
            padding: 20px;
            position: relative;
            box-sizing: border-box;
        }

        .stub:before,
        .stub:after {
            content: '';
            position: absolute;
            width: 0;
            border-style: solid;
        }

        .stub:before {
            top: 0;
            right: 0;
            border-width: 20px 0 0 20px;
            border-color: transparent transparent transparent #dd3f3e;
        }

        .stub:after {
            bottom: 0;
            right: 0;
            border-width: 0 0 20px 20px;
            border-color: transparent transparent #dd3f3e transparent;
        }

        .top {
            display: flex;
            align-items: center;
            text-transform: uppercase;
            margin-bottom: 40px;
        }

        .line {
            width: 3px;
            height: 40px;
            background: #fff;
            margin: 0 20px;
        }

        .num {
            font-size: 12px;
            line-height: 1.2;
        }

        .num span {
            display: block;
            font-weight: bold;
            color: #000;
        }

        .number {
            font-size: 60px;
            position: absolute;
            bottom: 20px;
            left: 20px;
            font-weight: bold;
        }

        .invite {
            position: absolute;
            bottom: 20px;
            right: 20px;
            font-size: 14px;
            font-weight: bold;
            text-align: right;
        }

        .invite:before {
            content: '';
            display: block;
            width: 40px;
            height: 3px;
            background: #fff;
            margin-bottom: 5px;
        }

        .check {
            background: #fff;
            height: 100%;
            width: 11cm;
            /* Sesuaikan proporsi */
            padding: 20px;
            position: relative;
            box-sizing: border-box;
            border-left: 2px dashed #ef5658;
        }

        .check:before,
        .check:after {
            content: '';
            position: absolute;
            width: 0;
            border-style: solid;
        }

        .check:before {
            top: 0;
            left: 0;
            border-width: 20px 20px 0 0;
            border-color: #dd3f3e transparent transparent transparent;
        }

        .check:after {
            bottom: 0;
            left: 0;
            border-width: 0 20px 20px 0;
            border-color: transparent transparent #dd3f3e transparent;
        }

        .big {
            font-size: 40px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        .number-check {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 30px;
            font-weight: bold;
            color: #ef5658;
        }

        .info {
            font-size: 14px;
        }

        .info section {
            margin-bottom: 10px;
        }

        .title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="ticket">
        <div class="stub">
            <div class="top">
                <span class="admit">B-Zoo</span>
                <span class="line"></span>
                <span class="num">
                    Kebun Binatang,
                    <span>Medan</span>
                </span>
            </div>
            <div class="number">{{ $pembeli->id_pesanan }}</div>
            <div class="invite">Invite for you</div>
        </div>
        <div class="check">
            <div class="big">You're <br> Invited</div>
            <div class="number-check">{{ $pembeli->id_pesanan }}</div>
            <div class="info">
                <section>
                    <div class="title">Tanggal :</div>
                    <div>{{ $pembeli->pesanan->tanggal_pesanan }}</div>
                </section>
                <section>
                    <div class="title">Jumlah Tiket :</div>
                    <div>{{ $pembeli->pesanan->jumlah_tiket }}</div>
                </section>
                <section>
                    <div class="title">Total Harga :</div>
                    <div>Rp {{ number_format($pembeli->pesanan->jumlah_tiket * $pembeli->pesanan->harga, 0, ',', '.') }}
                    </div>
                </section>
            </div>
        </div>
    </div>
</body>

</html>
