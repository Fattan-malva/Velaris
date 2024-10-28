@extends('layouts.app')
@section('title', 'Assets Mutation')

@section('content')
<h1 class="mt-4 text-center fw-bold display-5">Assets Mutation</h1>
<br>
<br>
<br>
<div class="container">
    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Name Holder</th>
                            <th scope="col">Asset Tagging</th>
                            <th scope="col">Asset Type</th>
                            <th scope="col">Merk</th>
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
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('assets.pindahtangan', ['id' => $asset->id]) }}"
                                            class="btn btn-sm btn-warning" title="Mutasi">
                                            Mutation
                                        </a>
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
                                                    <strong>Name Customer:</strong> {{ $asset->customer_name }}<br>
                                                    <strong>Position:</strong> {{ $asset->customer_mapping }}<br>
                                                    <strong>Location:</strong> {{ $asset->lokasi }}<br>
                                                    <strong>Jenis Aset:</strong> {{ $asset->jenis_aset }}<br>
                                                    <strong>Merk:</strong> {{ $asset->merk_name }}<br>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Serial Number:</strong> {{ $asset->serial_number }}<br>
                                                    <strong>O365:</strong> {{ $asset->o365 }}<br>
                                                    <strong>Status:</strong> {{ $asset->status }}<br>
                                                    <strong>Kondisi:</strong> {{ $asset->kondisi }}<br>
                                                    <strong>Serah Terima:</strong>
                                                    {{ \Carbon\Carbon::parse($asset->created_at)->format('d-m-Y') }}<br>
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
                                <td colspan="9" class="text-center"
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
<br>
<br>
@endsection