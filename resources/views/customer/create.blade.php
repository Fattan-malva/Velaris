@extends('layouts.noheader')
@section('title', 'User Create')
@section('content')
<br><br><br>
<div class="container">
    <div class="card shadow">
        <h2 style="margin-top: 25px; margin-bottom: 20px; text-align: center; font-weight: 600;">Create User</h2>
        <hr style="width: 80%; margin: 0 auto;">
        <div class="card-body" style="padding: 30px;">
            <form action="{{ route('customer.store') }}" method="POST" id="createUserForm">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="username" class="form-label">Email</label>
                        <input type="email" class="form-control @error('username') is-invalid @enderror" id="username"
                            name="username" value="{{ old('username') }}" placeholder="Username" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <div class="mb-1" style="position: relative;">
                            <span style="position: absolute; right: 10px; top: 35%; cursor: pointer;"
                                onclick="togglePassword()">
                                <i id="password-icon" class="fas fa-eye" style="margin-top: 15px;"></i>
                            </span>
                        </div>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password" value="{{ old('password') }}" placeholder="Password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Role and NRP -->
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="" disabled {{ old('role', $customer->role ?? '') == '' ? 'selected' : '' }}>
                                Select role</option>
                            <option value="admin" {{ old('role', $customer->role ?? '') == 'admin' ? 'selected' : '' }}>
                                Admin</option>
                            <option value="user" {{ old('role', $customer->role ?? '') == 'user' ? 'selected' : '' }}>User
                            </option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="nrp" class="form-label">NRP</label>
                        <input type="text" class="form-control @error('nrp') is-invalid @enderror" id="nrp" name="nrp"
                            value="{{ old('nrp') }}" placeholder="NRP" required>
                        @error('nrp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Name and Position -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" placeholder="Name User" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="mapping" class="form-label">Position</label>
                        <input type="text" class="form-control @error('mapping') is-invalid @enderror" id="mapping"
                            name="mapping" value="{{ old('mapping') }}" placeholder="Position">
                        @error('mapping')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-3 mb-2" style="text-align: right;">
                    <button type="submit" class="btn" style="background-color:#1bcfb4;">Submit</button>
                    <a href="{{ route('customer.index') }}" class="btn ml-3"
                        style="background-color:#FE7C96;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<br><br>

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('createUserForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent form submission

        // Show loading alert
        Swal.fire({
            title: 'Loading...',
            text: 'Please wait while we create the user.',
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

    // Show success message after redirect from controller (on successful creation)
    @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif

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
</script>
@endsection