@extends('layouts.app')
@section('title', 'Approve Assets')

@section('content')
<br>
<br>
<div class="container form-container">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="mt-2 mb-4 text-center fw-bold">Approve Assets</h2>
            <hr style="width: 80%; margin: 0 auto;" class="mb-4"/>
            <!-- Approve all form -->
            <form action="{{ route('assets.updateserahterima') }}" method="POST" enctype="multipart/form-data" style="margin-bottom: -45px">
                @csrf
                @method('PUT')

                <div class="row">
                    @foreach($assets as $asset)
                        @if($asset->aksi !== 'Return')
                            <div> <!-- Keep the column for 4 items -->
                                <div class="asset-wrapper"> <!-- New wrapper for each asset -->
                                    <div class="form-group">
                                        <label for="asset_tagging_{{ $asset->id }}">Asset Tagging</label>
                                        @php
                                            $taggingValue = $inventories->where('id', $asset->asset_tagging)->first();
                                        @endphp
                                        <input type="text" class="form-control" id="asset_tagging_{{ $asset->id }}"
                                            name="asset_tagging_display[]"
                                            value="{{ is_string($taggingValue->tagging ?? null) ? htmlspecialchars($taggingValue->tagging) : 'N/A' }}"
                                            readonly>
                                        <input type="hidden" name="asset_tagging[]" value="{{ $asset->asset_tagging }}">
                                    </div>
                                    <input type="hidden" name="assets[]" value="{{ $asset->id }}">
                                    <input type="hidden" name="approval_status[]" value="Approved">

                                    <div class="form-group">
                                        <label for="nama_{{ $asset->id }}">Name</label>
                                        @php
                                            $customerName = $customers->where('id', $asset->nama)->first();
                                        @endphp
                                        <input type="text" class="form-control" id="nama_{{ $asset->id }}" name="nama_display[]"
                                            value="{{ is_string($customerName->name ?? null) ? htmlspecialchars($customerName->name) : 'N/A' }}"
                                            readonly>
                                        <input type="hidden" name="nama[]" value="{{ $asset->nama }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="lokasi_{{ $asset->id }}">Location</label>
                                        @php
                                            $lokasiValue = old('lokasi')[$asset->id] ?? $asset->lokasi;
                                        @endphp
                                        <input type="text" class="form-control" id="lokasi_{{ $asset->id }}" name="lokasi[]"
                                            value="{{ is_string($lokasiValue) ? htmlspecialchars($lokasiValue) : 'N/A' }}"
                                            readonly>
                                    </div>

                                    <input type="hidden" name="status[]" value="{{ $asset->status }}">
                                    <input type="hidden" name="o365[]" value="{{ $asset->o365 }}">
                                    <input type="hidden" name="kondisi[]" value="{{ $asset->kondisi }}">
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                @if($assets->where('aksi', '!=', 'Return')->count() > 0) <!-- Only show this if there are assets not returned -->
                    <div class="form-group mb-4" style="padding: 0px 15px;">
                        <label for="documentation">Documentation</label>
                        <input type="file" class="form-control" id="documentation" name="documentation" accept="image/*" required>
                    </div>

                    <div class="btn-container">
                        <button type="submit" class="btn-approve">Approve</button>
                        <a href="{{ route('shared.homeUser') }}" style="padding: 8px 25px; border: none; border-radius: 5px; background-color: #fe7c96; color: #fff; font-weight: 600; margin-right: 10px; text-align: center;">Cancel</a>
                    </div>
                @endif
            </form>

            <!-- Return all form -->
            <form action="{{ route('assets-user.returnmultiple') }}" method="POST" enctype="multipart/form-data" class="mt-5">
                @csrf
                @method('DELETE')

                <div class="row">
                    @foreach($assets as $asset)
                        @if($asset->aksi === 'Return')
                            <div> <!-- Keep the column for 4 items -->
                                <div class="asset-wrapper"> <!-- New wrapper for each asset -->
                                    <div class="form-group">
                                        <label for="asset_tagging">Asset Tagging</label>
                                        <input type="text" class="form-control" id="asset_tagging"
                                            value="{{ htmlspecialchars($inventories->where('id', $asset->asset_tagging)->first()->tagging ?? 'N/A', ENT_QUOTES) }}"
                                            readonly>
                                        <input type="hidden" name="asset_tagging[]" value="{{ $asset->asset_tagging }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="nama">Name</label>
                                        <input type="text" class="form-control" id="nama"
                                            value="{{ htmlspecialchars($customers->where('id', $asset->nama)->first()->name ?? 'N/A', ENT_QUOTES) }}"
                                            readonly>
                                        <input type="hidden" name="nama[]" value="{{ $asset->nama }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="keterangan">Reason</label>
                                        <input type="text" class="form-control" id="keterangan"
                                            value="{{ old('keterangan', $asset->keterangan) }}" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="lokasi">Location</label>
                                        <input type="text" class="form-control" id="lokasi" value="{{ old('lokasi', $asset->lokasi) }}"
                                            readonly>
                                    </div>

                                    <div class="form-group" style="margin-bottom: -10px;">
                                        <label for="documentation_return">Documentation</label>
                                        <input type="file" class="form-control" id="documentation_return" name="documentation[]" accept="image/*" required>
                                        @if($asset->documentation)
                                            <p class="mt-2"
                                                style="display: inline-block; background-color: rgba(128, 128, 128, 0.3); padding: 4px 8px; border-radius: 4px;">
                                                <span class="bold-text">Current file:</span>
                                                <a href="{{ asset('storage/' . $asset->documentation) }}" target="_blank"
                                                    class="text-decoration-underline">View</a>
                                            </p>
                                        @endif
                                    </div>

                                    <input type="hidden" name="assets[]" value="{{ $asset->id }}">
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                @if($assets->where('aksi', 'Return')->count() > 0) <!-- Only show this if there are assets to return -->
                    <div class="btn-container">
                        <button type="submit" class="btn-approve">Return</button>
                        <a href="{{ route('shared.homeUser') }}" style="padding: 11px 25px; border: none; border-radius: 5px; background-color: #fe7c96; color: #fff; font-weight: 600; margin-right: 10px; text-align: center;">Cancel</a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
<br>
<br>
@endsection

<style>
.card {
    margin: 0 auto; /* Center the card */
    max-width: 1090px; /* Max width for larger screens */
    width: 100%; /* Full width on smaller screens */
    height: 10px;
}

.card-body {
    display: flex; /* Use flexbox to align contents */
    flex-direction: column; /* Arrange children in a column */
    align-items: stretch; /* Stretch items to fill space */
    padding: 2rem; /* Add padding */
 
}

.form-container {
    max-width: 1000px; /* Limit max width */
    margin: 0 auto; /* Center the form container */
    padding: 2rem;
    border-radius: 8px;
}



.asset-wrapper {
    border-radius: 8px; /* Rounded corners */
    padding: 1rem; /* Padding inside the wrapper */
    width: 100%; /* Full width for responsiveness */
    margin-bottom: -1rem; /* Space between asset wrappers */
}

.form-group {
    width: 100%; /* Ensure full width */
    margin-bottom: 1rem;
}

.form-group label {
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.form-group input,
.form-group select {
    width: 100% !important;
    border-radius: 8px !important;
    border: 1px solid #000 !important;
    padding: .375rem .75rem;
}


.form-group input[type="submit"] {
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    padding: 0.75rem 1.5rem;
    border-radius: 4px;
}

.form-group input[type="submit"]:hover {
    background-color: #0056b3;
}

.btn-container {
    display: flex;
    justify-content: flex-end; /* Menggeser tombol ke kanan */
    margin-top: 1rem; /* Jarak di atas tombol */
}

.btn-approve {
    padding: 8px 18px;
    border: none;
    border-radius: 5px;
    background-color: #1bcfb4;
    color: #fff;
    font-weight: 600;
    margin-right: 10px;
}

@media (max-width: 768px) {
    .card-body {
        padding: 1rem; /* Adjust padding for mobile */
        height: auto;
    }

    .asset-wrapper {
        width: 100%; /* Full width for mobile */
    }

    .btn-container {
        flex-direction: column;
        justify-content: flex-end; /* Menggeser tombol ke kanan */
        padding: 0px 3px 0px 14px;
    }

    .btn-approve {
        padding: 8px 18px;
        border: none;
        border-radius: 5px;
        background-color: #1bcfb4;
        color: #fff;
        font-weight: 600;
        margin-right: 10px;
        margin-bottom: 10px;
    }

}


</style>