<div class="page-sidebar">
    <ul class="list-unstyled accordion-menu">
        <li class="sidebar-title">
            {{ __('Principal') }}
        </li>

        <li class="{{ request()->routeIs('index.dashboard') ? 'active-page' : '' }}">
            <a href="{{ route('index.dashboard') }}"><i class="fas fa-tasks"></i> &nbsp; {{ __('Dashboard') }}</a>
        </li>

        @can('aliado.index')
            <li class="{{ request()->routeIs('index.aliados') ? 'active-page' : '' }}">
                <a href="{{ route('index.aliados') }}"><i class="far fa-handshake"></i> &nbsp; {{ __('Aliados') }}</a>
            </li>
        @endcan
        @can('asesor.index')
            <li class="{{ request()->routeIs('index.asesores') ? 'active-page' : '' }}">
                <a href="{{ route('index.asesores') }}"><i class="fas fa-user-check"></i> &nbsp; {{ __('Asesores') }}</a>
            </li>
        @endcan

        @can('cliente.index')
            <li class="{{ request()->routeIs('index.clientes') ? 'active-page' : '' }}">
                <a href="{{ route('index.clientes') }}"><i class="fas fa-user-graduate"></i> &nbsp;
                    {{ __('Clientes') }}</a>
            </li>
        @endcan

        @can('instructor.index')
            <li class="{{ request()->routeIs('index.instructores') ? 'active-page' : '' }}">
                <a href="{{ route('index.instructores') }}"><i class="fas fa-chalkboard-teacher"></i> &nbsp;
                    {{ __('Instructores') }}</a>
            </li>
        @endcan

        @can('curso.index')
            <li class="{{ request()->routeIs('index.cursos') ? 'active-page' : '' }}">
                <a href="{{ route('index.cursos') }}"><i class="fas fa-graduation-cap"></i> &nbsp;
                    {{ __('Cursos') }}</a>
            </li>
        @endcan

        @can('certificado.index')
            <li class="{{ request()->routeIs('index.certificados') ? 'active-page' : '' }}">
                <a href="{{ route('index.certificados') }}"><i class="fas fa-chalkboard"></i> &nbsp;
                    {{ __('Certificados') }}</a>
            </li>
        @endcan        

        @can('reporte.index')
            <li class="{{ request()->routeIs('index.reportes') ? 'active-page' : '' }}">
                <a href="{{ route('index.reportes') }}"><i class="fas fa-clipboard"></i> &nbsp;
                    {{ __('Reportes') }}</a>
            </li>
        @endcan


        <li class="sidebar-title">
            {{ __('Configuracion') }}
        </li>

        @can('users.index')
            <li class="{{ request()->routeIs('index.usuarios') ? 'active-page' : '' }}">
                <a href="{{ route('index.usuarios') }}"><i class="fas fa-user-friends"></i> &nbsp; {{ __('Usuarios') }}</a>
            </li>
        @endcan

        @can('roles.index')
            <li class="{{ request()->routeIs('index.roles') ? 'active-page' : '' }}">
                <a href="{{ route('index.roles') }}"><i class="fas fa-user-shield"></i> &nbsp; {{ __('Roles') }}</a>
            </li>
        @endcan

        @can('tipo.documento.index')
            <li class="{{ request()->routeIs('index.tipos.documentos') ? 'active-page' : '' }}">
                <a href="{{ route('index.tipos.documentos') }}"><i class="fas fa-id-card"></i> &nbsp;
                    {{ __('Documentos identidad') }}</a>
            </li>
        @endcan

        @can('sectores.index')
            <li class="{{ request()->routeIs('index.sectores') ? 'active-page' : '' }}">
                <a href="{{ route('index.sectores') }}"><i class="fas fa-tractor"></i> &nbsp;
                    {{ __('Sectores') }}</a>
            </li>
        @endcan

        @can('representante.legal.index')
            <li class="{{ request()->routeIs('index.respresentantes.legales') ? 'active-page' : '' }}">
                <a href="{{ route('index.respresentantes.legales') }}"><i class="fas fa-user-tie"></i> &nbsp;
                    {{ __('Representantes Legales') }}</a>
            </li>
        @endcan

        @can('configuracion.index')
            <li class="{{ request()->routeIs('index.configuracion') ? 'active-page' : '' }}">
                <a href="{{ route('index.configuracion') }}"><i class="fas fa-sliders-h"></i> &nbsp; {{ __('Configuracion') }}</a>
            </li>
        @endcan

        @can('horario.index')
            <li class="{{ request()->routeIs('index.horarios') ? 'active-page' : '' }}">
                <a href="{{ route('index.horarios') }}"><i class="fas fa-stopwatch"></i> &nbsp;
                    {{ __('Horarios') }}</a>
            </li>
        @endcan
    </ul>
</div>
