@extends('layouts.app')
@section('title', 'Tracking Asset')

@section('content')
<div class="container">
    <div>
        <div class="container">
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>
                // Menampilkan pesan sukses setelah redirect dari controller
                @if(session('success'))
                    Swal.fire({
                        title: 'Success!',
                        text: '{{ session('success') }}', // Pesan sukses dari session
                        icon: 'success', // Ikon sukses
                        confirmButtonText: 'OK' // Tombol OK
                    });
                @endif

                // Menampilkan pesan error validasi
                @if($errors->any())
                    Swal.fire({
                        title: 'Error!',
                        text: '{!! implode(', ', $errors->all()) !!}', // Menggabungkan semua pesan error
                        icon: 'error', // Ikon error
                        confirmButtonText: 'OK' // Tombol OK
                    });
                @endif
            </script>

            <div class="header-container">
                <div class="back-wrapper">
                    <i class='bx bxs-chevron-left back-icon' id="back-icon"></i>
                    <div class="back-text">
                        <span class="title">Back</span>
                        <span class="small-text">to previous page</span>
                    </div>
                </div>
                <h3 class="track-title">
                    Track Asset&nbsp;&nbsp;
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-map-marker-alt previous-icon"></i>
                    </span>
                </h3>
            </div>
            <div class="header-container-mobile mt-4">
                <h3 class="track-title">
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-map-marker-alt previous-icon"></i>
                    </span>
                    &nbsp;&nbsp;Track Asset
                </h3>
            </div>
            <br>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h2>Tracking Asset: {{ $asset->jenis_aset }} ({{ $asset->serial_number }})</h2>
            <div id="map" class="map-container"></div>

            <!-- Link to Open Location in Google Maps -->
            <div class="text-center mt-3">
                <a href="https://www.google.com/maps/search/?api=1&query={{ $asset->latitude }},{{ $asset->longitude }}"
                    target="_blank" class="btn-track">
                    Open in Google Maps
                </a>
            </div>
        </div>

        <!-- Fullscreen Map Modal -->
        <div id="fullscreen-map" class="fullscreen-map">
            <div id="map-fullscreen"></div>
        </div>

    </div>
</div>


<!-- Display Leaflet Map -->


<!-- Leaflet and OpenStreetMap CSS/JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
   document.addEventListener('DOMContentLoaded', function () {
    // Ensure the asset latitude and longitude are set
    var latitude = {{ $asset->latitude }};
    var longitude = {{ $asset->longitude }};

    // Initialize the map for the regular view
    var map = L.map('map').setView([latitude, longitude], 15);

    // Add tile layer from OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add a marker to the map at the asset's location
    var marker = L.marker([latitude, longitude]).addTo(map)
        .bindPopup('{{ $asset->jenis_aset }} - {{ $asset->serial_number }}')
        .openPopup();

    // Fullscreen map setup
    var fullscreenMapContainer = document.getElementById('fullscreen-map');
    var mapFullscreen = L.map('map-fullscreen').setView([latitude, longitude], 15);

    // Add the tile layer for fullscreen map
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mapFullscreen);

    // Add a marker to the fullscreen map
    var fullscreenMarker = L.marker([latitude, longitude]).addTo(mapFullscreen)
        .bindPopup('{{ $asset->jenis_aset }} - {{ $asset->serial_number }}')
        .openPopup();

    // Function to refresh map size (important for fullscreen)
    function resizeMap() {
        map.invalidateSize();
        mapFullscreen.invalidateSize();
    }

    // Show fullscreen map when clicking the regular map
    document.getElementById('map').addEventListener('click', function () {
        fullscreenMapContainer.style.display = 'block';
        document.body.classList.add('no-scroll'); // Disable scrolling
        mapFullscreen.setView([latitude, longitude], 15); // Make sure the fullscreen map is centered on the asset location

        // Wait for the map container to be displayed before resizing
        setTimeout(resizeMap, 100);
    });

    // Close fullscreen map on ESC key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            fullscreenMapContainer.style.display = 'none';
            document.body.classList.remove('no-scroll'); // Re-enable scrolling
        }
    });
});


</script>

@endsection

<style>
  /* Fullscreen Map Container */
.fullscreen-map {
    display: none; /* Default hidden */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background-color: rgba(0, 0, 0, 0.8); /* Semi-transparent background */
}

/* Regular Map */
#map {
    width: 100%;
    height: 400px; /* Adjust based on your layout */
}

/* Fullscreen Map */
#map-fullscreen {
    width: 100%;
    height: 100%;
}

/* Hide scrollbars */
body.no-scroll {
    overflow: hidden;
}



    .btn-track {
        background-color: #F89720;
        color: #fff;
        font-weight: 500;
        padding: 10px;
        border-radius: 10px;
    }

    .card {
        box-shadow: rgba(0, 0, 0, 0.15) 2.4px 2.4px 3.2px;
    }

    .map-container {
        height: 500px;
        /* Set a fixed height for the map */
        width: 100%;
        /* Full width */
        margin-top: 20px;
        /* Space between header and map */
        justify-content: center;
        z-index: 0;
        position: relative;
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
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) -10%, #FCA918);
        height: 36px;
        width: 36px;
        border-radius: 4px;
        color: #fff;
        padding-top: 10px;
        padding-left: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.25);
        margin-right: auto;
        transition: background 0.3s ease;
        /* Transition untuk efek hover */
    }

    .back-icon:hover {
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) -13%, #FBCA07);
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

    .track-title {
        font-weight: bold;
        font-size: 1.125rem;
    }

    .icon-wrapper {
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) -10%, #FCA918);
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

    @media (max-width: 576px) {
        .header-container {
            flex-direction: column;
            /* Stack items vertically on mobile */
            align-items: flex-start;
            /* Align items to the start */
            padding: 10px 20px;
            /* Adjust padding */
        }

        .back-text .title {
            font-size: 1rem;
            /* Adjust font size for mobile */
        }

        .back-text .small-text {
            font-size: 0.75rem;
            /* Smaller font size for mobile */
        }
    }
</style>