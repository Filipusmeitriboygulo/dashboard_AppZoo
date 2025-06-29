<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'UPA-CLUSTER')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/auth/images/logos/logo.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/auth/css/styles.min.css') }}" />

    @stack('styles')
</head>

<body>
    <!-- Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <!-- Sidebar Start -->
        @include('layouts.sidebar')
        <!-- Sidebar End -->

        <!-- Main wrapper -->
        <div class="body-wrapper">
            <!-- Header Start -->
            @include('layouts.header')
            <!-- Header End -->

            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Core Scripts -->
    <script src="{{ asset('assets/auth/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Dashboard & Layout Scripts -->
    <script src="{{ asset('assets/auth/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('assets/auth/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/auth/libs/simplebar/dist/simplebar.js') }}"></script>
    <script src="{{ asset('assets/auth/js/dashboard.js') }}"></script>

    <!-- Chart Library (optional) -->
    {{-- <script src="{{ asset('assets/auth/libs/apexcharts/dist/apexcharts.min.js') }}"></script> --}}
    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>

    <!-- DATATABLES + BUTTONS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>


    <!-- Custom Scripts -->
    @stack('scripts')
</body>

</html>
