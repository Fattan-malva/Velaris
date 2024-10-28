@extends('layouts.app')

@section('content')

<div class="container">
    <br>
    <h1 class="mt-4 text-center">Ticket List</h1>
    <br>
    <div class="card">
        <div class="card-header">
            <h2>Request</h2>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                @foreach ($saleses as $sales)
                    <div class="col-md-4 mb-3">
                        <div class="card border-secondary rounded-3 shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title mb-0" style="font-weight: bold;">{{ $sales->nama }}</h5>
                                    <small
                                        class="text-muted">{{ \Carbon\Carbon::parse($sales->created_at)->format('d M Y') }}</small>
                                </div>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $sales->departement }}</h6>
                                <hr class="my-2" style="border-top: 2px dashed #343a40;">

                                <div class="text-end">
                                    <span class="badge 
                                                            @if($sales->status == 'Approved') 
                                                                bg-success 
                                                            @elseif($sales->status == 'Pending') 
                                                                bg-secondary 
                                                            @endif">
                                        {{ $sales->status }}
                                    </span>
                                </div>

                                <div class="mt-3">
                                    <table style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td style="width: 40%; font-weight: bold;">Location</td>
                                                <td style="width: 60%;">{{ $sales->lokasi }}</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%; font-weight: bold;">Asset Type</td>
                                                <td style="width: 60%;">{{ $sales->nama_asset }}</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%; font-weight: bold;">Detail</td>
                                                <td style="width: 60%;">{{ $sales->description }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <div class="text-end w-100">
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#handleModal">
                                            Handle it
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Structure -->
                            <div class="modal fade" id="handleModal" tabindex="-1" aria-labelledby="handleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="handleModalLabel">Handle Action</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <h5 class="card-title mb-0" style="font-weight: bold;">
                                                        {{ $sales->nama }}
                                                    </h5>
                                                    <small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($sales->created_at)->format('d M Y') }}</small>
                                                </div>
                                                <h6 class="card-subtitle mb-2 text-muted">{{ $sales->departement }}</h6>
                                                <hr class="my-2" style="border-top: 2px dashed #343a40;">

                                                <div class="text-end">
                                                    <span class="badge 
                                                            @if($sales->status == 'Approved') 
                                                                bg-success 
                                                            @elseif($sales->status == 'Pending') 
                                                                bg-secondary 
                                                            @endif">
                                                        {{ $sales->status }}
                                                    </span>
                                                </div>

                                                <div class="mt-3">
                                                    <table style="width: 100%;">
                                                        <tbody>
                                                            <tr>
                                                                <td style="width: 40%; font-weight: bold;">Location</td>
                                                                <td style="width: 60%;">{{ $sales->lokasi }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 40%; font-weight: bold;">Asset Type</td>
                                                                <td style="width: 60%;">{{ $sales->nama_asset }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 40%; font-weight: bold;">Detail</td>
                                                                <td style="width: 60%;">{{ $sales->description }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <form action="{{ route('sales.update', $sales->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')

                                                <!-- Fields for handling the sales -->
                                                <input type="hidden" name="status" value="Approved">
                                                <input type="hidden" name="approval_status" value="Pending">
                                                <input type="hidden" name="aksi" value="Handover">

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label for="asset_tagging">Asset Tagging</label>
                                                            <select class="form-control" id="asset_tagging"
                                                                name="asset_tagging" required>
                                                                @foreach($inventories as $inventory)
                                                                    <option value="{{ $inventory->id }}">
                                                                        {{ $inventory->tagging }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label for="nama">Name</label>
                                                            <select class="form-control" id="nama" name="nama" required>
                                                                @foreach($customers as $customer)
                                                                    <option value="{{ $customer->id }}">{{ $customer->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label for="lokasi">Location Details</label>
                                                            <input type="text" id="lokasi" class="form-control"
                                                                name="lokasi"
                                                                placeholder="Location details will be set here" required>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <select class="form-control" id="status" name="status" hidden>
                                                                <option value="Operation">Operation</option>
                                                                <option value="Inventory">Inventory</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <select class="form-control" id="o365" name="o365" required
                                                                hidden>
                                                                <option value="Partner License">Partner License</option>
                                                                <option value="Business">Business</option>
                                                                <option value="Business Standard">Business Standard</option>
                                                                <option value="No License">No License</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <select class="form-control" id="kondisi" name="kondisi" hidden>
                                                                <option value="New">New</option>
                                                                <option value="Good">Good</option>
                                                                <option value="Exception">Exception</option>
                                                                <option value="Bad">Bad</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <input type="file" class="form-control" id="documentation"
                                                                name="documentation" accept="image/*" capture="camera"
                                                                hidden>
                                                            @if ($errors->has('documentation'))
                                                                <span
                                                                    class="text-danger">{{ $errors->first('documentation') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label for="location">Search Location</label>
                                                            <div class="input-group">
                                                                <input type="text" id="location-input" class="form-control"
                                                                    placeholder="Search for a location" required>
                                                                <div class="input-group-append">
                                                                    <button type="button" class="btn btn-primary"
                                                                        id="enter-location">Search</button>
                                                                </div>
                                                            </div>
                                                            <div id="map"
                                                                style="height: 300px; width: 100%; margin-top:10px;"></div>
                                                            <input type="hidden" id="latitude" name="latitude">
                                                            <input type="hidden" id="longitude" name="longitude">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="text-center mt-3">
                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                    <a href="{{ route('sales.index') }}"
                                                        class="btn btn-secondary ml-3">Cancel</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var map = L.map('map').setView([-6.2088, 106.8456], 13); // Default coordinates for Jakarta

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var geocoder = L.Control.Geocoder.nominatim();

        function onGeocodeResult(results) {
            if (results.length > 0) {
                var result = results[0];
                var latlng = result.center;

                // Update the input fields
                document.getElementById('latitude').value = latlng.lat;
                document.getElementById('longitude').value = latlng.lng;
                document.getElementById('location-input').value = result.name;

                // Also set the lokasi field with the location name
                document.getElementById('lokasi').value = result.name;

                // Add a marker on the map
                L.marker(latlng).addTo(map)
                    .bindPopup(result.name)
                    .openPopup();

                // Center the map on the result
                map.setView(latlng, 13);
            } else {
                console.error('No results found');
            }
        }

        var marker = L.marker([-6.2088, 106.8456], { draggable: true }).addTo(map);
        marker.on('moveend', function (e) {
            var latlng = e.target.getLatLng();
            document.getElementById('latitude').value = latlng.lat;
            document.getElementById('longitude').value = latlng.lng;
        });

        document.getElementById('enter-location').addEventListener('click', function () {
            var location = document.getElementById('location-input').value;
            geocoder.geocode(location, function (results) {
                onGeocodeResult(results);
            });
        });

        document.getElementById('location-input').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('enter-location').click();
            }
        });
    });
</script>