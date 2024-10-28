<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Handover Documentation')</title>
    <link rel="icon" href="{{ asset('assets/img/velaris.png') }}" type="image/png">
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
        <h1>Handover Asset</h1>

        @if(isset($handover))
            <table>
                <!-- Kop Surat -->
                <tr>
                    <th>Name User</th>
                    <td>{{ $handover->customer_name }}</td>
                </tr>
                <tr>
                    <th>NRP</th>
                    <td>{{ $handover->customer_nrp }}</td>
                </tr>

                <tr>
                    <th>Departemen</th>
                    <td>{{ $handover->customer_mapping }}</td>
                </tr>
                <tr>
                    <th>Location</th>
                    <td>{{ $handover->lokasi_old }}</td>
                </tr>
            </table>

            <!-- Garis Pembatas -->
            <hr>

            <!-- Teks Pengantar -->
            <div class="content">
                <p>Pada hari Senin, tanggal {{ \Carbon\Carbon::parse($handover->changed_at)->format('d-m-Y') }}, Unit Kerja
                    IT telah melakukan serah terima perangkat dengan spesifikasi sebagai berikut:</p>
            </div>

            <!-- Detail Perangkat -->
            <table>
                <tr>
                    <th>Type Device</th>
                    <td>{{ $handover->jenis_aset_old }} {{ $handover->merk_name }} {{ $handover->type_old }}</td>
                </tr>
                <tr>
                    <th>S/N Device</th>
                    <td>{{ $handover->serial_number_old }}</td>
                </tr>
                <tr>
                    <th>Asset Tagging</th>
                    <td>{{ $tagging }}</td>
                </tr>
                <tr>
                    <th>Kondisi</th>
                    <td>{{ $handover->kondisi_old }}</td>
                </tr>
                <tr>
                    <th>Transfer Date</th>
                    <td>{{ \Carbon\Carbon::parse($handover->changed_at)->format('d-m-Y') }}</td>
                </tr>
            </table>

            <!-- Poin-Poin Pernyataan -->
            <div class="content">
                <p>Menyatakan bahwa:</p>
                <ol>
                    <li>Perangkat diatas telah diperiksa oleh pengguna/user dan berfungsi sebagaimana mestinya.</li>
                    <li>Unit Kerja IT telah memberikan training orientasi produk kepada pengguna/user mengenai cara
                        instalasi perangkat, pemakaian perangkat, dan hal-hal yang harus dilakukan jika terjadi gangguan
                        terhadap perangkat.</li>
                    <li>Pengguna perangkat tunduk dan taat pada peraturan atau policy IT yang sudah ditetapkan oleh Unit
                        Kerja IT.</li>
                    <li>Satu pengguna/user hanya diperbolehkan memiliki 1 perangkat.</li>
                    <li>Perangkat ini tidak boleh dipindah tangankan.</li>
                    <li>Jika terjadi penyalahgunaan terhadap perangkat ini maka pengguna/user akan ditindak sesuai dengan
                        peraturan yang berlaku di perusahaan.</li>
                    <li>Jika terjadi kerusakan, maka pengguna/user harus membuat berita acara kerusakan yang ditandatangani
                        oleh Division Head user.</li>
                    <li>Jika perangkat ini hilang, maka pengguna/user harus membayar denda sesuai nilai depresiasi / denda
                        yang telah ditetapkan oleh Perusahaan serta membuat berita acara kehilangan dari kepolisian.</li>
                    <li>Pengguna harus mengembalikan perangkat ini kepada Unit Kerja IT jika:
                        <ul>
                            <li>Mutasi/rotasi/promosi/perubahan peraturan sehingga pengguna/user tidak membutuhkan perangkat
                                ini.</li>
                            <li>Keluar/masa kerjanya telah habis di PT Global Service Indonesia.</li>
                        </ul>
                    </li>
                    <li>Meskipun perangkat sudah dikembalikan ke Perusahaan, apabila ditemukan penyalahgunaan terhadap
                        perangkat tersebut selama masa pakai oleh pengguna/user bersangkutan maka akan ditindak sesuai
                        dengan peraturan yang berlaku di perusahaan.</li>
                </ol>
                <p>Saya yang bertanda tangan dibawah ini, menyatakan telah membaca seluruh isi berita acara ini dan akan
                    tunduk pada peraturan tersebut diatas.</p>
            </div>

            <!-- Tanda Tangan -->
            <div class="signatures">
                <div class="signature-box">
                    <p>Asset Holder</p>
                    <p>{{ $handover->customer_name }}</p>
                    <p>{{ $handover->customer_mapping }}</p>
                    <div class="signature-line"></div>
                </div>
                <div class="signature-box">
                    <p>Approving Officer</p>
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
