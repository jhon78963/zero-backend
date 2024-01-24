<div class="modal fade" id="assignClassroomTeacherModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="assignClassroomTeacherForm" action="{{ route('workload.classroom.assign', $period->id) }}"
            method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Asignar Aula</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <small class="text-light fw-semibold">Lista de aulas</small>
                    <input type="hidden" name="teacher_id" id="teacher_classroom_id">
                    <div class="demo-inline-spacing mt-3">
                        <select name="classroom_id" id="classroom_id" class="form-control">
                            <option value="">Seleccione ...</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnCreateGrade">Registrar</button>
                </div>
            </div>
        </form>
    </div>
</div>
