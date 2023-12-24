<div class="modal fade" id="editSecretaryModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editSecretaryForm">
            @csrf
            @method('put')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Editar Secretaria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="e_id">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="e_dni" class="form-label">DNI</label>
                            <input type="text" id="e_dni" name="dni" class="form-control"
                                placeholder="DNI" />
                        </div>
                    </div>

                    <div class="row">
                        <label for="e_first_name" class="form-label">Nombres</label>
                        <div class="col-6 mb-3">
                            <input type="text" id="e_first_name" name="first_name" class="form-control"
                                placeholder="Primer nombre" />
                        </div>

                        <div class="col-6 mb-3">
                            <input type="text" id="e_other_names" name="other_names" class="form-control"
                                placeholder="Otros nombres" />
                        </div>
                    </div>

                    <div class="row">
                        <label for="e_surname" class="form-label">Apellidos</label>
                        <div class="col-6 mb-3">
                            <input type="text" id="e_surname" name="surname" class="form-control"
                                placeholder="Apellido paterno" />
                        </div>

                        <div class="col-6 mb-3">
                            <input type="text" id="e_mother_surname" name="mother_surname" class="form-control"
                                placeholder="Apellido materno" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="e_code" class="form-label">Código</label>
                            <input type="text" id="e_code" name="code" class="form-control"
                                placeholder="Código institucional" />
                        </div>

                        <div class="col-6 mb-3">
                            <label for="e_intitutional_email" class="form-label">Email</label>
                            <input type="text" id="e_intitutional_email" name="intitutional_email"
                                class="form-control" placeholder="E-mail institucional" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col mb-3">
                            <label for="e_phone" class="form-label">Celular</label>
                            <input type="text" id="e_phone" name="phone" class="form-control"
                                placeholder="Número de celular" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col mb-3">
                            <label for="e_address" class="form-label">Dirección</label>
                            <input type="text" id="e_address" name="address" class="form-control"
                                placeholder="Dirección" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnUpdateSecretary">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
