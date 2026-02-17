<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'eCommerce Admin Panel')</title>
    <link rel="icon" href="{{ \App\Helpers\SettingsHelper::get('favicon_url', asset('favicon.ico')) }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ \App\Helpers\SettingsHelper::get('theme_color', '#4f46e5') }}',
                        indigo: {
                            50: '#eef2ff', // You might want to generate these dynamically or leave default
                            100: '#e0e7ff',
                            500: '{{ \App\Helpers\SettingsHelper::get('theme_color', '#6366f1') }}',
                            600: '{{ \App\Helpers\SettingsHelper::get('theme_color', '#4f46e5') }}',
                            700: '{{ \App\Helpers\SettingsHelper::get('theme_color', '#4338ca') }}',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tabulator CSS -->
    <link href="https://unpkg.com/tabulator-tables@5.5.2/dist/css/tabulator.min.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/admin/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/datatable.css') }}">


    <script>
        // Global variables
        const BASE_URL = '{{ url('/') }}';
        const ADMIN_URL = '{{ url('/admin') }}';
        const ASSET_URL = '{{ asset('') }}';
    </script>
</head>

<body class="bg-gray-50">
    @if (request()->is('admin/*') && !request()->is('admin/login'))
        @include('admin.partials.sidebar')
    @endif

    <div id="main-content" class="transition-all duration-300 @if (request()->is('admin/*') && !request()->is('admin/login')) ml-0 md:ml-24 @endif">
        @if (request()->is('admin/*') && !request()->is('admin/login'))
            @include('admin.partials.header')
        @endif

        <main class="@if (request()->is('admin/*') && !request()->is('admin/login')) p-4 sm:p-6 md:p-8 @endif">
            @yield('content')
        </main>
    </div>

    <!-- Common Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Tabulator JS -->
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>

    <!-- SheetJS library for Excel export (required by Tabulator) -->
    <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="{{ asset('js/admin/custom.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.5.0/axios.min.js"></script>
    <script>
        // Global Axios Configuration
        if (typeof axios !== 'undefined') {
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
            }
        }
    </script>




    @if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            toastr.success("{{ session('success') }}");
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            toastr.error("{{ session('error') }}");
        });
    </script>
    @endif

    @stack('scripts')
</body>

</html>
