@extends('layout.template')

@section('title')
    Aulas
@endsection

@section('content')
    <div class="card mb-4">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Gestión de Aulas</h5>
            <div style="padding-right: 1rem">
                <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1"
                    onclick="openCreateClassRoomModal()">
                    <span class="tf-icons bx bx-list-plus"></span>
                </button>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table" id="tabla-class-room">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th class="text-center">Aula</th>
                        <th class="text-center">Vacantes</th>
                        <th class="text-center">Asignados</th>
                        <th width="10%">Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0"></tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-12">
            <div class="card mb-4">
                <div class="d-flex align-items-center justify-content-between ">
                    <h5 class="card-header">Gestión de Grados</h5>
                    <div style="padding-right: 1rem">
                        <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1"
                            onclick="openCreateGradeModal()">
                            <span class="tf-icons bx bx-list-plus"></span>
                        </button>
                    </div>
                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table" id="tabla-grades">
                        <thead>
                            <tr>
                                <th width="10%">#</th>
                                <th class="text-center">Grado</th>
                                <th width="10%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card mb-4">
                <div class="d-flex align-items-center justify-content-between ">
                    <h5 class="card-header">Gestión de Secciones</h5>
                    <div style="padding-right: 1rem">
                        <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1"
                            onclick="openCreateSectionModal()">
                            <span class="tf-icons bx bx-list-plus"></span>
                        </button>
                    </div>
                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table" id="tabla-sections">
                        <thead>
                            <tr>
                                <th width="10%">#</th>
                                <th class="text-center">Secciones</th>
                                <th width="10%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('academic.grade.grade-create-modal')
    @include('academic.grade.grade-edit-modal')
    @include('academic.grade.grade-delete-modal')
    @include('academic.section.section-create-modal')
    @include('academic.section.section-edit-modal')
    @include('academic.section.section-delete-modal')
    @include('academic.classroom.classroom-create-modal')
    @include('academic.classroom.classroom-edit-modal')
    @include('academic.classroom.classroom-delete-modal')
@endsection

@section('css')
    <style>
        .form-control[readonly] {
            background-color: white !important;
            opacity: 1;
        }
    </style>
@endsection

@section('js')
    {{-- LISTS --}}
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
                                <td class="text-center" colspan="3">NO DATA</td>
                            </tr>
                        `;
                    } else {
                        $.each(data.grades, function(index, grade) {
                            filas += `
                            <tr id="row-${grade.id}">
                                <td>${++index}</td>
                                <td class="text-center">${grade.description}</td>
                                <td>
                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="openEditGradeModal(${grade.id})">
                                        <span class="tf-icons bx bx-edit-alt"></span>
                                    </button>
                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-danger me-1" onclick="openDeleteGradeModal(${grade.id})">
                                        <span class="tf-icons bx bx-trash"></span>
                                    </button>
                                </td>
                            </tr>
                        `;
                        });
                    }
                    $("#tabla-grades tbody").html(filas);
                },
                error: function(xhr, status, error) {
                    let filas = '<tr><td colspan="3" class="text-center">' + xhr.responseJSON.message +
                        '</td></tr>';
                    $("#tabla-grades tbody").html(filas);
                }
            });

            $.ajax({
                url: "{{ route('sections.getall') }}",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    let filas = "";
                    if (data.maxCount == 0) {
                        filas += `
                            <tr id="row-0">
                                <td class="text-center" colspan="3">NO DATA</td>
                            </tr>
                        `;
                    } else {
                        $.each(data.sections, function(index, section) {
                            filas += `
                            <tr id="row-${section.id}">
                                <td>${section.id}</td>
                                <td class="text-center">${section.description}</td>
                                <td>
                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="openEditSectionModal(${section.id})">
                                        <span class="tf-icons bx bx-edit-alt"></span>
                                    </button>
                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-danger me-1" onclick="openDeleteSectionModal(${section.id})">
                                        <span class="tf-icons bx bx-trash"></span>
                                    </button>
                                </td>
                            </tr>
                        `;
                        });
                    }
                    $("#tabla-sections tbody").html(filas);
                },
                error: function(xhr, status, error) {
                    let filas = '<tr><td colspan="3" class="text-center">' + xhr.responseJSON.message +
                        '</td></tr>';
                    $("#tabla-sections tbody").html(filas);
                }
            });

            $.ajax({
                url: "{{ route('class-room.getall') }}",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    let filas = "";
                    if (data.maxCount == 0) {
                        filas += `
                            <tr id="row-0">
                                <td class="text-center" colspan="5">NO DATA</td>
                            </tr>
                        `;
                    } else {
                        $.each(data.classRooms, function(index, classRoom) {
                            filas += `
                            <tr id="row-${classRoom.id}">
                                <td>${++index}</td>
                                <td class="text-center">${classRoom.description}</td>
                                <td class="text-center">${classRoom.limit}</td>
                                <td class="text-center">${classRoom.students_number}</td>
                                <td>
                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="openEditClassRoomModal(${classRoom.grade_id}, ${classRoom.section_id})">
                                        <span class="tf-icons bx bx-edit-alt"></span>
                                    </button>
                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-danger me-1" onclick="openDeleteClassRoomModal(${classRoom.grade_id}, ${classRoom.section_id})">
                                        <span class="tf-icons bx bx-trash"></span>
                                    </button>
                                </td>
                            </tr>
                        `;
                        });
                    }
                    $("#tabla-class-room tbody").html(filas);
                },
                error: function(xhr, status, error) {
                    let filas = '<tr><td colspan="5" class="text-center">' + xhr.responseJSON.message +
                        '</td></tr>';
                    $("#tabla-class-room tbody").html(filas);
                }
            });

            $.get('/grados/getAll', function(data) {
                let selectElement = $('#ccr-grade_id');
                $('option', selectElement).not(':first').remove();

                $.each(data.grades, function(index, grade) {
                    selectElement.append($('<option>', {
                        value: grade.id,
                        text: grade.description
                    }));
                });
            });

            $.get('/secciones/getAll', function(data) {
                let selectElement = $('#ccr-section_id');
                $('option', selectElement).not(':first').remove();

                $.each(data.sections, function(index, section) {
                    selectElement.append($('<option>', {
                        value: section.id,
                        text: section.description
                    }));
                });
            });

            $.get('/grados/getAll', function(data) {
                let selectElement = $('#ecr-grade_id');
                $('option', selectElement).not(':first').remove();

                $.each(data.grades, function(index, grade) {
                    selectElement.append($('<option>', {
                        value: grade.id,
                        text: grade.description
                    }));
                });
            });

            $.get('/secciones/getAll', function(data) {
                let selectElement = $('#ecr-section_id');
                $('option', selectElement).not(':first').remove();

                $.each(data.sections, function(index, section) {
                    selectElement.append($('<option>', {
                        value: section.id,
                        text: section.description
                    }));
                });
            });
        }
    </script>

    {{-- CREATE --}}
    <script>
        function openCreateGradeModal() {
            $('#createGradeModal').modal('toggle');
        }

        function openCreateSectionModal() {
            $('#createSectionModal').modal('toggle');
        }

        function openCreateClassRoomModal() {
            $('#createClassRoomModal').modal('toggle');
        }

        $('#createGradeForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('grades.create') }}",
                method: 'POST',
                dataType: 'json',
                data: new FormData($("#createGradeForm")[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnCreateGrade').attr("disabled", true);
                    $('#btnCreateGrade').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Registrando...'
                    );
                },
                success: function(data) {
                    $('#createGradeModal').modal('hide');
                    $('#createGradeForm')[0].reset();
                    toastr.success('El registro fue creado correctamente.', 'Crear Registro', {
                        timeOut: 3000
                    });

                    if (data.count == 1) {
                        $(`#tabla-grades tbody #row-0`).html("");
                    }

                    const fila = `
                        <tr id="row-${data.grade.id}">
                            <td>${data.count}</td>
                            <td class="text-center">${data.grade.description}</td>
                            <td>
                                <div class="d-flex">
                                    <button class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="openEditGradeModal(${data.grade.id})">
                                        <span class="tf-icons bx bx-edit-alt"></span>
                                    </button>
                                    <button class="btn rounded-pill btn-icon btn-outline-danger me-1" onclick="openDeleteGradeModal(${data.grade.id})">
                                        <span class="tf-icons bx bx-trash"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                    $("#tabla-grades tbody").append(fila);

                    let selectCreateElement = $('#ccr-grade_id');
                    selectCreateElement.append($('<option>', {
                        value: data.grade.id,
                        text: data.grade.description
                    }));

                    let selectEditElement = $('#ecr-grade_id');
                    selectEditElement.append($('<option>', {
                        value: data.grade.id,
                        text: data.grade.description
                    }));
                },
                error: function(data) {
                    $('#create-message-error-grade').text(data.responseJSON.message);
                },
                complete: function() {
                    $('#btnCreateGrade').text('Registrar');
                    $('#btnCreateGrade').attr("disabled", false);
                }
            });
        });

        $('#createSectionForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('sections.create') }}",
                method: 'POST',
                dataType: 'json',
                data: new FormData($("#createSectionForm")[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnCreateSection').attr("disabled", true);
                    $('#btnCreateSection').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Registrando...'
                    );
                },
                success: function(data) {
                    $('#createSectionModal').modal('hide');
                    $('#createSectionForm')[0].reset();
                    toastr.success('El registro fue creado correctamente.', 'Crear Registro', {
                        timeOut: 3000
                    });

                    if (data.count == 1) {
                        $(`#tabla-sections tbody #row-0`).html("");
                    }

                    const fila = `
                        <tr id="row-${data.section.id}">
                            <td>${data.count}</td>
                            <td class="text-center">${data.section.description}</td>
                            <td>
                                <div class="d-flex">
                                    <button class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="openEditGradeModal(${data.section.id})">
                                        <span class="tf-icons bx bx-edit-alt"></span>
                                    </button>
                                    <button class="btn rounded-pill btn-icon btn-outline-danger me-1" onclick="openDeleteGradeModal(${data.section.id})">
                                        <span class="tf-icons bx bx-trash"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                    $("#tabla-sections tbody").append(fila);

                    let selectCreateElement = $('#ccr-section_id');
                    selectCreateElement.append($('<option>', {
                        value: data.section.id,
                        text: data.section.description
                    }));

                    let selectEditElement = $('#ecr-section_id');
                    selectEditElement.append($('<option>', {
                        value: data.section.id,
                        text: data.section.description
                    }));
                },
                error: function(data) {
                    $('#create-message-error-section').text(data.responseJSON.message);
                },
                complete: function() {
                    $('#btnCreateSection').text('Registrar');
                    $('#btnCreateSection').attr("disabled", false);
                }
            });
        });

        $('#createClassRoomForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('class-room.create') }}",
                method: 'POST',
                dataType: 'json',
                data: new FormData($("#createClassRoomForm")[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnCreateClassRoom').attr("disabled", true);
                    $('#btnCreateClassRoom').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Registrando...'
                    );
                },
                success: function(data) {
                    $('#createClassRoomModal').modal('hide');
                    $('#createClassRoomForm')[0].reset();
                    toastr.success('El registro fue creado correctamente.', 'Crear Registro', {
                        timeOut: 3000
                    });

                    if (data.count == 1) {
                        $(`#tabla-class-room tbody #row-0`).html("");
                    }

                    const fila = `
                        <tr id="row-${data.classRoom.id}">
                            <td>${data.count}</td>
                            <td class="text-center">${data.classRoom.description}</td>
                            <td class="text-center">${data.classRoom.limit}</td>
                            <td class="text-center">${data.classRoom.students_number}</td>
                            <td>
                                <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="openEditClassRoomModal(${data.classRoom.grade_id}, ${data.classRoom.section_id})">
                                    <span class="tf-icons bx bx-edit-alt"></span>
                                </button>
                                <button type="button" class="btn rounded-pill btn-icon btn-outline-danger me-1" onclick="openDeleteClassRoomModal(${data.classRoom.grade_id}, ${data.classRoom.section_id})">
                                    <span class="tf-icons bx bx-trash"></span>
                                </button>
                            </td>
                        </tr>
                    `;
                    $("#tabla-class-room tbody").append(fila);
                },
                error: function(data) {
                    $('#create-message-error-class-room').text(data.responseJSON.message);
                },
                complete: function() {
                    $('#btnCreateClassRoom').text('Registrar');
                    $('#btnCreateClassRoom').attr("disabled", false);
                }
            });
        });
    </script>

    {{-- EDIT --}}
    <script>
        function openEditGradeModal(gradeId) {
            $('#editGradeModal').modal('toggle');
        }

        function openEditSectionModal(sectionId) {
            $('#editSectionModal').modal('toggle');
        }

        function openEditClassRoomModal(gradeId, sectionId) {
            $.get(`/aulas/get/${gradeId}/${sectionId}`, function(data) {
                $('#e_grade_id').val(data.classRoom.grade_id);
                $('#e_section_id').val(data.classRoom.section_id);
                $('#ecr-grade_id').val(data.classRoom.grade_id);
                $('#ecr-section_id').val(data.classRoom.section_id);
                $('#ecr_limit').val(data.classRoom.limit);
                $("input[name=_token]").val();
                $('#editClassRoomModal').modal('toggle');
            });
        }

        $('#editClassRoomForm').submit(function(e) {
            e.preventDefault();
            var gradeId = $('#e_grade_id').val();
            var sectionId = $('#e_section_id').val();
            $.ajax({
                url: `/aulas/update/${gradeId}/${sectionId}`,
                method: 'POST',
                dataType: 'json',
                data: new FormData($("#editClassRoomForm")[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnUpdateClassRoom').attr("disabled", true);
                    $('#btnUpdateClassRoom').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Actualizando...'
                    );
                },
                success: function(data) {
                    $('#editClassRoomModal').modal('hide');
                    toastr.success('El registro fue actualizado correctamente.',
                        'Actualizar Registro', {
                            timeOut: 3000
                        });

                    const fila = `
                        <td>${data.position}</td>
                        <td class="text-center">${data.classRoom.description}</td>
                        <td class="text-center">${data.classRoom.limit}</td>
                        <td class="text-center">${data.classRoom.students_number}</td>
                        <td>
                            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="openEditClassRoomModal(${data.classRoom.grade_id}, ${data.classRoom.section_id})">
                                <span class="tf-icons bx bx-edit-alt"></span>
                            </button>
                            <button type="button" class="btn rounded-pill btn-icon btn-outline-danger me-1" onclick="openDeleteClassRoomModal(${data.classRoom.grade_id}, ${data.classRoom.section_id})">
                                <span class="tf-icons bx bx-trash"></span>
                            </button>
                        </td>
                    `;
                    $(`#tabla-class-room tbody #row-${data.classRoom.id}`).html(fila);
                },
                complete: function() {
                    $('#btnUpdateClassRoom').text('Actualizar');
                    $('#btnUpdateClassRoom').attr("disabled", false);
                },
            })

        });
    </script>

    {{-- DELETE --}}
    <script>
        function openDeleteGradeModal(gradeId) {
            $('#deleteGradeModal').modal('toggle');
        }

        function openDeleteSectionModal(sectionId) {
            $('#deleteSectionModal').modal('toggle');
        }

        function openDeleteClassRoomModal(gradeId, sectionId) {
            $.get(`/aulas/get/${gradeId}/${sectionId}`, function(data) {
                $('#d_classroom_id').val(data.classRoom.id);
                $('#d_message_classroom').html(
                    `Deseas eliminar el salón <b>${data.classRoom.description}</b> de la lista?`
                );
                $('#deleteClassRoomModal').modal('toggle');
            });
        }

        $('#deleteClassRoomForm').submit(function(e) {
            e.preventDefault();
            var d_id = $('#d_classroom_id').val();

            $.ajax({
                url: "/aulas/delete/" + d_id,
                beforeSend: function() {
                    $('#btnDeleteClassRoom').attr("disabled", true);
                    $('#btnDeleteClassRoom').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Eliminando...'
                    );
                },
                success: function(data) {
                    $('#deleteClassRoomModal').modal('hide');
                    toastr.error('El registro fue eliminado correctamente.',
                        'Eliminar Registro', {
                            timeOut: 3000
                        });

                    $(`#tabla-class-room tbody #row-${data.classRoom.id}`).html("");

                    if (data.count == 0) {
                        const fila = `
                            <tr id="row-0">
                                <td class="text-center" colspan="6">NO DATA</td>
                            </tr>
                        `;

                        $(`#tabla-class-room tbody`).html(fila);
                    }
                },
                complete: function() {
                    $('#btnDeleteClassRoom').text('Eliminar');
                    $('#btnDeleteClassRoom').attr("disabled", false);
                },
            })
        });
    </script>
@endsection
