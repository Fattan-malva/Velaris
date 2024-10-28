@extends('layouts.app')
@section('title', 'Assets List')

@section('content')
<br>


<div class="container">
    <div>
        <div class="container">
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>
                // Menampilkan pesan sukses setelah redirect dari controller
                @if(session('success'))
                    Swal.fire({
                        title: 'Success!',
                        text: '{{ session('success') }}', // Pesan sukses dari session
                        icon: 'success', // Ikon sukses
                        confirmButtonText: 'OK' // Tombol OK
                    });
                @endif

                // Menampilkan pesan error validasi
                @if($errors->any())
                    Swal.fire({
                        title: 'Error!',
                        text: '{!! implode(', ', $errors->all()) !!}', // Menggabungkan semua pesan error
                        icon: 'error', // Ikon error
                        confirmButtonText: 'OK' // Tombol OK
                    });
                @endif
            </script>
            <div class="header-container">
                <div class="back-wrapper">
                    <i class='bx bxs-chevron-left back-icon' id="back-icon"></i>
                    <div class="back-text">
                        <span class="title">Back</span>
                        <span class="small-text">to previous page</span>
                    </div>
                </div>
                <!-- <a class="back-wrapper" id="back-icon" href="{{url()->previous()}}">
                    <i class='bx bxs-chevron-left back-icon'></i>
                    <div class="back-text">
                        <span class="title">Back</span>
                        <span class="small-text">to previous page</span>
                    </div>
                </a> -->
                <h3 class="assetList-title">
                     Asset List&nbsp;&nbsp;
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-list-ul previous-icon"></i>
                    </span>
                </h3>
            </div>
            <div class="header-container-mobile">
                <h3 class="assetList-title">
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-list-ul previous-icon"></i>
                    </span>
                    &nbsp;&nbsp;Asset List
                </h3>
            </div>
            <br>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            <div class="table-responsive">
                <table id="inventoryTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 70px;">No.</th>
                            <th scope="col" style="width: 100px;">Asset Code</th>
                            <th scope="col">S/N</th>
                            <!-- <th scope="col">Type</th>
                            <th scope="col">Merk</th> -->
                            <th scope="col" style="width: 200px;">Location</th>
                            <th scope="col" style="width: 130px;">Name Holder</th>
                            <th scope="col">Maintenance</th>
                            <th scope="col" style="width: 100px;">Status</th>
                            <!-- <th scope="col">Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inventorys as $index => $inventory)
                                                <tr data-bs-toggle="modal" data-bs-target="#detailsModal-{{ $inventory->id }}"
                                                    style="cursor: pointer;">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $inventory->tagging }}</td>
                                                    <td>{{ $inventory->seri }}</td>
                                                    <!-- <td>{{ $inventory->asets }}</td>
                                                                                                                            <td>{{ $inventory->merk_name }}</td> -->

                                                    <td>
                                                        @php
                                                            // Ambil nilai lokasi
                                                            $lokasi = $inventory->lokasi ?? 'In Inventory';

                                                            // Jika lokasi tidak kosong, ambil kata sebelum koma pertama
                                                            if ($lokasi !== 'In Inventory') {
                                                                $lokasi = strtok($lokasi, ',');
                                                            }
                                                        @endphp

                                                        {{ $lokasi }}
                                                    </td>
                                                    <td>{{ $inventory->name_holder ?? 'New Assets' }}</td>
                                                    <td>
                                                        @php
                                                            $tanggalMasuk = $inventory->tanggalmasuk;
                                                            $tanggalMaintenance = $inventory->maintenance ?? null;

                                                            $tanggalAcuan = $tanggalMaintenance ?? $tanggalMasuk;


                                                            $bulanSejakAcuan = now()->diffInMonths($tanggalAcuan);


                                                            $tanggalMaintenanceFormatted = $tanggalMaintenance ? date('d-m-Y', strtotime($tanggalMaintenance)) : '-';
                                                        @endphp


                                                        @if ($bulanSejakAcuan >= 1)
                                                            <span class="badge text-center align-middle"
                                                                style="padding: 5px; font-size: 0.9em;   background-color:#FE7C96;">
                                                                Need Maintenance
                                                            </span>
                                                        @else
                                                            <span class="badge text-center align-middle"
                                                                style="padding: 5px 44px; font-size: 0.9em;  background-color:#B46EFF;">
                                                                Done
                                                            </span>
                                                        @endif
                                                    </td>


                                                    <td class="text-center align-middle">
                                                        <!-- Status Badge -->
                                                        @if ($inventory->status === 'Inventory')
                                                            <span class="badge bg-warning"
                                                                style="padding: 5px 30  px;  font-size: 0.9em; background-color:#FED713;">Available</span>
                                                        @elseif ($inventory->status === 'Operation')
                                                            <span class="badge"
                                                                style="padding: 5px 18px;  font-size: 0.9em; background-color:#1BCFB4;">In
                                                                Use</span>
                                                        @endif
                                                    </td>
                                                    <!-- <td>
                                                                                                                                <div class="action-buttons">
                                                                                                                                    <button type="button" class="btn text-white" data-bs-toggle="modal"
                                                                                                                                        data-bs-target="#detailsModal-{{ $inventory->id }}" title="Details"
                                                                                                                                        style="background-color:#4FB0F1;">
                                                                                                                                        <i class="bi bi-file-earmark-text-fill text-white"></i> Detail
                                                                                                                                    </button>
                                                                                                                      
                                                                                                                                </div>
                                                                                                                            </td> -->
                                                </tr>
                        @endforeach

                    </tbody>
                </table>
                <!-- Legend for Status Badges -->
                <div class="mt-4">
                    <ul class="list-unstyled legend-list">
                        <li>
                            <span class="badge legend-badge"
                                style="padding: 5px 32px; color: #fff; margin-right: 5px; background-color: #ffdc3b;">Available</span>
                            <span class="legend-colon">:</span>
                            <span class="legend-description">Asset is available for use.</span>
                        </li>
                        <li>
                            <span class="badge legend-badge"
                                style="padding: 5px 39px; color: #fff; margin-right: 5px; background-color: #1bcfb4;">In
                                Use</span>
                            <span class="legend-colon">:</span>
                            <span class="legend-description">Asset is currently in operation.</span>
                        </li>
                        <li>
                            <span class="badge legend-badge"
                                style="padding: 5px 9px; margin-right: 5px; background-color: #fe7c96">Need
                                Maintenance</span>
                            <span class="legend-colon">:</span>
                            <span class="legend-description">Assets need maintenance.</span>
                        </li>
                        <li>
                            <span class="badge legend-badge"
                                style="padding: 5px 42px; color: #fff; margin-right: 5px; background-color: #b66dff">Done</span>
                            <span class="legend-colon">:</span>
                            <span class="legend-description">Assets have been maintained.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($inventorys as $inventory)
    <div class="modal fade" id="detailsModal-{{ $inventory->id }}" tabindex="-1" aria-labelledby="detailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center fw-bold w-100" id="detailsModalLabel">Asset Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Tabel Kiri -->
                        <div class="col-md-6">
                            <table class="table no-border-table">
                                <tbody>
                                    <tr>
                                        <th><strong>Code</strong></th>
                                        <td>{{ $inventory->tagging }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>Type</strong></th>
                                        <td>{{ $inventory->asets }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>Merk</strong></th>
                                        <td>{{ $inventory->merk_name }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>S/N</strong></th>
                                        <td>{{ $inventory->seri }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Tabel Kanan -->
                        <div class="col-md-6">
                            <table class="table no-border-table">
                                <tbody>
                                    <tr>
                                        <th><strong>Specification</strong></th>
                                        <td>{{ $inventory->type }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>Entry Date</strong></th>
                                        <td
                                            style="background-color: rgba(0, 0, 255, 0.2); border-radius: 20px; padding: 5px 10px; display: inline-block;">
                                            @php
                                                $tanggalMasuk = $inventory->tanggalmasuk;
                                                echo date('d-m-Y', strtotime($tanggalMasuk));
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <th><strong>Handover Date</strong></th>
                                        <td
                                            style="background-color: rgba(0, 255, 0, 0.2); border-radius: 20px; padding: 5px 10px; display: inline-block;">
                                            @php
                                                $tanggalDiterima = $inventory->tanggal_diterima ?? '-';
                                                if ($tanggalDiterima === '0000-00-00 00:00:00' || $tanggalDiterima === '-') {
                                                    echo '-';
                                                } else {
                                                    echo date('d-m-Y', strtotime($tanggalDiterima));
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <th><strong>Last Maintenance</strong></th>
                                        <td
                                            style="background-color: rgba(255, 255, 0, 0.2); border-radius: 20px; padding: 5px 10px; display: inline-block;">
                                            @php
                                                $maintenanceDate = $inventory->maintenance ?? '-';
                                                if ($maintenanceDate === '0000-00-00 00:00:00' || $maintenanceDate === '-') {
                                                    echo '-';
                                                } else {
                                                    echo date('d-m-Y', strtotime($maintenanceDate));
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">

                    <button type="button" class="btn"
                        onclick="window.open('{{ route('prints.qr', ['id' => $inventory->id]) }}', '_blank')"
                        style="background-color:#1BCFB4;">
                        <i class="bi bi-qr-code"></i> Print QR Code
                    </button>
                    <button type="button" class="btn  open-history-modal " style="background-color: #9A9A9A;"
                        data-tagging="{{ $inventory->tagging }}" data-inventory-id="{{ $inventory->id }}"
                        data-bs-toggle="modal" data-bs-target="#historyModal-{{ $inventory->id }}">
                        <i class="bi bi-clock-history"></i>
                        View History
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal structure for each inventory -->
    <div class="modal fade" id="historyModal-{{ $inventory->id }}" tabindex="-1" aria-labelledby="historyModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="historyModalLabel">History for Asset: {{ $inventory->tagging }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>Date</th>
                                <th>User</th>
                                <th>Reason</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="history-body-{{ $inventory->id }}">
                            <!-- Data history akan dimuat di sini -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Event listener for all modal open buttons
            const modalButtons = document.querySelectorAll('.open-history-modal'); // Ensure these buttons have this class

            modalButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const tagging = this.getAttribute('data-tagging'); // Get tagging from button attribute
                    const inventoryId = this.getAttribute('data-inventory-id'); // Get inventory ID

                    // Call loadHistory with the correct tagging and inventory ID
                    loadHistory(tagging, inventoryId);
                });
            });
        });

        function loadHistory(tagging, inventoryId) {
            fetch(`{{ route('inventory.historyModal') }}?tagging=${tagging}`)
                .then(response => response.json())
                .then(data => {
                    const historyBody = document.getElementById(`history-body-${inventoryId}`);
                    historyBody.innerHTML = ''; // Clear previous data
                    console.log(data); // Log the fetched data to check the structure

                    if (data.length > 0) {
                        let latestUpdateDocumentation = null;
                        let deleteDocumentation = null;

                        // First pass: Identify the relevant documentation
                        data.forEach(item => {
                            if (item.action === 'UPDATE' && item.documentation_new) {
                                latestUpdateDocumentation = item.documentation_new;
                            }
                            if (item.action === 'DELETE' && item.documentation_old) {
                                deleteDocumentation = item.documentation_old;
                            }
                        });

                        // Second pass: Display only the rows with badges (Handover or Return) and filter for 'Return' with a reason
                        data.forEach(item => {
                            let actionBadge = '';
                            let documentationLink = '';
                            let printButton = '';

                            if (item.action === 'CREATE') {
                                actionBadge = '<span class="badge bg-success">Handover</span>';
                                if (latestUpdateDocumentation) {
                                    documentationLink = `<a href="{{ asset('storage/${latestUpdateDocumentation}') }}" target="_blank" class="btn btn-primary btn-sm">View Document</a>`;
                                } else {
                                    documentationLink = `<button class="btn btn-secondary btn-sm" disabled>No Document</button>`;
                                }
                            } else if (item.action === 'DELETE' && item.keterangan) {
                                // Show only 'Return' (DELETE) actions where 'reason' (keterangan) is filled
                                actionBadge = '<span class="badge bg-danger">Return</span>';
                                if (deleteDocumentation) {
                                    documentationLink = `<a href="{{ asset('storage/${deleteDocumentation}') }}" target="_blank" class="btn btn-primary btn-sm">View Document</a>`;
                                } else {
                                    documentationLink = `<button class="btn btn-secondary btn-sm" disabled>No Document</button>`;
                                }
                            }

                            // Only generate rows for actions that have a badge (CREATE or DELETE with a reason)
                            if (actionBadge) {
                                // Print button
                                printButton = `<button type="button" class="btn btn-success btn-sm printButton" 
                                                                                                        data-action="${item.action}" 
                                                                                                        data-tagging="${item.asset_tagging}" 
                                                                                                        data-changed-at="${item.changed_at}">
                                                                                                        <i class="bi bi-printer"></i> Print Proof
                                                                                                    </button>`;

                                // Generate the row
                                const row = `<tr>
                                                                                                            <td>${actionBadge}</td>
                                                                                                            <td>${item.changed_at}</td>
                                                                                                            <td>${item.nama_old || '-'}</td>
                                                                                                            <td>${item.keterangan || '-'}</td>
                                                                                                            <td>${item.note || '-'}</td>
                                                                                                            <td>
                                                                                                                ${documentationLink}
                                                                                                                ${printButton}
                                                                                                            </td>
                                                                                                        </tr>`;

                                historyBody.innerHTML += row;
                            }
                        });

                        // Add event listeners for print buttons
                        document.querySelectorAll('.printButton').forEach(button => {
                            button.addEventListener('click', function () {
                                var action = this.getAttribute('data-action');
                                var assetTagging = this.getAttribute('data-tagging');
                                var changedAt = this.getAttribute('data-changed-at');

                                // Extract the date part (yyyy-mm-dd)
                                var changedAtDate = changedAt.split(' ')[0];

                                // Convert the date from 'yyyy-mm-dd' to 'd-m-Y'
                                var dateParts = changedAtDate.split('-');
                                var formattedDate = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;

                                var route = '';
                                if (action === 'CREATE') {
                                    route = '{{ route('prints.handover') }}';
                                } else if (action === 'UPDATE') {
                                    route = '{{ route('prints.mutation') }}';
                                } else if (action === 'DELETE') {
                                    route = '{{ route('prints.return') }}';
                                }

                                if (route) {
                                    var fullUrl = `${route}?asset_tagging=${encodeURIComponent(assetTagging)}&changed_at=${encodeURIComponent(formattedDate)}`;
                                    console.log('Opening URL: ' + fullUrl);
                                    window.open(fullUrl, '_blank');
                                }
                            });
                        });
                    } else {
                        historyBody.innerHTML = '<tr><td colspan="6" class="text-center">No history found</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching history:', error);
                });
        }


    </script>

@endforeach



@section('scripts')
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>


@endsection
@endsection

<style>
    .card {
        box-shadow: rgba(0, 0, 0, 0.15) 2.4px 2.4px 3.2px;
    }

    .no-border-table th,
    .no-border-table td {
        border: none !important;
        padding: 9px 12px;
    }

    .no-border-table td {
        margin-top: 6px;
    }

    .legend-list {
        font-size: 0.875em;
        line-height: 1.5;
        margin-top: 33px;
    }

    .legend-list li {
        display: flex;
        flex-direction: row;
        /* Align items horizontally */
        align-items: center;
        /* Center vertically */
        margin-bottom: 5px;
    }

    .legend-description {
        margin-left: 15px;
        /* Add margin for larger screens */
    }

    /* Responsive styles */
    @media (max-width: 576px) {
        .legend-list li {
            flex-direction: column;
            /* Stack items vertically on mobile */
            align-items: flex-start;
            /* Align items to the start */
        }

        .legend-colon {
            display: none;
            /* Hide colon on mobile */
        }

        .legend-description {
            margin-left: 0;
            /* Reset margin for mobile */
            text-align: left;
            /* Align description to the left */
        }
    }

    /* Container styles */
    .header-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        margin-top: 30px;
    }

    .back-wrapper {
        display: flex;
        align-items: center;
        margin-right: auto;
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
        padding-top: 9px;
        padding-left: 9px;
        transition: background 0.3s ease;
    }

    .back-icon:hover {
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) -13%, #B100FF);
    }

    .back-text {
        display: flex;
        flex-direction: column;
        margin-left: 10px;
    }

    .back-text .title {
        font-weight: 600;
        font-size: 17px;
    }

    .back-text .small-text {
        font-size: 0.8rem;
        color: #aaa;
        margin-top: -3px;
    }

    .assetList-title {
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

    .btn {
        margin: 0 0.5rem;

    }
</style>