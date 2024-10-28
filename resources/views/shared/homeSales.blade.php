@extends('layouts.app')

@section('content')


<main id="main">
    <!-- ======= User Assets Section ======= -->
    <section id="user-assets" class="user-assets">
        <br>
        <br>
        <h1 class="text-center animate__animated animate__fadeInDown display-4">
            Hello <b class="fw-bold">{{ ucfirst(strtolower(session('user_name'))) }}</b>,
            This is your asset
        </h1>
        <br>
        <br>
        <div class="container">
            <div class="row">
                <!-- Modal Form -->
                <div class="modal fade" id="salesModal" tabindex="-1" aria-labelledby="salesModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title text-center w-100 font-weight-bold" id="salesModalLabel">Request
                                    Asset</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="salesForm" method="POST" action="{{ route('sales.store') }}">
                                    @csrf
                                    <input type="hidden" id="nama" name="nama" value="{{ session('user_name') }}">
                                    <input type="hidden" id="departement" name="departement"
                                        value="{{ session('user_mapping') }}">

                                    <div class="mb-3">
                                        <label for="lokasi" class="form-label">Location</label>
                                        <input type="text" class="form-control" id="lokasi" name="lokasi" required>
                                    </div>
                                    <input type="hidden" id="status" name="status" value="Pending">
                                    <div class="mb-3">
                                        <label for="nama_asset" class="form-label">Asset Name</label>
                                        <input type="text" class="form-control" id="nama_asset" name="nama_asset"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="5"
                                            style="width: 100%;" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Success Modal -->
                <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-flex align-items-center" id="successModalLabel">
                                    <i class="bi bi-check-circle text-success me-2" style="font-size: 1.5rem;"></i>
                                    Success
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Your request has been successfully submitted!<br>
                                    Please wait for asset handover...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Error Modal -->
                <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="errorModalLabel">Error</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>There was an error submitting your request. Please try again later.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Section for Pending Assets -->
                <div class="col-md-4 mb-4">
                    <div class="card border-secondary">
                        <div class="card-header bg-secondary text-white">
                            <h2>Waiting for Approval</h2>
                        </div>
                        <div class="card-body">
                            @if ($pendingAssets->isEmpty())
                                <p class="text-center">No assets waiting for approval. <br>
                                    Need a new asset? Submit a request here.</p>
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#salesModal">
                                        Request Asset
                                    </button>
                                </div>
                            @else
                                <div class="row">
                                    @foreach ($pendingAssets as $asset)
                                        <div class="col-md-12 mb-3">
                                            <div class="card" style="background-color: rgba(
                                                        {{ $asset->aksi == 'Handover' ? '40, 167, 69, 0.2' : '' }}
                                                        {{ $asset->aksi == 'Mutasi' ? '255, 193, 7, 0.2' : '' }}
                                                        {{ $asset->aksi == 'Return' ? '220, 53, 69, 0.2' : '' }});
                                                        border: 3px solid black;">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-4">
                                                        <img src="{{ asset('assets/img/pending.png') }}"
                                                            alt="Pending Asset Icon" class="me-3"
                                                            style="width: 80px; height: 80px;">
                                                        <p class="card-text">
                                                            <span
                                                                class="badge position-absolute top-0 end-0 m-2
                                                                            {{ $asset->aksi == 'Handover' ? 'bg-success text-dark' : '' }}
                                                                            {{ $asset->aksi == 'Mutasi' ? 'bg-warning text-dark' : '' }}
                                                                            {{ $asset->aksi == 'Return' ? 'bg-danger text-dark' : '' }}">
                                                                {{ $asset->aksi }}
                                                            </span>
                                                            <strong>Asset Tag:</strong> {{ $asset->tagging }}<br>
                                                            <strong>Jenis Aset:</strong> {{ $asset->jenis_aset }}<br>
                                                            <strong>Merk:</strong> {{ $asset->merk_name }}
                                                        </p>
                                                    </div>

                                                    <div class="d-flex justify-content-between mt-4"
                                                        style="margin-bottom:-15px;">
                                                        <a href="{{ route('sales.salesserahterima', ['id' => $asset->id]) }}"
                                                            class="btn btn-success" style="margin-bottom:15px;">Approve</a>
                                                        <form action="{{ route('assets.reject', ['id' => $asset->id]) }}"
                                                            method="POST" onsubmit="return confirmReject();">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger">Reject</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-center mt-4">Need add asset? Submit a request here.</p>
                                            <div class="text-center">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#salesModal">
                                                    Request Asset
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
                <!-- Assets Section -->
                <div class="col-md-8">
                    <!-- Section for Approved Assets -->
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h2>Approved Assets</h2>
                        </div>
                        <div class="card-body">
                            @if ($assets->isEmpty())
                                <p class="text-center">No approved assets found.</p>
                            @else
                                                    <div class="row">
                                                        @foreach ($assets as $index => $asset)
                                                                                    <div class="col-md-5 mb-3">
                                                                                        <div class="card"
                                                                                            style="background-color: rgba(130, 130, 130, 0.2); border: 3px solid black;">
                                                                                            <div class="card-body">
                                                                                                <div class="d-flex align-items-center mb-4">
                                                                                                    @php
                                                                                                        // Determine the image file based on the jenis_aset
                                                                                                        $iconMap = [
                                                                                                            'PC' => 'pc.png',
                                                                                                            'Tablet' => 'tablet.png',
                                                                                                            'Laptop' => 'laptop.png',
                                                                                                            // Add more mappings as needed
                                                                                                        ];
                                                                                                        $iconFile = isset($iconMap[$asset->jenis_aset]) ? $iconMap[$asset->jenis_aset] : 'default.png'; // Fallback to default icon
                                                                                                    @endphp
                                                                                                    <img src="{{ asset('assets/img/' . $iconFile) }}" alt="Asset Icon"
                                                                                                        class="me-3" style="width: 60px; height: 60px;">
                                                                                                    <p class="card-text">
                                                                                                        <strong>Asset Tag:</strong> {{ $asset->tagging }}<br>
                                                                                                        <strong>Jenis Aset:</strong> {{ $asset->jenis_aset }}<br>
                                                                                                        <strong>Merk:</strong> {{ $asset->merk_name }}<br>
                                                                                                    </p>
                                                                                                </div>

                                                                                                <div class="action-buttons" style="margin-left: 220px;">
                                                                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
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
                                                                                                            <h5 class="modal-title" id="detailModalLabel{{ $asset->id }}">
                                                                                                                Asset Details</h5>
                                                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                                                                aria-label="Close"></button>
                                                                                                        </div>
                                                                                                        <div class="modal-body">
                                                                                                            <div class="row">
                                                                                                                <div class="col-md-6">
                                                                                                                    <table class="table"
                                                                                                                        style="border-collapse: collapse; border: none;">
                                                                                                                        <tbody>
                                                                                                                            <tr>
                                                                                                                                <td style="border: none;"><strong>Asset
                                                                                                                                        Tagging:</strong></td>
                                                                                                                                <td style="border: none;">
                                                                                                                                    {{ $asset->tagging }}
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <tr>
                                                                                                                                <td style="border: none;"><strong>Jenis
                                                                                                                                        Aset:</strong></td>
                                                                                                                                <td style="border: none;">
                                                                                                                                    {{ $asset->jenis_aset }}
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <tr>
                                                                                                                                <td style="border: none;">
                                                                                                                                    <strong>Merk:</strong>
                                                                                                                                </td>
                                                                                                                                <td style="border: none;">
                                                                                                                                    {{ $asset->merk_name }}
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <tr>
                                                                                                                                <td style="border: none;">
                                                                                                                                    <strong>Location:</strong>
                                                                                                                                </td>
                                                                                                                                <td style="border: none;">
                                                                                                                                    {{ $asset->lokasi }}
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <tr>
                                                                                                                                <td style="border: none;"><strong>Approval
                                                                                                                                        Status:</strong></td>
                                                                                                                                <td style="border: none;">
                                                                                                                                    {{ $asset->approval_status }}
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </div>
                                                                                                                <div class="col-md-6">
                                                                                                                    <table class="table"
                                                                                                                        style="border-collapse: collapse; border: none;">
                                                                                                                        <tbody>
                                                                                                                            <tr>
                                                                                                                                <td style="border: none;"><strong>Serial
                                                                                                                                        Number:</strong></td>
                                                                                                                                <td style="border: none;">
                                                                                                                                    {{ $asset->serial_number }}
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <tr>
                                                                                                                                <td style="border: none;">
                                                                                                                                    <strong>O365:</strong>
                                                                                                                                </td>
                                                                                                                                <td style="border: none;">{{ $asset->o365 }}
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <tr>
                                                                                                                                <td style="border: none;">
                                                                                                                                    <strong>Action:</strong>
                                                                                                                                </td>
                                                                                                                                <td style="border: none;">{{ $asset->aksi }}
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <tr>
                                                                                                                                <td style="border: none;">
                                                                                                                                    <strong>Kondisi:</strong>
                                                                                                                                </td>
                                                                                                                                <td style="border: none;">
                                                                                                                                    {{ $asset->kondisi }}
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <tr>
                                                                                                                                <td style="border: none;">
                                                                                                                                    <strong>Documentation:</strong>
                                                                                                                                </td>
                                                                                                                                <td style="border: none;">
                                                                                                                                    @if($asset->documentation)
                                                                                                                                        <a href="{{ asset('storage/' . $asset->documentation) }}"
                                                                                                                                            target="_blank"
                                                                                                                                            class="text-decoration-underline">View
                                                                                                                                            Documentation</a>
                                                                                                                                    @else
                                                                                                                                        No documentation available.
                                                                                                                                    @endif
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="modal-footer">
                                                                                                            @if($asset->aksi == 'Handover')
                                                                                                                <a href="{{ route('prints.handover', ['asset_tagging' => $asset->tagging]) }}"
                                                                                                                    class="btn btn-primary"><i class="bi bi-printer"></i>
                                                                                                                    Print</a>
                                                                                                            @elseif($asset->aksi == 'Mutasi')
                                                                                                                <a href="{{ route('prints.mutation', ['asset_tagging' => $asset->tagging]) }}"
                                                                                                                    class="btn btn-primary"><i class="bi bi-printer"></i>
                                                                                                                    Print</a>
                                                                                                            @elseif($asset->aksi == 'Return')
                                                                                                                <a href="{{ route('prints.return', ['asset_tagging' => $asset->tagging]) }}"
                                                                                                                    class="btn btn-primary"><i class="bi bi-printer"></i>
                                                                                                                    Print</a>
                                                                                                            @else
                                                                                                                <!-- Optionally, you can include a fallback or default route -->
                                                                                                                <a href="#" class="btn btn-secondary" disabled>Print Not
                                                                                                                    Available</a>
                                                                                                            @endif
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
    </section>
</main>

@section('scripts')
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

@endsection

<script>
    function confirmReject() {
        return confirm("Are you sure you want to reject this asset? This action cannot be undone.");
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('salesForm');
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent form from submitting the default way

            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json()) // Parse JSON response
                .then(data => {
                    if (data.success) {
                        $('#salesModal').modal('hide'); // Hide the form modal
                        $('#successModal').modal('show'); // Show the success modal
                    } else {
                        $('#salesModal').modal('hide'); // Hide the form modal
                        $('#errorModal').modal('show'); // Show the error modal
                    }
                })
                .catch(error => {
                    $('#salesModal').modal('hide'); // Hide the form modal
                    $('#errorModal').modal('show'); // Show the error modal
                });
        });
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
            background-color: #28a745 !important;
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