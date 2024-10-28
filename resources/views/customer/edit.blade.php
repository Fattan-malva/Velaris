@extends('layouts.noheader')
@section('title', 'User Update')

@section('content')
<div class="container">
    <br>
    <br>
    <br>
    <div class="card shadow">
        <h2 style="margin-top: 25px; margin-bottom: 20px; text-align: center; font-weight: 600;">Edit User</h2>
        <hr style="width: 80%; margin: 0 auto;">
        <div class="card-body" style="padding: 30px;">
            <form method="POST" action="{{ route('customer.update', $customer->id) }}" id="confirm-update">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                            name="username" placeholder="Enter your username"
                            value="{{ old('username', $customer->username) }}" required>
                        @error('username')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="mb-1" style="position: relative;">
                            <span style="position: absolute; right: 10px; top: 35%; cursor: pointer;"
                                onclick="togglePassword()">
                                <i id="password-icon" class="fas fa-eye" style="margin-top: 15px;"></i>
                            </span>
                        </div>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password" placeholder="Enter your password"
                            value="{{ old('password', $customer->password) }}" required>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="" disabled selected>Select role</option>
                            <option value="admin" {{ (old('role', $customer->role) == 'admin') ? 'selected' : '' }}>Admin
                            </option>
                            <option value="user" {{ (old('role', $customer->role) == 'user') ? 'selected' : '' }}>User
                            </option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nrp" class="form-label">NRP</label>
                        <input type="text" class="form-control @error('nrp') is-invalid @enderror" id="nrp" name="nrp"
                            placeholder="Enter your NRP" value="{{ old('nrp', $customer->nrp) }}" required>
                        @error('nrp')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" placeholder="Enter your name" value="{{ old('name', $customer->name) }}"
                            required>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="mapping" class="form-label">Position</label>
                        <input type="text" class="form-control @error('mapping') is-invalid @enderror" id="mapping"
                            name="mapping" placeholder="Enter your position"
                            value="{{ old('mapping', $customer->mapping) }}">
                        @error('mapping')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="mb-2 mt-2" style="text-align: right;">
                    <button type="submit" class="btn" style="background-color:#1bcfb4;" id="update-btn">Submit</button>
                    <a href="{{ route('customer.index') }}" class="btn ml-3"
                        style="background-color:#FE7C96;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-label {
        font-weight: 550;
    }

    .form-control {
        border: 1px solid #000;
    }

    .btn {
        margin: 0 0.5rem;
        font-size: 16px;
        font-weight: bold;
        color: white;
    }
</style>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('password-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        }
    }

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
                        document.getElementById('confirm-update').submit(); // Submit the form after confirmation
                    }
                });
            }
        });
    });

    // If there is a success or error session
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

@endsection
