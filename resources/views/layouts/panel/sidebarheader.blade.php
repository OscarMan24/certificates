<div class="page-header">
    <nav class="navbar navbar-expand-lg d-flex justify-content-between">
        <div class="" id="navbarNav">
            <ul class="navbar-nav" id="leftNav">
                <li class="nav-item">
                    <a class="nav-link" id="sidebar-toggle" href="#">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </li>
                <li class="nav-item" style="padding: 5px 0;">
                    <button class="darkModeSwitch" id="switch">
                        <span><i class="fas fa-sun"></i></span>
                        <span><i class="fas fa-moon"></i></span>
                    </button>
                </li>
            </ul>
        </div>
        <div class="logo">
            @desktop
                <a class="navbar-brand"
                    style="background: url({{ Vite::imagenes(\App\Models\Setting::find(1)->logo) }}) center center no-repeat;background-size: cover;"
                    href="#"></a>
                <a class="navbar-brand dark"
                    style="background: url({{ Vite::imagenes(\App\Models\Setting::find(1)->logo_dark) }}) center center no-repeat;background-size: cover;"
                    href="#"></a>
            @elsedesktop
                <a class="navbar-brand"
                    style="background: url({{ Vite::imagenes(\App\Models\Setting::find(1)->logo) }}) center center no-repeat;background-size: cover;"
                    href="#"></a>
                <a class="navbar-brand dark"
                    style="background: url({{ Vite::imagenes(\App\Models\Setting::find(1)->logo_dark) }}) center center no-repeat;background-size: cover;"
                    href="#"></a>
            @enddesktop
        </div>
        <div class="" id="headerNav">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link profile-dropdown" href="#" id="profileDropDown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img style="max-width: 60px; border-radius:50%"
                            src="{{ '/storage/users/' . Auth::user()->image }}" alt=""></a>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-drop-menu" aria-labelledby="profileDropDown">
                        <a class="dropdown-item"
                            href="{{ route('logout') }}"onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i data-feather="log-out"></i>{{ __('Cerrar Sesion') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="GET" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>
