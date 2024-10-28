<!-- resources/views/assets/print.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Asset</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .print-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            margin: 0 auto;
            border: 1px solid #ccc;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .signatures {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }
        .signature-box {
            text-align: center;
            padding: 20px;
            border: 2px dashed #333;
            border-radius: 5px;
        }
        .signature-line {
            margin-top: 80px;
            width: 80%;
            border-top: 1px solid #333;
            margin-left: auto;
            margin-right: auto;
        }
        /* Watermark Styles */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            opacity: 0.1;
            width: 300px;
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
            }
            .signature-box {
                border: none;
            }
            .signature-line {
                border-top: 1px solid #000;
            }
        }
    </style>
</head>
<body>
    <!-- Watermark Image -->
    <img src="{{ asset('assets/img/GSI.png') }}" alt="GSI Logo" class="watermark">

    <div class="print-container">
        <h1>Asset Details</h1>
        <table>
            <tr>
                <th>Asset Tagging</th>
                <td>{{ $asset->tagging }}</td>
            </tr>
            <tr>
                <th>Jenis Aset</th>
                <td>{{ $asset->jenis_aset }}</td>
            </tr>
            <tr>
                <th>Merk</th>
                <td>{{ $asset->merk_name }}</td>
            </tr>
            <tr>
                <th>Location</th>
                <td>{{ $asset->lokasi }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $asset->approval_status }}</td>
            </tr>
            <tr>
                <th>Kondisi</th>
                <td>{{ $asset->kondisi }}</td>
            </tr>
            <tr>
                <th>O365</th>
                <td>{{ $asset->o365 }}</td>
            </tr>
            <tr>
                <th>Transfer Date</th>
                <td>{{ \Carbon\Carbon::parse($asset->created_at)->format('d-m-Y') }}</td>
            </tr>
        </table>

        <div class="signatures"> 
            <div class="signature-box">
                <p>Asset Holder</p>
                <p>{{ $asset->customer_name }}</p>
                <p>{{ $asset->customer_mapping }}</p>
                <div class="signature-line"></div>
            </div>
            <div class="signature-box">
                <p>Approving Officer</p>
                <p>Admin</p>
                <p>Infrastructure</p>
                <div class="signature-line"></div>
            </div>
        </div>
    </div>
</body>
</html>
