<div class="modal fade" id="deleteRoleModal" aria-labelledby="modalDeleteRoleLabel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="deleteRoleForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteRoleLabel">Eliminar rol</h5>
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
                    <button type="submit" class="btn btn-danger" id="btnDeleteRole">Eliminar</button>
                </div>
            </div>
        </form>
    </div>
</div>
