<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutation Documentation</title>
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
            max-width: 100%;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 30px;
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
            margin: 0 0 5px;
        }

        .signatures {
            margin-top: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .signature-box {
            text-align: center;
            padding: 10px;
            border: 1px dashed #333;
            border-radius: 5px;
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
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            opacity: 0.3;
            width: 300px;
            height: auto;
        }

        .centered-header {
            text-align: center;
        }

        .nested-table {
            border: 1px solid #ddd;
            border-collapse: collapse;
            width: 100%;
        }

        .nested-table th,
        .nested-table td {
            border: 1px solid #ddd;
            padding: 8px;
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

            /* Avoid page breaks inside these elements */
            h1,
            table,
            .content,
            .signatures {
                page-break-inside: avoid;
            }

            /* Set the page size */
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
        <h1>Mutation Asset</h1>


        <!-- Teks Pengantar -->
        <div class="content">
            <p>Pada hari ini, tanggal {{ \Carbon\Carbon::parse($mutation->changed_at)->format('d-m-Y') }} telah
                dilakukan mutasi asset TI dan dilakukan
                instalasi terhadap perangkat tersebut dari pengguna lama kepada pengguna yang baru beserta
                kelengkapannya dengan data sebagai berikut :</p>
        </div>

        @if(isset($mutation))
            <table>
                <!-- Detail Perangkat -->
                <tr>
                    <th>Asset Tagging</th>
                    <td>{{ $tagging }}</td>
                </tr>
                <tr>
                    <th>Computer Name</th>
                    <td>{{ $tagging }}</td>
                </tr>

                <tr>
                    <th>Serial Number</th>
                    <td>{{ $mutation->serial_number_old }}</td>
                </tr>
                <tr>
                    <th>Type Device</th>
                    <td>{{ $mutation->jenis_aset_new}}</td>
                </tr>
            </table>

            <!-- Garis Pembatas -->
            <hr>
            <table>
                <!-- Detail Holder -->
                <tr>
                    <th class="centered-header">Old Holder</th>
                    <td>
                        <table class="nested-table">
                            <tr>
                                <th>Name</th>
                                <td>{{ $mutation->old_customer_name }}</td>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <td>{{ $mutation->mapping_old }}</td>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <td>{{ $mutation->lokasi_old }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th class="centered-header">New Holder</th>
                    <td>
                        <table class="nested-table">
                            <tr>
                                <th>Name</th>
                                <td>{{ $mutation->new_customer_name }}</td>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <td>{{ $mutation->mapping_new }}</td>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <td>{{ $mutation->lokasi_new }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>




            <!-- Poin-Poin Pernyataan -->
            <div class="content">
                <p>Dan menyatakan bahwa :</p>
                <ol>
                    <li>Perangkat diatas telah diperiksa oleh pengguna/ penanggungjawab perangkat yang baru dan berfungsi
                        sebagaimana mestinya.</li>
                    <li>Pengguna baru bertanggungjawab penuh terhadap perangkat yang diserahkan.</li>
                </ol>
                <p>Saya yang bertanda tangan dibawah ini, menyatakan telah membaca seluruh isi berita acara ini dan akan
                    tunduk pada peraturan tersebut diatas.</p>
            </div>

            <!-- Tanda Tangan -->
            <div class="signatures">
                <div class="signature-box">
                    <p>Old Holder Asset</p>
                    <p>{{ $mutation->old_customer_name }}</p>
                    <p>{{ $mutation->old_customer_nrp }}</p>
                    <div class="signature-line"></div>
                </div>
                <div class="signature-box">
                    <p>New Holder Asset</p>
                    <p>{{ $mutation->new_customer_name }}</p>
                    <p>{{ $mutation->new_customer_nrp }}</p>
                    <div class="signature-line"></div>
                </div>
                <div class="signature-box">
                    <p>PIC IT</p>
                    <p>Admin</p>
                    <p>Infrastructure</p>
                    <div class="signature-line"></div>
                </div>
            </div>
        @else
            <p>Data not found</p>
        @endif
    </div>
</body>

</html>

<script>
    window.onload = function () {
        window.print();
    }
</script>