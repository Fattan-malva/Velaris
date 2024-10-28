@extends('layouts.app')

@section('content')
<br>
<br>
<br>
<br>
<h1 class="mt-4 text-center">My Assets</h1>
<br>
<br>
<br>
<div class="container">
    <!-- Section for Pending Assets -->
    <div class="card mb-3">
        <div class="card-header">
            <h2>Waiting for Approval</h2>
        </div>
        <div class="card-body">
            @if ($pendingAssets->isEmpty())
                <p class="text-center">No assets waiting for approval.</p>
            @else
                <div class="row">
                    @foreach ($pendingAssets as $asset)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $asset->customer_name }}</h5>
                                    <p class="card-text">
                                        <strong>Asset Tag :</strong> {{ $asset->tagging }}<br>
                                        <strong>Jenis Aset :</strong> {{ $asset->jenis_aset }}<br>
                                        <strong>Merk :</strong> {{ $asset->merk_name }}
                                    </p>
                                    <a href="{{ route('assets.serahterima', ['id' => $asset->id]) }}"
                                        class="btn btn-success">Approve</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Approved Assets</h2>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-responsive">
                <table id="assetTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Name</th>
                            <th scope="col">Asset Tagging</th>
                            <th scope="col">Jenis Aset</th>
                            <th scope="col">Merk</th>
                            <th scope="col">Location</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assets as $index => $asset)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $asset->customer_name }}</td>
                                <td>{{ $asset->tagging }}</td>
                                <td>{{ $asset->jenis_aset }}</td>
                                <td>{{ $asset->merk_name }}</td>
                                <td>{{ $asset->lokasi }}</td>
                                <td>{{ $asset->approval_status }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $asset->id }}" title="View Details">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </button>
                                        <a href="{{ route('assets.pindahtangan', ['id' => $asset->id]) }}"
                                            class="btn btn-sm btn-warning" title="Mutasi">
                                            Mutasi
                                        </a>
                                        <form action="{{ route('assets-user.delete', ['id' => $asset->id]) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Return"
                                                onclick="return confirm('Are you sure you want to return this asset?')">
                                                Return
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal -->
                            <div class="modal fade" id="detailModal{{ $asset->id }}" tabindex="-1"
                                aria-labelledby="detailModalLabel{{ $asset->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detailModalLabel{{ $asset->id }}">Asset Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>Asset Tagging:</strong> {{ $asset->tagging }}<br>
                                                    <strong>Jenis Aset:</strong> {{ $asset->jenis_aset }}<br>
                                                    <strong>Merk:</strong> {{ $asset->merk_name }}<br>
                                                    <strong>Location:</strong> {{ $asset->lokasi }}<br>
                                                    <strong>Approval Status:</strong> {{ $asset->approval_status }}<br>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Serial Number:</strong> {{ $asset->serial_number }}<br>
                                                    <strong>O365:</strong> {{ $asset->o365 }}<br>
                                                    <strong>Status:</strong> {{ $asset->status }}<br>
                                                    <strong>Kondisi:</strong> {{ $asset->kondisi }}<br>
                                                    <strong>Documentation:</strong>
                                                    @if($asset->documentation)
                                                        <a href="{{ asset('storage/' . $asset->documentation) }}"
                                                            target="_blank">View Document</a>
                                                    @else
                                                        No Document
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center"
                                    style="padding: 50px; padding-bottom: 100px; padding-top: 100px; font-size: 1.2em;">No
                                    assets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- ======= User Assets Section ======= -->
<section id="user-assets" class="user-assets">
    <div class="container">
        <div class="row">
            <!-- User Profile Card -->
            <div class="col-md-4 mb-4">
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h2>User Information</h2>
                    </div>
                    <div class="card-body" style="margin-bottom: 100px;">
                        <p><strong>Name:</strong> {{ ucfirst(strtolower(session('user_name'))) }}</p>
                        <p><strong>Role:</strong> {{ session('user_role') }}</p>
                        <p><strong>User ID:</strong> {{ session('user_id') }}</p>
                    </div>
                </div>
            </div>

            <!-- Assets Section -->
            <div class="col-md-8">
                <!-- Section for Pending Assets -->
                <div class="card mb-4 bg-light">
                    <div class="card-header bg-primary text-white">
                        <h2>Waiting for Approval</h2>
                    </div>
                    <div class="card-body">
                        @if ($pendingAssets->isEmpty())
                            <p class="text-center">No assets waiting for approval.</p>
                        @else
                            <div class="row">
                                @foreach ($pendingAssets as $asset)
                                    <div class="col-md-6 mb-3">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $asset->customer_name }}</h5>
                                                <p class="card-text">
                                                    <strong>Asset Tag:</strong> {{ $asset->tagging }}<br>
                                                    <strong>Jenis Aset:</strong> {{ $asset->jenis_aset }}<br>
                                                    <strong>Merk:</strong> {{ $asset->merk_name }}
                                                </p>
                                                <a href="{{ route('assets.serahterima', ['id' => $asset->id]) }}"
                                                    class="btn btn-light">Approve</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Section for Approved Assets -->
                <div class="card bg-light">
                    <div class="card-header bg-success text-white">
                        <h2>Approved Assets</h2>
                    </div>
                    <div class="card-body">
                        @if ($assets->isEmpty())
                            <p class="text-center">No approved assets found.</p>
                        @else
                            <div class="row">
                                @foreach ($assets as $asset)
                                    <div class="col-md-6 mb-3">
                                        <div class="card bg-success text-white">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $asset->customer_name }}</h5>
                                                <p class="card-text">
                                                    <strong>Asset Tag:</strong> {{ $asset->tagging }}<br>
                                                    <strong>Jenis Aset:</strong> {{ $asset->jenis_aset }}<br>
                                                    <strong>Merk:</strong> {{ $asset->merk_name }}<br>
                                                    <strong>Location:</strong> {{ $asset->lokasi }}<br>
                                                    <strong>Status:</strong> {{ $asset->approval_status }}
                                                </p>
                                                <div class="action-buttons">
                                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $asset->id }}" title="View Details">
                                                        <i class="bi bi-file-earmark-text"></i>
                                                    </button>
                                                    <a href="{{ route('assets.pindahtangan', ['id' => $asset->id]) }}"
                                                        class="btn btn-sm btn-warning" title="Mutasi">
                                                        Mutasi
                                                    </a>
                                                    <form action="{{ route('assets-user.delete', ['id' => $asset->id]) }}"
                                                        method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Return"
                                                            onclick="return confirm('Are you sure you want to return this asset?')">
                                                            Return
                                                        </button>
                                                    </form>
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
</section><!-- End User Assets Section -->

<!-- Add the following styles to ensure proper layout -->
<style>
    .user-assets .card {
        margin-bottom: 1rem;
    }

    .user-assets .card-header {
        font-size: 1.25rem;
    }

    .user-assets .card-body {
        font-size: 1rem;
    }

    .user-assets .btn-light {
        color: #000;
        background-color: #f8f9fa;
    }

    .user-assets .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .user-assets .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .user-assets .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .user-assets .card.bg-warning {
        background-color: #ffc107;
        color: #000;
    }

    .user-assets .card.bg-success {
        background-color: #28a745;
        color: #fff;
    }
</style>
<br>
<br>
@endsection