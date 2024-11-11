@extends('layouts.noheader')
@section('title', 'Detail Asset')

@section('content')

<br>
<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        display: flex;
        justify-content: center;
        padding: 20px;
        align-items: flex-start;
        gap: 20px;
        max-width: 1200px;
        margin: auto;
    }

    .panel-left,
    .panel-right {
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .panel-left {
        width: 35%;
    }

    .panel-right {
        width: 60%;
    }

    .tabs {
        display: flex;
        border-bottom: 1px solid #ddd;
        margin-bottom: 20px;
    }

    .tab {
        flex-grow: 1;
        padding: 12px 0;
        text-align: center;
        cursor: pointer;
        background-color: #f1f1f1;
        font-weight: 600;
        transition: background-color 0.3s;
    }

    .tab:hover {
        background-color: #ddd;
    }

    .tab.active {
        border-bottom: 3px solid #F8C50F;
        background-color: white;
        font-weight: bold;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .asset-image {
        text-align: center;
        margin-bottom: 20px;
    }

    .asset-details {
        text-align: center;
    }

    .form-row {
        display: flex;
        justify-content: space-between;
        width: 100%;
    }

    .form-group {
        width: 48%;
    }

    label {
        font-weight: bold;
    }

    input {
        padding: 5px;
        font-size: 14px;
        width: 100%;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    @media (max-width: 768px) {
        .container {
            flex-direction: column;
            align-items: stretch;
        }

        .panel-left,
        .panel-right {
            width: 100%;
            margin: 0;
        }

        .tabs {
            flex-direction: row;
            overflow-x: auto;
            white-space: nowrap;
            border-bottom: none;
        }

        .tab {
            flex-grow: 1;
            width: auto;
            padding: 5px;
            border-right: 1px solid #ddd;
        }

        .form-row {
            flex-direction: column;
        }

        .form-group {
            margin-right: 0;
            width: 100%;
            /* Increased margin for better spacing */
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .form-group.category,
        .form-group.merk,
        .form-group.sn {
            display: none;
        }

    }
</style>
<!-- resources/views/asset-detail.blade.php -->

<div class="container">
    <!-- Left Panel for Asset Details -->
    <div class="panel-left">
        <div class="asset-image">
            @php
                $iconMap = [
                    'PC' => 'pc.png',
                    'Tablet' => 'tablet.png',
                    'Laptop' => 'laptop.png',
                ];
                $iconFile = $iconMap[$asset->category] ?? 'default.png';
            @endphp
            <img src="{{ asset('assets/img/' . $iconFile) }}" alt="Asset Icon" style="width: 100px; height: 100px;">
        </div>
        <div class="asset-details">
            <h3>
                {{$asset->code}} {{$asset->merk_name}} {{$asset->spesification}}
            </h3>
            <p>
                {{$asset->serial_number}}
            </p>
        </div>
    </div>

    <!-- Right Panel for Tab Content -->
    <div class="panel-right">
        <div class="tabs">
            <div class="tab active" onclick="showTab('general')">General</div>
            <div class="tab" onclick="showTab('transactions')">Transactions</div>
            <div class="tab" onclick="showTab('maintenance')">Maintenance</div>
        </div>

        <!-- General Tab Content -->
        <div class="tab-content active" id="general">
            <div class="card-body mt-3">
                <div class="form-row">
                    <div class="form-group category">
                        <label>Category:</label>
                        <input type="text" value="{{$asset->category}}" readonly />
                    </div>
                    <div class="form-group">
                        <label>Asset Code:</label>
                        <input type="text" value="{{$asset->code}}" readonly />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group merk">
                        <label>Merk:</label>
                        <input type="text" value="{{$asset->merk_name}}" readonly />
                    </div>
                    <div class="form-group sn">
                        <label>Serial Number:</label>
                        <input type="text" value="{{$asset->serial_number}}" readonly />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Specification:</label>
                        <input type="text" value="{{$asset->spesification}}" readonly />
                    </div>
                    <div class="form-group">
                        <label>Condition:</label>
                        <input type="text" value="{{$asset->condition}}" readonly />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Age Asset:</label>
                        <input type="text" value="{{$asset->asset_age}}" readonly />
                    </div>
                    <div class="form-group">
                        <label>Asset Value ({{ \Carbon\Carbon::now()->format('d-m-Y') }}):</label>
                        <input type="text" value="Rp {{ number_format($asset->depreciation_price, 0, ',', '.') }}"
                            readonly />
                    </div>
                </div>

                <div class="input-group mt-2 d-flex justify-content-end">
                    <button type="button" class="btn" style="background-color: #F8C50F; border-radius:10px;"
                        data-toggle="modal" data-target="#depreciationModal"><i class="bi bi-calculator"></i>
                        Depreciation History
                    </button>
                </div>

            </div>
        </div>

        <!-- Modal for Depreciation History -->
        <div class="modal fade" id="depreciationModal" tabindex="-1" aria-labelledby="depreciationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center fw-bold w-100" id="depreciationModalLabel">Depreciation
                            History</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Value</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tbody>
                            <tbody>
                                @foreach ($depreciationRecords as $record)
                                        @php
                                            $today = \Carbon\Carbon::now();
                                            $depreciationDate = \Carbon\Carbon::parse($record->date);
                                            $statusText = $depreciationDate->isBefore($today) ? 'Deprecated' : 'Has not Deprecated';
                                            $bgColor = $depreciationDate->isBefore($today) ? '#f8d7da' : '#d4edda'; // Red for Deprecated, Green for Not Deprecated
                                            $borderRadius = '0.25rem'; // Rounded corners
                                        @endphp
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($record->date)->format('d-m-Y') }}</td>
                                            <td>Rp {{ number_format($record->depreciation_price, 0, ',', '.') }}</td>
                                            <td style="
                                                            background-color: {{ $bgColor }};
                                                            border-radius: {{ $borderRadius }};
                                                            padding: 5px 15px;
                                                            display: inline-block;
                                                            text-align: center;
                                                            margin-top:5px;
                                                            white-space: nowrap;
                                                        ">
                                                {{ $statusText }}
                                            </td>
                                        </tr>
                                @endforeach
                            </tbody>

                            </tbody>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Tab Content -->
        <div class="tab-content" id="transactions">
            <div class="card-body mt-3">
                <div class="form-row">
                    <div class="form-group">
                        <label>Status:</label>
                        <input type="text" value="{{$asset->status}}" readonly />
                    </div>
                    <div class="form-group">
                        <label>Name Holder:</label>
                        <input type="text" value="{{$asset->name_holder ?? 'Not Yet Handover'}}" readonly />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label>Location:</label>
                        <input type="text" value="{{$asset->location ?? 'Not Yet Handover'}}" readonly />
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintenance Tab Content -->
        <div class="tab-content" id="maintenance">
            <div class="card-body mt-3">
                <div class="form-row">
                    <div class="form-group">
                        <label>Last Maintenance:</label>
                        <input type="text" value="{{$asset->last_maintenance ?? 'Not Yet Maintenance'}}" readonly />
                    </div>
                    <div class="form-group">
                        <label>Next Maintenance:</label>
                        <input type="text" value="{{$asset->next_maintenance}}" readonly />
                    </div>
                </div>
                <div class="input-group mt-2 d-flex justify-content-end">
                    <button type="button" class="btn" style="background-color: #F8C50F; border-radius:10px;"
                        data-toggle="modal" data-target="#maintenanceModal"><i
                            class="fa-solid fa-screwdriver-wrench"></i>
                        Maintenance History
                    </button>
                </div>
            </div>
        </div>
        <!-- Modal for Maintennace History -->
        <div class="modal fade" id="maintenanceModal" tabindex="-1" aria-labelledby="maintenanceModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center fw-bold w-100" id="maintenanceModalLabel">Maintenance
                            History</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Condition</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tbody>
                            <tbody>
                                @foreach ($maintenanceRecords as $maintenance)
                                        <tr>
                                            <td>{{$maintenance->last_maintenance }}</td>
                                            <td>{{$maintenance->condition }}</td>
                                            <td>{{$maintenance->note_maintenance }}</td>
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<script>
    function showTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));

        document.getElementById(tabName).classList.add('active');
        document.querySelector('.tab[onclick="showTab(\'' + tabName + '\')"]').classList.add('active');
    }
</script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>