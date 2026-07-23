<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Rekam WeBe</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('public/logo_webe.png') }}">

    <!-- Google Font: Source Sans Pro (or Inter for a more modern look) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('public/adminlte') }}/plugins/fontawesome-free/css/all.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ url('public/adminlte') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('public/adminlte') }}/dist/css/adminlte.min.css">

    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
        }

        /* Split-Screen Layout */
        .split-container {
            display: flex;
            min-height: 100vh;
        }

        /* Left Side (Image & Branding) */
        .split-left {
            flex: 1;
            background: url('{{ asset('public/adminlte/dist/img/background_login1.jpg') }}') no-repeat center center;
            background-size: cover;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .split-left::before {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: linear-gradient(135deg, rgba(0, 31, 63, 0.85) 0%, rgba(0, 123, 255, 0.6) 100%);
        }

        .split-left-content {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 2rem;
            max-width: 600px;
            margin-top: -22vh; /* Sejajar dengan posisi logo di form kanan */
        }

        .split-left-content h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .split-left-content p {
            font-size: 1.2rem;
            font-weight: 300;
            opacity: 0.9;
        }

        /* Right Side (Form) */
        .split-right {
            flex: 0 0 500px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            box-shadow: -10px 0 30px rgba(0,0,0,0.05);
            z-index: 2;
        }

        /* Form Container */
        .login-form-container {
            width: 100%;
            max-width: 400px;
        }

        .brand-logo {
            width: 70px;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        }

        .login-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            font-size: 0.95rem;
            color: #6c757d;
            margin-bottom: 2.5rem;
        }

        /* Custom Inputs */
        .input-group-custom {
            position: relative;
        }

        .form-control-custom {
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 0.75rem 1rem 0.75rem 2.75rem; /* Left padding for icon */
            font-size: 1rem;
            transition: all 0.2s ease-in-out;
            height: auto;
        }

        .form-control-custom:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            z-index: 10;
            transition: color 0.2s;
        }

        .form-control-custom:focus + .input-icon,
        .form-control-custom:not(:placeholder-shown) + .input-icon {
            color: #007bff;
        }

        /* Password Toggle */
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            cursor: pointer;
            z-index: 10;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #495057;
        }

        /* Custom Button */
        .btn-login {
            background-color: #001f3f;
            color: white;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 31, 63, 0.15);
            width: 100%;
            border: none;
        }

        .btn-login:hover {
            background-color: #000814;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 31, 63, 0.25);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }

        /* Error Text */
        .text-error {
            font-size: 0.85rem;
            color: #dc3545;
            margin-top: 0.25rem;
            display: block;
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
        }
        
        .is-invalid:focus {
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .split-right {
                flex: 0 0 400px;
            }
        }

        @media (max-width: 768px) {
            .split-container {
                flex-direction: column;
            }
            .split-left {
                display: none; /* Sembunyikan gambar pada mobile agar fokus ke form */
            }
            .split-right {
                flex: 1;
                width: 100%;
                padding: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="split-container">
        
        <!-- Left Side: Branding & Background -->
        <div class="split-left">
            <div class="split-left-content">
                <h2>Selamat Datang di Rekam WeBe</h2>
                <p>Platform terintegrasi Yayasan WeBe untuk pengelolaan kegiatan yang sistematis, produktif, dan terarah.</p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="split-right d-flex align-items-center justify-content-center min-vh-100 w-100">
            <div style="max-width: 450px; width: 100%; padding: 2rem;">
                <!-- Logo -->
                <div class="text-center mb-4">
                    <img src="{{ asset('public/logo_webe.png') }}" alt="Logo WeBe" class="brand-logo" style="width: 80px;">
                </div>
                
                <!-- Welcome Text -->
                <div class="text-center mb-4">
                    <h2 class="login-title" style="font-size: 1.8rem; font-weight: 800; color: #1a1a1a; margin-bottom: 0.25rem;">Rekam WeBe</h2>
                    <p class="login-subtitle" style="font-size: 0.95rem; color: #6c757d; margin-bottom: 0; line-height: 1.5;">Sistem Informasi Manajemen Kegiatan<br>Yayasan WeBe</p>
                </div>

                <!-- Form -->
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    
                    <!-- Email Input -->
                    <div class="mb-3">
                        <div class="input-group-custom">
                            <input type="email" name="email" class="form-control form-control-custom @error('email') is-invalid @enderror" placeholder="Email Address" value="{{ old('email') }}" required autofocus autocomplete="off">
                            <i class="fas fa-user input-icon"></i>
                        </div>
                        @error('email')
                            <span class="text-error"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="mb-4">
                        <div class="input-group-custom">
                            <input type="password" name="password" id="passwordField" class="form-control form-control-custom @error('password') is-invalid @enderror" placeholder="Password" required>
                            <i class="fas fa-lock input-icon"></i>
                            <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                        </div>
                        @error('password')
                            <span class="text-error"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-login btn-block w-100">
                        <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                    </button>
                </form>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ url('public/adminlte') }}/plugins/jquery/jquery.min.js"></script>
    <script src="{{ url('public/adminlte') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toggle Password Visibility JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const passwordField = document.querySelector('#passwordField');

            togglePassword.addEventListener('click', function (e) {
                // Toggle the type attribute
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                
                // Toggle the eye / eye-slash icon
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>
</html>
