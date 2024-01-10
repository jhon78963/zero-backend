<div class="modal fade" id="createCourseModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="createCourseForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Registrar Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="cc_description" class="form-label">Curso</label>
                            <input type="text" id="cc_description" name="description" class="form-control"
                                placeholder="Curso" />
                        </div>
                    </div>
                    <div class="row">
                        <span style="color: red;" id="create-message-error-course"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnCreateCourse">Registrar</button>
                </div>
            </div>
        </form>
    </div>
</div>
