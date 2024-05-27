<div class="modal fade" id="editUserr" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Editar usuario') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <h4 class="mb-2"><i class="fas fa-id-card"></i> {{ __('Informacion Personal ') }}</h4>

                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Tipo de documento') }}</span>
                        <select class="form-control @error('typeDocument') is-invalid @enderror" wire:target="editUser"
                            wire:loading.attr="disabled" wire:model.defer="typeDocument">
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
                            wire:target="editUser" wire:loading.attr="disabled">
                        @error('document')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Nombres') }}</span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            placeholder="{{ __('Nombres') }}" wire:model.defer="name" wire:target="editUser"
                            wire:loading.attr="disabled">
                        @error('name')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Apellidos') }}</span>
                        <input type="text" class="form-control @error('lastName') is-invalid @enderror"
                            placeholder="{{ __('Apellidos') }}" wire:model.defer="lastName" wire:target="editUser"
                            wire:loading.attr="disabled">
                        @error('lastName')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Cargo') }}</span>
                        <input type="text" class="form-control @error('jobTitle') is-invalid @enderror"
                            placeholder="{{ __('Cargo') }}" wire:model.defer="jobTitle" wire:target="editUser"
                            wire:loading.attr="disabled">
                        @error('jobTitle')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Telefono') }}</span>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                            placeholder="{{ __('Telefono') }}" wire:model.defer="phone" wire:target="editUser"
                            wire:loading.attr="disabled">
                        @error('phone')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-lg-12 col-md-12 col-12 mb-3">
                        <span>{{ __('Direccion') }}</span>
                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                            placeholder="{{ __('Direccion') }}" wire:model.defer="address" wire:target="editUser"
                            wire:loading.attr="disabled">
                        @error('address')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>



                    <h4 class="mt-4 mb-2"><i class="fas fa-user-lock"></i> {{ __('Información de la cuenta') }}</h4>

                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Nombre de usuario') }}</span>
                        <input type="text" class="form-control @error('nameUser') is-invalid @enderror"
                            placeholder="{{ __('Nombre de usuario') }}" wire:model.defer="nameUser"
                            wire:target="editUser" wire:loading.attr="disabled">
                        @error('nameUser')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Correo') }}</span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            wire:model.defer="email" wire:target="editUser" wire:loading.attr="disabled"
                            placeholder="{{ __('Correo') }}">
                        @error('email')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>
                    <div class="col-md-6 col-12 mb-3">
                        <span>{{ __('Password') }}</span>
                        <input type="text" class="form-control @error('password') is-invalid @enderror"
                            wire:model.defer="password" wire:target="editUser" wire:loading.attr="disabled"
                            placeholder="{{ __('Password') }}">
                        @error('password')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <span>{{ __('Imagen del usuario') }} (1080 x 1080px) opcional</span>
                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                            accept="image/*" wire:model="image" wire:target="editUser" wire:loading.attr="disabled">
                        @error('image')
                            <div class="invalid-feedback ">{{ $message }} </div>
                        @enderror

                        <div wire:loading.inline wire:target="image">
                            <div class="col-12 my-1 text-center justify-content-center row">
                                <div class="spinner-grow my-2" role="status">
                                </div>
                            </div>
                        </div>

                        @if ($this->image)
                            <div class="col-12 mb-3 mt-3 text-center justify-content-center row">
                                <span>{{ __('Previa de la imagen') }}</span>
                                <img class="img-fluid " src="{{ $image->temporaryUrl() }}"
                                    style="max-width: 300px; border-radius:1rem">
                            </div>
                        @endif

                        @if ($image_current)
                            <div class="col-12 mb-2 mt-3 text-center justify-content-center row">
                                <span>{{ __('Imagen actual') }}</span>
                                <img class="img-fluid " src="{{ asset('/storage/users/' . $image_current) }}"
                                    style="max-width: 300px; border-radius:1rem">
                            </div>
                        @endif
                    </div>

                    <h4 class="mt-4 mb-2"><i class="fas fa-medal"></i> {{ __('Nivel de privilegio') }}</h4>
                    <div class="col-12 mb-3">
                        <span>{{ __('Roles') }} </span>
                        <div class="row">
                            @foreach ($this->Roles as $rol)
                                @php
                                    $found = false;
                                    foreach ($roles_user as $r3) {
                                        if ($r3['id'] == $rol) {
                                            $found = true;
                                        }
                                    }
                                @endphp

                                <div class="col-auto mb-2">
                                    <div class="custom-check" wire:key="rol-edit-{{ $loop->iteration }}">
                                        <input class="form-check-input @error('roles_user') is-invalid @enderror"
                                            type="checkbox" value="{{ $rol }}"
                                            id="rol2{{ $loop->iteration }}"
                                            wire:key="rol-input-edit-{{ $loop->iteration }}"
                                            wire:click="addrol('{{ $rol }}')" wire:target="editUser"
                                            wire:loading.attr="disabled" {{ $found == true ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rol2{{ $loop->iteration }}">
                                            {{ $rol }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                            @error('roles_user')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>
                    </div>

                    <div wire:loading wire:target="editUser">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                {{ __('Loading...') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" wire:target="editUser" wire:loading.attr="disabled"
                    data-bs-dismiss="modal" wire:click="clean()">{{ __('Cancelar') }}</button>
                <button type="button" class="btn btn-primary" wire:target="editUser" wire:loading.attr="disabled"
                    wire:click="editUser()">{{ __('Actualizar') }}</button>
            </div>
        </div>
    </div>
</div>
