<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar | Rekam WeBe</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/public/logo_webe.png">

    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/public/adminlte/plugins/fontawesome-free/css/all.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="/public/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="/public/adminlte/dist/css/adminlte.min.css">

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
            background: url('/public/adminlte/dist/img/background_login1.jpg') no-repeat center center;
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
        }

        .split-left-content h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .split-left-content p {
            font-size: 1.1rem;
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
            padding: 2.5rem;
            box-shadow: -10px 0 30px rgba(0,0,0,0.05);
            z-index: 2;
        }

        .brand-logo {
            width: 70px;
            margin-bottom: 1rem;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        }

        .register-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 0.25rem;
        }

        .register-subtitle {
            font-size: 0.95rem;
            color: #6c757d;
            margin-bottom: 1.5rem;
        }

        /* Custom Inputs */
        .input-group-custom {
            position: relative;
        }

        .form-control-custom {
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            font-size: 0.95rem;
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

        .btn-register {
            background-color: #001f3f;
            color: white;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 31, 63, 0.15);
            width: 100%;
            border: none;
        }

        .btn-register:hover {
            background-color: #000814;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 31, 63, 0.25);
        }

        .text-error {
            font-size: 0.85rem;
            color: #dc3545;
            margin-top: 0.25rem;
            display: block;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

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
                display: none;
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

        <!-- Right Side: Register Form -->
        <div class="split-right d-flex align-items-center justify-content-center min-vh-100 w-100">
            <div style="max-width: 450px; width: 100%;">
                <!-- Logo -->
                <div class="text-center mb-3">
                    <img src="/public/logo_webe.png" alt="Logo WeBe" class="brand-logo" style="width: 70px;">
                </div>
                
                <!-- Welcome Text -->
                <div class="text-center mb-3">
                    <h2 class="register-title">Daftar Akun</h2>
                    <p class="register-subtitle">Buat akun baru untuk mengakses Rekam WeBe</p>
                </div>

                <!-- Form -->
                <form action="{{ route('register') }}" method="post">
                    @csrf
                    
                    <!-- Nama Lengkap -->
                    <div class="mb-3">
                        <div class="input-group-custom">
                            <input type="text" name="name" class="form-control form-control-custom @error('name') is-invalid @enderror" placeholder="Nama Lengkap" value="{{ old('name') }}" required autofocus>
                            <i class="fas fa-user input-icon"></i>
                        </div>
                        @error('name')
                            <span class="text-error"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email Input -->
                    <div class="mb-3">
                        <div class="input-group-custom">
                            <input type="email" name="email" class="form-control form-control-custom @error('email') is-invalid @enderror" placeholder="Alamat Email" value="{{ old('email') }}" required autocomplete="off">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                        @error('email')
                            <span class="text-error"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="mb-3">
                        <div class="input-group-custom">
                            <input type="password" name="password" class="form-control form-control-custom @error('password') is-invalid @enderror" placeholder="Password (min. 8 karakter)" required>
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                        @error('password')
                            <span class="text-error"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password Input -->
                    <div class="mb-4">
                        <div class="input-group-custom">
                            <input type="password" name="password_confirmation" class="form-control form-control-custom" placeholder="Konfirmasi Password" required>
                            <i class="fas fa-check-circle input-icon"></i>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-register btn-block w-100 mb-3">
                        <i class="fas fa-user-plus mr-2"></i> Daftar
                    </button>

                    <div class="text-center">
                        <span class="text-muted">Sudah punya akun?</span>
                        <a href="{{ route('login') }}" class="font-weight-bold ml-1" style="color: #001f3f;">Masuk</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/public/adminlte/plugins/jquery/jquery.min.js"></script>
    <script src="/public/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
