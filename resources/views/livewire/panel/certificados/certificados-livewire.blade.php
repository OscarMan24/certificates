<div wire:init="loadData">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Certificados') }}
                        @can('certificado.store')
                            <button data-bs-toggle="modal" data-bs-target="#createNewItem" class="btn btn-primary ml-2"
                                type="button" wire:click="clean()"><i class="fas fa-plus"></i></button>
                        @endcan
                    </h5>
                    <div class="row col-12 mb-4">
                        <div class="col-lg-3 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar certificado') }}</span>
                            <input type="search" class="form-control @error('search') is-invalid @enderror"
                                placeholder="{{ __('Buscar por consecutivo') }}" wire:model="search">
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
                            <span>{{ __('Buscar por instructor') }}</span>
                            <select class="form-control @error('search_instructor') is-invalid @enderror"
                                wire:model="search_instructor">
                                <option value="" selected>{{ __('Selecciona una opcion') }}</option>
                                @foreach ($this->Instructores as $item)
                                    <option value="{{ $item->id }}">{{ $item->name . ' ' . $item->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('search_instructor')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>

                        <div class="col-lg-3 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar por aliado') }}</span>
                            <select class="form-control @error('search_aliado') is-invalid @enderror"
                                wire:model="search_aliado">
                                <option value="" selected>{{ __('Selecciona una opcion') }}</option>
                                @foreach ($this->Aliados as $item)
                                    <option value="{{ $item->id }}">{{ $item->name . ' ' . $item->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('search_aliado')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>

                        <div class="col-lg-3 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar por asesores') }}</span>
                            <select class="form-control @error('search_asesores') is-invalid @enderror"
                                wire:model="search_asesores">
                                <option value="" selected>{{ __('Selecciona una opcion') }}</option>
                                @foreach ($this->Asesores as $item)
                                    <option value="{{ $item->id }}">{{ $item->name . ' ' . $item->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('search_asesores')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>

                        <div class="col-lg-3 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar por clientes') }}</span>
                            <input type="search" class="form-control @error('search_cliente') is-invalid @enderror"
                                placeholder="{{ __('Buscar por nombre, apellido, documento identidad') }}"
                                wire:model.debounce.300ms="search_cliente">

                            @error('search_cliente')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>

                        <div class="col-lg-3 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar por cursos') }}</span>
                            <select class="form-control @error('search_curso') is-invalid @enderror"
                                wire:model="search_curso">
                                <option value="" selected>{{ __('Selecciona una opcion') }}</option>
                                @foreach ($this->Cursos as $item)
                                    <option value="{{ $item->id }}">
                                        {{ '(' . $item->consecutive . ')' . ' - ' . $item->name }}</option>
                                @endforeach
                            </select>
                            @error('search_curso')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 col-12 mb-2">
                            <span>{{ __('Descargar reporte') }}</span> <br>
                            <button data-bs-toggle="modal" data-bs-target="#exportItem" type="button"
                                wire:click="clean()" class="btn btn-primary btn-block w-100"><i
                                    class="fas fa-file-export"></i></button>
                        </div>
                        <div class="col-lg-1 col-md-4 col-12 mb-2">
                            <br>
                            <button type="button" wire:click="limpiarfiltros"
                                class="btn btn-primary">{{ __('Limpiar') }}</button>
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
                                    <th scope="col">{{ __('Consecutivo') }}</th>
                                    <th scope="col">{{ __('Cliente') }}</th>
                                    <th scope="col">{{ __('Curso') }}</th>
                                    <th scope="col">{{ __('Fecha de expiracion') }}</th>
                                    <th scope="col">{{ __('Horas') }}</th>
                                    <th scope="col">{{ __('Estado') }}</th>
                                    @can(['certificado.edit', 'certificado.delete'])
                                        <th scope="col">{{ __('Accion') }}</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($this->Certificados as $item)
                                    <tr>
                                        <th scope="row">#{{ $item->id }}</th>
                                        <td>{{ $item->consecutive }}</td>
                                        <td>{{ $item->cliente->name . ' ' . $item->cliente->last_name }}</td>
                                        <td>{{ $item->course_name }}</td>
                                        <td>{{ Str::title(Carbon\Carbon::create($item->expiration_date)->locale('es')->isoFormat('LLLL')) }}
                                        </td>
                                        <td>{{ $item->hours }}</td>

                                        <td>
                                            <span class="badge bg-{{ $item->status == 1 ? 'success' : 'secondary' }}">
                                                {{ $item->status == 1 ? __('Activo') : __('Desactivado') }}</span>

                                        </td>
                                        @can(['certificado.edit', 'certificado.delete'])
                                            <td>
                                                <div class="dropdown dropstart">
                                                    <button class="btn btn-primary dropdown-toggle" type="button"
                                                        wire:key="dropdownd-{{ $item->id }}" id="dropdownMenuButton"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        @can('certificado.edit')
                                                            <li><button class="dropdown-item"
                                                                    wire:click="edit({{ $item->id }})"> <i
                                                                        class="fas fa-edit"></i>
                                                                    {{ __('Editar') }}</button>
                                                            </li>

                                                            <li><button class="dropdown-item"
                                                                    wire:click="changestatus({{ $item->id }})">
                                                                    <i
                                                                        class="fas fa-eye{{ $item->status == 1 ? '-slash' : '' }} "></i>
                                                                    {{ $item->status == 1 ? __('Desactivar') : __('Activar') }}</button>
                                                            </li>

                                                            <li>
                                                                <button class="dropdown-item"
                                                                    wire:click="share({{ $item->id }})">
                                                                    <i class="fas fa-share-square"></i>
                                                                    {{ __('Opciones') }}
                                                                </button>
                                                            </li>
                                                        @endcan

                                                        @can('certificado.delete')
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
                                        @can(['certificado.edit', 'certificado.delete'])
                                            <td colspan="8" class="text-center justify-content-center">
                                                ¡{{ __('No hay certificados disponibles') }}!
                                            </td>
                                        @else
                                            <td colspan="7" class="text-center justify-content-center">
                                                ¡{{ __('No hay certificados disponibles') }}!
                                            </td>
                                        @endcan

                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if (count($this->Certificados) > 0)
                        <div class="row text-center justify-content-center mt-2" style="max-width: 99%">
                            {{ $this->Certificados->onEachSide(1)->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('panel.certificados.modal.create')
    @include('panel.certificados.modal.options')
    @include('panel.certificados.modal.edit')
    @include('panel.certificados.modal.export')



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
            $('#optionesItem').modal('show');
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

        window.addEventListener('searched', event => {
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El item ha sido encontrado',
                showConfirmButton: false,
                timer: 1000
            })
        })

        window.addEventListener('openShare', event => {
            $('#optionesItem').modal('show');
        })

        window.addEventListener('existenteCertificado', event => {
            Swal.fire({
                icon: 'question',
                title: "¿Deseas crear igualmente?",
                text: "Ya este cliente contiene un certificado para este curso vigente.",
                showCancelButton: true,
            }).then((result) => {
                if (result.value) {
                    window.livewire.emit('crearCertificado')
                    let timerInterval
                    Swal.fire({
                        icon: 'success',
                        title: '¡Procesando! ',
                        text: 'Espera un momento, pronto estará disponible',
                        timer: 1000,
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
    </script>
</div>
