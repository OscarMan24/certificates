<div class="modal fade" id="editItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Editar instructor') }}</h5>
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
                        <span class="mb-1">{{ __('Nombre del instructor') }}</span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder=""
                            wire:model.defer="name" wire:target="actualizar" wire:loading.attr="disabled">
                        @error('name')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Apellidos del instructor') }}</span>
                        <input type="text" class="form-control @error('lastName') is-invalid @enderror"
                            placeholder="" wire:model.defer="lastName" wire:target="actualizar"
                            wire:loading.attr="disabled">
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

                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Resolución SO') }}</span>
                        <input type="text" class="form-control @error('resolucionSo') is-invalid @enderror"
                            placeholder="" wire:model.defer="resolucionSo" wire:target="store"
                            wire:loading.attr="disabled">
                        @error('resolucionSo')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Observaciones') }}</span>
                        <input type="text" class="form-control @error('observations') is-invalid @enderror"
                            placeholder="" wire:model.defer="observations" wire:target="store"
                            wire:loading.attr="disabled">
                        @error('observations')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <span>{{ __('Firma del instructor') }} (350<small>px {{ __('ancho') }}</small> x
                            200<small>px {{ __('alto') }}</small>) <small>(Solo formato png)</small></span>
                        <input type="file" class="form-control @error('signature') is-invalid @enderror"
                            accept=".png" wire:model="signature" wire:target="store" wire:loading.attr="disabled">
                        @error('signature')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror

                        <div wire:loading.inline wire:target="signature">
                            <div class="col-12 my-1 text-center justify-content-center row">
                                <div class="spinner-grow my-2" role="status">
                                </div>
                            </div>
                        </div>

                        @if ($this->signature)
                            <div class="col-12 mb-3 mt-3 text-center justify-content-center row">
                                <span>{{ __('Previa de la firma') }}</span>
                                <img class="img-fluid " src="{{ $signature->temporaryUrl() }}"
                                    style="max-width: 300px; border-radius:1rem">
                            </div>
                        @endif

                        @if ($currentSignature)
                            <div class="col-12 mb-2 mt-3 text-center justify-content-center row">
                                <span>{{ __('Imagen de la firma actual') }}</span>
                                <img class="img-fluid "
                                    src="{{ asset('/storage/instructores/' . $currentSignature) }}"
                                    style="max-width: 300px; border-radius:1rem">
                            </div>
                        @endif
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
