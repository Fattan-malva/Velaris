<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'QR Code')</title>
    <link rel="icon" href="{{ asset('assets/img/velaris.png') }}" type="image/png">
    <style>
        @media print {
            .container {
                display: flex;
                flex-wrap: wrap; /* Allow wrapping of stickers */
                margin: 0;
                font-family: Arial, sans-serif;
                justify-content: space-between; /* Distribute stickers evenly */
            }
            .sticker {
                display: flex;
                flex-direction: column;
                align-items: center;
                border: 2px solid #000;
                padding: 30; /* Remove padding for exact sizing */
                background-color: #f9f9f9;
                width: 40mm; /* Set width to 40mm */
                height: 40mm; /* Set height to 40mm */
                box-sizing: border-box;
                page-break-inside: avoid;
                margin: 2mm; /* Space between stickers */
                position: relative; /* Position relative to contain absolute elements */
                overflow: hidden;
            }
            .qr-code {
                display: flex;
                justify-content: center; /* Center the QR code */
                align-items: center; /* Center the QR code vertically */
                width: 100%; /* Use full width */
                height: 100%; /* Use full height */
            }
            .qr-code img {
                max-width: 100%; /* Make the QR code image fit the container */
                max-height: 100%; /* Make the QR code image fit the container */
            }
            .logo {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 150mm; /* Adjust size for print */
                height: 150mm; /* Adjust size for print */
                background-color: white;
                border-radius: 20px;
            }
            .serial-number {
                font-size: 18pt; /* Adjust font size for smaller size */
                font-weight: bold;
                margin-top: 2mm; /* Adjust spacing for smaller size */
            }
        }

        .container {
            display: flex;
            flex-wrap: wrap; /* Allow wrapping of stickers */
            justify-content: center; /* Center the stickers in the container */
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .sticker {
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 2px solid #000;
            padding: 0; /* Remove padding for exact sizing */
            background-color: #f9f9f9;
            width: 40mm; /* Set width to 40mm */
            height: 40mm; /* Set height to 40mm */
            box-sizing: border-box;
            margin: 2mm; /* Space between stickers */
        }
        .qr-code {
            display: flex; 
            justify-content: center; /* Center the QR code */
            align-items: center; /* Center the QR code vertically */
            width: 100%; /* Use full width */
            height: 100%; /* Use full height */
        }
        .qr-code img {
            max-width: 100%; /* Make the QR code image fit the container */
            max-height: 100%; /* Make the QR code image fit the container */
        }
        .logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -70%);
            width: 7mm; /* Adjust size for screen */
            height: 7mm; /* Adjust size for screen */
        }
        .serial-number {
            font-size: 8px; /* Adjust font size for screen */
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        @foreach ($qrCodes as $item)
            <!-- Create two identical stickers for each QR code -->
            <div class="sticker">
                <div class="qr-code">
                    {!! $item['qrCode'] !!}
                    <img src="{{ asset('assets/img/GSI.png') }}" alt="GSI Logo" class="logo">
                </div>
                <div class="serial-number">
                    {{ $item['inventory']->serial_number }}
                </div>
            </div>
            <div class="sticker">
                <div class="qr-code">
                    {!! $item['qrCode'] !!}
                    <img src="{{ asset('assets/img/GSI.png') }}" alt="GSI Logo" class="logo">
                </div>
                <div class="serial-number">
                    {{ $item['inventory']->serial_number }}
                </div>
            </div>
        @endforeach
    </div>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
