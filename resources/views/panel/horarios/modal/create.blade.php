<div class="modal fade" id="createNewItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Crear nuevo horario') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Tpo') }}</span>
                        <select class="form-control @error('type') is-invalid @enderror" wire:target="store"
                            wire:loading.attr="disabled" wire:model.defer="type">
                            <option value="">{{ __('Seleccione una opción') }}</option>
                            <option value="1">{{ __('Horas') }}</option>
                            <option value="2">{{ __('Dias') }}</option>                           
                        </select>
                        @error('type')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Cantidad') }}</span>
                        <input type="number" class="form-control @error('timer') is-invalid @enderror"
                            wire:model.defer="timer" wire:target="store" wire:loading.attr="disabled">
                        @error('timer')
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
