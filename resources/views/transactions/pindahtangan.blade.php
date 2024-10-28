@extends('layouts.app')

@section('content')
<h1 class="mt-4 text-center fw-bold display-5">Asset Mutation</h1>
<br>
<div class="container">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('assets.pindahUpdate', $asset->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Asset Tagging Field -->
                <div class="form-group">
                    <label for="asset_tagging">Asset Tagging</label>
                    <select class="form-control" id="asset_tagging" name="asset_tagging" disabled>
                        @foreach($inventories as $inventory)
                            <option value="{{ $inventory->id }}" {{ $inventory->id == $asset->asset_tagging ? 'selected' : '' }}>
                                {{ $inventory->tagging }}
                            </option>
                        @endforeach
                    </select>
                </div>

                     <!-- Documentation Field -->
                <div class="form-group">
                    <input type="file" class="form-control" id="documentation" name="documentation" accept="image/*" hidden>
                    @if($asset->documentation)
                        <p class="mt-4" style="display: inline-block; background-color: rgba(128, 128, 128, 0.3); padding: 4px 10px; border-radius: 4px; font-weight: bold;">
                            Current file:<a href="{{ asset('storage/' . $asset->documentation) }}" target="_blank" class="text-decoration-underline">View</a>
                        </p>
                    @endif
                </div>

                
                <input type="hidden" name="approval_status" value="Pending">
                <input type="hidden" name="aksi" value="Mutasi">

                <!-- New Holder Name Field -->
                <div class="form-group">
                    <label for="nama">New Holder Name</label>
                    <select class="form-control" id="nama" name="nama" required>
                        @foreach($customers->filter(fn($customer) => $customer->id != $asset->nama) as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                  
                <!-- Location Input and Map -->
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location-input" class="form-control" placeholder="Search for a location" required>
                    <button type="button" class="btn btn-primary mt-2 mb-2" id="enter-location">Enter Location</button>
                    <div id="map" style="height: 300px; width: 100%;"></div>
                    <input type="hidden" id="latitude" name="latitude">
                    <input type="hidden" id="longitude" name="longitude">
                </div>
                
                <!-- Lokasi Field -->
                <div class="form-group">
                    <label for="lokasi">Lokasi</label>
                    <input type="text" class="form-control" id="lokasi" name="lokasi"
                        value="{{ old('lokasi', $asset->lokasi) }}" required>
                </div>

         

                <!-- Submit and Cancel Buttons -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-warning">Mutation</button>
                    <a href="{{ route('assets.indexmutasi') }}" class="btn btn-secondary ml-3">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<br>
<br>

<!-- Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize the map
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

    L.Control.geocoder({
        defaultMarkGeocode: false
    })
    .on('markgeocode', function (e) {
        onGeocodeResult([e.geocode]);
    })
    .addTo(map);

    // Add draggable marker
    var marker = L.marker([-6.2088, 106.8456], { draggable: true }).addTo(map);
    marker.on('moveend', function (e) {
        var latlng = e.target.getLatLng();
        document.getElementById('latitude').value = latlng.lat;
        document.getElementById('longitude').value = latlng.lng;
    });

    // Event listener for "Enter Location" button
    document.getElementById('enter-location').addEventListener('click', function () {
        var location = document.getElementById('location-input').value;
        geocoder.geocode(location, function (results) {
            onGeocodeResult(results);
        });
    });

    // Event listener for Enter key
    document.getElementById('location-input').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('enter-location').click();
        }
    });
});
</script>

@endsection
