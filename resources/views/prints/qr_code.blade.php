<!-- resources/views/prints/qr_code.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'QR Code')</title>
    <link rel="icon" href="{{ asset('assets/img/velaris.png') }}" type="image/png">
    <style>
        @media print {
            .container {
                display: block;
                margin: 0;
                font-family: Arial, sans-serif;
            }
            .sticker {
                display: flex;
                flex-direction: column;
                align-items: center;
                border: 2px solid #000;
                padding: 10px;
                background-color: #f9f9f9;
                width: 100mm; /* Adjust for your size */
                height: 100mm; /* Adjust for your size */
                box-sizing: border-box;
                page-break-inside: avoid;
                position: relative; /* Position relative to contain absolute elements */
                overflow: hidden;
            }
            .qr-code {
                position: relative; /* Position relative to contain the logo */
            }
            .qr-code img {
                width: 60mm; /* Adjust size for print */
                height: 60mm; /* Adjust size for print */
            }
            .logo {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 20mm;
                height: 20mm;
            }
            .serial-number {
                font-size: 12pt; 
                font-weight: bold;
                margin-top: 5mm;
            }
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .sticker {
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 2px solid #000;
            padding: 10px;
            background-color: #f9f9f9;
            width: 250px; 
            height: 265px;
            box-sizing: border-box;
            position: relative;
        }
        .qr-code {
            position: relative; 
        }
        .qr-code img {
            width: 100px; /* Adjust size for screen */
            height: 100px; /* Adjust size for screen */
        }
        .logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 30px; /* Adjust size for screen */
            height: 30px; /* Adjust size for screen */
        }
        .serial-number {
            font-size: 16px; /* Adjust font size for screen */
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sticker" id="sticker">
            <div class="qr-code">
                {!! $qrCode !!}
                <img src="{{ asset('assets/img/GSI.png') }}" alt="GSI Logo" class="logo">
            </div>
            <div class="serial-number">
            {{ $inventory->seri }}
            </div>
        </div>
    </div>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
