@extends('layouts.app')
@section('title', 'Maintenance')

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
                <h3 class="maintenance-title">
                    Maintenance&nbsp;&nbsp;
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-screwdriver-wrench previous-icon"></i>
                    </span>
                </h3>
            </div>
            <div class="header-container-mobile">
                <h3 class="maintenance-title">
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-screwdriver-wrench previous-icon"></i>
                    </span>
                    &nbsp;&nbsp;Maintenance
                </h3>
            </div>
            <br>
        </div>
    </div>

    @if($assetss->isEmpty())
        <div class="alert alert-warning" role="alert">
            No assets available for maintenance. Please add assets before proceeding.
        </div>
    @else
        <div class="card">
            <div class="card-body" style="padding: 30px;">
                <form method="POST" action="{{ route('assets.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                        <div class="text-end">
                            <a href="{{ route('assets.historymaintenance') }}" class="btn btn-sm history-btn"
                                style="background-color: #9A9A9A;">
                                <i class="fa-solid fa-clock"></i> History
                            </a>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="code" class="form-label">Select Assets to Maintenance</label>
                            <select class="form-control select-dark" id="code" name="ids[]" multiple="multiple" required>
                                @foreach($assetss as $assets)
                                                        @php
                                                            $tanggalMasuk = $assets->entry_date;
                                                            $tanggalMaintenance = $assets->last_maintenance ?? $tanggalMasuk;

                                                            // Convert scheduling maintenance to months
                                                            switch ($assets->scheduling_maintenance) {
                                                                case '3 Weeks':
                                                                    $bulanJadwal = 0.75; // 3 weeks in months
                                                                    break;
                                                                case '1 Month':
                                                                    $bulanJadwal = 1;
                                                                    break;
                                                                case '1 Year':
                                                                    $bulanJadwal = 12;
                                                                    break;
                                                                case '5 Years':
                                                                    $bulanJadwal = 60;
                                                                    break;
                                                                default:
                                                                    $bulanJadwal = 0; // Default to 0 if no schedule
                                                            }

                                                            // Calculate months since the last maintenance
                                                            $bulanSejakAcuan = now()->diffInMonths($tanggalMaintenance);
                                                        @endphp

                                                        @if ($bulanSejakAcuan >= $bulanJadwal)
                                                            <option value="{{ $assets->id }}">{{ $assets->code }}</option>
                                                        @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="last_maintenance" class="form-label">Maintenance Date</label>
                            <input type="date" class="form-control" id="last_maintenance" name="last_maintenance"
                                value="{{ old('last_maintenance') }}" required>
                        </div>
                    </div>

                    <!-- New Row for Maintenance Note and Condition Side-by-Side -->
                    <div class="row mb-4">
                        <div class="col-md-6 form-group">
                            <label for="note_maintenance" class="form-label">Maintenance Note</label>
                            <input type="text" class="form-control" id="note_maintenance" name="note_maintenance"
                                value="{{ old('note_maintenance') }}" placeholder="Enter maintenance note" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="condition" class="form-label">Condition</label>
                            <select id="condition" name="condition" class="form-select" required>
                                <option value="Good" {{ old('condition') == 'Good' ? 'selected' : '' }}>Good</option>
                                <option value="Exception" {{ old('condition') == 'Exception' ? 'selected' : '' }}>Exception
                                </option>
                                <option value="Bad" {{ old('condition') == 'Bad' ? 'selected' : '' }}>Bad</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 mb-2 text-end">
                        <button type="submit" class="btn" style="background-color:#1bcfb4;">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    @endif
</div>

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

    .maintenance-title {
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

    .form-label {
        font-weight: 550;
    }

    .form-control {
        border: 1px solid #000;
    }

    .btn-save {
        background-color: transparent;
        border: 1.3px solid #1bcfb4;
        color: #1bcfb4;
        transition: background-color 0.3s, color 0.3s;
        font-weight: 500;
        padding: 5px 25px;
    }

    .btn {
        margin: 0 0.5rem;
        font-size: 16px;
        font-weight: bold;
        color: white;
    }

    .history-btn {
        background-color: #9A9A9A;
        border-radius: 20PX;
    }
</style>
@endsection