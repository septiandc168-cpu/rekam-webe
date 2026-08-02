<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kegiatan</title>
    <style>
        @yield('print-styles')

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            margin: 0;
            padding: 20px;
            background: white;
            color: black;
        }

        .container-fluid {
            max-width: 100%;
        }

        .text-primary {
            color: #007bff;
        }

        .text-success {
            color: #28a745;
        }

        .text-warning {
            color: #ffc107;
        }

        .text-info {
            color: #17a2b8;
        }

        .text-secondary {
            color: #6c757d;
        }

        .text-muted {
            color: #6c757d;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }

        .bg-primary {
            background-color: #007bff;
            color: white;
        }

        .bg-success {
            background-color: #28a745;
            color: white;
        }

        .bg-warning {
            background-color: #ffc107;
            color: black;
        }

        .bg-info {
            background-color: #17a2b8;
            color: white;
        }

        .bg-secondary {
            background-color: #6c757d;
            color: white;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: none;
            margin-bottom: 20px;
        }

        .card-header {
            padding: 15px;
            border-bottom: none;
            border-radius: 8px 8px 0 0;
        }

        .card-body {
            padding: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }

        .table td {
            border: none;
            padding: 8px;
            vertical-align: top;
        }

        .table-borderless td {
            border: none !important;
            padding: 4px 8px;
        }

        .table-borderless td:first-child {
            font-weight: normal;
            width: 150px;
        }

        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-top: 0;
            margin-bottom: 15px;
        }

        h2 {
            font-size: 24px;
        }

        h3 {
            font-size: 20px;
        }

        h4 {
            font-size: 18px;
        }

        h5 {
            font-size: 16px;
        }

        h6 {
            font-size: 14px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }

        .col-12 {
            flex: 0 0 100%;
            padding: 0 15px;
        }

        .col-md-6 {
            flex: 0 0 50%;
            padding: 0 15px;
        }

        .col-md-4 {
            flex: 0 0 33.333%;
            padding: 0 15px;
        }

        .text-center {
            text-align: center;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mb-3 {
            margin-bottom: 15px;
        }

        .mb-4 {
            margin-bottom: 20px;
        }

        .mb-5 {
            margin-bottom: 25px;
        }

        .mt-3 {
            margin-top: 15px;
        }

        .mt-5 {
            margin-top: 25px;
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }

        .rounded {
            border-radius: 5px;
        }

        .border {
            border: 1px solid #ddd;
        }

        .img-fluid {
            max-width: 100%;
            height: auto;
        }

        .d-flex {
            display: flex;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        .gap-2 {
            gap: 8px;
        }
    </style>
</head>

<body>
    @yield('content')
</body>

</html>
