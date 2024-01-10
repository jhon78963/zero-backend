<div class="modal fade" id="editCourseModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editCourseForm">
            @csrf
            @method('put')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Editar Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="e_id">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="ec_description" class="form-label">Curso</label>
                            <input type="text" id="ec_description" name="description" class="form-control"
                                placeholder="Curso" />
                        </div>
                    </div>
                    <div class="row">
                        <span style="color: red;" id="edit-message-error-course"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnUpdateCourse">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
