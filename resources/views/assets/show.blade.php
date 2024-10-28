@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Inventory Item Details</h2>
    
    <h3>{{ $inventory->tagging }}</h3>
    <p><strong>ID:</strong> {{ $inventory->id }}</p>
    <p><strong>Description:</strong> {{ $inventory->description }}</p>
    <p><strong>Location:</strong> {{ $inventory->location }}</p>
    <!-- Add more fields as needed -->

    <a href="{{ route('inventorys.index') }}" class="btn btn-primary">Back to List</a>
</div>
@endsection
