@extends('layouts.app')

@section('content')
<br>
<br>
<h1 class="mt-4 text-center fw-bold display-5">Approve Asset</h1>
<br>
<div class="container form-container">
    <div class="card">
        <div class="card-body">
            @if($asset->aksi !== 'Return')
                <form action="{{ route('assets.updateserahterimaSales', $asset->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="asset_tagging">Asset Tagging</label>
                        <input type="text" class="form-control" id="asset_tagging" name="asset_tagging_display"
                            value="{{ $inventories->where('id', $asset->asset_tagging)->first()->tagging ?? 'N/A' }}"
                            readonly>
                        <input type="hidden" name="asset_tagging" value="{{ $asset->asset_tagging }}">
                    </div>

                    <input type="hidden" name="approval_status" value="Approved">

                    <div class="form-group">
                        <label for="nama">Name</label>
                        <input type="text" class="form-control" id="nama" name="nama_display"
                            value="{{ $customers->where('id', $asset->nama)->first()->name ?? 'N/A' }}" readonly>
                        <input type="hidden" name="nama" value="{{ $asset->nama }}">
                    </div>

                    <div class="form-group">
                        <label for="lokasi">Location</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi"
                            value="{{ old('lokasi', $asset->lokasi) }}" readonly>
                    </div>

                    <input type="hidden" name="status" value="{{ $asset->status }}">
                    <input type="hidden" name="o365" value="{{ $asset->o365 }}">
                    <input type="hidden" name="kondisi" value="{{ $asset->kondisi }}">

                    <div class="form-group">
                        <label for="documentation">Documentation</label>
                        <input type="file" class="form-control" id="documentation" name="documentation" accept="image/*"
                            required>
                        @if($asset->documentation)
                            <p>Current file: <a href="{{ asset('storage/' . $asset->documentation) }}" target="_blank">View</a>
                            </p>
                        @endif
                    </div>

                    <div class="text-center">
                        @if($asset->aksi !== 'Return')
                            <button type="submit" class="btn btn-success">Approve</button>
                        @endif
                        <a href="{{ route('assets.index') }}" class="btn btn-secondary ml-3">Cancel</a>
                    </div>
                </form>
            @endif

            @if($asset->aksi === 'Return')
                <form action="{{ route('assets-user.delete', $asset->id) }}" method="POST" class="mt-3">
                    @csrf
                    @method('DELETE')

                    <div class="form-group">
                        <label for="asset_tagging">Asset Tagging</label>
                        <input type="text" class="form-control" id="asset_tagging" name="asset_tagging"
                            value="{{ $inventories->where('id', $asset->asset_tagging)->first()->tagging ?? 'N/A' }}"
                            readonly>
                        <input type="hidden" name="asset_tagging" value="{{ $asset->asset_tagging }}">
                    </div>

                    <div class="form-group">
                        <label for="nama">Name</label>
                        <input type="text" class="form-control" id="nama" name="nama"
                            value="{{ $customers->where('id', $asset->nama)->first()->name ?? 'N/A' }}" readonly>
                        <input type="hidden" name="nama" value="{{ $asset->nama }}">
                    </div>

                    <div class="form-group">
                        <label for="lokasi">Location</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi"
                            value="{{ old('lokasi', $asset->lokasi) }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="documentation">Documentation</label>
                        <input type="file" class="form-control" id="documentation" name="documentation" accept="image/*"
                            required>
                        @if($asset->documentation)
                            <p class="mt-2"
                                style="display: inline-block; background-color: rgba(128, 128, 128, 0.3); padding: 4px 8px; border-radius: 4px;">
                                <span class="bold-text">Current file:</span>
                                <a href="{{ asset('storage/' . $asset->documentation) }}" target="_blank"
                                    class="text-decoration-underline">View</a>
                            </p>
                        @endif
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-danger">Return Asset</button>
                        <a href="{{ route('shared.homeUser') }}" class="btn btn-secondary ml-3">Cancel</a>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
<br>
<br>
@endsection

<style>
    .form-container {
        max-width: 500px;
        /* Adjusted width for a more square appearance */
        margin: 0 auto;
        padding: 2rem;
        /* Added padding for better spacing inside the container */
        border-radius: 8px;
        /* Rounded corners for a softer look */
    }

    .form-section {
        margin-bottom: 1.5rem;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        font-weight: bold;
        margin-bottom: 0.5rem;
        /* Added margin to separate label from input */
    }

    .form-group input,
    .form-group select {
        width: 100%;
        border-radius: 4px;
        /* Rounded corners for input fields */
        border: 1px solid #ced4da;
        /* Light border color */
        padding: 0.5rem;
        /* Padding inside input fields */
    }

    .form-group input[type="submit"] {
        background-color: #007bff;
        /* Primary button color */
        color: white;
        border: none;
        cursor: pointer;
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
    }

    .form-group input[type="submit"]:hover {
        background-color: #0056b3;
        /* Darker shade on hover */
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 1rem;
            /* Adjust padding for smaller screens */
            max-width: 100%;
            /* Full width on smaller screens */
        }
    }
</style>