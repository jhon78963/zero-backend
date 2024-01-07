<div class="modal fade" id="createClassRoomModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="createClassRoomForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Registrar Aula</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="ccr-grade_id" class="form-label">Grado</label>
                            <select name="grade_id" id="ccr-grade_id" class="form-control">
                                <option value="">Seleccione ...</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="ccr-section_id" class="form-label">Sección</label>
                            <select name="section_id" id="ccr-section_id" class="form-control">
                                <option value="">Seleccione ...</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="ccr_limit" class="form-label">Nro Vacantes</label>
                            <input type="text" id="ccr_limit" name="limit" class="form-control"
                                placeholder="Nro de vacances" />
                        </div>
                    </div>
                    <div class="row">
                        <span style="color: red;" id="create-message-error-class-room"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnCreateClassRoom">Registrar</button>
                </div>
            </div>
        </form>
    </div>
</div>
