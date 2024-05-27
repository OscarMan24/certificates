<div class="modal fade" id="editItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Editar certificado') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <h4 class="mb-4"><i class="fas fa-id-card"></i> {{ __('Buscar cliente') }}</h4>
                    <div class="col-12 row mb-3">
                        <div class="col-lg-4 col-md-12 col-12 mb-3">
                            <span>{{ __('Tipo de documento') }}</span>
                            <select
                                class="form-control @error('tipoDocumentoCliente') is-invalid @enderror {{ $clienteEncontrado == true ? 'is-valid' : '' }}"
                                wire:target="store" wire:loading.attr="disabled"
                                wire:model.defer="tipoDocumentoCliente">
                                <option value="">{{ __('Seleccione una opción') }}</option>
                                @foreach ($this->Documentos as $item)
                                    <option value="{{ $item->abbreviation }}">{{ $item->name_document }}</option>
                                @endforeach
                            </select>
                            @error('tipoDocumentoCliente')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-12 col-12 mb-3">
                            <span>{{ __('Numero de documento') }}</span>
                            <input type="number"
                                class="form-control @error('buscarClienteDocumento') is-invalid @enderror {{ $clienteEncontrado == true ? 'is-valid' : '' }}"
                                wire:model.defer="buscarClienteDocumento" wire:target="store"
                                wire:loading.attr="disabled" wire:keydown.enter='buscarCliente'>
                            @error('buscarClienteDocumento')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-12 col-12 mb-3">
                            <br>
                            <button class="btn btn-primary" wire:click="buscarCliente">
                                <i class="fas fa-search"></i></button>
                        </div>
                    </div>

                    @if ($clienteEncontrado == true)
                        <div class="col-12 row mb-3">
                            <div class="col-md-6 col-12 mb-2">
                                <span class="mb-1">{{ __('Nombres del cliente') }}</span>
                                <input type="text" class="form-control @error('nombreCliente') is-invalid @enderror "
                                    placeholder="{{ __('Nombres') }}" value="{{ $nombreCliente }}" wire:target="store"
                                    wire:loading.attr="disabled" readonly>
                                @error('nombreCliente')
                                    <div class="invalid-feedback ">{{ $message }} </div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-12 mb-2">
                                <span class="mb-1">{{ __('Apellidos del cliente') }}</span>
                                <input type="text"
                                    class="form-control @error('apellidoCliente') is-invalid @enderror"
                                    placeholder="{{ __('Apellidos') }}" wire:model.defer='apellidoCliente'
                                    wire:target="store" wire:loading.attr="disabled" readonly>
                                @error('apellidoCliente')
                                    <div class="invalid-feedback ">{{ $message }} </div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-12 mb-2">
                                <span class="mb-1">{{ __('Correo') }}</span>
                                <input type="text" class="form-control @error('correoCliente') is-invalid @enderror"
                                    placeholder="{{ __('Correo') }}" value="{{ $correoCliente }}" wire:target="store"
                                    wire:loading.attr="disabled" readonly>
                                @error('correoCliente')
                                    <div class="invalid-feedback ">{{ $message }} </div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-12 mb-2">
                                <span class="mb-1">{{ __('Telefono - Celular') }}</span>
                                <input type="tel"
                                    class="form-control @error('telefonoCliente') is-invalid @enderror"
                                    placeholder="{{ __('Telefono - Celular') }}" value="{{ $telefonoCliente }}"
                                    wire:target="store" wire:loading.attr="disabled" readonly>
                                @error('telefonoCliente')
                                    <div class="invalid-feedback ">{{ $message }} </div>
                                @enderror
                            </div>

                            <div class="col-lg-6 col-md-12 col-12 mb-3">
                                <span>{{ __('Asesor') }}</span>
                                <select class="form-control @error('asesorCertificado') is-invalid @enderror" readonly
                                    wire:target="store" wire:loading.attr="disabled"
                                    wire:model.defer="asesorCertificado" disabled>
                                    <option value="">{{ __('Seleccione una opción') }}</option>
                                    @foreach ($this->Asesores as $item)
                                        <option value="{{ $item->id }}">{{ $item->name . ' ' . $item->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('asesorCertificado')
                                    <div class="invalid-feedback ">{{ $message }} </div>
                                @enderror
                            </div>

                        </div>

                        <h4 class="mb-4"><i class="fas fa-database"></i> {{ __('Datos') }}</h4>

                        <div class="col-12 row mb-3">
                            <div class="col-lg-6 col-md-12 col-12 mb-3">
                                <span>{{ __('Cursos') }}</span>
                                <select class="form-control @error('cursosId') is-invalid @enderror" wire:target="store"
                                    wire:loading.attr="disabled" wire:model.defer="cursosId" disabled>
                                    @foreach ($this->Cursos as $item)
                                        <option value="{{ $item->id }}">
                                            {{ '(' . $item->consecutive . ')' . ' - ' . $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('cursosId')
                                    <div class="invalid-feedback ">{{ $message }} </div>
                                @enderror
                            </div>

                            <div class="col-lg-6 col-md-12 col-12 mb-3">
                                <span>{{ __('Instructores') }}</span>
                                <select class="form-control @error('instructoresId') is-invalid @enderror"
                                    wire:target="store" wire:loading.attr="disabled" wire:model.defer="instructoresId">
                                    <option value="" selected>{{ __('Seleccione una opción') }}
                                    </option>
                                    @foreach ($this->Instructores as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->name . ' ' . $item->last_name }}</option>
                                    @endforeach
                                </select>
                                @error('instructoresId')
                                    <div class="invalid-feedback ">{{ $message }} </div>
                                @enderror
                            </div>

                            <div class="col-lg-6 col-md-12 col-12 mb-3">
                                <span>{{ __('Aliados') }}</span>
                                <select class="form-control @error('aliadoCertificado') is-invalid @enderror" readonly
                                    wire:target="store" wire:loading.attr="disabled"
                                    wire:model.defer="aliadoCertificado">
                                    <option value="">{{ __('Seleccione una opción') }}</option>
                                    @foreach ($this->Aliados as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->name . ' ' . $item->last_name }}
                                    @endforeach
                                </select>
                                @error('aliadoCertificado')
                                    <div class="invalid-feedback ">{{ $message }} </div>
                                @enderror
                            </div>

                            <div class="col-lg-6 col-md-12 col-12 mb-3">
                                <span>{{ __('Horarios') }}</span>
                                <select class="form-control @error('horariosId') is-invalid @enderror"
                                    wire:target="store" wire:loading.attr="disabled" wire:model.defer="horariosId">
                                    <option value="" selected>{{ __('Seleccione una opción') }}
                                    </option>
                                    @foreach ($this->Horarios as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->timer . ' ' . $item->type }}</option>
                                    @endforeach
                                </select>
                                @error('horariosId')
                                    <div class="invalid-feedback ">{{ $message }} </div>
                                @enderror
                            </div>

                            <div class="col-lg-6 col-md-12 col-12 mb-3">
                                <span>{{ __('Fecha Inicial') }}</span>
                                <input type="date"
                                    class="form-control @error('fechaInicialCertificado') is-invalid @enderror"
                                    wire:model.defer="fechaInicialCertificado" wire:target="store"
                                    wire:loading.attr="disabled">
                                @error('fechaInicialCertificado')
                                    <div class="invalid-feedback ">{{ $message }} </div>
                                @enderror
                            </div>

                            <div class="col-lg-6 col-md-12 col-12 mb-3">
                                <span>{{ __('Fecha Final') }}</span>
                                <input type="date"
                                    class="form-control @error('fechaFinalCertificado') is-invalid @enderror"
                                    wire:model.defer="fechaFinalCertificado" wire:target="store"
                                    wire:loading.attr="disabled">
                                @error('fechaFinalCertificado')
                                    <div class="invalid-feedback ">{{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <div wire:loading wire:target="store,buscarCliente">
                        <div class="progress col-12">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                {{ __('Cargando...') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" wire:target="actualizar" wire:loading.attr="disabled"
                    data-bs-dismiss="modal" wire:click="clean()">{{ __('Cancelar') }}</button>
                <button type="button" class="btn btn-primary" wire:target="actualizar" wire:loading.attr="disabled"
                    wire:click="actualizar()">{{ __('Actualizar') }}</button>
            </div>
        </div>
    </div>
</div>
