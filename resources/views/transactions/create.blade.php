@extends('layouts.app')
@section('title', 'Handover')
@section('content')
<div class="container">
    <div>
        <div class="container">
            <div class="header-container">
                <div class="back-wrapper">
                    <i class='bx bxs-chevron-left back-icon' id="back-icon"></i>
                    <div class="back-text">
                        <span class="title">Back</span>
                        <span class="small-text">to previous page</span>
                    </div>
                </div>
                <h3 class="handover-title">
                    Handover&nbsp;&nbsp;
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-hand-holding-dollar previous-icon"></i>
                    </span>
                </h3>
            </div>
            <div class="header-container-mobile mt-4">
                <h3 class="handover-title">
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-hand-holding-dollar previous-icon"></i>
                    </span>
                    &nbsp;&nbsp;Handover
                </h3>
            </div>
            <br>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @if ($assetTaggingAvailable && $namesAvailable)
                <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data" id="Handover">
                    @csrf

                    <input type="hidden" name="approval_status" value="Pending">
                    <input type="hidden" name="aksi" value="Handover">

                    <!-- Row untuk membagi dua kolom -->
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asset_tagging">Asset Code</label>
                                <select class="form-control" id="asset_tagging" name="asset_tagging[]" multiple="multiple"
                                    required>
                                    @foreach($inventories as $inventory)
                                        <option style="color:black;" value="{{ $inventory->id }}">{{ $inventory->tagging }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="nama">Name Holder</label>
                                <select class="form-control" id="nama" name="nama" required>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nama">Detail Location</label>
                                <br>
                                <small>
                                    *Add here if you want to add a more specific location.
                                </small>
                                <input type="text" id="lokasi" class="form-control" name="lokasi"
                                    placeholder="Location details will be set here" required>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location">Location</label>
                                <div class="input-group">
                                    <input type="text" id="location-input" class="form-control"
                                        placeholder="Search for a location" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" id="enter-location"><i
                                                class="bi bi-search"></i> Search</button>
                                    </div>
                                </div>
                                <div id="map" style="height: 300px; width: 100%; margin-top:10px;"></div>
                                <input type="hidden" id="latitude" name="latitude">
                                <input type="hidden" id="longitude" name="longitude">
                            </div>

                        </div>
                    </div>

                    <!-- Form lainnya di bawah row -->
                    <div class="form-group">
                        <select class="form-control" id="status" name="status" hidden>
                            <option value="Operation">Operation</option>
                            <option value="Inventory">Inventory</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <select class="form-control" id="o365" name="o365" required hidden>
                            <option value="Partner License">Partner License</option>
                            <option value="Business">Business</option>
                            <option value="Business Standard">Business Standard</option>
                            <option value="No License">No License</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <select class="form-control" id="kondisi" name="kondisi" hidden>
                            <option value="New">New</option>
                            <option value="Good">Good</option>
                            <option value="Exception">Exception</option>
                            <option value="Bad">Bad</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="file" class="form-control" id="documentation" name="documentation" accept="image/*"
                            capture="camera" hidden>
                        @if ($errors->has('documentation'))
                            <span class="text-danger">{{ $errors->first('documentation') }}</span>
                        @endif
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn" style="background-color:#1bcfb4;">Submit</button>
                        <a href="{{ route('transactions.indexreturn') }}" class="btn ml-3"
                            style="background-color:#FE7C96;">Cancel</a>
                    </div>
                </form>
            @elseif (!$assetTaggingAvailable)
                <p class="text-center">All assets have been used</p>
            @elseif (!$namesAvailable)
                <p class="text-center">There are no more users, register users anymore</p>
            @endif
        </div>
    </div>
</div>
<br>
<br>

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

                // Update the input fields for latitude, longitude, and location
                document.getElementById('latitude').value = latlng.lat;
                document.getElementById('longitude').value = latlng.lng;
                document.getElementById('location-input').value = result.name;

                // Store the location value in a variable but don't add it to the detail input directly
                locationName = result.name;

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

        var locationName = ''; // Store the location name from the search
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

        // Handle user input for the "Detail Location" field
        document.getElementById('lokasi').addEventListener('blur', function (e) {
            const lokasiInput = e.target.value.trim();

            // If user has entered something in the "Detail Location" field
            if (lokasiInput) {
                // Format the value as "[lokasiInput], [locationName]"
                document.getElementById('lokasi').value = lokasiInput + (locationName ? ', ' + locationName : '');
            } else {
                // If no input, just use the location name from the search result
                document.getElementById('lokasi').value = locationName;
            }
        });

    });

    // Event listener untuk form submit
    document.getElementById('Handover').addEventListener('submit', function (event) {
        event.preventDefault(); // Mencegah form submit default

        // Tampilkan loading alert menggunakan SweetAlert
        Swal.fire({
            title: 'Loading...',
            text: 'Please wait while we handover to the user.',
            allowOutsideClick: false, // Mencegah klik di luar
            didOpen: () => {
                Swal.showLoading(); // Menampilkan loading spinner
            }
        });

        // Simulasi pengiriman form (ganti dengan logika pengiriman form yang sesungguhnya)
        setTimeout(() => {
            this.submit(); // Kirim form setelah 1,5 detik (simulasi)
        }, 1500);
    });

    // Menampilkan pesan sukses setelah redirect dari controller
    @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}', // Pesan sukses dari session
            icon: 'success', // Ikon sukses
            confirmButtonText: 'OK' // Tombol OK
        });
    @endif

</script>

<style>
    .card {
        box-shadow: rgba(0, 0, 0, 0.15) 2.4px 2.4px 3.2px;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .list-group-item {
        cursor: pointer;
    }

    .input-group {
        display: flex;
        align-items: center;
    }

    .input-group-append {
        margin-left: -1px;
    }

    #map {
        height: 400px;
        width: 100%;
        margin-top: 10px;
    }

    .btn-primary {
        margin-top: 0;
        /* Remove extra margin if any */
    }

    .text-center {
        text-align: center;
    }

    .btn {
        margin: 0 0.5rem;
        font-size: 16px;
        font-weight: bold;
        color: white;
    }

    .select2-container {
        width: 100% !important;
        /* Ensure Select2 takes full width */
    }

    /* Header Styles */
    .header-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        margin-top: 54px;
    }

    .back-icon {
        cursor: pointer;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0) -30%, #B66DFF);
        height: 36px;
        width: 36px;
        border-radius: 4px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.25);
        margin-right: auto;
        transition: background 0.3s ease;
        /* Transition untuk efek hover */
    }

    .back-icon:hover {
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) -13%, #B100FF);
        /* Warna gradien saat hover dengan putih sedikit di kiri */
    }

    .back-wrapper {
        display: flex;
        align-items: center;
        /* Center vertically */
        margin-right: auto;
        /* Push the dashboard title to the right */
    }

    .back-text {
        display: flex;
        flex-direction: column;
        /* Stack text vertically */
        margin-left: 10px;
        /* Space between icon and text */
    }

    .back-text .title {
        font-weight: 600;
        font-size: 17px;
    }

    .back-text .small-text {
        font-size: 0.8rem;
        /* Smaller font size for the second line */
        color: #aaa;
        /* Optional: a lighter color for the smaller text */
        margin-top: -3px;
    }

    .handover-title {
        font-weight: bold;
        font-size: 1.125rem;
    }

    .icon-wrapper {
        background: linear-gradient(90deg, rgba(255, 255, 255, 0) -30%, #B66DFF);
        height: 36px;
        width: 36px;
        border-radius: 4px;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.25);
    }

    .previous-icon {
        font-size: 16px;
    }
</style>
@endsection