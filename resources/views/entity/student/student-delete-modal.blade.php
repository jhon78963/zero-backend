<div class="modal fade" id="deleteStudentModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="deleteStudentForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Eliminar Estudiante</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="d_id">
                        <p id="d_message"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cerrar
                        </button>
                        <button type="submit" class="btn btn-danger" id="btnDeleteStudent">Eliminar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
