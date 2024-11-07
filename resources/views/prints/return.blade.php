<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Return Documentation')</title>
    <link rel="icon" href="{{ asset('assets/img/assetslogo.png') }}" type="image/png">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            position: relative;
        }

        .print-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 5px;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            /* Hapus border agar tidak tercetak */
            border: none;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 12px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            width: 25%;
            /* Sesuaikan lebar kolom header */
            background-color: #f2f2f2;
        }

        td {
            width: 75%;
            /* Sesuaikan lebar kolom data */
            background-color: #fff;
        }

        hr {
            border: none;
            border-top: 1px solid #333;
            margin: 10px 0;
        }

        .content {
            font-size: 12px;
            margin-bottom: 15px;
        }

        .content p {
            margin: 0 0 10px;
        }

        .signatures {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            gap: 30px;
        }

        .signature-box {
            text-align: center;
            padding: 10px;
            /* Hapus border agar tidak tercetak */
            border: none;
            font-size: 12px;
        }

        .signature-line {
            margin-top: 50px;
            width: 80%;
            border-top: 1px solid #333;
            margin-left: auto;
            margin-right: auto;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            opacity: 0.1;
            width: 250px;
            height: auto;
        }

        @media print {
            body {
                background-color: #ffffff;
            }

            .print-container {
                width: 100%;
                border-radius: 0;
                padding: 0;
                margin: 0;
            }

            /* Hilangkan border pada mode cetak */
            .signature-box {
                border: none;
            }

            .signature-line {
                border-top: 1px solid #000;
            }

            /* Hindari pemutusan halaman di dalam elemen-elemen ini */
            h1,
            table,
            .content,
            .signatures {
                page-break-inside: avoid;
            }

            /* Atur ukuran halaman */
            @page {
                size: A4;
                margin: 20mm;
            }
        }
    </style>
</head>

<body>
    <!-- Watermark Image -->
    <img src="{{ asset('assets/img/GSI.png') }}" alt="GSI Logo" class="watermark">

    <div class="print-container">
        <h1>Return Asset</h1>

        @if(isset($history))

            <!-- Introduction Text -->
            <div class="content">
                <p>Saya, yang bertandatangan di bawah ini:</p>
                <p><strong>Name:</strong> Bagas Griyatama</p>
                <p><strong>NRP:</strong> 90122424</p>
                <p><strong>Department:</strong> IT Infrastructure & Operation</p>
            </div>

            <div class="content">
                <p>Telah menerima pengembalian Asset IT yang digunakan oleh:</p>
                <p><strong>Name:</strong> {{ $history->customer_name }}</p>
                <p><strong>NRP:</strong> {{ $history->customer_nrp }}</p>
                <p><strong>Department:</strong> {{ $history->position }}</p>
            </div>

            <div class="content">
                <p>Adapun penjelasan mengenai pengembalian aset tersebut adalah sebagai berikut:</p>
            </div>

            <!-- Device Details Table -->
            <table>
                <tr>
                    <th>Device Name</th>
                    <th>Asset Code</th>
                    <th>Device S/N</th>
                    <th>Note</th>
                </tr>
                <tr>
                    <td>{{ $history->category_asset }} {{ $history->merk_name }} {{ $history->specification }}</td>
                    <td>{{ $history->asset_code }}</td>
                    <td>{{ $history->serial_number}}</td>
                    <td>{{ $history->note}}</td>
                </tr>
            </table>

            <!-- Return Statement -->
            <div class="content">
                <p><strong>Tanggal Pengembalian:</strong> {{ \Carbon\Carbon::parse($history->created_at)->format('d-m-Y') }}
                </p>
            </div>

            <!-- Signatures Section -->
            <div class="signatures">
                <div class="signature-box">
                    <p><strong>Diserahkan Oleh :</strong></p>
                    <p>{{ $history->customer_name }}</p>
                    <p>{{ $history->position }}</p>
                    <div class="signature-line"></div>
                </div>
                <div class="signature-box">
                    <p><strong>Diterima Oleh :</strong></p>
                    <p>Admin</p>
                    <p>Infrastructure</p>
                    <div class="signature-line"></div>
                </div>
            </div>
        @else
            <p>No data available</p>
        @endif
    </div>
</body>

</html>
<script>
    window.onload = function () {
        window.print();
    }
</script>