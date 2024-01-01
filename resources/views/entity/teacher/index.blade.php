@extends('layout.template')

@section('title')
    Docente
@endsection

@section('content')
    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">Gestión de profesores</h5>
            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1"
                onclick="openCreateTeacherModal()">
                <span class="tf-icons bx bx-list-plus"></span>
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table" id="tabla-teachers">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Salón</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">

                </tbody>
            </table>
        </div>
    </div>
    @include('entity.teacher.teacher-create-modal')
    @include('entity.teacher.teacher-edit-modal')
    @include('entity.teacher.teacher-delete-modal')
@endsection

@section('js')
    {{-- LIST --}}
    <script>
        window.onload = function() {
            $.ajax({
                url: "{{ route('teachers.getall') }}",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    let filas = "";
                    if (data.maxCount == 0) {
                        filas += `
                            <tr id="row-0">
                                <td class="text-center" colspan="6">NO DATA</td>
                            </tr>
                        `;
                    } else {
                        $.each(data.teachers, function(index, teacher) {
                            filas += `
                            <tr id="row-${teacher.id}">
                                <td>${teacher.code}</td>
                                <td>${teacher.first_name} ${teacher.other_names != null ? teacher.other_names : ''}</td>
                                <td>${teacher.surname} ${teacher.mother_surname != null ? teacher.mother_surname : ''}</td>
                                <td>${teacher.institutional_email}</td>
                                <td>salon</td>
                                <td>
                                    <div class="d-flex">
                                        <button class="btn btn-primary btn-sm me-2" onclick="openEditTeacherModal(${teacher.id})">
                                            <span class="tf-icons bx bx-edit-alt"></span>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="openDeleteTeacherModal(${teacher.id})">
                                            <span class="tf-icons bx bx-trash"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                        });
                    }
                    $("#tabla-teachers tbody").html(filas);
                },
                error: function(xhr, status, error) {
                    let filas = '<tr><td colspan="4" class="text-center">' + xhr.responseJSON.message +
                        '</td></tr>';
                    $("#tabla-teachers tbody").html(filas);
                }
            });
        }
    </script>

    {{-- CREATE --}}
    <script>
        function openCreateTeacherModal() {
            $('#createTeacherModal').modal('toggle');
        }

        $('#createTeacherForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('teachers.create') }}",
                method: 'POST',
                dataType: 'json',
                data: new FormData($("#createTeacherForm")[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnCreateTeacher').attr("disabled", true);
                    $('#btnCreateTeacher').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Registrando...'
                    );
                },
                success: function(data) {
                    $('#createTeacherModal').modal('hide');
                    $('#createTeacherForm')[0].reset();
                    toastr.success('El registro fue creado correctamente.', 'Crear Registro', {
                        timeOut: 3000
                    });

                     if(data.count == 1){
                        $(`#tabla-teachers tbody #row-0`).html("");
                    }

                    const fila = `
                        <tr id="row-${data.teacher.id}">
                            <td>${data.teacher.code}</td>
                            <td>${data.teacher.first_name} ${data.teacher.other_names != null ? data.teacher.other_names : ''}</td>
                            <td>${data.teacher.surname} ${data.teacher.mother_surname != null ? data.teacher.other_names : ''}</td>
                            <td>${data.teacher.institutional_email}</td>
                            <td>salón</td>
                            <td>
                                <div class="d-flex">
                                    <button class="btn btn-primary btn-sm me-2" onclick="openEditTeacherModal(${data.teacher.id})">
                                        <span class="tf-icons bx bx-edit-alt"></span>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="openDeleteTeacherModal(${data.teacher.id})">
                                        <span class="tf-icons bx bx-trash"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                    $("#tabla-teachers tbody").append(fila);
                },
                complete: function() {
                    $('#btnCreateTeacher').text('Registrar');
                    $('#btnCreateTeacher').attr("disabled", false);
                }
            });
        });
    </script>

    {{-- EDIT --}}
    <script>
        function openEditTeacherModal(teacherId) {
            $.get('/profesores/get/' + teacherId, function(data) {
                $('#e_id').val(data.teacher.id);
                $('#e_dni').val(data.teacher.dni);
                $('#e_first_name').val(data.teacher.first_name);
                $('#e_other_names').val(data.teacher.other_names);
                $('#e_surname').val(data.teacher.surname);
                $('#e_mother_surname').val(data.teacher.mother_surname);
                $('#e_code').val(data.teacher.code);
                $('#e_institutional_email').val(data.teacher.institutional_email);
                $('#e_phone').val(data.teacher.phone);
                $('#e_address').val(data.teacher.address);
                $("input[name=_token]").val();
                $('#editTeacherModal').modal('toggle');
            })
        }

        $('#editTeacherForm').submit(function(e) {
            e.preventDefault();
            var e_id = $('#e_id').val();
            $.ajax({
                url: "/profesores/update/" + e_id,
                method: 'POST',
                dataType: 'json',
                data: new FormData($("#editTeacherForm")[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnUpdateTeacher').attr("disabled", true);
                    $('#btnUpdateTeacher').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Actualizando...'
                    );
                },
                success: function(data) {
                    $('#editTeacherModal').modal('hide');
                    toastr.success('El registro fue actualizado correctamente.',
                        'Actualizar Registro', {
                            timeOut: 3000
                        });

                    const fila = `
                        <td>${data.teacher.code}</td>
                        <td>${data.teacher.first_name} ${data.teacher.other_names != null ? data.teacher.other_names : ''}</td>
                        <td>${data.teacher.surname} ${data.teacher.mother_surname != null ? data.teacher.mother_surname : ''}</td>
                        <td>${data.teacher.institutional_email}</td>
                        <td>salón</td>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-primary btn-sm me-2" onclick="openEditTeacherModal(${data.teacher.id})">
                                    <span class="tf-icons bx bx-edit-alt"></span>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="openDeleteTeacherModal(${data.teacher.id})">
                                    <span class="tf-icons bx bx-trash"></span>
                                </button>
                            </div>
                        </td>
                    `;
                    $(`#tabla-teachers tbody #row-${data.teacher.id}`).html(fila);
                },
                complete: function() {
                    $('#btnUpdateTeacher').text('Actualizar');
                    $('#btnUpdateTeacher').attr("disabled", false);
                },
            })

        });
    </script>

    {{-- DELETE --}}
    <script>
        function openDeleteTeacherModal(teacherId) {
            $.get('/profesores/get/' + teacherId, function(data) {
                $('#d_id').val(data.teacher.id);
                $('#d_message').html(
                    `Deseas eliminar el usuario <b>${data.teacher.first_name || ''} ${data.teacher.surname || ''}</b> de la lista?`
                );
                $('#deleteTeacherModal').modal('toggle');
            });
        }

        $('#deleteTeacherForm').submit(function(e) {
            e.preventDefault();
            var d_id = $('#d_id').val();

            $.ajax({
                url: "/profesores/delete/" + d_id,
                beforeSend: function() {
                    $('#btnDeleteTeacher').attr("disabled", true);
                    $('#btnDeleteTeacher').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Eliminando...'
                    );
                },
                success: function(data) {
                    $('#deleteTeacherModal').modal('hide');
                    toastr.error('El registro fue eliminado correctamente.',
                        'Eliminar Registro', {
                            timeOut: 3000
                        });

                    $(`#tabla-teachers tbody #row-${data.teacher.id}`).html("");

                    if (data.count == 0) {
                        const fila = `
                            <tr id="row-0">
                                <td class="text-center" colspan="6">NO DATA</td>
                            </tr>
                        `;

                        $(`#tabla-teachers tbody`).html(fila);
                    }
                },
                complete: function() {
                    $('#btnDeleteTeacher').text('Eliminar');
                    $('#btnDeleteTeacher').attr("disabled", false);
                },
            })
        });
    </script>
@endsection
