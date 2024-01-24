<div class="modal fade" id="assignCourseTeacherModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="assignCourseTeacherForm" action="{{ route('workload.course.assign', $period->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Asignar Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <small class="text-light fw-semibold">Lista de cursos</small>
                    <input type="hidden" name="teacher_id" id="teacher_course_id">
                    <div class="demo-inline-spacing mt-3">
                        <div class="list-group">
                            @foreach ($courses as $course)
                                <label class="list-group-item">
                                    <input class="form-check-input me-1" type="checkbox" value="{{ $course->id }}"
                                        name="course_id[]"
                                        {{ $assignCourses->contains('course_id', $course->id) ? 'checked' : '' }}>
                                    {{ $course->description }}
                                </label>
                            @endforeach
                        </div>

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
