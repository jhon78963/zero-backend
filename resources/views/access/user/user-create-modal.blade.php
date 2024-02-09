<div class="modal fade" id="modalCreateUser" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="userCreateForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Registrar Usuarios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="c_username" class="form-label">Username</label>
                            <input type="text" id="c_username" name="username" class="form-control"
                                placeholder="Username" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="c_name" class="form-label">Nombres</label>
                            <input type="text" id="c_name" name="name" class="form-control"
                                placeholder="Nombre" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="c_surname" class="form-label">Apellidos</label>
                            <input type="text" id="c_surname" name="surname" class="form-control"
                                placeholder="Apellidos" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="c_email" class="form-label">Email</label>
                            <input type="text" id="c_email" name="email" class="form-control"
                                placeholder="xxxx@xxx.xx" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="c_phoneNumber" class="form-label">Celular</label>
                            <input type="text" id="c_phoneNumber" name="phoneNumber" class="form-control"
                                placeholder="Número de celular" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col mb-0">
                            <div class="form-password-toggle">
                                <label class="form-label" for="c_password">Contraseña</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" id="c_password" placeholder="Contraseña"
                                        name="password">
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-0">
                            <div class="form-password-toggle">
                                <label class="form-label" for="c_password_confirmation">Confirmar contraseña</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" id="c_password_confirmation"
                                        placeholder="Contraseña" name="password_confirmation">
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnCreateUser">Registrar</button>
                </div>
            </div>
        </form>
    </div>
</div>
