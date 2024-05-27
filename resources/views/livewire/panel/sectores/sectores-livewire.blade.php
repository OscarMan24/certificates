<div wire:init="loadDatos">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Todos los sectores') }}
                        @can('sectores.store')
                            <button data-bs-toggle="modal" data-bs-target="#createSector" class="btn btn-primary ml-2"
                                type="button" wire:click="clean()"><i class="fas fa-plus"></i></button>
                        @endcan
                    </h5>
                    <div class="row col-12 mb-4">
                        <div class="col-lg-3 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar sector') }}</span>
                            <input type="search" class="form-control @error('search') is-invalid @enderror"
                                placeholder="{{ __('Buscar sector por nombre') }}" wire:model="search">
                            @error('search')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar por estado') }}</span>
                            <select class="form-control @error('searchStatus') is-invalid @enderror"
                                wire:model="searchStatus">
                                <option value="" selected>{{ __('Selecciona una opcion') }}</option>
                                <option value="1">{{ __('Activos') }}</option>
                                <option value="0">{{ __('Desactivados') }}</option>
                            </select>
                            @error('searchStatus')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
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
                                    <th scope="col">{{ __('Estado') }}</th>
                                    @can(['sectores.edit', 'sectores.delete'])
                                        <th scope="col">{{ __('Accion') }}</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($this->Sectores as $sector)
                                    <tr>
                                        <th scope="row">#{{ $sector->id }}</th>
                                        <td>{{ $sector->name }}</td>
                                        <td>
                                            @if ($sector->status == 1)
                                                <span class="badge bg-success">{{ __('Activo') }}</span>
                                            @elseif($sector->status == 0)
                                                <span class="badge bg-secondary">{{ __('Desactivado') }}</span>
                                            @endif
                                        </td>

                                        @can(['sectores.edit', 'sectores.delete'])
                                            <td>
                                                @if ($sector->name != 'Superadmin' || Auth::user()->hasRole('Superadmin'))
                                                    <div class="dropdown dropstart">
                                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                                            wire:key="dropdownd-{{ $sector->id }}"
                                                            id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            @can('sectores.edit')
                                                                <li><button class="dropdown-item"
                                                                        wire:click="edit({{ $sector->id }})"> <i
                                                                            class="fas fa-edit"></i>
                                                                        {{ __('Editar') }}</button>
                                                                </li>
                                                                <li>
                                                                    <button class="dropdown-item" wire:click="changestatus({{ $sector->id }})"> 
                                                                        <i class="fas fa-eye{{ $sector->status == 1 ? '-slash' : '' }} "></i>
                                                                        {{ $sector->status == 1 ? __('Desactivar') : __('Activar') }}
                                                                    </button>
                                                                </li>
                                                            @endcan

                                                            @can('sectores.delete')
                                                                <li><button class="dropdown-item"
                                                                        wire:click="borrar({{ $sector->id }})"> <i
                                                                            class="fas fa-trash-alt"></i>
                                                                        {{ __('Borrar') }}</button></li>
                                                            @endcan
                                                        </ul>
                                                    </div>
                                                @endif

                                            </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        @can(['sectores.edit', 'sectores.delete'])
                                            <td colspan="4" class="text-center justify-content-center">
                                                ¡{{ __('No hay sectores disponible') }}!
                                            </td>
                                        @else
                                            <td colspan="3" class="text-center justify-content-center">
                                                ¡{{ __('No hay sectores disponible') }}!
                                            </td>
                                        @endcan

                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                    @if (count($this->Sectores) > 0)
                        <div class="row text-center justify-content-center mt-2" style="max-width: 99%">
                            {{ $this->Sectores->onEachSide(1)->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @include('panel.sectores.modal.create')
    @include('panel.sectores.modal.edit')

    <script>
         window.addEventListener('storeSector', event => {
            $('#createSector').modal('hide');
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El item ha sido creado con éxito.',
                showConfirmButton: false,
                timer: 1500
            })
        })
        window.addEventListener('errores', event => {
            Swal.fire(
                '¡Error!',
                event.detail.error,
                'error'
            )
        })
         window.addEventListener('openEdit', event => {
            $('#editSectores').modal('show');
        })
        window.addEventListener('updateSector', event => {
            $('#editSectores').modal('hide');
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El item ha sido actualizado con éxito.',
                showConfirmButton: false,
                timer: 1500
            })
        })
        window.addEventListener('openBorrar', event => {
            Swal.fire({
                icon: 'question',
                title: "¿Estas seguro?",
                text: "Esta acción no se puede devolver.",
                showCancelButton: true,
            }).then((result) => {
                if (result.value) {
                    window.livewire.emit('deleteSector')
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
        window.addEventListener('statusChanged', event => {
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El item ha cambiado de estado con éxito',
                showConfirmButton: false,
                timer: 1500
            })
        })
    </script>
</div>
