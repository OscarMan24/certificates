<div class="modal fade" id="createNewItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Crear nuevo aliado') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Tipo de documento') }}</span>
                        <select class="form-control @error('typeDocument') is-invalid @enderror" wire:target="store"
                            wire:loading.attr="disabled" wire:model.defer="typeDocument">
                            <option value="">{{ __('Seleccione una opción') }}</option>
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
                            wire:target="store" wire:loading.attr="disabled">
                        @error('document')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Nombre del aliado') }}</span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            placeholder="{{ __('Nombre del aliado') }}" wire:model.defer="name" wire:target="store"
                            wire:loading.attr="disabled">
                        @error('name')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>


                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Representante legal') }}</span>
                        <input type="text" class="form-control @error('legal_representative') is-invalid @enderror"
                            placeholder="{{ __('Representante legal') }}" wire:model.defer="legal_representative"
                            wire:target="store" wire:loading.attr="disabled">
                        @error('legal_representative')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Tipo de documento Representante Legal') }}</span>
                        <select class="form-control @error('typeDocumentLegalRepresentative') is-invalid @enderror" wire:target="store"
                            wire:loading.attr="disabled" wire:model.defer="typeDocumentLegalRepresentative">
                            <option value="">{{ __('Seleccione una opción') }}</option>
                            @foreach ($this->Documentos as $item)
                                <option value="{{ $item->abbreviation }}">{{ $item->name_document }}</option>
                            @endforeach
                        </select>
                        @error('typeDocumentLegalRepresentative')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Numero de documento Representante Legal') }}</span>
                        <input type="number" class="form-control @error('documentLegalRepresentative') is-invalid @enderror"
                            placeholder="{{ __('Numero de documento') }}" wire:model.defer="documentLegalRepresentative"
                            wire:target="store" wire:loading.attr="disabled">
                        @error('documentLegalRepresentative')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Sector economico') }}</span>
                        <select class="form-control @error('economicSectorId') is-invalid @enderror" wire:target="store"
                            wire:loading.attr="disabled" wire:model.defer="economicSectorId">
                            <option value="">{{ __('Seleccione una opción') }}</option>
                            @foreach ($this->Sectores as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('economicSectorId')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Nombre arl') }}</span>
                        <input type="text" class="form-control @error('arl_name') is-invalid @enderror"
                            placeholder="{{ __('Nombre arl') }}" wire:model.defer="arl_name" wire:target="store"
                            wire:loading.attr="disabled">
                        @error('arl_name')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>


                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Correo') }}</span>
                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                            placeholder="{{ __('Correo') }}" wire:model.defer="email" wire:target="store"
                            wire:loading.attr="disabled">
                        @error('email')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>


                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Direccion') }}</span>
                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                            placeholder="{{ __('Direccion') }}" wire:model.defer="address" wire:target="store"
                            wire:loading.attr="disabled">
                        @error('address')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>


                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Telefono') }}</span>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                            placeholder="{{ __('Telefono') }}" wire:model.defer="phone" wire:target="store"
                            wire:loading.attr="disabled">
                        @error('phone')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                      <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Celular') }}</span>
                        <input type="tel" class="form-control @error('celular') is-invalid @enderror"
                            placeholder="{{ __('Celular') }}" wire:model.defer="celular" wire:target="store"
                            wire:loading.attr="disabled">
                        @error('celular')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div wire:loading wire:target="store">
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
                <button type="button" class="btn btn-danger" wire:target="store" wire:loading.attr="disabled"
                    data-bs-dismiss="modal" wire:click="clean()">{{ __('Cancelar') }}</button>
                <button type="button" class="btn btn-primary" wire:target="store" wire:loading.attr="disabled"
                    wire:click="store()">{{ __('Guardar') }}</button>
            </div>
        </div>
    </div>
</div>
