<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekam WeBe</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/public/logo_webe.png">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ url('public/adminlte') }}/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('public/adminlte') }}/dist/css/adminlte.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ url('public/adminlte') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="{{ url('public/adminlte') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet"
        href="{{ url('public/adminlte') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ url('public/adminlte') }}/plugins/summernote/summernote-bs4.min.css">
    @stack('styles')
    @stack('css')
    @stack('js')
    <style>
        /* Custom CSS untuk page-link navy */
        .page-link {
            color: #001f3f !important;
            background-color: transparent !important;
            border: 1px solid #dee2e6 !important;
        }
 
        .page-link:hover {
            color: #001f3f !important;
            background-color: transparent !important;
            border-color: #001f3f !important;
        }
 
        .page-item.active .page-link {
            color: #ffffff !important;
            background-color: #001f3f !important;
            border-color: #001f3f !important;
        }
 
        .page-item.disabled .page-link {
            color: #6c757d !important;
            background-color: transparent !important;
            border-color: #dee2e6 !important;
        }
 
        /* Custom CSS untuk bg-navy */
        .bg-navy {
            background-color: #001f3f !important;
            color: #ffffff !important;
        }
 
        .bg-navy th {
            color: #ffffff !important;
        }

        /* Highlight active sidebar item */
        .nav-sidebar > .nav-item > .nav-link.active {
            background-color: rgba(255, 255, 255, 0.12) !important;
            border-left: 3px solid rgba(255, 255, 255, 0.8);
            font-weight: 600;
            color: #ffffff !important;
        }
 
        /* Pastikan btn-secondary selalu berteks putih */
        .btn-secondary, 
        .btn-secondary:hover, 
        .btn-secondary i, 
        a.btn-secondary, 
        a.btn-secondary:hover {
            color: #ffffff !important;
        }
        
        /* UI/UX Enhancements */
        .hover-card, .small-box {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-card:hover, .small-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }

        /* Page load fade-in animation */
        .content-wrapper {
            animation: fadeIn 0.4s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Empty State */
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 0;
        }
        
        /* Button Loading State */
        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.8;
        }
        .btn-loading .spinner-border {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }

        /* Fix Tooltip and UI Tooltip sizing globally */
        .tooltip, .ui-tooltip {
            font-size: 0.75rem !important;
            z-index: 9999 !important;
            max-width: 280px !important;
        }
        .tooltip-inner, .ui-tooltip-content {
            max-width: 280px !important;
            font-size: 0.75rem !important;
            padding: 6px 10px !important;
            background-color: rgba(0, 0, 0, 0.9) !important;
            color: #fff !important;
            text-align: left !important;
            line-height: 1.4 !important;
            border-radius: 4px !important;
            border: none !important;
        }
        .tooltip-inner *, .ui-tooltip-content * {
            font-size: 0.75rem !important;
            line-height: 1.4 !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifications Dropdown -->
                <x-navbar-notification />
                
                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                        {{ ucwords(auth()->user()->name) }}
                    </button>
                    <div class="dropdown-menu">
                        <button type="button" class="btn" data-toggle="modal" data-target="#formGantiPassword">
                            Ganti Password
                        </button>
                        <form id="form-logout" action="{{ route('logout') }}" method="POST" class="d-none no-loader">
                            @csrf
                        </form>
                        <button type="button" class="btn text-danger btn-sm dropdown-item" style="cursor: pointer;" onclick="confirmLogout()">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </div>
                </div>
            </ul>
        </nav>
        <!-- /.navbar -->

        <x-user.form-ganti-password />

        <!-- Main Sidebar Container -->
        <x-admin.aside />

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-6">
                            <h4 class="m-0 font-weight-bold text-dark">@yield('content_title')</h4>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right text-sm mb-0">
                                <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                                <li class="breadcrumb-item active">@yield('content_title')</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline d-flex">
                <small><strong>Yayasan WeBe</strong></small>
            </div>
            <!-- Default to the left -->
            <small>&copy; 2025-2026 <strong>Rekam WeBe</strong>. All rights reserved.</small>
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{ url('public/adminlte') }}/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('public/adminlte') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('public/adminlte') }}/dist/js/adminlte.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ url('public/adminlte') }}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('public/adminlte') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ url('public/adminlte') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ url('public/adminlte') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ url('public/adminlte') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ url('public/adminlte') }}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ url('public/adminlte') }}/plugins/jszip/jszip.min.js"></script>
    <script src="{{ url('public/adminlte') }}/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="{{ url('public/adminlte') }}/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="{{ url('public/adminlte') }}/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ url('public/adminlte') }}/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="{{ url('public/adminlte') }}/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- Summernote -->
    <script src="{{ url('public/adminlte') }}/plugins/summernote/summernote-bs4.min.js"></script>
    <script>
        $(function() {
            // Summernote
            $('#summernote').summernote({
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']]
                ]
            })

            // CodeMirror
            // CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
            //     mode: "htmlmixed",
            //     theme: "monokai"
            // });
        })
    </script>
    <script>
        $(function() {
            $("#table1").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "buttons": ["print"],
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "emptyTable": "Tidak ada data tersedia",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    },
                    "aria": {
                        "sortAscending": ": aktifkan untuk mengurutkan kolom secara ascending",
                        "sortDescending": ": aktifkan untuk mengurutkan kolom secara descending"
                    },
                    "buttons": {
                        "print": "Cetak"
                    }
                }
            }).buttons().container().appendTo('#table1_wrapper .col-md-6:eq(0)');
            $('#table2').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "emptyTable": "Tidak ada data tersedia",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    },
                    "aria": {
                        "sortAscending": ": aktifkan untuk mengurutkan kolom secara ascending",
                        "sortDescending": ": aktifkan untuk mengurutkan kolom secara descending"
                    }
                }
            });
        });
    </script>
    @stack('scripts')
    <script src="{{ url('public/adminlte') }}/plugins/sweetalert2/sweetalert2.all.js"></script>
    @include('sweetalert::alert')
    
    <script>
        $(document).ready(function() {
            // SweetAlert2 Delete Confirmation
            $('form[data-confirm-delete="true"]').on('submit', function(e) {
                e.preventDefault();
                let $form = $(this);
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading before submitting natively
                        Swal.fire({
                            title: 'Menghapus Data...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        // Use native DOM submit after a small delay so Swal can render
                        setTimeout(() => {
                            $form[0].submit();
                        }, 200);
                    }
                });
            });

            // Global Loading Spinner
            $('form').not('.no-loader').on('submit', function(e) {
                // Ignore if it's a delete form waiting for confirmation
                if ($(this).attr('data-confirm-delete') === 'true') {
                    return;
                }
                
                // Ensure HTML5 validation passes before showing loader
                if (this.checkValidity && !this.checkValidity()) {
                    return;
                }
                
                let $form = $(this);
                // Disable submit buttons to prevent double click after a slight delay
                // so the browser can still send the clicked button's value
                setTimeout(function() {
                    $form.find('button[type="submit"], input[type="submit"]').prop('disabled', true);
                }, 50);
                
                Swal.fire({
                    title: 'Memproses Data...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        });

        function confirmLogout() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan keluar dari sistem.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Logout!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Logout...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    setTimeout(() => {
                        document.getElementById('form-logout').submit();
                    }, 100);
                }
            });
        }
    </script>
</body>

</html>
