<div class="modal fade" id="createScheduleModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="courseForm" method="POST" action="{{ route('save-schedule') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Registrar Horario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="courseSelect">Seleccionar Curso:</label>
                            <select id="courseSelect" name="course_id" class="form-control" style="25%;">
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="classroom_id">Aula</label>
                            <input type="text" value="{{ $classroom_description }}" class="form-control" readonly>
                            <input type="hidden" name="classroom_id" id="selected-classroom_id"
                                value="{{ $classroom_id }}">
                        </div>

                    </div>

                    <div class="row">
                        <div class="col mb-3">
                            <label for="selected_blocks">Horario</label>
                            <textarea name="selected_blocks" id="selected_blocks" cols="10" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnCreateGrade">Registrar</button>
                </div>
            </div>
        </form>
    </div>
</div>
