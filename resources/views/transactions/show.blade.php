@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Asset Details</h1>
    <div class="card">
        <div class="card-header">
            Asset Information
        </div>
        <div class="card-body">
            <p><strong>Asset Tagging:</strong> {{ $asset->tagging }}</p>
            <p><strong>Jenis Aset:</strong> {{ $asset->jenis_aset }}</p>
            <p><strong>Merk:</strong> {{ $asset->merk_name }}</p>
            <p><strong>Type:</strong> {{ $asset->type }}</p>
            <p><strong>Serial Number:</strong> {{ $asset->serial_number }}</p>
            <p><strong>Name:</strong> {{ $asset->customer_name }}</p>
            <p><strong>Mapping:</strong> {{ $asset->customer_mapping }}</p>
            <p><strong>O365:</strong> {{ $asset->o365 }}</p>
            <p><strong>Lokasi:</strong> {{ $asset->lokasi }}</p>
            <p><strong>Status:</strong> {{ $asset->status }}</p>
            <p><strong>Kondisi:</strong> {{ $asset->kondisi }}</p>
            @if($asset->documentation)
                <p><strong>Documentation:</strong> <a href="{{ asset('storage/' . $asset->documentation) }}"
                        target="_blank">View Document</a></p>
            @endif
        </div>
    </div>
</div>
@endsection