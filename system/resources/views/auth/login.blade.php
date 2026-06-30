<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman Login</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('public/logo_webe.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('public/adminlte') }}/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ url('public/adminlte') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('public/adminlte') }}/dist/css/adminlte.min.css">

    <style>
        body.login-page {
            background: url('{{ asset('public/adminlte/dist/img/background_login3.jpg') }}') no-repeat center center fixed;
            background-size: cover;
        }

        /* Container pengganti flex AdminLTE */
        .login-wrapper {
            width: 100%;
            height: 100vh;

            display: flex;
            align-items: center;
            justify-content: flex-start;
            /* KE KIRI */
            padding-left: 80px;
        }

        /* Card login */
        .login-box {
            margin: 0;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
        }

        /* Mobile tetap center */
        @media (max-width: 768px) {
            .login-wrapper {
                justify-content: center;
                padding-left: 0;
            }
        }

        /* Custom navy button */
        .btn-navy {
            background-color: #001f3f !important;
            border-color: #001f3f !important;
            color: #ffffff !important;
        }

        .btn-navy:hover {
            background-color: #000814 !important;
            border-color: #000814 !important;
            color: #ffffff !important;
        }

        .btn-navy:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 31, 63, 0.25) !important;
        }
    </style>

</head>

<body class="hold-transition login-page">
    <div class="login-wrapper">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-navy">
                <div class="card-header text-center">
                    <div class="h1">
                        <b>Rekam WeBe</b><br>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>

                            <div class="input-group">
                                <input type="email" name="email" class="form-control" placeholder="Masukkan Email"
                                    autocomplete="off" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>

                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Masukkan Password" value="{{ old('password') }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="togglePassword" style="cursor:pointer">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid text-center mt-2 mb-3">
                            <button type="submit" class="btn btn-navy btn-block">Login</button>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.login-box -->
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');

            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
    <!-- jQuery -->
    <script src="{{ url('public/adminlte') }}/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('public/adminlte') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('public/adminlte') }}/dist/js/adminlte.min.js"></script>
</body>

</html>
