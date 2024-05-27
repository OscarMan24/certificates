<div wire:init="loadData">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Todos los usuarios') }}
                        @can('users.store')
                            <button data-bs-toggle="modal" data-bs-target="#createuser" class="btn btn-primary ml-2"
                                type="button" wire:click="clean()"><i class="fas fa-plus"></i></button>
                        @endcan
                    </h5>
                    <div class="row col-12 mb-4">
                        <div class="col-lg-3 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar usuarios') }}</span>
                            <input type="search" class="form-control @error('search') is-invalid @enderror"
                                placeholder="{{ __('Buscar por') . ' nombre, apellido, correo o telefono' }}"
                                wire:model="search">
                            @error('search')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>

                        <div class="col-lg-3 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar por estado') }}</span>
                            <select class="form-control @error('search_status') is-invalid @enderror"
                                wire:model="search_status">
                                <option value="" selected>{{ __('Selecciona una opcion') }}</option>
                                <option value="1">{{ __('Activos') }}</option>
                                <option value="0">{{ __('Desactivados') }}</option>
                            </select>
                            @error('search_status')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar por rol') }}</span>
                            <select class="form-control @error('search_rol') is-invalid @enderror"
                                wire:model="search_rol">
                                <option value="" selected>{{ __('Selecciona una opción') }}</option>
                                @foreach ($this->Roles as $rol)
                                    <option value="{{ $rol }}">{{ $rol }}</option>
                                @endforeach

                            </select>
                            @error('search_rol')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>
                        <div class="col-lg-2 col-md-4 col-12 mb-2">
                            <span>{{ __('Descargar reporte') }}</span> <br>
                            <button id="downloadReport" class="btn btn-outline-primary btn-block w-100"><i
                                    class="fas fa-file-export"></i></button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('Nombre') }}</th>
                                    <th scope="col">{{ __('Correo') }}</th>
                                    <th scope="col">{{ __('Roles') }}</th>
                                    <th scope="col">{{ __('Estado') }}</th>
                                    @can(['users.edit', 'user.delete'])
                                        <th scope="col">{{ __('Accion') }}</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($this->Users as $user)
                                    <tr>
                                        <th scope="row">#{{ $user->id }}</th>
                                        <td>
                                            <img src="{{ asset('/storage/users/' . $user->image) }}" alt="img-user"
                                                style="max-width: 50px; border-radius:50%">
                                            {{ $user->name }} {{ $user->last_name }}
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach ($user->getRoleNames() as $rol)
                                                <span class="badge bg-info">{{ $rol }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if ($user->status == 1)
                                                <span class="badge bg-success">{{ __('Activo') }}</span>
                                            @elseif($user->status == 0)
                                                <span class="badge bg-secondary">{{ __('Desactivado') }}</span>
                                            @endif
                                        </td>
                                        @can(['users.edit', 'users.delete'])
                                            <td>
                                                <div class="dropdown dropstart">
                                                    <button class="btn btn-primary dropdown-toggle" type="button"
                                                        wire:key="dropdownMenuButton-{{ $user->id }}"
                                                        id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        @can('users.edit')
                                                            <li><button class="dropdown-item"
                                                                    wire:click="edit({{ $user->id }})"> <i
                                                                        class="fas fa-edit"></i> {{ __('Editar') }}</button>
                                                            </li>

                                                            <li><button class="dropdown-item"
                                                                    wire:click="changestatus({{ $user->id }})"> <i
                                                                        class="fas fa-eye{{ $user->status == 1 ? '-slash' : '' }} "></i>
                                                                    {{ $user->status == 1 ? __('Desactivar') : __('Activar') }}</button>
                                                            </li>
                                                        @endcan

                                                        @can('users.delete')
                                                            <li>
                                                                <button class="dropdown-item"
                                                                    wire:click="borrar({{ $user->id }})">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                    {{ __('Borrar') }}
                                                                </button>
                                                            </li>
                                                        @endcan
                                                    </ul>
                                                </div>
                                            </td>
                                        @endcan

                                    </tr>
                                @empty
                                    <tr>
                                        @can(['users.edit', 'user.delete'])
                                            <td colspan="6" class="text-center justify-content-center">
                                                ¡{{ __('No hay usuarios disponibles') }}!
                                            </td>
                                        @else
                                            <td colspan="5" class="text-center justify-content-center">
                                                ¡{{ __('No hay usuarios disponibles') }}!
                                            </td>
                                        @endcan

                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                    @if (count($this->Users) > 0)
                        <div class="row text-center justify-content-center mt-2" style="max-width: 99%">
                            {{ $this->Users->onEachSide(1)->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if ($readytoload)
        @include('panel.usuarios.modal.create')
        @include('panel.usuarios.modal.edit')
    @endif


    <script>
        window.addEventListener('errores', event => {
            Swal.fire(
                '¡Error!',
                event.detail.error,
                'error'
            )
        })
        window.addEventListener('storeuser', event => {
            $('#createuser').modal('hide');
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El elemento ha sido creado con éxito.',
                showConfirmButton: false,
                timer: 1500
            })
        })

        window.addEventListener('openEdit', event => {
            $('#editUserr').modal('show');
        })

        window.addEventListener('updateusser', event => {
            $('#editUserr').modal('hide');
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El elemnto ha sido actualizado con éxito.',
                showConfirmButton: false,
                timer: 1500
            })
        })
        window.addEventListener('borrar', event => {
            Swal.fire({
                icon: 'question',
                title: "¿Estas seguro?",
                text: "Esta acción no se puede devolver.",
                showCancelButton: true,
            }).then((result) => {
                if (result.value) {
                    window.livewire.emit('borrado')
                    let timerInterval
                    Swal.fire({
                        icon: 'success',
                        title: '¡Procesando! ',
                        text: 'Espera un momento, pronto estará disponible',
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    })
                }
            });
        });
        window.addEventListener('statuschanged', event => {
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El elemento ha cambiado de estado con éxito',
                showConfirmButton: false,
                timer: 1500
            })
        })
    </script>
    <script>
        const downloadReport = document.getElementById("downloadReport");
        downloadReport.addEventListener('click', event => {
            Swal.fire({
                title: '¿Que reporte desea descargar?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Todos',
                denyButtonText: `Activos`,
                cancelButtonText: 'Desactivados'
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    window.livewire.emit('downloadReportes', 1)
                    let timerInterval
                    Swal.fire({
                        icon: 'success',
                        title: '¡Procesando! ',
                        text: 'Espera un momento, pronto estará disponible',
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    })
                } else if (result.isDenied) {
                    window.livewire.emit('downloadReportes', 2)
                    let timerInterval
                    Swal.fire({
                        icon: 'success',
                        title: '¡Procesando! ',
                        text: 'Espera un momento, pronto estará disponible',
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    })
                } else if (result.isDismissed) {
                    window.livewire.emit('downloadReportes', 3)
                    let timerInterval
                    Swal.fire({
                        icon: 'success',
                        title: '¡Procesando! ',
                        text: 'Espera un momento, pronto estará disponible',
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    })
                }
            })
        })
    </script>
</div>
