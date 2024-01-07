<div class="modal fade" id="editGradeModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editGradeForm">
            @csrf
            @method('put')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Editar Grado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="e_id">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="eg_description" class="form-label">Grado</label>
                            <input type="text" id="eg_description" name="description" class="form-control"
                                placeholder="Grado" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnUpdateGrade">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
