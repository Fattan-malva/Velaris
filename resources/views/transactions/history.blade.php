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
                            <th scope="col" style="width:150px;">Asset Tagging</th>
                            <th scope="col">Merk</th>
                            <th scope="col" style="width:100px;">Jenis Aset</th>
                            <th scope="col" style="width:200px;">Transfer Date</th>
                            <th scope="col" style="width:70px;">Action</th>
                            <th scope="col">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $assetTagging => $items)
                            @foreach ($items as $item)
                                <tr data-bs-toggle="modal" data-bs-target="#detailModal" data-asset="{{ $item->asset_tagging }}"
                                    data-merk="{{ $item->merk }}" data-jenis="{{ $item->jenis_aset_old }}"
                                    data-oldholder="{{ $item->nama_old }}" data-newholder="{{ $item->nama_new }}"
                                    data-changedat="{{ \Carbon\Carbon::parse($item->changed_at)->format('d-m-Y H:i:s') }}"
                                    data-action="{{ $item->action }}" data-keterangan="{{ $item->keterangan }}"
                                    data-document="{{ $item->documentation_new }}"
                                    data-document-old="{{ $item->documentation_old }}" style="cursor:pointer;">
                                    <td>{{ $item->asset_tagging }}</td>
                                    <td>{{ $item->merk }}</td>
                                    <td>{{ $item->jenis_aset_old }}</td>
                                    <td>{{ $item->changed_at }}</td>
                                    <td>
                                        @if ($item->action === 'CREATE')
                                            <span class="badge badge-custom"
                                                style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em; background-color:#1BCFB4;">Handover</span>
                                        @elseif ($item->action === 'UPDATE')
                                            <span class="badge badge-custom bg-warning"
                                                style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em;">Mutasi</span>
                                        @elseif ($item->action === 'DELETE')
                                            <span class="badge badge-custom"
                                                style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em;  background-color:#FE7C96;">Return</span>
                                        @else
                                            <span class="badge badge-custom bg-secondary"
                                                style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em;">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->action === 'CREATE')
                                            New asset added. Holder: <span class="badge badge-custom"
                                                style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em; background-color:#5f9efa;">{{ $item->nama_old }}</span>
                                        @elseif ($item->action === 'UPDATE')
                                            Mutation from <span class="badge badge-custom bg-secondary"
                                                style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em;">{{ $item->nama_old }}</span>
                                            to <span class="badge badge-custom bg-primary"
                                                style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em;">{{ $item->nama_new }}</span>
                                        @elseif ($item->action === 'DELETE')
                                            Asset returned by: <span class="badge badge-custom"
                                                style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em; background-color:#c7ccd1;">{{ $item->nama_old }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="9" class="text-center" style="padding: 50px; font-size: 1.2em;">No history
                                    found.</td>
                            </tr>
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
                                    <th scope="row">Asset Tagging</th>
                                    <td id="modalAssetTagging"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Merk</th>
                                    <td id="modalMerk"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Jenis Aset</th>
                                    <td id="modalJenisAset"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Old Holder</th>
                                    <td id="modalOldHolder"></td>
                                </tr>
                                <!-- <tr>
                                    <th scope="row">Documentation</th>
                                    <td id="modalDocumentNew"></td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <table class="table table-borderless no-border-table">
                            <tbody>
                                <tr>
                                    <th scope="row">New Holder</th>
                                    <td id="modalNewHolder"></td>
                                </tr>
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
                                    <td id="modalKeterangan"></td>
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
        background: linear-gradient(90deg, rgba(255, 255, 255, 0) -30%, #B66DFF);
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
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) -13%, #B100FF);
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
        background: linear-gradient(90deg, rgba(255, 255, 255, 0) -30%, #B66DFF);
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var detailModal = document.getElementById('detailModal');
        detailModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var assetTagging = button.getAttribute('data-asset');
            var merk = button.getAttribute('data-merk');
            var jenisAset = button.getAttribute('data-jenis');
            var oldHolder = button.getAttribute('data-oldholder');
            var newHolder = button.getAttribute('data-newholder');
            var changedAt = button.getAttribute('data-changedat');
            var action = button.getAttribute('data-action');
            var keterangan = button.getAttribute('data-keterangan') || '-';
            var documentLinkNew = button.getAttribute('data-document'); // for new documentation
            var documentLinkOld = button.getAttribute('data-document-old'); // for old documentation


            // Update modal content
            document.getElementById('modalAssetTagging').textContent = assetTagging;
            document.getElementById('modalMerk').textContent = merk;
            document.getElementById('modalJenisAset').textContent = jenisAset;
            document.getElementById('modalOldHolder').textContent = oldHolder;
            document.getElementById('modalNewHolder').textContent = newHolder;
            document.getElementById('modalChangedAt').textContent = changedAt;
            document.getElementById('modalKeterangan').textContent = keterangan;

            // Determine action text and badge color
            var actionText = '';
            var actionColor = ''; // Default color set per action

            if (action === 'CREATE') {
                actionText = 'Handover';
                actionColor = '#1BCFB4'; // Light teal for Handover
            } else if (action === 'UPDATE') {
                actionText = 'Mutasi';
                actionColor = '#007bff'; // Blue for Mutasi
            } else if (action === 'DELETE') {
                actionText = 'Return';
                actionColor = '#FE7C96'; // Red for Return
            } else {
                actionText = 'N/A';
                actionColor = '#6c757d'; // Grey for unknown actions
            }

            // Set badge text, background color, and apply the custom class
            var modalAction = document.getElementById('modalAction');
            modalAction.textContent = actionText;
            modalAction.style.backgroundColor = actionColor;
            modalAction.classList.add('badge-custom'); // Apply custom badge styling

            document.getElementById('printButton').setAttribute('data-action', action);
            var modalDocumentNew = document.getElementById('modalDocumentNew');
            if (documentLinkNew && documentLinkNew !== '-') {
                modalDocumentNew.innerHTML = `<a href="{{ asset('storage/') }}/${documentLinkNew}" target="_blank">View Document</a>`;
            } else {
                modalDocumentNew.textContent = 'No new documentation available';
            }

            // Old Documentation
            var modalDocumentOld = document.getElementById('modalDocumentOld');
            if (documentLinkOld && documentLinkOld !== '-') {
                modalDocumentOld.innerHTML = `<a href="{{ asset('storage/') }}/${documentLinkOld}" target="_blank">View Document</a>`;
            } else {
                modalDocumentOld.textContent = 'No old documentation available';
            }
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
                var fullUrl = route + '?asset_tagging=' + encodeURIComponent(assetTagging) + '&changed_at=' + encodeURIComponent(changedAtDate);
                console.log('Opening URL: ' + fullUrl); // Debug URL
                window.open(fullUrl, '_blank');
            }
        });


    });
</script>
@endsection