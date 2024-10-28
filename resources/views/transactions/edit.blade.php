@extends('layouts.app')
@section('title', 'Edit Asset')

@section('content')
<br>
<br>
<h1 class="mt-4 text-center">Edit Asset</h1>
<br>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Edit Asset</h2>
            <!-- Approval Status Label -->
            <div class="text-end mb-3">
                <span
                    class="badges {{ $asset->approval_status == 'Approved' ? 'bg-success' : ($asset->approval_status == 'Pending' ? 'bg-primary' : 'bg-danger') }}"
                    style="padding:5px; border-radius: 5px;">
                    {{ $asset->approval_status }}
                </span>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('assets.update', $asset->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')



                <!-- Asset Tagging -->
                <div class="form-group mb-3">
                    <label for="asset_tagging">Asset Tagging</label>
                    <select class="form-control" id="asset_tagging" name="asset_tagging" required>
                        @foreach($inventories as $inventory)
                            <option value="{{ $inventory->id }}" {{ $inventory->id == $asset->asset_tagging ? 'selected' : '' }}>
                                {{ $inventory->tagging }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Hidden Field for Approval Status -->
                <input type="hidden" name="approval_status" value="Approved">

                <!-- Name -->
                <div class="form-group mb-3">
                    <label for="nama">Name</label>
                    <select class="form-control" id="nama" name="nama" required>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ $customer->id == $asset->nama ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Location -->
                <div class="form-group mb-3">
                    <label for="lokasi">Location</label>
                    <input type="text" class="form-control" id="lokasi" name="lokasi"
                        value="{{ old('lokasi', $asset->lokasi) }}" required>
                </div>

                <!-- Status (Hidden) -->
                <input type="hidden" name="status" value="{{ $asset->status }}">

                <!-- O365 -->
                <div class="form-group mb-3">
                    <label for="o365">O365</label>
                    <select class="form-control" id="o365" name="o365" required>
                        <option value="Partner License" {{ $asset->o365 == 'Partner License' ? 'selected' : '' }}>Partner
                            License</option>
                        <option value="Business" {{ $asset->o365 == 'Business' ? 'selected' : '' }}>Business</option>
                        <option value="Business Standard" {{ $asset->o365 == 'Business Standard' ? 'selected' : '' }}>
                            Business Standard</option>
                        <option value="No License" {{ $asset->o365 == 'No License' ? 'selected' : '' }}>No License
                        </option>
                    </select>
                </div>

                <!-- Condition (Hidden) -->
                <input type="hidden" name="kondisi" value="{{ $asset->kondisi }}">

                <!-- Documentation -->
                <div class="form-group mb-3">
                    <label for="documentation">Documentation</label>
                    <input type="file" class="form-control" id="documentation" name="documentation" accept="image/*">
                    @if($asset->documentation)
                        <p class="mt-2">Current file: <a href="{{ asset('storage/' . $asset->documentation) }}"
                                target="_blank">View</a></p>
                    @endif
                </div>

                <!-- Buttons -->
                <div class="form-group mb-3 d-flex justify-content-start">
                    <button type="submit" class="btn btn-success mr-2">Update</button>

                    <!-- Return to Inventory Form -->
                    <form action="{{ route('assets.delete', ['id' => $asset->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-warning" title="Return to Inventory"
                            onclick="return confirm('Are you sure you want to return this asset to inventory?')">
                            Return to Inventory
                        </button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</div>
<br>
<br>
@endsection