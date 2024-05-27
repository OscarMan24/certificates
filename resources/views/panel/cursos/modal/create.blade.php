<div class="modal fade" id="createNewItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Crear nuevo curso') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Consecutivo') }}</span>
                        <input type="text" class="form-control @error('consecutive') is-invalid @enderror"
                            wire:model.defer="consecutive" wire:target="store" wire:loading.attr="disabled">
                        @error('consecutive')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-3">
                        <span class="mb-1">{{ __('Nombre del curso') }}</span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder=""
                            wire:model.defer="name" wire:target="store" wire:loading.attr="disabled">
                        @error('name')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                   <div class="col-md-6 col-12 mb-3">
                        <span>{{ __('Tipo de plantilla') }}</span>
                        <select class="form-control @error('type') is-invalid @enderror" wire:target="store"
                            wire:loading.attr="disabled" wire:model.defer="type">
                            <option value="">{{ __('Seleccione una opción') }}</option>
                            <option value="1">{{ __('Plantilla #1') }}</option>
                            <option value="2">{{ __('Plantilla #2') }}</option>

                        </select>
                        @error('type')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-3">
                        <span class="mb-1">{{ __('Color del curso para graficas') }}</span>
                        <input type="color" class="form-control @error('color') is-invalid @enderror" placeholder=""
                            wire:model.defer="color" wire:target="store" wire:loading.attr="disabled" style="height: 41px">
                        @error('color')
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
