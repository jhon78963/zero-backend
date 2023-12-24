<div class="modal fade" id="deleteSecretaryModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="deleteSecretaryForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Eliminar Secretaria</h5>
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
                        <button type="submit" class="btn btn-danger" id="btnDeleteSecretary">Eliminar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
