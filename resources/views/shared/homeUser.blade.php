@extends('layouts.app')
@section('title', 'Home')
@section('content')
<link rel="icon" href="{{ asset('assets/img/velaris.png') }}" type="image/png">
<main id="main">
    <section id="user-transactions" class="user-transactions">
        <br>
        <br>
        <h1 class="text-center ms-3 animate_animated animate_fadeInDown display-4 fw-bold">
            Hello <b class="fw-bold">{{ ucfirst(strtolower(session('user_name'))) }}</b>,
            This is your asset
        </h1>
        <br>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    
                    <div class="card" style="box-shadow: rgba(0, 0, 0, 0.15) 2.4px 2.4px 3.2px;">
                        <div class="card-header text-white" style="background-color: #fed713; text-align: center">
                            <h2 style="font-weight: bold; margin-top:5px">Waiting Approval</h2>
                        </div>

                        <div class="col-md-12 d-flex justify-content-end" style="margin-top: 15px; margin-left: -20px; margin-right: 20px;">
                            <div class="form-check" style="display: inline-flex; align-items: center;">
                                <label for="selectAll" class="form-check-label" style="margin-right: 30px;">Select All</label>
                                <input type="checkbox" id="selectAll" class="form-check-input" style="margin-top: 0px;">
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($pendingAssets->isEmpty())
                                <p class="text-center">No transactions waiting for approval.</p>
                            @else
                                <form action="{{ route('transactions.bulkAction') }}" method="POST" id="bulkActionForm">
                                    @csrf
                                    <div class="row">
                                        @foreach ($pendingAssets as $asset)
                                            <div class="col-md-12 mb-3">
                                                <div class="card"
                                                    style="background-color: rgba({{ $asset->type_transactions == 'Handover' ? '174,244,191' : '' }} {{ $asset->type_transactions == 'Mutasi' ? '255, 193, 7, 0.2' : '' }} {{ $asset->type_transactions == 'Return' ? '220, 53, 69, 0.2' : '' }}); border: 3px solid black;">
                                                    <div class="card-body" style="margin-top: 30px;">
                                                        <div class="d-flex align-items-center justify-content-between mb-4">
                                                            <img src="{{ asset('assets/img/pending.png') }}"
                                                                alt="Pending Asset Icon" class="me-3"
                                                                style="width: 80px; height: 80px;">
                                                            <p class="card-text flex-grow-1">
                                                                <strong>Category:</strong> {{ $asset->category_asset }}<br>
                                                                <strong>Asset Code:</strong> {{ $asset->tagging }}<br>
                                                                <strong>Merk:</strong> {{ $asset->merk_name }}
                                                            </p>
                                                            <div class="form-check ms-auto position-absolute top-0 end-0 m-2 " style="margin-left: auto;">
                                                            <span
                                                                class="badge" style="background-color: rgba({{ $asset->type_transactions == 'Handover' ? '27,207,180' : '' }} {{ $asset->type_transactions == 'Mutasi' ? '254,215,19' : '' }} {{ $asset->type_transactions == 'Return' ? '254,124,150' : '' }}); margin-left: {{ $asset->type_transactions == 'Return' ? '-90px' : '-105px' }};">
                                                                {{ $asset->type_transactions }}
                                                            </span>
                                                                <input type="checkbox" class="form-check-input" name="transactions[]"
                                                                    value="{{ $asset->id }}" id="asset-{{ $asset->id }}"
                                                                    style="transform: scale(1.5);">
                                                                <label class="form-check-label"
                                                                    for="asset-{{ $asset->id }}"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="d-flex justify-content-between mt-4">
                                        <button id="approveButton" type="button" class="btn btn-success"
                                            onclick="submitApproveForm()" style="display: none; padding: 8px 18px; border: none; border-radius: 5px; background-color: #1bcfb4; color: #fff; font-weight: 600; margin-right: 10px;">Approve</button>
                                        <button id="rejectButton" type="button" class="btn btn-danger"
                                            onclick="confirmReject()" style="display: none;  padding: 8px 18px; border: none; border-radius: 5px; background-color: #fe7c96; color: #fff; font-weight: 600; margin-right: 10px;">Reject</button>

                                    </div>
                                    <input type="hidden" name="action" id="action" value="">
                                </form>
                                
                            @endif
                        </div>
                    </div>
            </div>
                <!-- Assets Section -->
                <div class="col-md-8">
                    <!-- Section for Approved Assets -->
                    <div class="card" style="box-shadow: rgba(0, 0, 0, 0.15) 2.4px 2.4px 3.2px;">
                        <div class="card-header text-white" style="background-color: #1bcfb4;">
                            <h2 style="text-align: center; margin-top:5px; font-weight: bold;">Approved Assets</h2>
                        </div>
                        <div class="card-body flex-column align-items-center">
                            @if ($transactions->isEmpty())
                                <p class="text-center">No approved transactions found.</p>
                            @else
                                <div class="row justify-content-center">
                                    @foreach ($transactions as $index => $asset)
                                        <div class="col-md-5 mb-3">
                                            <div class="card"
                                                style="background-color: rgb(218,181,255); border: none;">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-4">
                                                        @php
                                                            // Determine the image file based on the category_asset
                                                            $iconMap = [
                                                                'PC' => 'pc.png',
                                                                'Tablet' => 'tablet.png',
                                                                'Laptop' => 'laptop.png',
                                                                // Add more mappings as needed
                                                            ];
                                                            $iconFile = isset($iconMap[$asset->category_asset]) ? $iconMap[$asset->category_asset] : 'default.png'; // Fallback to default icon
                                                        @endphp
                                                        <img src="{{ asset('assets/img/' . $iconFile) }}" alt="Asset Icon"
                                                            class="me-3" style="width: 60px; height: 60px;">
                                                        <p class="card-text">
                                                            <strong>Category:</strong> {{ $asset->category_asset }}<br>
                                                            <strong>Asset Code:</strong> {{ $asset->tagging }}<br>
                                                            <strong>Merk:</strong> {{ $asset->merk_name }}<br>
                                                        </p>
                                                    </div>

                                                    <div class="action-buttons d-flex justify-content-end">
                                                    <button class="btn btn-sm" style="background-color: #4FB0F1; color: #fff; font-weight: 500;" data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $asset->id }}" title="View Details">
                                                        <i class="bi bi-file-earmark-text"></i> Detail
                                                    </button>
                                                </div>

                                                </div>
                                                <!-- Modal -->
                                                <div class="modal fade" id="detailModal{{ $asset->id }}" tabindex="-1"
                                                    aria-labelledby="detailModalLabel{{ $asset->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title text-center flex-grow-1" style="font-weight: 600;" id="detailModalLabel{{ $asset->id }}">
                                                                    Asset Details</h4>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                <div class="col-md-6">
                                                                <table class="table" style="border-collapse: collapse; border: none;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <th style="border: none; font-size: 16px; width: 110px;">Category</th>
                                                                            <td style="border: none;">{{ $asset->category_asset }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th style="border: none; font-size: 16px; width: 120px;">Asset Code</th>
                                                                            <td style="border: none;">{{ $asset->tagging }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th style="border: none; font-size: 16px; width: 120px;">S/N</th>
                                                                            <td style="border: none;">{{ $asset->serial_number }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th style="border: none; font-size: 16px; width: 110px;">Merk</th>
                                                                            <td style="border: none;">{{ $asset->merk_name }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th style="border: none; font-size: 16px; width: 110px;">Specification</th>
                                                                            <td style="border: none;">{{ $asset->spesification }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th style="border: none; font-size: 16px; width: 110px;">Condition</th>
                                                                            <td style="border: none;">{{ $asset->condition }}</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <table class="table" style="border-collapse: collapse; border: none;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <th style="border: none; font-size: 16px; width: 135px;">Handover Date</th>
                                                                            <td style="border: none;">{{ $asset->handover_date }}</td>
                                                                        </tr>                                                                    
                                                                        <tr>
                                                                            <th style="border: none; font-size: 16px; width: 110px;">Scheduling Maintenance</th>
                                                                            <td style="border: none;">{{ $asset->scheduling_maintenance }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th style="border: none; font-size: 16px; width: 110px;">Last Maintenance</th>
                                                                            <td style="border: none;">{{ $asset->last_maintenance ?? 'Not Yet Maintenance' }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th style="border: none; font-size: 16px; width: 110px;">Next Maintenance</th>
                                                                            <td style="border: none;">{{ $asset->next_maintenance }}</td>
                                                                        </tr>
                                                                    </tbody>
                                                                    
                                                                </table>
                                                            </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    @if($asset->documentation)
                                                                        <button type="button" class="btn" style="background-color: #4294FF;" onclick="window.open('{{ asset('storage/' . $asset->documentation) }}', '_blank')">
                                                                            <i class="bi bi-file-earmark-image"></i> Documentation
                                                                        </button>
                                                                    @else
                                                                        <button type="button" class="btn" style="background-color: #6c757d;" disabled>
                                                                            <i class="bi bi-file-earmark-excel"></i> No Documentation
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!-- Offcanvas Ticket -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="ticketOffcanvas" aria-labelledby="ticketOffcanvasLabel" style="width: 50%; z-index: 1050;">
    <div class="offcanvas-header" style="background-color: #B46EFF;">
        <h5 id="ticketOffcanvasLabel" class="text-white fw-bold">Ticket</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- Ticket Index Component -->
        <div>
            @livewire('ticket-index')
        </div>
    </div>
</div>

<!-- Modal Create Ticket (placed outside offcanvas to appear above it) -->
<div class="modal fade" id="createTicketModal" tabindex="-1" aria-labelledby="createTicketModalLabel" aria-hidden="true" wire:ignore.self style="z-index: 1051;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-center" id="createTicketModalLabel">Create New Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- TicketCreate Livewire Component -->
                @livewire('ticket-create')
            </div>
        </div>
    </div>
</div>






    </section>
</main>
@section('scripts')
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@endsection
<script>
    // Toggle "Select All" functionality
    document.getElementById('selectAll').addEventListener('click', function () {
        const checkboxes = document.querySelectorAll('input[name="transactions[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleActionButtons(); // Update button visibility after toggling
    });

    function submitApproveForm() {
        document.getElementById('action').value = 'approve';
        document.getElementById('bulkActionForm').submit();
    }

    function confirmReject() {
        const confirmation = confirm("Are you sure you want to reject the selected transactions?");
        if (confirmation) {
            document.getElementById('action').value = 'reject';
            document.getElementById('bulkActionForm').submit();
        }
    }

    function toggleActionButtons() {
        const selectedAssets = document.querySelectorAll('input[name="transactions[]"]:checked');
        const approveButton = document.getElementById('approveButton');
        const rejectButton = document.getElementById('rejectButton');

        // Show or hide buttons based on selection
        if (selectedAssets.length > 0) {
            approveButton.style.display = 'inline-block'; // Show buttons
            rejectButton.style.display = 'inline-block';
        } else {
            approveButton.style.display = 'none'; // Hide buttons
            rejectButton.style.display = 'none';
        }
    }

    // Call toggleActionButtons on page load and when checkboxes change
    document.addEventListener('DOMContentLoaded', toggleActionButtons);
    document.querySelectorAll('input[name="transactions[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', toggleActionButtons);
    });
</script>


@push('styles')
    <style>
        /* Header styles */
        .card-header.bg-success {
            background-color: #28a745 !important;
        }

        .card-header.bg-warning {
            background-color: #ffc107 !important;
        }

        .card-header.bg-danger {
            background-color: #dc3545 !important;
        }

        /* Badge styles */
        .badge.bg-success {
            background-color: #1bcfb4 !important;
        }

        .badge.bg-warning {
            background-color: #ffc107 !important;
        }

        .badge.bg-danger {
            background-color: #dc3545 !important;
        }

        /* Card styles for Pending Assets */
        .card.border-warning {
            border-color: #ffc107 !important;
        }

        /* Background for Pending Assets card */
        .card-body {
            background-color: rgba(255, 193, 7, 0.5);
            /* Light yellow background with transparency */
        }

        .modal-body .table {
            margin-bottom: 0;
            border-collapse: collapse;
        }

        .modal-body .table td {
            border: none;
            padding: 8px;
        }

        .modal-body .table thead {
            display: none;
        }

        .modal-body .table tr {
            border-bottom: 1px solid #dee2e6;
        }

        .no-border-table td,
        .no-border-table th {
            border: none !important;
        }
    </style>
@endpush
<style>
    /* Card and badge styling */
.ticket-container {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    background-color: #fff;
    transition: transform 0.2s;
}

.ticket-container:hover {
    transform: translateY(-4px);
}

.status-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px 10px;
    border-radius: 15px;
    color: #fff;
    font-size: 0.8rem;
    font-weight: bold;
    text-transform: uppercase;
}

.status-badge.open {
    background-color: #28a745;
}

.status-badge.in-progress {
    background-color: #007bff;
}

.status-badge.closed {
    background-color: #6c757d;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .ticket-container {
        margin: 0px 0;
    }
    .row {
        margin-left: 0;
        margin-right: 0;
    }
    .card-body {
        padding: 10px;
    }
    .card-title {
        font-size: 1rem;
    }
    .status-badge {
        font-size: 0.7rem;
    }
}

</style>