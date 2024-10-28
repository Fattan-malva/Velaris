@extends('layouts.app')

@section('content')

<div class="container">
    <br>
    <br>
    <h1 class="mt-4 text-center">Add Merk</h1>
    <br>
    <div class="card">
        <div class="card-header">
            <h2>Add Merk</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('merk.store') }}">

                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Merk Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('inventorys.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<br>
<br>
<br>

@endsection