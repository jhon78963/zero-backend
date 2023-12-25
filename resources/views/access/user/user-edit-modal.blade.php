<div class="modal fade" id="modalUpdateUser" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="userUpdateForm">
            @csrf
            @method('put')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Editar Usuarios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="e_id">
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="name" class="form-label">Rol</label>
                            <input type="text" id="e_role" class="form-control" placeholder="Rol" readonly />
                        </div>
                        <div class="col mb-3">
                            <label for="name" class="form-label">Username</label>
                            <input type="text" id="e_username" class="form-control" placeholder="Username"
                                readonly />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="name" class="form-label">Nombres</label>
                            <input type="text" id="e_name" name="name" class="form-control"
                                placeholder="Nombre" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="dobBasic" class="form-label">Apellidos</label>
                            <input type="text" id="e_surname" name="surname" class="form-control"
                                placeholder="Apellidos" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" id="e_email" class="form-control" placeholder="xxxx@xxx.xx"
                                readonly />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-0">
                            <label for="phoneNumber" class="form-label">Celular</label>
                            <input type="text" id="e_phoneNumber" name="phoneNumber" class="form-control"
                                placeholder="Número de celular" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnUpdateUser">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
