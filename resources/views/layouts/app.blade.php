<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <link rel="icon" href="{{ asset('assets/img/logo-perusahaan.png') }}" type="image/x-icon" />

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    {{-- Fonts and icons --}}
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <style>
        /* Perkecil tinggi baris table DataTables */
        table.dataTable tbody tr {
            height: 10px !important;
        }

        table.dataTable tbody td,
        table.dataTable thead th {
            padding: 4px 28px !important;
            font-size: 13px;
            line-height: 1.2;
        }

        table.dataTable .btn {
            padding: 7px 14px;
            font-size: 12px;
            line-height: 1;
        }

        table.dataTable td {
            white-space: nowrap;
        }
    </style>

    @stack('styles')

    {{-- Custom Theme CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}"> --}}
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    {{-- Tambahan CSS dari child view --}}
    @stack('styles')
</head>

<body>

    @php
        $role = Auth::user()->role;
        $dashboardRoute = route('dashboard');
    @endphp

    <div class="wrapper">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')
        {{-- END OF SIDE BAR --}}

        <div class="main-panel">
            {{-- <div class="main-header"> --}}
            <!-- Navbar Header -->
            <!-- End Navbar -->
            {{-- </div> --}}
            <div class="main-header">
                @include('layouts.partials.navbar')
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">

                    </div>
                </div>
            </div>
            @yield('content')

            @include('layouts.partials.footer')
        </div>
    </div>


    {{-- Core JS --}}
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

    {{-- Plugin JS --}}
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/chart-circle/circles.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/world.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Kaiadmin JS --}}
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/setting-demo.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Tambahan JS dari child view --}}
    @stack('scripts')
</body>

</html>
