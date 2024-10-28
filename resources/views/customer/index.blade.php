@extends('layouts.app')
@section('title', 'Users')

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
                <h3 class="Users-title">
                    Users&nbsp;&nbsp;
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-users previous-icon"></i>
                    </span>
                </h3>
            </div>
            <div class="header-container-mobile">
                <h3 class="Users-title">
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-2xs fa-users previous-icon"></i>
                    </span>
                    &nbsp;&nbsp;Users
                </h3>
            </div>
            <br>
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
        </div>
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('customer.create') }}" class="btn btn-lg btn-custom" id="create-btn"
                        style="background-color: #3EEAD0;">
                        <i class="bi bi-plus-circle"></i> Create
                    </a>
                </div>
                <div class="table-responsive">
                    <table id="customerTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">NRP</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Position</th>
                                <th scope="col">Type Register</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customers as $index => $customer)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $customer->nrp }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->username }}</td>
                                    <td>{{ $customer->mapping }}</td>
                                    <td>{{ $customer->login_method }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('customer.edit', $customer->id) }}" class="btn"
                                                style="background-color:#4fb0f1;" id="edit-btn" title="Edit">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <form action="{{ route('customer.destroy', $customer->id) }}" method="POST"
                                                style="display: inline;" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn" style="background-color:#FE7C96;"
                                                    title="Delete">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        // SweetAlert for loading on Create button
        document.getElementById('create-btn').addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Loading',
                text: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    window.location.href = "{{ route('customer.create') }}";
                }
            });
        });

        // SweetAlert for loading on Edit button
        document.querySelectorAll('.btn-edit').forEach(function (button) {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Loading',
                    text: 'Please wait...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                        window.location.href = this.href;
                    }
                });
            });
        });

        // SweetAlert for Delete confirmation
        document.querySelectorAll('.delete-form').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
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
                        Swal.fire({
                            title: 'Deleting',
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
        // Enable Pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('4e48e575ba3df906eedf', {
            cluster: 'ap1'
        });

        // Function to dynamically append a new customer row
        function appendCustomerRow(customer) {
            const tableBody = document.querySelector('#customerTable tbody');
            const newRow = `
        <tr id="customer-row-${customer.id}">
            <td>${tableBody.rows.length + 1}</td>
            <td>${customer.nrp}</td>
            <td>${customer.name}</td>
            <td>${customer.username}</td>
            <td>${customer.mapping}</td>
            <td>${customer.login_method}</td>
            <td>
                <div class="action-buttons">
                    <a href="/customer/${customer.id}/edit" class="btn" style="background-color:#4fb0f1;" title="Edit">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    <form action="/customer/${customer.id}" method="POST" style="display: inline;" class="delete-form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn" style="background-color:#FE7C96;" title="Delete">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    `;
            tableBody.insertAdjacentHTML('beforeend', newRow);
        }

        // Function to update an existing customer row
        function updateCustomerRow(customer) {
            const row = document.querySelector(`#customer-row-${customer.id}`);
            if (row) {
                row.innerHTML = `
            <td>${row.rowIndex}</td>
            <td>${customer.nrp}</td>
            <td>${customer.name}</td>
            <td>${customer.username}</td>
            <td>${customer.mapping}</td>
            <td>${customer.login_method}</td>
            <td>
                <div class="action-buttons">
                    <a href="/customer/${customer.id}/edit" class="btn" style="background-color:#4fb0f1;" title="Edit">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    <form action="/customer/${customer.id}" method="POST" style="display: inline;" class="delete-form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn" style="background-color:#FE7C96;" title="Delete">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </td>
        `;
            }
        }

        // Function to delete an existing customer row
        function deleteCustomerRow(customerId) {
            const row = document.querySelector(`#customer-row-${customerId}`);
            if (row) {
                row.remove();
            }
        }

        var channel = pusher.subscribe('customers');
        channel.bind('App\\Events\\CustomerDataChanged', function (data) {
            let actionMessage = '';

            if (data.action === 'created') {
                actionMessage = 'A new customer has been created / registered!';
                appendCustomerRow(data.customer); // Append the new customer row
            } else if (data.action === 'updated') {
                actionMessage = 'A customer has been updated!';
                updateCustomerRow(data.customer); // Update the existing customer row
            } else if (data.action === 'deleted') {
                actionMessage = 'A customer has been deleted!';
                deleteCustomerRow(data.customer.id); // Remove the relevant customer row
            }

            // Display a SweetAlert notification with the action message
            Swal.fire({
                title: 'Notification',
                text: actionMessage,
                icon: 'info',
                confirmButtonColor: '#6B07C2'
            });

            console.log('Real-time update:', data);
        });

    </script>



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

        .Users-title {
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

        .btn-custom {
            color: white;
            border: none;
            padding: 0.4rem 0.5rem;
            border-radius: 0.5rem;
            margin-bottom: -70px;
            font-weight: 600;
            font-size: 16px;
            margin-left: 10px;
        }

        .btn {
            font-size: 16px;
            font-weight: bold;
            color: white;
        }



        @media (max-width: 576px) {
            .btn-custom {
                width: 100%;
                /* Make the button full-width on small screens */
                margin-bottom: 1rem;
                /* Add some spacing below the button */
                margin-right: 8px;
            }

            .d-flex {
                flex-direction: column;
                /* Stack elements vertically */
                align-items: stretch;
                /* Stretch elements to full width */
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

        .legend-colon {
            margin: 0 5px;
            /* Space around the colon */
        }

        /* Hide colon on mobile devices */
        @media (max-width: 576px) {
            .legend-colon {
                display: none;
                /* Hide colon */
            }
        }
    </style>
    @endsection