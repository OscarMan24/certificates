<div class="modal fade" id="optionesItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $consecutivo . ' - ' . $nombreCurso }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <a href="#" wire:click='descargarCertificado'
                            wire:target="descargarCertificado,visualizarCertificado" wire:loading.attr="disabled">
                            <div class="card">
                                <div class="card-body bg-success rounded text-center">
                                    <h3 class="text-white"><i class="fas fa-download"></i> {{ __('Descargar') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <a href="{{ route('show.pdfs', ['id' => base64_encode($certificadoId), 'name' => $consecutivo . ' - ' . $nombreCurso . '.pdf']) }}"
                            target="_blank" wire:target="descargarCertificado,visualizarCertificado"
                            wire:loading.attr="disabled">
                            <div class="card">
                                <div class="card-body bg-info rounded text-center">
                                    <h3 class="text-white"><i class="fas fa-search-plus"></i> {{ __('Visualizar') }}
                                    </h3>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div wire:loading wire:target="descargarCertificado,visualizarCertificado">
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
                <button type="button" class="btn btn-danger" wire:target="descargarCertificado,visualizarCertificado"
                    wire:loading.attr="disabled" data-bs-dismiss="modal"
                    wire:click="clean()">{{ __('Cancelar') }}</button>

            </div>
        </div>
    </div>
</div>
