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
    <title>{{ \App\Models\Setting::find(1)->title }} | {{ __('Panel de control') }}</title>
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
        <div class="row justify-content-md-center">
            <div class="col-md-12 col-lg-4">
                <div class="card login-box-container">
                    <div class="card-body">
                        <div class="authent-logo">
                            <img src=" {{ Vite::imagenes(\App\Models\Setting::find(1)->logo) }} " alt="">
                        </div>
                        <div class="authent-text">
                            <p>{{ __('Bienvenido a') . ' ' . \App\Models\Setting::find(1)->title }} </p>
                            <p>{{ __('Inicia sesión en su cuenta') }}.</p>
                        </div>

                        <form action="{{ route('iniciar.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('usuario') is-invalid @enderror"
                                        id="usuario" name="usuario" placeholder="{{ __('Usuario') }}"
                                        value="{{ old('usuario') }}" required>
                                    <label for="usuario">{{ __('Usuario') }}</label>
                                    @error('usuario')
                                        <div class="invalid-feedback ">{{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="{{ __('Tu contraseña') }}"
                                        value="{{ old('contraseña') }}" required>
                                    <label for="password">{{ __('Contraseña') }}</label>
                                    @error('password')
                                        <div class="invalid-feedback ">{{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">{{ __('Recuedame') }}</label>
                            </div>
                            <div class="d-grid">
                                <button type="submit"
                                    class="btn btn-primary m-b-xs">{{ __('Iniciar Sesion') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
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
</body>

</html>
