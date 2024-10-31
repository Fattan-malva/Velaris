<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Register')</title>
    <link rel="icon" href="{{ asset('assets/img/velaris.png') }}" type="image/png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }

        .card {
            display: flex;
            flex-direction: row;
            width: 80%;
            height: 80%;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: white;
        }

        .left-section,
        .right-section {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            width: 50%;
            padding: 20px;
        }

        .left-section {
            background: linear-gradient(to top left, #fff, #FECE04);
        }

        .right-section {
            background-color: #FFF;
        }

        .btn-primary {
            background-color: #FEBD0D;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            color: white;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #fcc735;
        }

        .form-group label {
            font-weight: bold;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: #ffc105;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        img {
            max-width: 80%;
            height: auto;
        }

        h1 {
            font-size: 2.5rem;
            text-align: center;
            font-weight: bold;
            margin-bottom: 5px;
            margin-left: -12px;
        }

        .form-container {
            width: 100%;
            max-width: 380px;
            text-align: center;
        }

        .welcome {
            margin-bottom: 30px;
        }

        .logo-text-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .logo-text-wrapper img {
            height: 60px;
        }

        small.form-text {
            margin-top: 10px;
            display: block;
            color: #666;
        }

        .form-group {
            position: relative;
            margin-bottom: 0px;
            width: 100%;
        }

        .form-control {
            padding-left: 40px;
            padding-right: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
            width: 100%;
            height: 40px;
        }

        .form-control::placeholder {
            color: #999;
            opacity: 1;
        }

        .icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #A3A3A3;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-row .col {
            display: flex;
            flex-direction: column;
        }

        .form-group-full {
            grid-column: 1 / -1;
        }

        .button-group {
            margin-top: 15px;
            text-align: center;
        }

        .button-group button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 10px;
            background-color: #8d45d1;
            color: white;
            border: none;
            cursor: pointer;
        }

        .button-group button:hover {
            background-color: #B86DFF;
        }

        .text-center {
            margin-top: 60px;
        }

        .register-section {
            margin-left: 50%;
            margin-top: 10px;
        }

        /* Overlay and loading animation */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .overlay.active {
            display: flex;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .card {
                flex-direction: column;
                width: 100%;
                height: auto;
            }

            .left-section {
                display: none;
            }

            .right-section {
                width: 100%;
                height: auto;
                padding: 10px;
                overflow: hidden;
            }

            .form-container {
                max-width: 320px;
                width: 100%;
            }

            h1 {
                font-size: 1.8rem;
            }

            .button-group button {
                padding: 12px;
                font-size: 14px;
            }

            /* Make form fields stack vertically */
            .form-row {
                display: flex;
                flex-direction: column;
            }

            .form-row .col {
                width: 100%;
                margin-bottom: 30px;
            }

            .register-section {
                margin-left: 0%;
                margin-top: 10px;
            }

            .right-section.animate-out {
                display: none;

            }

            .left-section.animate-out {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="left-section">
            <div class="text-center">
                <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs"
                    type="module"></script>

                <dotlottie-player src="https://lottie.host/333d89f5-67ef-4495-b186-359ce34833cf/od0pzE1P6Q.json"
                    background="transparent" speed="1" style="width: 500px; height: 7000px;" loop
                    autoplay></dotlottie-player>
            </div>
        </div>

        <div class="right-section">
            <div class="welcome">
                <div class="logo-text-wrapper">
                    <img src="{{ asset('assets/img/assetslogo.png') }}" alt="Register Image">
                    <h1>ssets</h1>
                </div>
            </div>
            <form id="registrationForm" action="{{ route('user.storeregister') }}" method="POST">
                @csrf
                <div class="form-row">
                    <!-- NRP Field -->
                    <div class="col">
                        <div class="form-group">
                            <i class="fas fa-id-card icon"></i>
                            <input type="text" class="form-control @error('nrp') is-invalid @enderror" id="nrp"
                                name="nrp" placeholder="Your NRP..." value="{{ old('nrp') }}" required>
                            @error('nrp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Name Field -->
                    <div class="col">
                        <div class="form-group">
                            <i class="fas fa-user icon"></i>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" placeholder="Your name..." value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>



                    <!-- Mapping Field -->
                    <div class="col">
                        <div class="form-group">
                            <i class="fas fa-briefcase icon"></i>
                            <input type="text" class="form-control @error('mapping') is-invalid @enderror" id="mapping"
                                name="mapping" placeholder="Your position..." value="{{ old('mapping') }}">
                            @error('mapping')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="col">
                        <div class="form-group">
                            <i class="fas fa-envelope icon"></i>
                            <input type="email" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" placeholder="Your email..." value="{{ old('username') }}"
                                required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Password Field -->
                    <div class="col">
                        <div class="form-group">
                            <!-- Icon Gembok -->
                            <div style="position: relative;">
                                <i class="fas fa-lock icon"
                                    style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);"></i>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Your password..." required
                                    style="padding-left: 40px; padding-right: 40px;">
                                <span
                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color:#A3A3A3;"
                                    onclick="togglePassword()">
                                    <i id="password-icon" class="fas fa-eye"></i>
                                </span>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <!-- Icon Gembok -->
                            <div style="position: relative;">
                                <i class="fas fa-lock icon"
                                    style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);"></i>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="confirm_password" name="confirm_password" placeholder="Confirm Your password..."
                                    required style="padding-left: 40px; padding-right: 40px;">
                                <span
                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color:#A3A3A3;"
                                    onclick="toggleConfirmPassword()">
                                    <i id="confirm-password-icon" class="fas fa-eye"></i>
                                </span>
                                <div class="invalid-feedback" id="passwordError" style="display: none;">
                                    Passwords do not match.
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="role" value="user">
                    <div class="form-group register-section">
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                        <div class="login-link">
                            <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                        </div>
                    </div>
            </form>
        </div>
    </div>
    <div id="loading-animation" class="overlay" style="display: none; text-align: center;">

        <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs"
            type="module"></script>

        <dotlottie-player src="https://lottie.host/104f5ddb-9632-4973-a5d4-dd98659272dc/nrBfuqx8XO.json"
            background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></dotlottie-player>
    </div>


    <script>
        document.getElementById('registrationForm').addEventListener('submit', function (e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const passwordError = document.getElementById('passwordError');
            const loadingAnimation = document.getElementById('loading-animation');

            // Prevent form submission if passwords do not match
            if (password !== confirmPassword) {
                e.preventDefault(); // Stop form submission
                passwordError.style.display = 'block'; // Show error
            } else {
                passwordError.style.display = 'none'; // Hide error if matched

                // Display loading animation
                loadingAnimation.style.display = 'flex';

                // Allow form submission to proceed
            }
        });
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

        function toggleConfirmPassword() {
            const confirmPasswordInput = document.getElementById('confirm_password');
            const confirmPasswordIcon = document.getElementById('confirm-password-icon');

            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                confirmPasswordIcon.classList.remove('fa-eye');
                confirmPasswordIcon.classList.add('fa-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
                confirmPasswordIcon.classList.remove('fa-eye-slash');
                confirmPasswordIcon.classList.add('fa-eye');
            }
        }

    </script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>

<style>
    @keyframes collapseToRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(100%);
            opacity: 1;
        }
    }

    @keyframes collapseToLeft {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(-100%);
            opacity: 1;
        }
    }


    .left-section.animate-out {
        animation: collapseToRight 0.8s ease-in-out forwards;
    }

    .right-section.animate-out {
        animation: collapseToLeft 0.8s ease-in-out forwards;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const leftSection = document.querySelector('.left-section');
        const rightSection = document.querySelector('.right-section');



        // Saat pengguna meninggalkan halaman, animasi keluar (menutup ke dalam) ditambahkan
        window.addEventListener("beforeunload", function () {
            leftSection.classList.add('animate-out');
            rightSection.classList.add('animate-out');
        });
    });

</script>