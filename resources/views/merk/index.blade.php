@extends('layouts.app')
@section('title', 'Merk')

@section('content')
<br>
<div class="container">
    <script>

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
                <h3 class="merk-title">
                    Merk&nbsp;&nbsp;
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-tag  previous-icon"></i>
                    </span>
                </h3>
            </div>
            <div class="header-container-mobile">
                <h3 class="merk-title">
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-tag  previous-icon"></i>
                    </span>
                    &nbsp;&nbsp;Merk
                </h3>
            </div>
        </div>
        <br>
    </div>

    <!-- Index Table -->
    <div class="merk-card">
        <div class="card flex-fill">
            <div class="card-body">
                <div class=" mb-3">
                    <button class="btn" id="toggleCreateMerk" data-bs-toggle="collapse" data-bs-target="#createMerkForm"
                        aria-expanded="false" aria-controls="createMerkForm" style="background-color:#1bcfb4;">
                        <i class="fa-solid fa-circle-plus"></i> Add Merk
                    </button>
                </div>

                <div class="collapse form-create" id="createMerkForm">
                    <form action="{{ route('merk.store') }}" method="POST" class="mb-3" id="addMerk">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label label-weight">Merk Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror input-bg"
                                id="name" placeholder="Merk Name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn" style="background-color:#1bcfb4;">Submit
                            </button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <div class="table-container">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Merk Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($merkes as $merk)
                                    <tr>
                                        <td>{{ $merk->name }}</td>
                                        <td>
                                            <button class="btn" style="background-color:#4fb0f1;"
                                                onclick="editMerk({{ $merk->id }})">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                            <form action="{{ route('merk.destroy', $merk->id) }}" method="POST"
                                                style="display: inline;" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn" style="background-color:#FE7C96;"
                                                    title="Delete">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updateMerkModal" tabindex="-1" aria-labelledby="updateMerkModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-black " style="background-color: #f8f8f8;">
                    <h5 class="modal-title" id="updateMerkModalLabel" style="font-weight: 600;">Edit Merk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateMerkForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="update_name" class="form-label text-black" style="font-weight: 600;">Merk
                                Name</label>
                            <input type="text" class="form-control" id="update_name" name="name" required>
                        </div>

                        <input type="hidden" id="update_merk_id" name="id">
                        <div style="text-align: right;">
                            <button type="submit" class="btn" style="background-color:#1bcfb4;"
                                id="update-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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

        .merk-title {
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

        .row {
            display: flex;
            align-items: stretch;
            /* Ensure the columns stretch to the same height */
        }

        .card-title {
            font-weight: bold;
            margin-top: 15px;
        }

        .label-weight {
            font-weight: 600;
        }

        .input-bg {
            background-color: #f8f8f8;
        }

        .text-end {
            text-align: right;
        }

        .btn {
            font-size: 16px;
            font-weight: bold;
            color: white;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 10px;
            text-align: left;
        }

        .table thead {
            position: sticky;
            /* Keep the header fixed */
            top: 0;
            /* Set to the top of the container */
            z-index: 10;
            /* Ensure it stays above the scrolling content */
            background-color: #f8f9fa;
            /* Match header background */
        }

        .table tbody tr {
            border-bottom: 1px solid #ebedf2;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }





        @media (max-width: 576px) {
            .card-title {
                font-size: 1.5rem;
            }

            .btn-add,
            .btn-edit,
            .btn-delete {
                width: 100%;
                margin-bottom: 5px;
            }

            .merk-card {
                margin-top: 15px;
            }
        }

        /* CSS for table row borders */
        .table-hover tbody tr td,
        .table-hover thead tr th {
            border-bottom: 1px solid #ebedf2;
            /* Add a border to the bottom of each row */
            background-color: #fff;
        }

        .table-hover tbody tr td {
            font-weight: 300;
        }

        .table-hover thead tr th {
            font-weight: 600;
        }

        /* Remove any cell borders */
        .table-hover th,
        .table-hover td {
            border: none;
            /* Remove borders from cells */
            padding: 10px;
            /* Keep padding for cells */
        }

        /* Close button style */
        .btn-close {
            padding: 0.25rem 0.5rem;
            /* Adjust padding */
            font-size: 1rem;
            /* Match alert text size */
            line-height: 1;
            /* Align vertically with text */
        }

        .form-create {
            margin-right: 70%;
            background-color: rgba(27, 207, 180, 0.1);
            padding: 10px 30px;
            border-radius: 10px;
        }
    </style>

    @endsection
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function editMerk(id) {
            fetch(`/merk/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('update_name').value = data.name;
                    document.getElementById('update_merk_id').value = data.id;
                    document.getElementById('updateMerkForm').action = `/merk/${data.id}`;
                    var myModal = new bootstrap.Modal(document.getElementById('updateMerkModal'), {
                        backdrop: 'static'
                    });
                    myModal.show();
                })
                .catch(error => console.error('Error:', error));
        }
        // SweetAlert for Delete confirmation
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-form').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();  // Prevent the form from submitting immediately
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#6B07C2',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show deleting confirmation
                            Swal.fire({
                                title: 'Deleting',
                                text: 'Please wait...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // After confirming, submit the form
                            form.submit();
                        }
                    });
                });
            });
            document.getElementById('addMerk').addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent form submission

                // Show loading alert
                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we add the Merk.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Simulate form submission
                setTimeout(() => {
                    this.submit(); // Submit the form after the loading alert
                }, 1500);
            });
            // Event listener for the update button
            document.getElementById('update-btn').addEventListener('click', function (event) {
                event.preventDefault(); // Prevent the default form submission
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to save the changes?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6B07C2',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Updating',
                            text: 'Please wait...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                                document.getElementById('updateMerkForm').submit(); // Submit the form after confirmation
                            }
                        });
                    }
                });
            });
        });



        // Event listener for toggle button
        document.getElementById('toggleCreateMerk').addEventListener('click', function () {
            const createMerkForm = document.getElementById('createMerkForm');
            const toggleIcon = document.getElementById('toggleIcon');
            const isCollapsed = createMerkForm.classList.contains('collapse');

            if (isCollapsed) {
                createMerkForm.classList.remove('collapse');
                createMerkForm.classList.add('show');
                toggleIcon.classList.remove('fa-chevron-down');
                toggleIcon.classList.add('fa-chevron-up');
            } else {
                createMerkForm.classList.remove('show');
                createMerkForm.classList.add('collapse');
                toggleIcon.classList.remove('fa-chevron-up');
                toggleIcon.classList.add('fa-chevron-down');
            }
        });


    </script>