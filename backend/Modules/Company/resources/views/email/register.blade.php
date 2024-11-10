<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AKTIVASI EMAIL</title>
    <style>
        .content{
            display: flex;
            justify-content: center;
            width: 100%;
        }
        .wrap-content{
            display: block;
        }
        .header{
            margin: 30px 0;
        }
        .header p{
            font-size: 15px;
            color: rgb(53, 53, 53);
        }
        .button{
            background-color: rgb(33, 33, 132);
            color: white;
            font-weight: 700;
            font-size: 15px;
            padding: 8px 15px 10px 15px;
            border-radius: 10px;
            text-transform: uppercase;
            text-decoration: none;
        }
        .logo{
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
        }
        footer{
            text-align: center;
            margin-top: 50px;
            color: rgb(38, 38, 38);
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="wrap-content">
            <p class="logo">Disini Logo Platform</p>
            <div class="header">
                <p>
                    Selamat datang di <strong>PROPERTY ERP</strong>, 
                    senang anda bisa bergabung dengan Platform Angeline Universe. 
                    Sebelum anda bisa mengakses platform. Silahkan untuk mengklik tombol dibawah untuk mengaktivasi perusahaan anda
                </p>
            </div>
            <a class="button" href={{'http://127.0.0.1:8000/company/email/activated/' . $token}}>Aktivasi Perusahaan</a>
        </div>
    </div>
    <footer>
        <p style="font-size: 13px;">Powered By PT. Angeline Universe Inc</p>
    </footer>
</body>
</html>