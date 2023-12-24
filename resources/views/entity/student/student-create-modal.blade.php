<div class="modal fade" id="createStudentModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="createStudentForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Registrar Estudiante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="c_dni" class="form-label">DNI</label>
                            <input type="text" id="c_dni" name="dni" class="form-control"
                                placeholder="DNI" />
                        </div>
                    </div>
                    <div class="row">
                        <label for="c_first_name" class="form-label">Nombres</label>
                        <div class="col-6 mb-3">
                            <input type="text" id="c_first_name" name="first_name" class="form-control"
                                placeholder="Primer nombre" />
                        </div>
                        <div class="col-6 mb-3">
                            <input type="text" id="c_other_names" name="other_names" class="form-control"
                                placeholder="Otros nombres" />
                        </div>
                    </div>
                    <div class="row">
                        <label for="c_surname" class="form-label">Apellidos</label>
                        <div class="col-6 mb-3">
                            <input type="text" id="c_surname" name="surname" class="form-control"
                                placeholder="Apellido paterno" />
                        </div>
                        <div class="col-6 mb-3">
                            <input type="text" id="c_mother_surname" name="mother_surname" class="form-control"
                                placeholder="Apellido materno" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="c_code" class="form-label">Código</label>
                            <input type="text" id="c_code" name="code" class="form-control"
                                placeholder="Código institucional" />
                        </div>
                        <div class="col-6 mb-3">
                            <label for="c_institutional_email" class="form-label">Email</label>
                            <input type="text" id="c_institutional_email" name="institutional_email"
                                class="form-control" placeholder="E-mail institucional" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="c_phone" class="form-label">Celular</label>
                            <input type="text" id="c_phone" name="phone" class="form-control"
                                placeholder="Número de celular" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="c_address" class="form-label">Dirección</label>
                            <input type="text" id="c_address" name="address" class="form-control"
                                placeholder="Dirección" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnCreateStudent">Registrar</button>
                </div>
            </div>
        </form>
    </div>
</div>
