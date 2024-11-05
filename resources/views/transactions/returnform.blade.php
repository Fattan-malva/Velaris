@extends('layouts.noheader')
@section('title', 'Return')

@section('content')

<br>
<div class="container mt-5">
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <div class="card shadow">
        <h2 style="margin-top: 25px; margin-bottom: 20px; text-align: center; font-weight: 600;">Asset Return</h2>
        <hr style="width: 80%; margin: 0 auto;">
        <div class="card-body mt-3">
            <form action="{{ route('transactions.returnUpdate', $asset->id) }}" method="POST" enctype="multipart/form-data"
                id="Return" class="return-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="asset_code" value="{{ $asset->asset_code }}">
                <input type="hidden" name="name_holder" value="{{ $asset->name_holder }}">
                <input type="hidden" name="location" value="{{ $asset->location }}">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="asset_tagging_display">Asset Code</label>
                            @php
                                $taggingValue = $inventories->firstWhere('id', $asset->asset_code)->code ?? 'Not Found';
                            @endphp
                            <input type="text" class="form-control" id="asset_tagging_display"
                                value="{{ $taggingValue }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="nama_display">Name Holder</label>
                            <input type="text" class="form-control" id="nama_display"
                                value="{{ $asset->customer_name }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="reason">Reason for Return</label>
                            <select class="form-select" id="reason" name="reason" required>
                                <option value="" disabled selected>Choose a reason</option>
                                <option value="Damaged" {{ $asset->reason == 'Damaged' ? 'selected' : '' }}>Damaged
                                </option>
                                <option value="Service" {{ $asset->reason == 'Service' ? 'selected' : '' }}>Service
                                </option>
                                <option value="Not yet given" {{ $asset->reason == 'Not yet given' ? 'selected' : '' }}>Not yet given</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lokasi_display">Location</label>
                            <input type="text" class="form-control" id="lokasi_display" value="{{ $asset->location }}"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label for="note">Note</label>
                            <input class="form-control" id="note" name="note" rows="3" placeholder="Enter note here"
                                style="color:black; padding-bottom: 93px;"></input>

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <input type="file" class="form-control" id="documentation" name="documentation" accept="image/*"
                        hidden>
                </div>

                <div class="form-group current">
                    @if($asset->documentation)
                        <p class="mt-4"
                            style="display: inline-block; background-color: rgba(128, 128, 128, 0.3); padding: 4px 10px; border-radius: 4px; font-weight: bold;">
                            Current file: <a href="{{ asset('storage/' . $asset->documentation) }}" target="_blank"
                                style="color: #4fb0f1;" class="text-decoration-underline"> View</a>
                        </p>
                    @endif
                </div>

                <div class="text-end">
                    <button type="submit" class="btn" style="background-color:#1bcfb4;">Submit</button>
                    <a href="{{ route('transactions.indexreturn') }}" class="btn ml-3"
                        style="background-color:#FE7C96;">Cancel</a>
                </div>
            </form>

        </div>
    </div>
</div>
<br>
<br>
<script>
    document.querySelectorAll('.return-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6B07C2',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, return it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Return Asset',
                        text: 'Please wait...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                            form.submit(); // Submit the form after confirmation
                        }
                    });
                }
            });
        });
    });
    @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonColor: '#6B07C2'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: 'Error!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonColor: '#d33'
        });
    @endif
</script>

<style>
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
    }

    .back-icon:hover {
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) -13%, #B100FF);
    }

    .back-wrapper {
        display: flex;
        align-items: center;
        margin-right: auto;
    }

    .back-text {
        display: flex;
        flex-direction: column;
        margin-left: 10px;
    }

    .back-text .title {
        font-weight: 600;
        font-size: 17px;
    }

    .back-text .small-text {
        font-size: 0.8rem;
        color: #aaa;
        margin-top: -3px;
    }

    .dashboard-title {
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

    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }

    .form-group {
        flex: 1;
        min-width: 240px;
        padding: 0 10px;

    }

    .form-group input,
    .form-group select {
        width: 100%;
        border-radius: 8px;
        border: 1px solid #000;
        padding: .375rem .75rem;
    }

    @media (max-width: 768px) {
        .form-group {
            flex: 100%;
            margin-bottom: 15px;
        }
    }

    .current {
        margin-top: -30px;
        margin-bottom: 1px;
    }

    .btn {
        margin: 0 0.5rem;
        font-size: 16px;
        font-weight: bold;
        color: white;
    }


    @media (max-width: 576px) {
        .btn-danger {
            width: 100%;
            /* Tombol mengambil lebar penuh pada mobile */
            font-size: 0.9rem;
            /* Ukuran font yang lebih kecil untuk mobile */
            padding: 0.3rem 0.4rem;
            /* Mengurangi padding untuk mobile */
            text-align: center;
            margin-bottom: 10px;
        }

        .btn-secondary {
            width: 100%;
            /* Tombol mengambil lebar penuh pada mobile */
            font-size: 0.9rem;
            /* Ukuran font yang lebih kecil untuk mobile */
            padding: 0.4rem 0.49rem;
            /* Mengurangi padding untuk mobile */
            text-align: center;
        }
    }
</style>
@endsection