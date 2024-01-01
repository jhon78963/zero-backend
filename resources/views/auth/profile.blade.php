@extends('layout.template')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Perfil de Usuario</h5>
                <!-- Account -->
                <form action="{{ route('auth.profile.store', auth()->user()->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img src="{{ auth()->user()->profilePicture }}" alt="user-avatar" class="d-block rounded"
                                height="100" width="100" id="uploadedAvatar" />
                            <div class="button-wrapper">
                                <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                    <span class="d-none d-sm-block">Subir nueva foto</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                    <input type="file" id="upload" name="profilePicture" class="account-file-input"
                                        hidden accept="image/*" />
                                </label>
                                <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                    <i class="bx bx-reset d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Reiniciar</span>
                                </button>

                                <p class="text-muted mb-0">Permitido JPG, GIF or PNG. Tamaño máximo de 500K</p>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0" />
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="firstName" class="form-label">Nombre</label>
                                <input class="form-control" type="text" id="e_name" name="name"
                                    value="{{ auth()->user()->name }}" autofocus />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="lastName" class="form-label">Apellidos</label>
                                <input class="form-control" type="text" name="surname" id="e_surname"
                                    value="{{ auth()->user()->surname }}" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input class="form-control" type="text" id="e_email" name="email"
                                    value="{{ auth()->user()->email }}" placeholder="john.doe@example.com" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="phoneNumber">Celular</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">PE (+51)</span>
                                    <input type="text" id="e_phoneNumber" name="phoneNumber" class="form-control"
                                        placeholder="202 555 0111" value="{{ auth()->user()->phoneNumber }}" />
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="address" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="e_address" name="address"
                                    placeholder="Address" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="organization" class="form-label">Distrito</label>
                                <input type="text" class="form-control" id="e_distric" name="district_id"
                                    value="" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Actualizar</button>
                            <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                        </div>

                    </div>
                </form>
                <!-- /Account -->
            </div>
            <div class="card">
                <h5 class="card-header">Eliminar mi Cuenta</h5>
                <div class="card-body">
                    <div class="mb-3 col-12 mb-0">
                        <div class="alert alert-warning">
                            <h6 class="alert-heading fw-bold mb-1">¿Estás seguro de eliminar tu cuenta?</h6>
                            <p class="mb-0">Una vez que eliminas tu cuenta, no hay vuelta atrás. Por favor asegúrese.
                            </p>
                        </div>
                    </div>
                    <form id="formAccountDeactivation" onsubmit="return false">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="accountActivation"
                                id="accountActivation" />
                            <label class="form-check-label" for="accountActivation">Confirmo la desactivación de mi
                                cuenta</label>
                        </div>
                        <button type="submit" class="btn btn-danger deactivate-account">Desactivar mi Cuenta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
@endsection

@section('js')
@endsection
