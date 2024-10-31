@extends('layouts.app')
@section('title', 'Approve Assets')

@section('content')
<br>
<br>
<div class="container form-container">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="mt-2 mb-4 text-center fw-bold">Approve Assets</h2>
            <hr style="width: 80%; margin: 0 auto;" class="mb-4" />
            <!-- Approve all form -->
            <form action="{{ route('transactions.updateserahterima') }}" method="POST" enctype="multipart/form-data"
                style="margin-bottom: -45px">
                @csrf
                @method('PUT')

                <div class="row">
                    @foreach($transactions as $asset)
                                    @if($asset->aksi !== 'Return')

                                                    <div class="asset-wrapper col-md-4 mb-2">
                                                        <div class="card" style="background-color: rgb(218, 181, 255);">
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center mb-3">
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
                                                                    <img src="{{ asset('assets/img/' . $iconFile) }}" alt="Asset Icon" class="me-3"
                                                                        style="width: 40px; height: 40px;"> <!-- Reduced icon size -->

                                                                    <div class="card-text" style="font-size: 0.9rem;"> <!-- Reduced font size -->
                                                                        <strong>Asset Tag:</strong>
                                                                        @php
                                                                            $taggingValue = $inventories->where('id', $asset->asset_tagging)->first();
                                                                        @endphp
                                                                        {{ $taggingValue->tagging ?? 'N/A' }}<br>

                                                                        <strong>Name:</strong>
                                                                        @php
                                                                            $customerName = $customers->where('id', $asset->nama)->first();
                                                                        @endphp
                                                                        {{ $customerName->name ?? 'N/A' }}<br>

                                                                        <strong>Location:</strong> {{ $asset->lokasi }}
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="transactions[]" value="{{ $asset->id }}">
                                                                <input type="hidden" name="nama[]" value="{{ $asset->nama }}">
                                                                <input type="hidden" name="status[]" value="{{ $asset->status }}">
                                                                <input type="hidden" name="o365[]" value="{{ $asset->o365 }}">
                                                                <input type="hidden" name="kondisi[]" value="{{ $asset->kondisi }}">
                                                            </div>
                                                        </div>
                                                    </div>


                                    @endif
                    @endforeach
                </div>

                @if($transactions->where('aksi', '!=', 'Return')->count() > 0)
                    <div class="form-group mb-4" style="padding: 0px 15px;">
                        <label for="documentation">Documentation</label>
                        <input type="file" class="form-control" id="documentation" name="documentation" accept="image/*">
                    </div>

                    <div class="btn-container">
                        <button type="submit" class="btn-approve">Approve</button>
                        <a href="{{ route('shared.homeUser') }}"
                            style="padding: 8px 25px; border: none; border-radius: 5px; background-color: #fe7c96; color: #fff; font-weight: 600; margin-right: 10px; text-align: center;">Cancel</a>
                    </div>
                @endif
            </form>


            <form action="{{ route('transactions-user.returnmultiple') }}" method="POST" enctype="multipart/form-data"
                class="mt-5">
                @csrf
                @method('DELETE')

                <div class="row">
                    @foreach($transactions as $asset)
                                    @if($asset->aksi === 'Return')
                                                    <div class="asset-wrapper col-md-4 mb-2"> <!-- Each card will occupy 4 columns on medium screens -->
                                                        <div class="card" style="background-color: rgb(218, 181, 255);">
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center mb-3">
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
                                                                    <img src="{{ asset('assets/img/' . $iconFile) }}" alt="Asset Icon" class="me-3"
                                                                        style="width: 40px; height: 40px;"> <!-- Reduced icon size -->

                                                                    <div class="card-text" style="font-size: 0.9rem;"> <!-- Reduced font size -->
                                                                        <strong>Asset Tag:</strong>
                                                                        <span>
                                                                            {{ htmlspecialchars($inventories->where('id', $asset->asset_tagging)->first()->tagging ?? 'N/A', ENT_QUOTES) }}
                                                                        </span><br>

                                                                        <strong>Name:</strong>
                                                                        <span>
                                                                            {{ htmlspecialchars($customers->where('id', $asset->nama)->first()->name ?? 'N/A', ENT_QUOTES) }}
                                                                        </span><br>

                                                                        <strong>Location:</strong>
                                                                        <span>{{ old('lokasi', $asset->lokasi) }}</span><br>

                                                                        <strong>Reason:</strong>
                                                                        <span>{{ old('keterangan', $asset->keterangan) }}</span><br>

                                                                        <strong>Note:</strong>
                                                                        <span>{{ old('note', $asset->note) }}</span>
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="asset_tagging[]" value="{{ $asset->asset_tagging }}">
                                                                <input type="hidden" name="nama[]" value="{{ $asset->nama }}">
                                                                <input type="hidden" name="transactions[]" value="{{ $asset->id }}">
                                                            </div>
                                                        </div>
                                                    </div> <!-- End of asset card -->
                                    @endif
                    @endforeach
                </div>


                @if($transactions->where('aksi', 'Return')->count() > 0)
                    <!-- Documentation upload field for a single file for all assets -->
                    <div class="form-group mt-4">
                        <label for="documentation_return">Documentation</label>
                        <input type="file" class="form-control" id="documentation_return" name="documentation"
                            accept="image/*" required>
                    </div>

                    <div class="btn-container">
                        <button type="submit" class="btn-approve">Return</button>
                        <a href="{{ route('shared.homeUser') }}"
                            style="padding: 11px 25px; border: none; border-radius: 5px; background-color: #fe7c96; color: #fff; font-weight: 600; margin-right: 10px; text-align: center;">Cancel</a>
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
        margin: 0 auto;
        /* Center the card */
        max-width: 1090px;
        /* Max width for larger screens */
        width: 100%;
        /* Full width on smaller screens */
        height: 10px;
    }

    .card-body {
        display: flex;
        /* Use flexbox to align contents */
        flex-direction: column;
        /* Arrange children in a column */
        align-items: stretch;
        /* Stretch items to fill space */
        padding: 2rem;
        /* Add padding */

    }

    .form-container {
        max-width: 1000px;
        /* Limit max width */
        margin: 0 auto;
        /* Center the form container */
        padding: 2rem;
        border-radius: 8px;
    }



    .asset-wrapper {
        border-radius: 8px;
        /* Rounded corners */
        padding: 1rem;
        /* Padding inside the wrapper */
        width: 100%;
        /* Full width for responsiveness */
        margin-bottom: -1rem;
        /* Space between asset wrappers */
    }

    .form-group {
        width: 100%;
        /* Ensure full width */
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
        justify-content: flex-end;
        /* Menggeser tombol ke kanan */
        margin-top: 1rem;
        /* Jarak di atas tombol */
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
            padding: 1rem;
            /* Adjust padding for mobile */
            height: auto;
        }

        .asset-wrapper {
            width: 100%;
            /* Full width for mobile */
        }

        .btn-container {
            flex-direction: column;
            justify-content: flex-end;
            /* Menggeser tombol ke kanan */
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