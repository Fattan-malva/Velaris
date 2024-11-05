@extends('layouts.app')
@section('title', 'Scrap')

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
                <h3 class="scrap-title">
                    Scrap&nbsp;&nbsp;
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-ban previous-icon"></i>
                    </span>
                </h3>
            </div>
            <div class="header-container-mobile">
                <h3 class="scrap-title">
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-ban previous-icon"></i>
                    </span>
                    &nbsp;&nbsp;Scrap
                </h3>
            </div>
            <br>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('assets.delete') }}" method="POST" enctype="multipart/form-data" class="delete-form">
                @csrf
                @method('DELETE')

                <div class="row" style="padding: 15px;">
                    <div class="col-md-6 form-group">
                        <label for="code">Select Assets to Scrap</label>
                        <select class="form-control select-dark" id="code" name="ids[]" multiple="multiple" required>
                            @foreach($inventories as $inventory)
                                <option value="{{ $inventory->id }}">{{ $inventory->code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="documentation">Upload Documentation (Reason for Scrapping)</label>
                        <input type="file" class="form-control" id="documentation" name="documentation"
                            accept=".pdf,.doc,.docx,.jpg,.png" required>
                        <small class="form-text text-muted">*Please upload the documentation file.</small>
                    </div>
                </div>

                <div class="button">
                    <button type="submit" class="btn" style="background-color:#FE7C96;"><i class="bi bi-trash"></i>
                        Scrap Assets</button>
                </div>
            </form>
        </div>
    </div>
</div>
<br><br>

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
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) -10%, #FCA918);
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

    .scrap-title {
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

    /* Form and Table Styles */
    .form-group {
        margin-bottom: 1.5rem;
        padding: 10px;
        /* Menambahkan padding pada setiap kolom */
    }

    .form-control {
        width: 100%;
        /* Memastikan elemen mengambil lebar penuh */
        font-size: 1rem;
        padding: 10px;
        /* Padding untuk memberikan ruang di dalam */
        border-radius: 5px;
        /* Sudut yang lebih halus */
    }

    .btn {
        margin: 0 0.5rem;
        font-size: 16px;
        font-weight: bold;
        color: white;
    }

    .table-hover tbody tr td,
    .table-hover thead tr th {
        border-bottom: 1px solid #ebedf2;
    }

    .table-hover tbody tr td {
        font-weight: 300;
    }

    .table-hover thead tr th {
        font-weight: 600;
    }

    .button {
        text-align: right;
    }

    .btn-action {
        background-color: #fe7c96;
        color: #fff;
        font-weight: 500;
    }

    .btn-action:hover {
        background-color: transparent;
        border: 1px solid #fe7c96;
        color: #fe7c96;
        font-weight: 500;
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



        .form-group {
            margin-bottom: 1rem;
            /* Mengurangi jarak antar form di mobile */
            padding: 5px;
            /* Mengurangi padding untuk mobile */
        }

        .form-control {
            padding: 8px;
            /* Mengurangi padding untuk mobile */
            font-size: 0.9rem;
            /* Ukuran font yang sedikit lebih kecil */
        }

        .btn-action {
            width: 100%;
            /* Tombol mengambil lebar penuh pada mobile */
            font-size: 0.9rem;
            /* Ukuran font yang lebih kecil untuk mobile */
            padding: 8px;
            /* Mengurangi padding untuk mobile */
            text-align: center;
        }
    }
</style>
<script>
    document.querySelectorAll('.delete-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6B07C2',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, scrap it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show a loading message while submitting
                    Swal.fire({
                        title: 'Scraping Asset',
                        text: 'Please wait...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading(); // Show loading indicator
                        }
                    });

                    // Submit the form
                    form.submit();
                }
            });
        });
    });
</script>
@endsection