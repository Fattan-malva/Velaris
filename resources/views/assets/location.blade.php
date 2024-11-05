@extends('layouts.app')
@section('title', 'Assets Location')
@section('content')

<br>
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
                <h3 class="assetLocation-title">
                    Asset Location&nbsp;&nbsp;
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-map-marker-alt" style="font-size: 16px;"></i>
                    </span>
                </h3>
            </div>
            <div class="header-container-mobile">
                <h3 class="assetLocation-title">
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-map-marker-alt" style="font-size: 16px;"></i>
                    </span>
                    &nbsp;&nbsp;Asset Location
                </h3>
            </div>
            <br>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-responsive">
                <table id="mappingTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Location</th>
                            <th scope="col">Type</th>
                            <th scope="col">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $item)
                            <tr>
                                <td>{{ $item->location }}</td>
                                <td>{{ $item->category_asset }}</td>
                                <td>{{ $item->jumlah_aset }}</td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<br>
<br>
<style>
    .card {
        box-shadow: rgba(0, 0, 0, 0.15) 2.4px 2.4px 3.2px;
    }

    /* Header Styles */
    .header-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        margin-top: 30px;
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

    .assetLocation-title {
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