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
                @if (session('success'))
                    Swal.fire({
                        title: 'Success!',
                        text: '{{ session('success') }}', // Pesan sukses dari session
                        icon: 'success', // Ikon sukses
                        confirmButtonText: 'OK' // Tombol OK
                    });
                @endif

                // Menampilkan pesan error validasi
                @if ($errors->any())
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
                <!-- <a class="back-wrapper" id="back-icon" href="{{ url()->previous() }}">
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
            <div class="d-flex">
                <button id="generateQRCodeButton" class="btn btn-secondary" style="display: none;">
                    <i class="fa-solid fa-qrcode fa-lg"></i> QR-Code
                </button>
                <button id="exportToExcelButton" class="btn btn-success" style="display: none;">
                    <i class="fa-solid fa-file-excel fa-lg"></i> Export to Excel
                </button>
            </div>
            <div class="table-responsives">
                <table id="assetTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 70px;">No.</th>
                            <th scope="col" style="width: 150px;">Asset Code</th>
                            <th scope="col">S/N</th>
                            <th scope="col" style="width: 100px;">Location</th>
                            <th scope="col" style="width: 130px;">Name Holder</th>
                            <th scope="col">Value (Rp)</th>
                            <th scope="col">Maintenance</th>
                            <th scope="col" style="width: 100px;">Status</th>
                            <th scope="col" style="width: 50px;" class="non-sortable">
                                <input type="checkbox" id="selectAllCheckbox">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($assetss as $index => $asset)
                                                <tr>
                                                    <td data-bs-toggle="modal" title="Click to view details" data-bs-target="#detailsModal-{{ $asset->id }}"
                                                    style="cursor: pointer;">{{ $index + 1 }}</td>
                                                    <td data-bs-toggle="modal" title="Click to view details" data-bs-target="#detailsModal-{{ $asset->id }}"
                                                    style="cursor: pointer;">{{ $asset->code }}</td>
                                                    <td data-bs-toggle="modal" title="Click to view details" data-bs-target="#detailsModal-{{ $asset->id }}"
                                                    style="cursor: pointer;">{{ $asset->serial_number }}</td>

                                                    <td data-bs-toggle="modal" title="Click to view details" data-bs-target="#detailsModal-{{ $asset->id }}"
                                                    style="cursor: pointer;">
                                                        @php
                                                            $location = $asset->location ?? 'In Inventory';
                                                            if ($location !== 'In Inventory') {
                                                                $location = strtok($location, ',');
                                                            }
                                                        @endphp
                                                        {{ $location }}
                                                    </td>
                                                    <td data-bs-toggle="modal" title="Click to view details" data-bs-target="#detailsModal-{{ $asset->id }}"
                                                    style="cursor: pointer;">{{ $asset->customer_name ?? 'Not Yet Handover' }}</td>
                                                    <td data-bs-toggle="modal" title="Click to view details" data-bs-target="#detailsModal-{{ $asset->id }}"
                                                    style="cursor: pointer;">{{ number_format($asset->depreciation_price, 0, ',', '.') }}</td>

                                                    <td>
                                                        @php
                                                            $tanggalMaintenance = $asset->last_maintenance ?? $asset->entry_date;
                                                            [$intervalValue, $intervalUnit] = explode(' ', $asset->scheduling_maintenance);
                                                            switch (strtolower($intervalUnit)) {
                                                                case 'weeks':
                                                                    $nextMaintenanceDate = \Carbon\Carbon::parse($tanggalMaintenance)->addWeeks($intervalValue);
                                                                    break;
                                                                case 'months':
                                                                    $nextMaintenanceDate = \Carbon\Carbon::parse($tanggalMaintenance)->addMonths($intervalValue);
                                                                    break;
                                                                case 'years':
                                                                    $nextMaintenanceDate = \Carbon\Carbon::parse($tanggalMaintenance)->addYears($intervalValue);
                                                                    break;
                                                                default:
                                                                    $nextMaintenanceDate = \Carbon\Carbon::parse($tanggalMaintenance);
                                                                    break;
                                                            }
                                                            $maintenanceDue = now()->greaterThanOrEqualTo($nextMaintenanceDate);
                                                        @endphp
                                                        @if ($maintenanceDue)
                                                            <a href="{{ route('assets.maintenance') }}" title="Click to Maintenance" style="cursor: pointer;">
                                                            <span class="badge text-center align-middle"
                                                                style="padding: 5px; font-size: 0.9em; background-color:#FE7C96;">Need
                                                                Maintenance</span>
                                                            </a>
                                                        @else
                                                            <span class="badge text-center align-middle"
                                                                style="padding: 5px 44px; font-size: 0.9em; background-color:#B46EFF;">Done</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                    <a href="{{ route('transactions.index') }}" title="Click to view transactions" style="cursor: pointer;">
                                                        @if ($asset->status === 'Inventory')
                                                            <span class="badge bg-warning"
                                                                style="padding: 5px 10px; font-size: 0.9em; background-color:#FED713;">Available</span>
                                                        @elseif ($asset->status === 'Operation')
                                                            <span class="badge"
                                                                style="padding: 5px 18px; font-size: 0.9em; background-color:#1BCFB4;">In Use</span>
                                                        @endif
                                                    </a>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" style="cursor: pointer;" class="assetCheckbox" value="{{ $asset->id }}"
                                                            id="checkbox-{{ $asset->id }}" data-serial="{{ $asset->serial_number }}">
                                                    </td>
                                                </tr>
                        @endforeach
                    </tbody>
                </table>
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
                            <span class="legend-description">Assets need Maintenance.</span>
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

@foreach ($assetss as $asset)
    <div class="modal fade" id="detailsModal-{{ $asset->id }}" tabindex="-1" aria-labelledby="detailsModalLabel"
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
                                        <th><strong>Category</strong></th>
                                        <td>{{ $asset->category }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>Code</strong></th>
                                        <td>{{ $asset->code }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>Merk</strong></th>
                                        <td>{{ $asset->merk_name }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>S/N</strong></th>
                                        <td>{{ $asset->serial_number }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>Specification</strong></th>
                                        <td>{{ $asset->spesification }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>Condition</strong></th>
                                        <td>{{ $asset->condition }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Tabel Kanan -->
                        <div class="col-md-6">
                            <table class="table no-border-table">
                                <tbody>
                                    <tr>
                                        <th><strong>Entry Date</strong></th>
                                        <td
                                            style="background-color: rgba(0, 0, 255, 0.2); border-radius: 20px; padding: 5px 10px; display: inline-block;">
                                            @php
                                                $tanggalMasuk = $asset->entry_date;
                                                echo date('d-m-Y', strtotime($tanggalMasuk));
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <th><strong>Handover Date</strong></th>
                                        <td @php
                                            $tanggalDiterima = $asset->handover_date ?? '-';
                                        $bgColor = ($tanggalDiterima === '0000-00-00 00:00:00' || $tanggalDiterima === '-') ? 'rgba(128, 128, 128, 0.2)' : 'rgba(0, 255, 0, 0.2)'; @endphp
                                            style="background-color: {{ $bgColor }}; border-radius: 20px; padding: 5px 10px; display: inline-block;">

                                            @if ($tanggalDiterima === '0000-00-00 00:00:00' || $tanggalDiterima === '-')
                                                Not Yet Handover
                                            @else
                                                {{ date('d-m-Y', strtotime($tanggalDiterima)) }}
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th><strong>Scheduling Maintenance</strong></th>
                                        <td>{{ $asset->scheduling_maintenance }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>Last Maintenance</strong></th>
                                        <td
                                            style="background-color: rgba(255, 255, 0, 0.2); border-radius: 20px; padding: 5px 10px; display: inline-block;">
                                            @php
                                                $last_maintenanceDate = $asset->last_maintenance ?? '-';
                                                if (
                                                    $last_maintenanceDate === '0000-00-00 00:00:00' ||
                                                    $last_maintenanceDate === '-'
                                                ) {
                                                    echo '-';
                                                } else {
                                                    echo date('d-m-Y', strtotime($last_maintenanceDate));
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><strong>Next Maintenance</strong></th>
                                        <td
                                            style="background-color: rgba(255, 182, 193, 0.2); border-radius: 20px; padding: 5px 10px; display: inline-block;">
                                            @if ($asset->next_maintenance)
                                                                                @php
                                                                                    $nextMaintenanceDate = \Carbon\Carbon::parse(
                                                                                        $asset->next_maintenance,
                                                                                    );
                                                                                    echo $nextMaintenanceDate->format('d-m-Y');
                                                                                @endphp
                                            @else
                                                <span>Not Scheduled</span>
                                            @endif
                                        </td>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn open-history-modal" style="background-color: #FFC107;"
                        data-code="{{ $asset->code }}" data-asset-id="{{ $asset->id }}" data-bs-toggle="modal"
                        data-bs-target="#DepreciationModal-{{ $asset->id }}">
                        <i class="bi bi-calculator"></i>
                        View Depreciation
                    </button>
                    <button type="button" class="btn open-history-modal" style="background-color: #9A9A9A;"
                        data-code="{{ $asset->code }}" data-asset-id="{{ $asset->id }}" data-bs-toggle="modal"
                        data-bs-target="#historyModal-{{ $asset->id }}"
                        onclick="loadTransactionHistory('{{ $asset->code }}', '{{ $asset->id }}')">
                        <i class="bi bi-clock-history"></i>
                        View History
                    </button>
             




                </div>
            </div>
        </div>
    </div>

    <!-- History Modal -->
    <div class="modal fade" id="historyModal-{{ $asset->id }}" tabindex="-1" aria-labelledby="historyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center fw-bold w-100" id="historyModalLabel">
                        Transaction History for
                        <span class="asset-code">{{ $asset->code }}</span>
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-4">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Transfer Date</th>
                                    <th>Action</th>
                                    <th>Holder</th>
                                    <th>Note</th>
                                    <th>Documentation</th>
                                </tr>
                            </thead>
                            <tbody id="modalHistoryBody-{{ $asset->id }}">
                                <!-- History rows will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="DepreciationModal-{{ $asset->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center fw-bold w-100" id="exampleModalLabel">Depreciation History for {{ $asset->code }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Asset Value</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="modalDepreciationBody-{{ $asset->id }}">
                            <!-- Data akan dimuat di sini -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>







    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.open-history-modal').forEach(button => {
            button.addEventListener('click', function () {
                var assetCode = this.getAttribute('data-code');
                var assetId = this.getAttribute('data-asset-id');

                // Load transaction history
                loadTransactionHistory(assetCode, assetId);

                // Load depreciation data
                loadDepreciationData(assetCode, assetId);
            });
        });
    });

    // Function to load transaction history
    function loadTransactionHistory(assetCode, assetId) {
        var modalBody = document.getElementById('modalHistoryBody-' + assetId);

        fetch(`/transaction-history/${assetCode}`)
            .then(response => response.json())
            .then(data => {
                modalBody.innerHTML = '';

                data.forEach(item => {
                    let typeBadge;
                    let printButton = '';
                    let docIcon = '';

                    if (item.type_transactions === 'Return') {
                        typeBadge = '<span class="badge bg-danger">Return</span>';
                        printButton = `<button class="btn btn-sm btn-success" onclick="printReturnProof(${item.id})"><i class="fas fa-print"></i></button>`;
                    } else if (item.type_transactions === 'Handover') {
                        typeBadge = '<span class="badge bg-success">Handover</span>';
                        printButton = `<button class="btn btn-sm btn-success" onclick="printHandoverProof(${item.id})"><i class="fas fa-print"></i></button>`;
                    } else {
                        typeBadge = `<span class="badge bg-secondary">${item.type_transactions}</span>`;
                    }

                    if (item.documentation) {
                        docIcon = `<a href="/storage/${item.documentation}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-file-alt"></i></a>`;
                    } else {
                        docIcon = '<span class="text-muted"><i class="fas fa-times"></i></span>';
                    }

                    var row = `<tr>
                                    <td>${item.created_at}</td>
                                    <td>${typeBadge}</td>
                                    <td>${item.name_holder}</td>
                                    <td>${item.note}</td>
                                    <td>${docIcon}</td> 
                                    <td>${printButton}</td> 
                                </tr>`;
                    modalBody.innerHTML += row;
                });
            });
    }

    // Function to load depreciation data
    function loadDepreciationData(assetCode, assetId) {
        var modalBody = document.getElementById('modalDepreciationBody-' + assetId);

        console.log('Asset Code:', assetCode);

        fetch(`/depreciation/${assetCode}`)
            .then(response => response.json())
            .then(data => {
                modalBody.innerHTML = '';

                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(item => {
                        var currentDate = new Date();
                        var depreciationDate = new Date(item.date);

                        var status = (depreciationDate > currentDate) ? 'Has not depreciated' : 'Depreciated';
                        var badgeClass = (status === 'Depreciated') ? 'badge bg-danger' : 'badge bg-success';

                        var row = `<tr>
                                        <td>${item.date}</td>
                                        <td>${formatNumber(item.depreciation_price)}</td>
                                        <td><span class="${badgeClass}">${status}</span></td>
                                    </tr>`;
                        modalBody.innerHTML += row;
                    });
                } else {
                    modalBody.innerHTML = '<tr><td colspan="3">No depreciation data available</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error fetching depreciation data:', error);
                modalBody.innerHTML = '<tr><td colspan="3">Error loading data</td></tr>';
            });
    }

    // Basic number formatting function if formatNumber is not defined
    function formatNumber(number) {
        return new Intl.NumberFormat().format(number);
    }

    // Print Handover Proof
    function printHandoverProof(transactionId) {
        const route = `/prints/handover/${transactionId}`;
        window.open(route, '_blank');
    }

    // Print Return Proof
    function printReturnProof(transactionId) {
        const route = `/prints/return/${transactionId}`;
        window.open(route, '_blank');
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
<!-- Include QRCode.js library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const generateQRCodeButton = document.getElementById('generateQRCodeButton');
        const exportToExcelButton = document.getElementById('exportToExcelButton');
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const assetCheckboxes = document.querySelectorAll('.assetCheckbox');

        function toggleActionButtons() {
            const isAnyCheckboxChecked = Array.from(assetCheckboxes).some(checkbox => checkbox.checked);
            generateQRCodeButton.style.display = isAnyCheckboxChecked ? 'inline-block' : 'none';
            exportToExcelButton.style.display = isAnyCheckboxChecked ? 'inline-block' : 'none';
        }

        // Toggle button visibility on 'Select All' checkbox change
        selectAllCheckbox.addEventListener('change', () => {
            assetCheckboxes.forEach(checkbox => checkbox.checked = selectAllCheckbox.checked);
            toggleActionButtons();
        });

        // Toggle button visibility on individual checkbox changes
        assetCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', toggleActionButtons);
        });

        // QR Code button click handler
        generateQRCodeButton.addEventListener('click', function () {
            const selectedIds = Array.from(assetCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            if (selectedIds.length === 0) {
                alert('Please select at least one asset.');
                return;
            }

            const idsString = selectedIds.join(',');
            window.open(`{{ url('/print/qr') }}?ids=${idsString}`, '_blank');
        });

        // Export to Excel button click handler
        exportToExcelButton.addEventListener('click', function () {
            const selectedIds = Array.from(assetCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            if (selectedIds.length === 0) {
                alert('Please select at least one asset.');
                return;
            }

            // Send a request to the export route with selected IDs
            const idsString = selectedIds.join(',');
            window.location.href = `{{ url('/export/excel') }}?ids=${idsString}`;
        });
    });
</script>







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
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) -10%, #FCA918);
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
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) -13%, #FBCA07);
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

    .btn {
        margin: 0 0.5rem;

    }

    .asset-code {
        background-color: rgba(128, 128, 128, 0.1);
        /* Light gray with transparency */
        padding: 4px 8px;
        /* Optional: Adjust padding for spacing */
        border-radius: 5px;
        /* Rounded corners */
        display: inline-block;
        /* Keeps the span as an inline element */
    }
</style>