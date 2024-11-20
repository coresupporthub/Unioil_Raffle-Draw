<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title> {{ $title }} </title>

    <link rel="icon" type="image/x-icon" href="/unioil_images/unioil.png">
    <link href="{{ asset('./dist/css/tabler.min.css?1692870487') }}" rel="stylesheet" />
    <link href="{{ asset('./dist/css/tabler-flags.min.css?1692870487') }}" rel="stylesheet" />
    <link href="{{ asset('./dist/css/tabler-payments.min.css?1692870487') }}" rel="stylesheet" />
    <link href="{{ asset('./dist/css/tabler-vendors.min.css?1692870487') }}" rel="stylesheet" />
    <link href="{{ asset('./dist/css/demo.min.css?1692870487') }}" rel="stylesheet" />
    <link rel="stylesheet" href="/css/app.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>

    <!-- Alertify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <!-- Alertify Theme CSS (optional, for custom themes) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
    <!-- Alertify JS -->
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <!-- jQuery (required for DataTables) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">

    <script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }

        .Unioil-header {
            background: linear-gradient(135deg, #ff6f00, #d43f00);
            color: white;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .unioil-info {
            background-color: #0052cc;
            color: white;

        }

        .dt-button:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-around;
            position: relative;
            overflow: hidden;
            padding: 10px 20px;
        }

        .nav-item {
            position: relative;
            margin: 0 10px;
            cursor: pointer;
            transition: transform 0.3s ease, background 0.4s ease;
        }

        .nav-item:hover {
            transform: scale(1.1);
        }

        .nav-item .nav-link {
            text-decoration: none;
            color: #333;
            padding: 5px 10px;
            display: flex;
            align-items: center;
            transition: color 0.3s ease;
        }

        .nav-item .nav-link-icon svg {
            margin-right: 5px;
            transition: fill 0.3s ease, stroke 0.3s ease;
        }

        .nav-item.active .nav-link {
            color: white;
        }

        .nav-item.active .nav-link-icon svg {
            margin-right: 5px;
            transition: stroke 0.3s ease;
            stroke: #ffffff;
        }

        .nav-item.active {
            background: linear-gradient(135deg, #ff7c00, #f36f6f);
            border-radius: 5px;
        }

        .nav-item.active:hover {
            background: linear-gradient(135deg, #f36f6f, #ff7c00);
        }

        .navbar::after {
            content: '';
            position: absolute;
            bottom: 0;
            height: 3px;
            background-color: #ff6600;
            width: 0;
            transition: width 0.4s ease, transform 0.4s ease;
        }

        .nav-item.active~.navbar::after {
            width: 100px;
            /* Adjust based on nav item width */
            transform: translateX(calc(100px * var(--nav-index)));
            /* Slide smoothly */
        }

        /* CSS variables for index */
        .nav-item:nth-child(1) {
            --nav-index: 0;
        }

        .nav-item:nth-child(2) {
            --nav-index: 1;
        }

        .nav-item:nth-child(3) {
            --nav-index: 2;
        }

        .nav-item:nth-child(4) {
            --nav-index: 3;
        }

        .nav-item:nth-child(5) {
            --nav-index: 4;
        }
    </style>
</head>
