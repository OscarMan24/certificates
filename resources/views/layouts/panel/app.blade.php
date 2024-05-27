<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, follow" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ \App\Models\Setting::find(1)->description }}">
    <meta name="keywords" content="admin,dashboard">
    <meta name="author" content="Oscar Castellano">
    <title>{{ $title }} | {{ \App\Models\Setting::find(1)->title }} | {{ __('Panel de control') }}</title>
    <link href="{{ Vite::imagenes(\App\Models\Setting::find(1)->icon) }} " rel="icon" type="image/png">

    @include('layouts.panel.style')
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    @yield('css')
</head>

<body>
    <div class='loader'>
        <div class='spinner-grow' role='status'>
            <span class='sr-only'>{{ __('Cargando') }}...</span>
        </div>
    </div>
    <div class="page-container">
        @include('layouts.panel.sidebarheader')
        @include('layouts.panel.sidebar')
        <div class="page-content">
            <div class="main-wrapper">
                @yield('contenido')
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="{{ asset('js/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="{{ asset('js/main.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/blazy.min.js') }}"></script>
    @livewireScripts
    @yield('jss')
    <script>
        ;
        (function() {
            // Initialize
            var bLazy = new Blazy();
        })();
    </script>
    @yield('scripts')
</body>

</html>
