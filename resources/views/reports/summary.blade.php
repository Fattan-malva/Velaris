@extends('layouts.app')

@section('content')
<div class="container my-5">
    <br>
    <br>
    <br>
    <h1 class="text-center mb-4">Summary Report</h1>
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Summary Report</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Asset</th>
                            <th>Merk</th>
                            <th>Location</th>
                            <th>Operation</th>
                            <th class="bg-secondary text-white">Inventory GSI</th>
                            <th class="bg-secondary text-white">Condition Unit in Inventory</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($summary as $item)
                            <tr>
                                <td>{{ $item['asset_name'] }}</td>
                                <td>{{ $item['merk_name'] }}</td>
                                <td>
                                    @forelse ($item['locations'] as $location)
                                        {{ $location['location'] }}<br>
                                    @empty
                                        <span class="text-muted">No locations available</span>
                                    @endforelse
                                </td>
                                <td>
                                    @forelse ($item['locations'] as $location)
                                        {{ $location['operation_count'] }}<br>
                                    @empty
                                        <span class="text-muted">No operations recorded</span>
                                    @endforelse
                                </td>
                                <td style="background-color: #d3d3d3;">{{ $item['inventory_GSI'] }}</td>
                                <td style="background-color: #d3d3d3;">
                                    <div class="condition-bad">
                                        <span class="badge bg-danger">Bad: {{ $item['bad_count'] }}</span>
                                    </div>
                                    <div class="condition-exception">
                                        <span class="badge bg-warning">Exception: {{ $item['exception_count'] }}</span>
                                    </div>
                                    <div class="condition-good">
                                        <span class="badge bg-success">Good: {{ $item['good_count'] }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">No Report Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .condition-bad, .condition-exception, .condition-good {
        margin-bottom: 5px;
    }
    .condition-bad .badge {
        font-size: 0.9em;
    }
    .condition-exception .badge {
        font-size: 0.9em;
    }
    .condition-good .badge {
        font-size: 0.9em;
    }
    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .table-bordered {
        border: 1px solid #dee2e6;
    }
</style>
