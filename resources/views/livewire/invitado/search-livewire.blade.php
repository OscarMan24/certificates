<div>
    <div class="row justify-content-md-center">
        <div class="col-md-12 col-lg-4">
            <div class="card login-box-container">
                <div class="card-body">
                    <div class="authent-logo">
                        <img src=" {{ Vite::imagenes(\App\Models\Setting::find(1)->logo) }} " alt="">
                    </div>
                    <div class="authent-text">
                        <p>{{ __('Bienvenido a') . ' ' . \App\Models\Setting::find(1)->title }} </p>
                        <p>{{ __('Verificar certificado de curso') }}.</p>
                    </div>

                    <div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <select class="form-control @error('typeDocument') is-invalid @enderror"
                                    wire:target="searchCertificados" wire:loading.attr="disabled"
                                    wire:model.defer="typeDocument">
                                    <option value="">{{ __('Seleccione una opción') }}</option>
                                    @foreach ($this->Documentos as $item)
                                        <option value="{{ $item->abbreviation }}">{{ $item->name_document }}</option>
                                    @endforeach
                                </select>
                                <label for="typeDocument">{{ __('Tipo de documento') }}</label>
                                @error('typeDocument')
                                    <div class="invalid-feedback ">{{ $message }} </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="number" class="form-control @error('documento') is-invalid @enderror"
                                    placeholder="{{ __('Documento') }}" wire:target="searchCertificados"
                                    wire:loading.attr="disabled" wire:model.defer="documento"
                                    wire:keydown.enter="searchCertificados">
                                <label for="documento">{{ __('Documento') }}</label>
                                @error('documento')
                                    <div class="invalid-feedback ">{{ $message }} </div>
                                @enderror
                            </div>
                        </div>

                        <!--<div class="mb-3">
                            <div class="form-floating">
                                <select class="form-control error('curso') is-invalid enderror"
                                    wiretarget="searchCertificados" wire:loading.attr="disabled"
                                    wire:model.defer="curso">
                                    <option value="">{ __('Seleccione una opción') }}</option>
                                    foreach ($this->Cursos as $item)
                                        <option value="{ $item->id }}">{ $item->name }}</option>
                                    endforeach
                                </select>
                                <label for="curso">{ __('Cursos') }}</label>
                                error('curso')
                                    <div class="invalid-feedback ">{ $message }} </div>
                                enderror
                            </div>
                        </div>-->                        

                        <div wire:loading wire:target="searchCertificados" class="w-100">
                            <div class="progress col-12 w-100">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                    {{ __('Cargando...') }}
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary m-b-xs" wire:click="searchCertificados"
                                wire:target="searchCertificados"
                                wire:loading.attr="disabled">{{ __('Buscar') }}</button>
                        </div>
                    </div>
                </div>
            </div>   
            
            <a href="https://verticaltgroup.com/" class="btn btn-primary m-b-xs w-100" 
                style="margin-top: 60%;
                background-color: #94AEC9;
                border-color: #94AEC9;
                color: #F7F4EF;" >{{ __('Regresar a la web') }}</a>
                  
        </div>
    </div>

    @includeWhen($encontrado,'invitado.modal.resultados')

    <script>
        window.addEventListener('errores', event => {
            Swal.fire(
                '¡Error!',
                event.detail.error,
                'error'
            )
        })

        window.addEventListener('encontrado', event => {
            $('#resultados').modal('show');
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'Datos encontrados.',
                showConfirmButton: false,
                timer: 1500
            })
        })

        window.addEventListener('vencido', event => {
            let timerInterval
            let name = event.detail.name;
            let name_curso = event.detail.name_curso;
            let expiration_date = event.detail.expiration_date;
            Swal.fire({
                icon: 'warning',
                title: '¡Vencido!',
                html: ` 
                    <h5> Cliente: <h3> <b> ${name} </b> </h3> </h5> <br>
                    <h5> Curso: <br> <h3> <b> ${name_curso} </b></h3>  </h5> <br>
                    <h5> Fecha expiracion: <br> <h3> <b> ${expiration_date} </b></h3>  </h5> <br>
                `,
                showConfirmButton: false,
                timer: 30000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
            })
        })

        window.addEventListener('vigente', event => {
            let name = event.detail.name;
            let name_curso = event.detail.name_curso;
            let url = event.detail.visualizar;
            let expiration_date = event.detail.expiration_date;
            Swal.fire({
                icon: 'success',
                title: "¡Vigente!",
                html: ` 
                    <h5> Cliente: <br> <h3> <b> ${name} </b></h3>  </h5> <br>
                    <h5> Curso: <br> <h3> <b> ${name_curso} </b></h3>  </h5> <br> 
                    <h5> Fecha expiracion: <br> <h3> <b> ${expiration_date} </b></h3>  </h5> <br>
                `,
                showCancelButton: true,
                confirmButtonText: "Visualizar"
            }).then((result) => {
                if (result.value) {
                    window.open(url, "_blank");
                    let timerInterval
                    Swal.fire({
                        icon: 'success',
                        title: '¡Procesando! ',
                        text: 'Espera un momento, pronto estará disponible',
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    })
                }
            });
        });
    </script>
</div>
