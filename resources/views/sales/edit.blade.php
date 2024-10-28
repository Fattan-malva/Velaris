@extends('layouts.app')

@section('content')

<div class="container">
    <br>
    <h1 class="mt-4 text-center">Edit Sale</h1>
    <br>
    <div class="card">
        <div class="card-header">
            <h2>Edit Sale</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('sales.update', $sales->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nama" class="form-label">Name</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="{{ $sales->nama }}" required>
                </div>
                <div class="mb-3">
                    <label for="departement" class="form-label">Department</label>
                    <input type="text" class="form-control" id="departement" name="departement" value="{{ $sales->departement }}" required>
                </div>
                <div class="mb-3">
                    <label for="lokasi" class="form-label">Location</label>
                    <input type="text" class="form-control" id="lokasi" name="lokasi" value="{{ $sales->lokasi }}" required>
                </div>
                <div class="mb-3">
                    <label for="nama_asset" class="form-label">Asset Name</label>
                    <input type="text" class="form-control" id="nama_asset" name="nama_asset" value="{{ $sales->nama_asset }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

@endsection
