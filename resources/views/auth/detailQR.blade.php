<html>

<head>
    <title>
        Asset Tagging
    </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
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
            border-bottom: 3px solid #4CAF50;
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
            margin-bottom: 10px;
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
                padding: 10px;
                border-right: 1px solid #ddd;
            }

            .form-row {
                flex-direction: column;
            }

            .form-group {
                width: 100%;
            }
        }
    </style>
</head>

<body>
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
                    // Fallback to 'default.png' if the category doesn't match any key in the iconMap
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
                <div class="tab active" onclick="showTab('general')">
                    General
                </div>
                <div class="tab" onclick="showTab('transactions')">
                    Transactions
                </div>
                <div class="tab" onclick="showTab('maintenance')">
                    Maintenance
                </div>
            </div>
            <!-- General Tab Content -->
            <div class="tab-content active" id="general">
                <div class="card-body mt-3">
                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                Asset Code:
                            </label>
                            <input type="text" value="{{$asset->code}}" readonly />
                        </div>
                        <div class="form-group">
                            <label>
                                Category:
                            </label>
                            <input type="text" value="{{$asset->category}}" readonly />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                Merk:
                            </label>
                            <input type="text" value="{{$asset->merk_name}}" readonly />
                        </div>
                        <div class="form-group">
                            <label>
                                Serial Number:
                            </label>
                            <input type="text" value="{{$asset->serial_number}}" readonly />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                Specification:
                            </label>
                            <input type="text" value="{{$asset->spesification}}" readonly />
                        </div>
                        <div class="form-group">
                            <label>
                                Condition:
                            </label>
                            <input type="text" value="{{$asset->condition}}" readonly />
                        </div>
                    </div>
                </div>
            </div>
            <!-- Transactions Tab Content -->
            <div class="tab-content" id="transactions">
                <div class="card-body mt-3">
                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                Status:
                            </label>
                            <input type="text" value="{{$asset->status}}" readonly />
                        </div>
                        <div class="form-group">
                            <label>
                                Location:
                            </label>
                            <input type="text" value="{{$asset->location}}" readonly />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                Value:
                            </label>
                            <input type="text" value="Rp {{$asset->depreciation_price}}" readonly />
                        </div>
                        <div class="form-group">
                            <label>
                                Depreciation Date:
                            </label>
                            <input type="text" value="{{$asset->depreciation_date}}" readonly />
                        </div>
                    </div>
                </div>
            </div>
            <!-- Maintenance Tab Content -->
            <div class="tab-content" id="maintenance">
                <div class="form-row">
                    <div class="form-group">
                        <label>
                            Next Maintenance:
                        </label>
                        <input type="text" value="{{$asset->next_maintenance}}" readonly />
                    </div>
                    <div class="form-group">
                        <label>
                            Last Maintenance:
                        </label>
                        <input type="text" value="{{$asset->last_maintenance}}" readonly />
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
</body>

</html>
