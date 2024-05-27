<div wire:init="loadData">

    <div class="accordion row" id="accordionExample">
        <div class="col-md-6 col-12 mb-2">
            <div class="card">
                <div class="card-header bg-primary" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left text-white {{ !$mostrar[1] ?? 'collapsed' }}"
                            type="button" data-toggle="collapse" data-target="#collapseOne"
                            aria-expanded="{{ $mostrar[1] ? 'true' : 'false' }}" aria-controls="collapseOne"
                            wire:click="cambiarVariable(1)">
                            <i class="fas fa-heading"></i> Titulo y descripción de la web
                        </button>
                    </h2>
                </div>

                <div id="collapseOne" class="collapse {{ $mostrar[1] ? 'show' : '' }} " aria-labelledby="headingOne"
                    data-parent="#accordionExample">
                    <div class="card-body">
                        <span class="mb-1">{{ __('Titulo web') }}</span>
                        <input type="text" class="form-control @error('titleWeb') is-invalid @enderror"
                            wire:model.defer="titleWeb" wire:target="store" wire:loading.attr="disabled">
                        @error('titleWeb')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror

                        <span class="mb-1">{{ __('Descripcion web') }}</span>
                        <textarea class="form-control @error('descriptionWeb') is-invalid @enderror" wire:model.defer="descriptionWeb"
                            wire:target="store" wire:loading.attr="disabled"></textarea>
                        @error('descriptionWeb')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror

                        <button class="btn btn-success mt-3 btn-block" wire:click="actualizarSetting(1)"
                            wire:target="store" wire:loading.attr="disabled">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-12 mb-2">
            <div class="card">
                <div class="card-header bg-primary" id="headingTwo">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left text-white {{ !$mostrar[2] ?? 'collapsed' }}"
                            type="button" data-toggle="collapse" data-target="#collapseTwo"
                            aria-expanded="{{ $mostrar[2] ? 'true' : 'false' }}" aria-controls="collapseTwo"
                            wire:click="cambiarVariable(2)">
                            <i class="fas fa-camera"></i> Iconos oscuros y claros
                        </button>
                    </h2>
                </div>
                <div id="collapseTwo" class="collapse {{ $mostrar[2] ? 'show' : '' }}" aria-labelledby="headingTwo"
                    data-parent="#accordionExample">
                    <div class="card-body">
                        <span class="mb-1">{{ __('Icono claro') }} (150<small>px ancho</small> x 150<small>px
                                alto</small>)</span>
                        <input type="file" class="form-control @error('iconWeb') is-invalid @enderror"
                            accept="image/*" wire:model="iconWeb" wire:target="editUser" wire:loading.attr="disabled">
                        @error('iconWeb')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror

                        <div wire:loading.inline wire:target="iconWeb">
                            <div class="col-12 my-1 text-center justify-content-center row">
                                <div class="spinner-grow my-2" role="status">
                                </div>
                            </div>
                        </div>

                        @if ($this->iconWeb)
                            <div class="col-12 mb-3 mt-3 text-center justify-content-center row">
                                <span>{{ __('Previa del icono claro') }}</span>
                                <img class="img-fluid " src="{{ $iconWeb->temporaryUrl() }}"
                                    style="max-width: 150px; border-radius:1rem">
                            </div>
                        @endif

                        @if ($iconCurrentWeb)
                            <div class="col-12 mb-2 mt-3 text-center justify-content-center row">
                                <span>{{ __('Icono claro actual') }}</span>
                                <img class="img-fluid " src="{{ asset('/storage/app/' . $iconCurrentWeb) }}"
                                    style="max-width: 150px; border-radius:1rem">
                            </div>
                        @endif

                        {{-- <span class="mb-1">{{ __('Icono oscuro') }} (256 x 256px)</span>
                        <input type="file" class="form-control @error('iconDarkWeb') is-invalid @enderror" accept="iconWeb/*"
                            wire:model="iconDarkWeb" wire:target="editUser" wire:loading.attr="disabled">
                        @error('iconDarkWeb')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    
                        <div wire:loading.inline wire:target="iconDarkWeb">
                            <div class="col-12 my-1 text-center justify-content-center row">
                                <div class="spinner-grow my-2" role="status">
                                </div>
                            </div>
                        </div>
                    
                        @if ($this->iconDarkWeb)
                            <div class="col-12 mb-3 mt-3 text-center justify-content-center row">
                                <span>{{ __('Previa del icono oscuro') }}</span>
                                <img class="img-fluid " src="{{ $iconDarkWeb->temporaryUrl() }}" style="max-width: 150px; border-radius:1rem">
                            </div>
                        @endif
                    
                        @if ($iconCurrentDarkWeb)
                            <div class="col-12 mb-2 mt-3 text-center justify-content-center row">
                                <span>{{ __('Icono oscuro actual') }}</span>
                                <img class="img-fluid " src="{{ asset('/storage/app/' . $iconCurrentDarkWeb) }}"
                                    style="max-width: 150px; border-radius:1rem">
                            </div>
                        @endif --}}

                        <button class="btn btn-success mt-3 btn-block" wire:click="actualizarSetting(2)"
                            wire:target="store" wire:loading.attr="disabled">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-12 mb-2">
            <div class="card">
                <div class="card-header bg-primary" id="headingThree">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left text-white {{ !$mostrar[3] ?? 'collapsed' }}"
                            type="button" data-toggle="collapse" data-target="#collapseThree"
                            aria-expanded="{{ $mostrar[3] ? 'true' : 'false' }}" aria-controls="collapseThree"
                            wire:click="cambiarVariable(3)">
                            <i class="fas fa-images"></i> Logos claros y oscuros
                        </button>
                    </h2>
                </div>
                <div id="collapseThree" class="collapse {{ $mostrar[3] ? 'show' : '' }}" aria-labelledby="headingThree"
                    data-parent="#accordionExample">
                    <div class="card-body">
                        <span class="mb-1">{{ __('Logo claro') }} (256<small>px ancho</small> x 256<small>px
                                alto</small>)</span>
                        <input type="file" class="form-control @error('logoWeb') is-invalid @enderror"
                            accept="image/*" wire:model="logoWeb" wire:target="editUser"
                            wire:loading.attr="disabled">
                        @error('logoWeb')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror

                        <div wire:loading.inline wire:target="iconWeb">
                            <div class="col-12 my-1 text-center justify-content-center row">
                                <div class="spinner-grow my-2" role="status">
                                </div>
                            </div>
                        </div>

                        @if ($this->logoWeb)
                            <div class="col-12 mb-3 mt-3 text-center justify-content-center row">
                                <span>{{ __('Previa del logo claro') }}</span>
                                <img class="img-fluid " src="{{ $logoWeb->temporaryUrl() }}"
                                    style="max-width: 150px; border-radius:1rem">
                            </div>
                        @endif

                        @if ($logoCurrentWeb)
                            <div class="col-12 mb-2 mt-3 text-center justify-content-center row">
                                <span>{{ __('Logo claro actual') }}</span>
                                <img class="img-fluid " src="{{ asset('/storage/app/' . $logoCurrentWeb) }}"
                                    style="max-width: 150px; border-radius:1rem">
                            </div>
                        @endif

                        <span class="mb-1">{{ __('Logo oscuro') }} (256<small>px ancho</small> x 256<small>px
                                alto</small>)</span>
                        <input type="file" class="form-control @error('logoDarkWeb') is-invalid @enderror"
                            accept="iconWeb/*" wire:model="logoDarkWeb" wire:target="editUser"
                            wire:loading.attr="disabled">
                        @error('logoDarkWeb')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror

                        <div wire:loading.inline wire:target="logoDarkWeb">
                            <div class="col-12 my-1 text-center justify-content-center row">
                                <div class="spinner-grow my-2" role="status">
                                </div>
                            </div>
                        </div>

                        @if ($this->logoDarkWeb)
                            <div class="col-12 mb-3 mt-3 text-center justify-content-center row">
                                <span>{{ __('Previa del Logo oscuro') }}</span>
                                <img class="img-fluid " src="{{ $logoDarkWeb->temporaryUrl() }}"
                                    style="max-width: 150px; border-radius:1rem">
                            </div>
                        @endif

                        @if ($logoCurrentDarkWeb)
                            <div class="col-12 mb-2 mt-3 text-center justify-content-center row">
                                <span>{{ __('Logo oscuro actual') }}</span>
                                <img class="img-fluid " src="{{ asset('/storage/app/' . $logoCurrentDarkWeb) }}"
                                    style="max-width: 150px; border-radius:1rem">
                            </div>
                        @endif
                        <button class="btn btn-success mt-3 btn-block" wire:click="actualizarSetting(3)"
                            wire:target="store" wire:loading.attr="disabled">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->id == 2)
            <div class="col-md-6 col-12 mb-2">
                <div class="card">
                    <input type="file" wire:model="archivoImport"
                        class="form-control  @error('archivoImport') is-invalid @enderror">
                    @error('logoDarkWeb')
                        <div class="invalid-feedback ">{{ $message }} </div>
                    @enderror

                    <div wire:loading.inline wire:target="archivoImport">
                        <div class="col-12 my-1 text-center justify-content-center row">
                            <div class="spinner-grow my-2" role="status">
                            </div>
                        </div>
                    </div>

                    @if ($this->archivoImport)
                        <div class="col-12 mb-3 mt-3 text-center justify-content-center row">
                            <span>{{ __('Archivo cargado') }}</span>

                        </div>
                    @endif

                    <div wire:loading.inline wire:target="subirImport,actualizar4">
                        <div class="col-12 my-1 text-center justify-content-center row">
                            <div class="spinner-grow my-2" role="status">
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary" wire:click="subirImport">Importar</button>
                    <button type="button" class="btn btn-primary" wire:click="actualizar4">Cambios</button>
                </div>
            </div>
        @endif

        
    </div>

    <script>
        window.addEventListener('errores', event => {
            Swal.fire(
                '¡Error!',
                event.detail.error,
                'error'
            )
        })

        window.addEventListener('actualiizar', event => {
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El item ha sido actualizado con éxito.',
                showConfirmButton: false,
                timer: 1500
            })
        })
    </script>
</div>
