<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UPA-CLUSTER</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/auth/images/logos/') }}" />
    <link rel="stylesheet" href="{{ asset('assets/auth/css/styles.min.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
    


</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        @include('layouts.sidebar')
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            @include('layouts.header')
            <!--  Header End -->
            <div class="container-fluid">
                <!--  Row 1 -->
                @yield('content')
            </div>
        </div>
        <script src="{{ asset('assets/auth/libs/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/auth/js/sidebarmenu.js') }}"></script>
        <script src="{{ asset('assets/auth/js/app.min.js') }}"></script>
        <script src="{{ asset('assets/auth/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
        <script src="{{ asset('assets/auth/libs/simplebar/dist/simplebar.js') }}"></script>
        <script src="{{ asset('assets/auth/js/dashboard.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
</body>

</html>
