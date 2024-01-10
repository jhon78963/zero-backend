<div class="modal fade" id="assignCourseModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="assignCourseForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Asignar Grados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="a_id" name="course_id">
                    <p id="a_message"></p>
                    <div id="gradesContainerCreate"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-warning" id="btnAssignCourse">Asignar</button>
                </div>
            </div>
        </form>
    </div>
</div>
