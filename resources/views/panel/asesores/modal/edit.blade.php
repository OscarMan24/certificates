<div class="modal fade" id="editItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Editar asesor') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Tipo de documento') }}</span>
                        <select class="form-control @error('typeDocument') is-invalid @enderror"
                            wire:target="actualizar" wire:loading.attr="disabled" wire:model.defer="typeDocument">
                            @foreach ($this->Documentos as $item)
                                <option value="{{ $item->abbreviation }}">{{ $item->name_document }}</option>
                            @endforeach
                        </select>
                        @error('typeDocument')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Numero de documento') }}</span>
                        <input type="number" class="form-control @error('document') is-invalid @enderror"
                            placeholder="{{ __('Numero de documento') }}" wire:model.defer="document"
                            wire:target="actualizar" wire:loading.attr="disabled">
                        @error('document')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Nombre del asesor') }}</span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            placeholder="{{ __('Nombre del aliado') }}" wire:model.defer="name"
                            wire:target="actualizar" wire:loading.attr="disabled">
                        @error('name')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Apellidos del asesor') }}</span>
                        <input type="text" class="form-control @error('lastName') is-invalid @enderror"
                            placeholder="{{ __('Apellidos del asesor') }}" wire:model.defer="lastName"
                            wire:target="actualizar" wire:loading.attr="disabled">
                        @error('lastName')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Correo') }}</span>
                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                            placeholder="{{ __('Correo') }}" wire:model.defer="email" wire:target="actualizar"
                            wire:loading.attr="disabled">
                        @error('email')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Telefono - Celular') }}</span>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                            placeholder="{{ __('Telefono - Celular') }}" wire:model.defer="phone"
                            wire:target="actualizar" wire:loading.attr="disabled">
                        @error('phone')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div wire:loading wire:target="actualizar">
                        <div class="progress col-12">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                {{ __('Loading...') }}
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
