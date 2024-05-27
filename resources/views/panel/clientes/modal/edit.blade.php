<div class="modal fade" id="editItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Editar cliente') }}</h5>
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
                        <span class="mb-1">{{ __('Nombre del cliente') }}</span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            placeholder="{{ __('Nombre') }}" wire:model.defer="name" wire:target="actualizar"
                            wire:loading.attr="disabled">
                        @error('name')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Segundo nombre del cliente') }}</span>
                        <input type="text" class="form-control @error('secondName') is-invalid @enderror"
                            placeholder="{{ __('Nombres') }}" wire:model.defer="secondName" wire:target="store"
                            wire:loading.attr="disabled">
                        @error('secondName')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Apellidos del cliente') }}</span>
                        <input type="text" class="form-control @error('lastName') is-invalid @enderror"
                            placeholder="{{ __('Apellidos') }}" wire:model.defer="lastName" wire:target="actualizar"
                            wire:loading.attr="disabled">
                        @error('lastName')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Segundo apellido del cliente') }}</span>
                        <input type="text" class="form-control @error('secondLastName') is-invalid @enderror"
                            placeholder="{{ __('Apellidos') }}" wire:model.defer="secondLastName" wire:target="store"
                            wire:loading.attr="disabled">
                        @error('secondLastName')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Fecha de nacimiento') }}</span>
                        <input type="date"
                            class="form-control @error('birthdate') is-invalid @enderror"wire:model.defer="birthdate"
                            wire:target="store" wire:loading.attr="disabled">
                        @error('birthdate')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>
                    
                   <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Pais de nacimiento') }}</span>
                        <select class="form-control @error('countryOfBirth') is-invalid @enderror"wire:model.defer="countryOfBirth" 
                                wire:target="store" wire:loading.attr="disabled">
                            @foreach ($this->Countries as $countrie)
                                <option value="{{ $countrie->name }}">{{ $countrie->name }}</option>
                            @endforeach
                        </select>
                     
                        @error('countryOfBirth')
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
                        <span class="mb-1">{{ __('Direccion') }}</span>
                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                            placeholder="{{ __('Direccion') }}" wire:model.defer="address" wire:target="store"
                            wire:loading.attr="disabled">
                        @error('address')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>  

                     <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Telefono ') }}</span>
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

                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Genero') }}</span>
                        <select class="form-control @error('gender') is-invalid @enderror" wire:target="store"
                            wire:loading.attr="disabled" wire:model.defer="gender">
                            <option value="">{{ __('Seleccione una opción') }}</option>                            
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Nivel de educación') }}</span>
                        <select  class="form-control @error('educationLevel') is-invalid @enderror"wire:model.defer="educationLevel"
                            wire:target="store" wire:loading.attr="disabled">                        
                            <option value="">{{ __('Seleccione una opcion') }}</option>
                            <option value="Sin educación">Sin educación</option>
                            <option value="Preescolar">Preescolar</option>
                            <option value="Primaria">Primaria</option>
                            <option value="Secundaria">Secundaria </option>
                            <option value="Bachillerato">Bachillerato</option>
                            <option value="Educación técnica o profesional">Educación técnica</option>
                            <option value="Educación universitaria">Educación universitaria</option>
                            <option value="Postgrado">Postgrado</option>
                            <option value="Formación profesional">Formación profesional</option>
                            <option value="Otro">Otro</option>
                        </select>

                        @error('educationLevel')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Area de trabajo') }}</span>
                        <input type="text"
                            class="form-control @error('workArea') is-invalid @enderror"wire:model.defer="workArea"
                            wire:target="store" wire:loading.attr="disabled">
                        @error('workArea')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-2">
                        <span class="mb-1">{{ __('Cargo actual') }}</span>
                        <input type="text"
                            class="form-control @error('actualCharge') is-invalid @enderror"wire:model.defer="actualCharge"
                            wire:target="store" wire:loading.attr="disabled">
                        @error('actualCharge')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Asesor') }}</span>
                        <select class="form-control @error('asesorId') is-invalid @enderror" wire:target="store"
                            wire:loading.attr="disabled" wire:model.defer="asesorId">
                            <option value="">{{ __('Seleccione una opción') }}</option>
                            @foreach ($this->Asesores as $item)
                                <option value="{{ $item->id }}">{{ $item->name . ' ' . $item->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('asesorId')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Aliados') }}</span>
                        <select class="form-control @error('aliadoId') is-invalid @enderror" wire:target="store"
                            wire:loading.attr="disabled" wire:model.defer="aliadoId">
                            <option value="">{{ __('Seleccione una opción') }}</option>
                            @foreach ($this->Aliados as $item)
                                <option value="{{ $item->id }}">{{ $item->name . ' ' . $item->last_name }}
                            @endforeach
                        </select>
                        @error('aliadoId')
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
                    wire:click="actualizar()">{{ __('Actualizar') }}</button>
            </div>
        </div>
    </div>
</div>
