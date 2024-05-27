<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, follow" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Panel de control v2">
    <meta name="keywords" content="admin,dashboard">
    <meta name="author" content="Oscar Castellano">
    <title>{{ \App\Models\Setting::find(1)->title }} | {{ __('Veritificar Certificados') }}</title>
    <link href="{{ Vite::imagenes(\App\Models\Setting::find(1)->icon) }} " rel="icon" type="image/png">

    @include('layouts.panel.style')
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    @yield('css')
</head>

<body class="login-page">
    <div class='loader'>
        <div class='spinner-grow text-primary' role='status'>
            <span class='sr-only'>{{ __('Cargando') }}...</span>
        </div>
    </div>
    <div class="container">
        @livewire('invitado.search-livewire')
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="{{ asset('js/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="{{ asset('js/main.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/perfect-scrollbar.min.js') }}"></script>
    @livewireScripts

    @yield('jss')

</body>

</html>
