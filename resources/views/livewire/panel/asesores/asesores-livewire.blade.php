<div wire:init="loadData">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Asesores') }}
                        @can('asesor.store')
                            <button data-bs-toggle="modal" data-bs-target="#createNewItem" class="btn btn-primary ml-2"
                                type="button" wire:click="clean()"><i class="fas fa-plus"></i></button>
                        @endcan
                    </h5>
                    <div class="row col-12 mb-4">
                        <div class="col-lg-3 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar asesor') }}</span>
                            <input type="search" class="form-control @error('search') is-invalid @enderror"
                                placeholder="{{ __('Buscar por nombre, documento, correo y telefono') }}"
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
                        <div class="col-lg-2 col-md-4 col-12 mb-2">
                            <span>{{ __('Descargar reporte') }}</span> <br>
                            <button data-bs-toggle="modal" data-bs-target="#exportItem" type="button"
                                wire:click="clean()" class="btn btn-outline-primary btn-block w-100"><i
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
                                    <th scope="col">{{ __('Documento') }}</th>
                                    <th scope="col">{{ __('Nombre') }}</th>
                                    <th scope="col">{{ __('Telefono') }}</th>
                                    <th scope="col">{{ __('Correo') }}</th>
                                    <th scope="col">{{ __('Estado') }}</th>
                                    @can(['asesor.edit', 'asesor.delete'])
                                        <th scope="col">{{ __('Accion') }}</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($this->Asesores as $item)
                                    <tr>
                                        <th scope="row">#{{ $item->id }}</th>
                                        <td>{{ $item->type_document . ' ' . $item->document }}</td>
                                        <td>{{ $item->name . ' ' . $item->last_name }}</td>
                                        <td>{{ $item->phone }}</td>
                                        <td>{{ $item->email }}</td>

                                        <td>
                                            @if ($item->status == 1)
                                                <span class="badge bg-success">{{ __('Activo') }}</span>
                                            @elseif($item->status == 0)
                                                <span class="badge bg-secondary">{{ __('Desactivado') }}</span>
                                            @endif
                                        </td>
                                        @can(['asesor.edit', 'asesor.delete'])
                                            <td>
                                                <div class="dropdown dropstart">
                                                    <button class="btn btn-primary dropdown-toggle" type="button"
                                                        wire:key="dropdownd-{{ $item->id }}" id="dropdownMenuButton"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        @can('asesor.edit')
                                                            <li><button class="dropdown-item"
                                                                    wire:click="edit({{ $item->id }})"> <i
                                                                        class="fas fa-edit"></i>
                                                                    {{ __('Editar') }}</button>
                                                            </li>
                                                            <li><button class="dropdown-item"
                                                                    wire:click="changestatus({{ $item->id }})"> <i
                                                                        class="fas fa-eye{{ $item->status == 1 ? '-slash' : '' }} "></i>
                                                                    {{ $item->status == 1 ? __('Desactivar') : __('Activar') }}</button>
                                                            </li>
                                                        @endcan

                                                        @can('asesor.delete')
                                                            <li><button class="dropdown-item"
                                                                    wire:click="borrar({{ $item->id }})"> <i
                                                                        class="fas fa-trash-alt"></i>
                                                                    {{ __('Borrar') }}</button></li>
                                                        @endcan
                                                    </ul>
                                                </div>
                                            </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        @can(['asesor.edit', 'asesor.delete'])
                                            <td colspan="7" class="text-center justify-content-center">
                                                ¡{{ __('No hay asesores disponible') }}!
                                            </td>
                                        @else
                                            <td colspan="6" class="text-center justify-content-center">
                                                ¡{{ __('No hay asesores disponible') }}!
                                            </td>
                                        @endcan

                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if (count($this->Asesores) > 0)
                        <div class="row text-center justify-content-center mt-2" style="max-width: 99%">
                            {{ $this->Asesores->onEachSide(1)->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('panel.asesores.modal.create')
    @include('panel.asesores.modal.edit')
    @include('panel.asesores.modal.export')

    <script>
        window.addEventListener('errores', event => {
            Swal.fire(
                '¡Error!',
                event.detail.error,
                'error'
            )
        })
        window.addEventListener('sstoree', event => {
            $('#createNewItem').modal('hide');
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El item ha sido creado con éxito.',
                showConfirmButton: false,
                timer: 1500
            })
        })

        window.addEventListener('actualiizar', event => {
            $('#editItem').modal('hide');
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El item ha sido actualizado con éxito.',
                showConfirmButton: false,
                timer: 1500
            })
        })
        window.addEventListener('statuschanged', event => {
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El item ha cambiado de estado con éxito',
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

        window.addEventListener('edit2', event => {
            $('#editItem').modal('show');
        })
        window.addEventListener('cerrarExport', event => {
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
            $('#exportItem').modal('hide');
        })
    </script>
</div>
