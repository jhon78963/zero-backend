@extends('layout.template')

@section('title')
    Cursos
@endsection

@section('content')
    <div class="card mb-4">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Cursos</h5>
            <div style="padding-right: 1rem">
                <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1"
                    onclick="openCreateCourseModal()">
                    <span class="tf-icons bx bx-list-plus"></span>
                </button>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table" id="tabla-courses">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th width="20%">Curso</th>
                        <th width="60%">Grado</th>
                        <th width="10%">Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                </tbody>
            </table>
        </div>
    </div>

    @include('academic.course.course-create-modal')
    @include('academic.course.course-edit-modal')
    @include('academic.course.course-delete-modal')
@endsection

@section('js')
    {{-- LIST --}}
    <script>
        window.onload = function() {
            $.ajax({
                url: "{{ route('courses.getall') }}",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    let filas = "";
                    if (data.maxCount == 0) {
                        filas += `
                            <tr id="row-0">
                                <td  colspan="3">NO DATA</td>
                            </tr>
                        `;
                    } else {
                        $.each(data.courses, function(index, course) {
                            filas += `
                                <tr id="row-${course.id}">
                                    <td>${++index}</td>
                                    <td>${course.description}</td>
                                    <td>
                                        <div class="d-flex flex-wrap">
                                            ${getGradesName(course.grades)}
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="openEditCourseModal(${course.id})">
                                            <span class="tf-icons bx bx-edit-alt"></span>
                                        </button>
                                        <button type="button" class="btn rounded-pill btn-icon btn-outline-warning me-1" onclick="openAssignCourseModal(${course.id})">
                                            <span class="tf-icons bx bx-check-square"></span>
                                        </button>
                                        <button type="button" class="btn rounded-pill btn-icon btn-outline-danger me-1" onclick="openDeleteCourseModal(${course.id})">
                                            <span class="tf-icons bx bx-trash"></span>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    $("#tabla-courses tbody").html(filas);
                },
                error: function(xhr, status, error) {
                    let filas = '<tr><td colspan="3" >' + xhr.responseJSON.message +
                        '</td></tr>';
                    $("#tabla-courses tbody").html(filas);
                }
            });
        }

        function getGradesName(grades) {
            const gradesNames = grades.map(grade =>
                `<span class="badge bg-label-primary mb-1 me-1">${grade}</span>`
            ).join(' ');
            return gradesNames;
        }
    </script>

    {{-- ASSIGN --}}
    <script>
        function openAssignCourseModal(courseId) {

        }
    </script>

    {{-- CREATE --}}
    <script>
        function openCreateCourseModal() {
            $('#createCourseModal').modal('toggle');
        }

        $('#createCourseForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('courses.create') }}",
                method: 'POST',
                dataType: 'json',
                data: new FormData($("#createCourseForm")[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnCreateCourse').attr("disabled", true);
                    $('#btnCreateCourse').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Registrando...'
                    );
                },
                success: function(data) {
                    $('#createCourseModal').modal('hide');
                    $('#createCourseForm')[0].reset();
                    toastr.success('El registro fue creado correctamente.', 'Crear Registro', {
                        timeOut: 3000
                    });

                    if (data.count == 1) {
                        $(`#tabla-courses tbody #row-0`).html("");
                    }

                    const fila = `
                        <tr id="row-${data.course.id}">
                            <td>${data.count}</td>
                            <td>${data.course.description}</td>
                            <td>
                                <div class="d-flex">
                                    <button class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="openEditCourseModal(${data.course.id})">
                                        <span class="tf-icons bx bx-edit-alt"></span>
                                    </button>
                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-warning me-1" onclick="openAssignCourseModal(${data.course.id})">
                                        <span class="tf-icons bx bx-check-square"></span>
                                    </button>
                                    <button class="btn rounded-pill btn-icon btn-outline-danger me-1" onclick="openDeleteCourseModal(${data.course.id})">
                                        <span class="tf-icons bx bx-trash"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                    $("#tabla-courses tbody").append(fila);
                },
                error: function(data) {
                    $('#create-message-error-course').text(data.responseJSON.message);
                },
                complete: function() {
                    $('#btnCreateCourse').text('Registrar');
                    $('#btnCreateCourse').attr("disabled", false);
                }
            });
        });
    </script>

    {{-- EDIT --}}
    <script>
        function openEditCourseModal(courseId) {

            $.get(`/cursos/get/${courseId}`, function(data) {
                $('#e_id').val(data.course.id);
                $('#ec_description').val(data.course.description);
                $("input[name=_token]").val();
                $('#editCourseModal').modal('toggle');
            });
        }

        $('#editCourseForm').submit(function(e) {
            e.preventDefault();
            var courseId = $('#e_id').val();
            $.ajax({
                url: `/cursos/update/${courseId}`,
                method: 'POST',
                dataType: 'json',
                data: new FormData($("#editCourseForm")[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnUpdateCourse').attr("disabled", true);
                    $('#btnUpdateCourse').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Actualizando...'
                    );
                },
                success: function(data) {
                    $('#editCourseModal').modal('hide');
                    toastr.success('El registro fue actualizado correctamente.',
                        'Actualizar Registro', {
                            timeOut: 3000
                        });

                    const fila = `
                        <td>${data.position}</td>
                        <td>${data.course.description}</td>
                        <td>
                            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="openEditCourseModal(${data.course.id})">
                                <span class="tf-icons bx bx-edit-alt"></span>
                            </button>
                            <button type="button" class="btn rounded-pill btn-icon btn-outline-warning me-1" onclick="openAssignCourseModal(${data.course.id})">
                                <span class="tf-icons bx bx-check-square"></span>
                            </button>
                            <button type="button" class="btn rounded-pill btn-icon btn-outline-danger me-1" onclick="openDeleteCourseModal(${data.course.id})">
                                <span class="tf-icons bx bx-trash"></span>
                            </button>
                        </td>
                    `;
                    $(`#tabla-courses tbody #row-${data.course.id}`).html(fila);
                },
                error: function(data) {
                    $('#edit-message-error-course').text(data.responseJSON.message);
                },
                complete: function() {
                    $('#btnUpdateCourse').text('Actualizar');
                    $('#btnUpdateCourse').attr("disabled", false);
                }
            });

        });
    </script>

    {{-- DELETE --}}
    <script>
        function openDeleteCourseModal(courseId) {
            $.get(`/cursos/get/${courseId}`, function(data) {
                $('#d_id').val(data.course.id);
                $('#d_course_message').html(
                    `Deseas eliminar el salón <b>${data.course.description}</b> de la lista?`
                );
                $('#deleteCourseModal').modal('toggle');
            });
        }

        $('#deleteCourseForm').submit(function(e) {
            e.preventDefault();
            var d_id = $('#d_id').val();

            $.ajax({
                url: "/cursos/delete/" + d_id,
                beforeSend: function() {
                    $('#btnDeleteCourse').attr("disabled", true);
                    $('#btnDeleteCourse').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Eliminando...'
                    );
                },
                success: function(data) {
                    $('#deleteCourseModal').modal('hide');
                    toastr.error('El registro fue eliminado correctamente.',
                        'Eliminar Registro', {
                            timeOut: 3000
                        });

                    $(`#tabla-courses tbody #row-${data.course.id}`).html("");

                    if (data.count == 0) {
                        const fila = `
                            <tr id="row-0">
                                <td  colspan="3">NO DATA</td>
                            </tr>
                        `;

                        $(`#tabla-courses tbody`).html(fila);
                    }
                },
                complete: function() {
                    $('#btnDeleteCourse').text('Eliminar');
                    $('#btnDeleteCourse').attr("disabled", false);
                },
            })
        });
    </script>
@endsection
