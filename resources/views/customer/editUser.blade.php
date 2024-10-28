@extends('layouts.app')

@section('content')
<br />
<br />
<div class="container my-5">
    <div class="header-container">
        <div class="back-wrapper">
            <i class='bx bxs-chevron-left back-icon' id="back-icon"></i>
            <div class="back-text">
                <span class="title">Back</span>
                <span class="small-text">to previous page</span>
            </div>
        </div>
        <h3 class="dashboard-title">
            Profile&nbsp;&nbsp;
            <span class="icon-wrapper">
                <i class="fa-solid fa-2xs fa-user-pen previous-icon"></i>
            </span>
        </h3>
    </div>
    <div class="card unique-profile-content">
        <div class="card-header text-center"
            style=" background: linear-gradient(90deg, #BB73f9, #e6d5f5); padding: 30px 30px; border-top-left-radius: 15px; border-top-right-radius: 15px;">
            <img src="{{ asset('assets/img/admin.png') }}" alt="Profile Picture"
                class="img-fluid rounded-circle profile-picture" style="margin-bottom: -80px; margin-top: 20px;">
        </div>
        <div class="card-body" style="margin-top: 30px;">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="text-left mt-2 mb-2">
                <h5 class="fw-bold">{{ session('user_name') }}</h5>
                <p style="font-size: 13px; margin-top: -8px;">{{ session('user_username') }}</p>
                <div class="text-end">
                    <button type="button" class="btn btn-edit" style="background-color: #4fb0f1;"
                        onclick="toggleEdit()"><i class="fa-solid fa-pen"></i></button>
                    <button type="submit" class="btn btn-save" style="display:none; background-color: #1bcfb4;"
                        onclick="submitForm(event)">Save
                        Changes</button>
                </div>
            </div>
            <hr style="margin: 10px 0px 10px 0px; width: 100%;" />
            <form id="userForm" method="POST">
                @csrf
                @method('PUT')
                <div class="profile-info">
                    <div class="profile-row">
                        <label for="nrp" class="fw-bold">NRP</label>
                        <input type="text" id="nrp" name="nrp" value="{{ old('nrp', $user->nrp) }}"
                            class="form-control @error('nrp') is-invalid @enderror" required readonly>
                    </div>
                    <hr style="margin: 10px 0px 10px 0px; width: 100%;" />
                    <div class="profile-row">
                        <label for="name" class="fw-bold">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                            class="form-control @error('name') is-invalid @enderror" required readonly>
                    </div>
                    <hr style="margin: 10px 0px 10px 0px; width: 100%;" />
                    <div class="profile-row">
                        <label for="mapping" class="fw-bold">Position</label>
                        <input type="text" id="mapping" name="mapping" value="{{ old('mapping', $user->mapping) }}"
                            class="form-control @error('mapping') is-invalid @enderror" required readonly>
                    </div>
                    <hr style="margin: 10px 0px 10px 0px; width: 100%;" />
                    <div class="profile-row">
                        <label for="email" class="fw-bold">Email</label>
                        <input type="text" id="email" name="email" value="{{ old('email', $user->username) }}"
                            class="form-control @error('email') is-invalid @enderror" required readonly>
                    </div>
                    <hr style="margin: 10px 0px 10px 0px; width: 100%;" />
                </div>
            </form>
        </div>
    </div>
</div>

<br />
<script>
    let editing = false;

    function toggleEdit() {
        const inputs = document.querySelectorAll('.profile-info input');
        const updateButton = document.querySelector('.btn-save');
        const toggleButton = document.querySelector('.btn-edit');

        editing = !editing;

        inputs.forEach(input => {
            input.readOnly = !editing;
            if (editing) input.classList.remove('is-invalid'); // Remove any invalid classes when editing
        });

        if (editing) {
            toggleButton.innerHTML = 'Cancel';
            toggleButton.style.backgroundColor = '#fe7c96';  // Change text to "Cancel"
            updateButton.style.display = 'inline-block';
            inputs[0].focus();
        } else {
            toggleButton.innerHTML = '<i class="fa-solid fa-pen"></i>';
            toggleButton.style.backgroundColor = '#4fb0f1';  // Change back to icon
            updateButton.style.display = 'none';
        }
    }


    function confirmAction() {
        Swal.fire({
            title: 'Profile updated successfully',
            text: "Updates will appear if you log in again. You will automatically relog or select the option to Stay logged in",
            icon: 'success',
            showDenyButton: true,
            denyButtonText: 'Stay logged in',
            confirmButtonText: 'OK, got it!',
            confirmButtonColor: '#6B07C2', // Color for 'OK, got it!' button
            timer: 8000, // 8 seconds timer
            timerProgressBar: true, // Optional: Show progress bar
            willClose: () => {
                // If the timer expires, redirect to login
                window.location.href = "{{ route('login') }}"; // Redirect to login page
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('login') }}"; // Redirect to login page
            } else if (result.isDenied) {
                window.location.href = "{{ route('shared.homeUser') }}"; // Redirect to home user page
            }
        });

        // Add custom style for deny button after rendering
        const denyButton = document.querySelector('.swal2-deny');
        if (denyButton) {
            denyButton.style.backgroundColor = '#757575'; // Set to light gray
            denyButton.style.color = '#fefcff'; // Set text color to black for visibility
        }
    }

    function submitForm(event) {
        event.preventDefault(); // Prevent default form submission
        const form = document.getElementById('userForm');
        const formData = new FormData(form);

        fetch(`{{ route('customer.updateUser', $user->id) }}`, {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    confirmAction(); // Show confirmation alert
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        for (const [key, value] of Object.entries(data.errors)) {
                            const input = document.getElementById(key);
                            if (input) {
                                input.classList.add('is-invalid'); // Add invalid class to input
                                // Display error messages as needed, you can customize this part
                                console.error(value); // Log the error for debugging
                            }
                        }
                    }
                }
            })
            .catch(error => console.error('Error:', error));
    }
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

    .previous-icon {
        font-size: 16px;
    }

    .card-body {
        padding: 30px;
    }

    .unique-profile-content {
        border-radius: 15px;
        box-shadow: rgba(0, 0, 0, 0.15) 2.4px 2.4px 3.2px;
    }

    .profile-info {
        display: flex;
        flex-direction: column;
    }

    .profile-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .profile-row label {
        flex: 1;
        text-align: left;
        margin-right: 10px;
        font-size: 13px;
    }

    .profile-row input {
        flex: 2;
        padding: 0.4rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        color: #495057;
        font-size: 12px;
    }

    .profile-row input:focus {
        border-color: #6B07C2;
        box-shadow: 0 0 0 0.2rem rgba(107, 7, 194, 0.25);
    }

    body {
        background-color: #f2edf3;
    }

    .profile-picture {
        width: 100px;
        height: 100px;
        display: block;
        margin-right: auto;
    }

    .btn-update {
        background-color: #1bcfb4;
        color: #fff;
        font-weight: 550;
        font-size: 14px;
        padding: 6px 8px;
    }

    .btn-update:hover {
        background-color: #1bcfb4;
        color: #fff;
    }

    .btn-cancel:hover {
        background-color: #c9302c;
        border-color: #ac2925;
    }

    .btn-toggle {
        background-color: #4fb0f1;
        color: white;
        padding: 6px 40px;
        font-size: 14px;
        font-weight: 550;
    }

    .btn-toggle:hover {
        background-color: #4fb0f1;
        color: #fff;
    }


    .btn {
        font-size: 16px;
        font-weight: bold;
        color: white;
    }

    @media (max-width: 576px) {
        .header-container {
            display: none;
        }
    }
</style>
@endsection