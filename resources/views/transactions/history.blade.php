@extends('layouts.app')
@section('title', 'Activity History')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4 fw-bold display-5">Activity History</h1>
    <br>
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Asset Tagging</th>
                            <th scope="col">Merk</th>
                            <th scope="col">Jenis Aset</th>
                            <th scope="col">Transfer Date</th>
                            <th scope="col">Action</th>
                            <th scope="col">Description</th>
                            <th scope="col">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $assetTagging => $items)
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $item->asset_tagging }}</td>
                                    <td>{{ $item->merk }}</td>
                                    <td>{{ $item->jenis_aset_old }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->changed_at)->format('d-m-Y') }}</td>
                                    <td>
                                        @if ($item->action === 'CREATE')
                                            <span class="badge badge-custom bg-success"
                                                style="font-size: 0.8rem; padding: 0.2em 1em; color: black; border-radius: 0.5em;">Handover</span>
                                        @elseif ($item->action === 'UPDATE')
                                            <span class="badge badge-custom bg-warning"
                                                style="font-size: 0.8rem; padding: 0.2em 1em; color: black; border-radius: 0.5em;">Mutasi</span>
                                        @elseif ($item->action === 'DELETE')
                                            <span class="badge badge-custom bg-danger"
                                                style="font-size: 0.8rem; padding: 0.2em 1em; color: black; border-radius: 0.5em;">Return</span>
                                        @else
                                            <span class="badge badge-custom bg-secondary"
                                                style="font-size: 0.8rem; padding: 0.2em 1em; color: black; border-radius: 0.5em;">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->action === 'CREATE')
                                            New asset added. Holder: <span class="badge badge-custom bg-primary"
                                                style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em;">{{ $item->nama_old }}</span>
                                        @elseif ($item->action === 'UPDATE')
                                            Mutation from <span class="badge badge-custom bg-secondary"
                                                style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em;">{{ $item->nama_old }}</span>
                                            to <span class="badge badge-custom bg-primary"
                                                style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em;">{{ $item->nama_new }}</span>
                                        @elseif ($item->action === 'DELETE')
                                            Asset returned by: <span class="badge badge-custom bg-secondary"
                                                style="font-size: 0.8rem; padding: 0.2em 1em; color: white; border-radius: 0.5em;">{{ $item->nama_old }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Detail Button -->
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#detailModal" data-asset="{{ $item->asset_tagging }}"
                                            data-merk="{{ $item->merk }}" data-jenis="{{ $item->jenis_aset_old }}"
                                            data-oldholder="{{ $item->nama_old }}" data-newholder="{{ $item->nama_new }}"
                                            data-changedat="{{ \Carbon\Carbon::parse($item->changed_at)->format('d-m-Y H:i:s') }}"
                                            data-action="{{ $item->action }}" data-keterangan="{{ $item->keterangan }}">
                                            <i class="bi bi-file-earmark-text"></i> Detail
                                        </button>

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
                            <span class="badge bg-success legend-badge" style="color:black;">Handover</span> : <span
                                class="legend-description">The asset has been approved by the user.</span>
                        </li>
                        <li>
                            <span class="badge bg-warning legend-badge" style="color:black;">Mutation</span> : <span
                                class="legend-description">Waiting for the asset to be approved
                                by the user.</span>
                        </li>
                        <li>
                            <span class="badge bg-danger legend-badge" style="color:black;">Return</span> : <span
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
                <h4 class="modal-title text-center font-weight-bold" id="detailModalLabel">Asset Details
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
            <div class="modal-footer">
                <!-- Print Button -->
                <button type="button" class="btn btn-success" id="printButton"><i class="bi bi-printer"></i>
                    Print Proof</button>
            </div>
        </div>
    </div>
</div>

<style>
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
</style>

<script>
    function confirmClear() {
        return confirm("Are you sure you want to delete all asset history records?");
    }

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
            var keterangan = button.getAttribute('data-keterangan');

            // Update modal content
            document.getElementById('modalAssetTagging').textContent = assetTagging;
            document.getElementById('modalMerk').textContent = merk;
            document.getElementById('modalJenisAset').textContent = jenisAset;
            document.getElementById('modalOldHolder').textContent = oldHolder;
            document.getElementById('modalNewHolder').textContent = newHolder;
            document.getElementById('modalChangedAt').textContent = changedAt;
            document.getElementById('modalKeterangan').textContent = keterangan;

            // Update action text based on action type
            var actionText = '';
            if (action === 'CREATE') {
                actionText = 'Handover';
            } else if (action === 'UPDATE') {
                actionText = 'Mutasi';
            } else if (action === 'DELETE') {
                actionText = 'Return';
            } else {
                actionText = 'N/A';
            }
            document.getElementById('modalAction').textContent = actionText;

            // Update the print button's data-action attribute
            document.getElementById('printButton').setAttribute('data-action', action);
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