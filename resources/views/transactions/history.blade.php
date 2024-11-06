@extends('layouts.app')
@section('title', 'Activity History')

@section('content')
<div class="container mt-4">
    <div>
        <div class="container">
            <div class="header-container">
                <div class="back-wrapper">
                    <i class='bx bxs-chevron-left back-icon' id="back-icon"></i>
                    <div class="back-text">
                        <span class="title">Back</span>
                        <span class="small-text">to previous page</span>
                    </div>
                </div>
                <h3 class="approval-title">
                    History Approval&nbsp;&nbsp;
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-calendar-check previous-icon"></i>
                    </span>
                </h3>
            </div>
            <div class="header-container-mobile mt-4">
                <h3 class="approval-title">
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-calendar-check previous-icon"></i>
                    </span>
                    &nbsp;&nbsp;History Approval
                </h3>
            </div>
            <br>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col" style="width:100px;">Category</th>
                            <th scope="col" style="width:150px;">Asset Code</th>
                            <th scope="col">Merk</th>
                            <th scope="col" style="width:200px;">Transfer Date</th>
                            <th scope="col" style="width:70px;">Action</th>
                            <th scope="col">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $item)
                            <tr data-bs-toggle="modal" data-bs-target="#detailModal" data-code="{{ $item->asset_code }}"
                                data-merk="{{ $item->merk_name }}" data-category="{{ $item->category_asset }}"
                                data-holder="{{ $item->name_holder }}"
                                data-changedat="{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i:s') }}"
                                data-type_transactions="{{ $item->type_transactions }}" data-reason="{{ $item->reason }}"
                                data-note="{{ $item->note }}" data-document="{{ $item->documentation }}"
                                data-serial-number="{{ $item->serial_number }}" style="cursor:pointer;">
                                <td>{{ $item->category_asset }}</td>
                                <td>{{ $item->asset_code }}</td>
                                <td>{{ $item->merk_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i:s') }}</td>
                                <td>
                                    @if ($item->type_transactions === 'Handover')
                                        <span class="badge badge-custom"
                                            style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em; background-color:#1BCFB4;">Handover</span>
                                    @elseif ($item->type_transactions === 'UPDATE')
                                        <span class="badge badge-custom bg-warning"
                                            style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em;">Mutasi</span>
                                    @elseif ($item->type_transactions === 'Return')
                                        <span class="badge badge-custom"
                                            style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em; background-color:#FE7C96;">Return</span>
                                    @else
                                        <span class="badge badge-custom bg-secondary"
                                            style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em;">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->type_transactions === 'Handover')
                                        New asset added. Holder: <span class="badge badge-custom"
                                            style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em; background-color:#5f9efa;">{{ $item->name_holder }}</span>
                                    @elseif ($item->type_transactions === 'UPDATE')
                                        Mutation from <span class="badge badge-custom bg-secondary"
                                            style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em;">{{ $item->name_holder }}</span>
                                        to <span class="badge badge-custom bg-primary"
                                            style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em;">{{ $item->new_holder }}</span>
                                    @elseif ($item->type_transactions === 'Return')
                                        Asset returned by: <span class="badge badge-custom"
                                            style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em; background-color:#c7ccd1;">{{ $item->name_holder }}</span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    <ul class="list-unstyled legend-list">
                        <li>
                            <span class="badge legend-badge"
                                style="color:white; background-color:#1BCFB4;">Handover</span> : <span
                                class="legend-description">The asset has been approved by the user.</span>
                        </li>

                        <li>
                            <span class="badge legend-badge"
                                style="color:white; background-color:#FE7C96;">Return</span> : <span
                                class="legend-description">The asset is rejected by the user.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center font-weight-bold" id="detailModalLabel">Asset Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <table class="table table-borderless no-border-table">
                            <tbody>
                                <tr>
                                    <th scope="row">Category</th>
                                    <td id="modalJenisCategory"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Asset Code</th>
                                    <td id="modalAssetCode"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Serial Number</th>
                                    <td id="modalSerialNumber"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Merk</th>
                                    <td id="modalMerk"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Holder</th>
                                    <td id="modalHolder"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <table class="table table-borderless no-border-table">
                            <tbody>

                                <tr>
                                    <th scope="row">Transfer Date</th>
                                    <td id="modalChangedAt"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Action</th>
                                    <td id="modalAction"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Reason</th>
                                    <td id="modalReason"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Note</th>
                                    <td id="modalNote"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Documentation</th>
                                    <td id="modalDocument"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Print Button -->
                <button type="button" class="btn text-white" id="printButton" style="background-color:#6B07C2">
                    <i class="bi bi-printer"></i> Print Proof
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Event listener for the modal
    document.addEventListener('DOMContentLoaded', function () {
        var detailModal = document.getElementById('detailModal');
        detailModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal

            // Get data attributes from the clicked row
            var assetCode = button.getAttribute('data-code');
            var merk = button.getAttribute('data-merk');
            var category = button.getAttribute('data-category');
            var holder = button.getAttribute('data-holder');
            var changedAt = button.getAttribute('data-changedat');
            var typeTransactions = button.getAttribute('data-type_transactions');
            var reason = button.getAttribute('data-reason');
            var note = button.getAttribute('data-note');
            var documentation = button.getAttribute('data-document');
            var serialNumber = button.getAttribute('data-serial-number');

            // Update the modal's content
            document.getElementById('modalAssetCode').textContent = assetCode;
            document.getElementById('modalMerk').textContent = merk;
            document.getElementById('modalJenisCategory').textContent = category;
            document.getElementById('modalHolder').textContent = holder;
            document.getElementById('modalSerialNumber').textContent = serialNumber;

            document.getElementById('modalChangedAt').textContent = changedAt;
            document.getElementById('modalAction').textContent = typeTransactions;
            document.getElementById('modalReason').textContent = reason;
            document.getElementById('modalNote').textContent = note;
            document.getElementById('modalDocument').innerHTML = documentation ? `<a href="{{ asset('storage/') }}/${documentation}" target="_blank">View Document</a>` : 'No document available';
        });

        document.getElementById('printButton').addEventListener('click', function () {
            var action = this.getAttribute('data-action');
            var assetTagging = document.getElementById('modalAssetTagging').textContent;
            var changedAt = document.getElementById('modalChangedAt').textContent; // Format: yyyy-mm-dd hh:mm:ss

            // Extract the date part (yyyy-mm-dd)
            var changedAtDate = changedAt.split(' ')[0];

            var route = '';

            if (action === 'CREATE') {
                route = '{{ route('prints.handover') }}';
            } else if (action === 'UPDATE') {
                route = '{{ route('prints.mutation') }}';
            } else if (action === 'DELETE') {
                route = '{{ route('prints.return') }}';
            }

            if (route) {
                var fullUrl = route + '?asset_code=' + encodeURIComponent(assetTagging) + '&changed_at=' + encodeURIComponent(changedAtDate);
                console.log('Opening URL: ' + fullUrl); // Debug URL
                window.open(fullUrl, '_blank');
            }
        });
    });
</script>
<style>
    /* CSS for table row borders */
    .table-hover tbody tr td,
    .table-hover thead tr th {
        border-bottom: 1px solid #ebedf2;
        /* Add a border to the bottom of each row */
        background-color: #fff;
    }

    .table-hover tbody tr td {
        font-weight: 300;
    }

    .table-hover thead tr th {
        font-weight: 600;
    }

    /* Remove any cell borders */
    .table-hover th,
    .table-hover td {
        border: none;
        /* Hapus border dari tabel */
    }

    /* CSS untuk menghapus garis tabel pada modal */
    .no-border-table th,
    .no-border-table td {
        border: none !important;
        /* Menghapus garis tepi */
        padding: 0.5rem;
        /* Mengatur padding untuk ruang di dalam sel */
    }

    .no-border-table th {
        font-weight: bold;
        /* Menebalkan teks header tabel */
        text-align: left;
        /* Menyelaraskan teks header ke kiri */
    }

    .no-border-table td {
        text-align: left;
        /* Menyelaraskan teks sel ke kiri */
    }

    .modal-title {
        font-weight: bold;
        text-align: center;
        width: 100%;
        /* Memastikan elemen judul memanfaatkan lebar penuh */
        margin: 0;
        /* Menghapus margin default */
        padding: 0;
        /* Menghapus padding default jika ada */
    }

    /* CSS tambahan untuk memastikan tidak ada margin atau padding yang mengganggu */
    .modal-header {
        display: flex;
        justify-content: center;
        /* Menyelaraskan konten ke tengah secara horizontal */
    }

    .legend-list {
        font-size: 0.875em;
        line-height: 1.5;
    }

    .legend-list li {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }

    .legend-list li .badge {
        min-width: 80px;
        margin-right: 10px;
    }

    .legend-list li .legend-description {
        margin-left: 10px;
        text-align: left;
    }

    /* Header Styles */
    .header-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        margin-top: 54px;
    }

    .back-icon {
        cursor: pointer;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) -10%, #FCA918);
        height: 36px;
        width: 36px;
        border-radius: 4px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.25);
        margin-right: auto;
        transition: background 0.3s ease;
        /* Transition untuk efek hover */
    }

    .back-icon:hover {
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) -13%, #FBCA07);
        /* Warna gradien saat hover dengan putih sedikit di kiri */
    }

    .back-wrapper {
        display: flex;
        align-items: center;
        /* Center vertically */
        margin-right: auto;
        /* Push the dashboard title to the right */
    }

    .back-text {
        display: flex;
        flex-direction: column;
        /* Stack text vertically */
        margin-left: 10px;
        /* Space between icon and text */
    }

    .back-text .title {
        font-weight: 600;
        font-size: 17px;
    }

    .back-text .small-text {
        font-size: 0.8rem;
        /* Smaller font size for the second line */
        color: #aaa;
        /* Optional: a lighter color for the smaller text */
        margin-top: -3px;
    }

    .approval-title {
        font-weight: bold;
        font-size: 1.125rem;
    }

    .icon-wrapper {
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) -10%, #FCA918);
        height: 36px;
        width: 36px;
        border-radius: 4px;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.25);
    }

    .previous-icon {
        font-size: 16px;
    }

    @media (max-width: 576px) {
        .header-container {
            flex-direction: column;
            /* Stack items vertically on mobile */
            align-items: flex-start;
            /* Align items to the start */
            padding: 10px 20px;
            /* Adjust padding */
        }

        .back-text .title {
            font-size: 1rem;
            /* Adjust font size for mobile */
        }

        .back-text .small-text {
            font-size: 0.75rem;
            /* Smaller font size for mobile */
        }


        .card-body {
            padding: 15px;
            /* Menyesuaikan padding untuk tampilan mobile */
        }

        .table-responsive {
            margin-top: 70px;
            /* Menambahkan jarak antara tombol dan tabel */
        }

        .btn {
            width: 100%;
            /* Buat tombol penuh lebar pada mobile */
        }

        .d-flex {
            flex-direction: column;
            /* Stack tombol secara vertikal */
        }

        .mb-2 {
            margin-bottom: 10px;
            /* Tambahkan jarak antara tombol di mobile */
        }
    }

    .badge-custom {
        font-size: 0.8rem;
        border-radius: 0.5em;
        padding-right: -20px;
    }
</style>
@endsection