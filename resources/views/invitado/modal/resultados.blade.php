<div class="modal fade" id="resultados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Certificados encontrados') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    wire:click="clean"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @forelse($this->certificados as $certificado)
                        <div class="card">
                            <div class="card-body" style="margin-bottom: -125px">
                                <div class="col-12 justify-content-center">
                                    <h5>Curso: <b> <span>{{ $certificado->course_name }}</span> </b></h5>
                                    <span class="mb-1">Cliente: <b> {{ $nombreCliente }} </b></span><br>
                                    <span class="mb-1">Consecutivo: <b> {{ $certificado->consecutive }}
                                        </b></span><br>
                                    <span class="mb-1">Fecha Expiracion:
                                        <b>{{ $certificado->fecha_expiracion }}</b></span> <br>


                                    <a href="{{ $certificado->url }}" target="_blank"
                                        class="btn btn-success w-100 my-2">{{ __('Visualizar') }}</a>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse

                    <div wire:loading wire:target="clean,searchCertificados">
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
            </div>
        </div>
    </div>
</div>
