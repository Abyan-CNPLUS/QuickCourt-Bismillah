<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Booking Lapangan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        .receipt {
            width: 300px;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background: #2ecc71;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        .content {
            padding: 15px;
        }
        .divider {
            border-top: 1px dashed #ddd;
            margin: 10px 0;
        }
        .detail {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .detail .label {
            font-weight: 600;
            color: #555;
        }
        .detail .value {
            color: #333;
        }
        .footer {
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #777;
            background: #f9f9f9;
        }
        .qr-code {
            text-align: center;
            margin: 15px 0;
        }
        .qr-code img {
            width: 100px;
            height: 100px;
            background: #eee;
            padding: 5px;
        }
        .btn{
            color:white;
            border-radius:10px;
            border-style:none;
            width: 270px;
            height:40px;
            background: blue;
            margin-top:10px;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>BOOKING LAPANGAN FUTSAL</h1>
            <p>Struk Pemesanan</p>
        </div>
        <div class="content">
            <div class="detail">
                <span class="label">No. Booking</span>
                <span class="value">{{$random}}</span>
            </div>
            <div class="detail">
                <span class="label">Tanggal</span>
                <span class="value">{{$date}}</span>
            </div>
            <div class="detail">
                <span class="label">Jam</span>
                <span class="value">09:00</span>
            </div>
            <div class="detail">
                <span class="label">Lapangan</span>
                <span class="value">Lapangan A</span>
            </div>
            <div class="detail">
                <span class="label">Durasi</span>
                <span class="value">2 Jam</span>
            </div>
            
            <div class="divider"></div>
            
            <div class="detail">
                <span class="label">Nama</span>
                <span class="value">{{Auth::user()->name}}</span>
            </div>
            <div class="detail">
                <span class="label">email</span>
                <span class="value">{{Auth::user()->email}}</span>
            </div>
                <!-- <div class="detail">
                <span class="label">password</span>
                <span class="value">        {{Auth::user()->password}}</span>
            </div> -->
            <div class="divider"></div>
            
            <div class="detail">
                <span class="label">Harga/Jam</span>
                <span class="value">Rp 150.000</span>
            </div>
            <div class="detail">
                <span class="label">Total</span>
                <span class="value">Rp 300.000</span>
            </div>
            <form action="">
                <input type="hidden" value='{{ $random }}' name='booking'>
                <input type="hidden" value='{{Auth::user()->name}}' name='name'>
                <input type="hidden">
                <input type="hidden">
                <button class='btn'>Comfrim Payment</button>
            </form>
        </div>
        <div class="footer">
            <p>Terima kasih telah melakukan booking</p>
            <p>Harap tunjukkan struk ini saat kedatangan</p>
        </div>
    </div>
</body>
</html>