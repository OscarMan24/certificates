<div class="modal fade" id="editSectores" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Editar sector') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-4">
                        <span class="mb-1">{{ __('Nombre') }}</span>
                        <input type="text" class="form-control @error('sectorName') is-invalid @enderror"
                            placeholder="{{ __('Nombre del rol') }}" wire:model.defer="sectorName"
                            wire:target="actualizar" wire:loading.attr="disabled">
                        @error('sectorName')
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
                    wire:click="actualizar()">{{ __('Guardar') }}</button>
            </div>
        </div>
    </div>
</div>
