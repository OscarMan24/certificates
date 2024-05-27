<div class="modal fade" id="subidaItems" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Subir Documentos') }}</h5>
                <button type="button" class="btn-close" aria-label="Close" wire:click="closeModal"
                    wire:target="subirInformacion2,documentosEntrenamiento,documentosPersonales"
                    wire:loading.attr="disabled"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12 mb-5">
                        <span>{{ __('Documentos personales') }} </span>
                        <input type="file" class="form-control @error('documentosPersonales') is-invalid @enderror"
                            wire:model="documentosPersonales" wire:target="subirInformacion2"
                            wire:loading.attr="disabled" accept=".doc, .docx, .xls, .xlsx, .pdf, .jpg, .jpeg, .png">
                        <small>*Archivos permitidos .doc, .docx, .xls, .xlsx, .pdf, .jpg,
                            .jpeg, .png</small>
                        @error('documentosPersonales')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror

                        <div class="col-12 row">
                            @if ($documentosPersonalesActual)
                                <div class="mb-3 mt-3 text-center justify-content-center row">
                                    <a href="{{ asset('/storage/Documentos/DocumentosPersonales/' . $documentosPersonalesActual) }}"
                                        target="_blank">
                                        <div class="card bg-primary">
                                            <div class="card-body text-white" style="">
                                                <div class="col-12  text-center justify-content-center">

                                                    <i class="fas fa-cloud-download-alt w-100"></i>

                                                </div>
                                                <div class="col-12 text-center justify-content-center mt-2"
                                                    style="padding-left: 0; padding-right:0">
                                                    Documentos Personales Actuales
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                            @if ($this->documentosPersonales)
                                <div class=" mb-3 mt-3 text-center justify-content-center row">
                                    <i class="fas fa-clipboard-check text-primary fa-4x mb-2"></i>
                                    <span class="font-weight-light">*Esperando a guardar para subir el archivo</span>
                                </div>
                            @endif
                        </div>

                        <div wire:loading.inline wire:target="documentosPersonales">
                            <div class="col-12 my-1 text-center justify-content-center row">
                                <div class="spinner-grow my-2" role="status">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-12 mb-5">
                        <span>{{ __('Documentos entrenamiento') }} </span>
                        <input type="file"
                            class="form-control @error('documentosEntrenamiento') is-invalid @enderror"
                            wire:model="documentosEntrenamiento" wire:target="subirInformacion2"
                            wire:loading.attr="disabled" accept=".doc, .docx, .xls, .xlsx, .pdf, .jpg, .jpeg, .png">
                        <small>*Archivos permitidos .doc, .docx, .xls, .xlsx,
                            .pdf, .jpg, .jpeg, .png</small>
                        @error('documentosEntrenamiento')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                      
                        <div class="col-12 row">
                            @if ($documentosEntrenamientosActual)
                                <div class="mb-3 mt-3 text-center justify-content-center row">
                                    <a href="{{ asset('/storage/Documentos/DocumentosEntrenamiento/' . $documentosEntrenamientosActual) }}"
                                        target="_blank">
                                        <div class="card bg-primary">
                                            <div class="card-body text-white" style="">
                                                <div class="col-12  text-center justify-content-center">

                                                    <i class="fas fa-cloud-download-alt w-100"></i>

                                                </div>
                                                <div class="col-12 text-center justify-content-center mt-2"
                                                    style="padding-left: 0; padding-right:0">
                                                    Documentos Entrenamientos Actual
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                            @if ($this->documentosEntrenamiento)
                                <div class="mb-3 mt-3 text-center justify-content-center row">
                                    <i class="fas fa-clipboard-check text-primary fa-4x mb-2"></i>
                                    <span class="font-weight-light">*Esperando a guardar para subir el archivo</span>
                                </div>
                            @endif
                        </div>

                        <div wire:loading.inline wire:target="documentosEntrenamiento">
                            <div class="col-12 my-1 text-center justify-content-center row">
                                <div class="spinner-grow my-2" role="status">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div wire:loading wire:target="subirInformacion2">
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
                <button type="button" class="btn btn-danger"
                    wire:target="subirInformacion2,documentosEntrenamiento,documentosPersonales"
                    wire:loading.attr="disabled" data-bs-dismiss="modal"
                    wire:click="closeModal()">{{ __('Cancelar') }}</button>
                <button type="button" class="btn btn-primary"
                    wire:target="subirInformacion2,documentosEntrenamiento,documentosPersonales"
                    wire:loading.attr="disabled" wire:click="subirInformacion2()">{{ __('Guardar') }}</button>
            </div>
        </div>
    </div>
</div>
