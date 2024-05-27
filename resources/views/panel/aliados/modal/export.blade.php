<div class="modal fade" id="exportItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Exportar por fecha') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Filtrar desde') }}</span>
                        <input type="date" class="form-control @error('export_from') is-invalid @enderror"
                            wire:model.defer="export_from" wire:target="export" wire:loading.attr="disabled">
                        @error('export_from')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Filtrar hasta') }}</span>
                        <input type="date" class="form-control @error('export_to') is-invalid @enderror"
                            wire:model.defer="export_to" wire:target="export" wire:loading.attr="disabled">
                        @error('export_to')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>


                    <div wire:loading wire:target="export">
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
                <button type="button" class="btn btn-danger" wire:target="export" wire:loading.attr="disabled"
                    data-bs-dismiss="modal" wire:click="clean()">{{ __('Cancelar') }}</button>
                <button type="button" class="btn btn-primary" wire:target="export" wire:loading.attr="disabled"
                    wire:click="export()">{{ __('Exportar') }}</button>
            </div>
        </div>
    </div>
</div>
