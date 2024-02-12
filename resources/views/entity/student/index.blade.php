@extends('layout.template')

@section('title')
    Estudiante
@endsection

@section('content')
    <div class="card">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="card-header">Gestión de Estudiantes</h5>

            <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                    <i class="bx bx-search fs-4 lh-0"></i>
                    <input type="text" name="search" id="search" class="form-control border-search shadow-none"
                        placeholder="Buscar..." style="width: 500px;">
                </div>
            </div>

            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-4"
                onclick="openCreateStudentModal()">
                <span class="tf-icons bx bx-list-plus"></span>
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table" id="tabla-students">
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

    <input type="hidden" value="{{ $period->id }}" id="period_id" name="period_id">

    @include('entity.student.student-create-modal')
    @include('entity.student.student-edit-modal')
    @include('entity.student.student-delete-modal')
@endsection

@section('css')
    <style>
        .border-search {
            border-top: 1px;
            border-right: 1px;
            border-left: 1px;
            border-radius: 0;
        }
    </style>
@endsection

@section('js')
    {{-- LIST --}}
    <script>
        const periodId = $('#period_id').val();
        window.onload = function() {
            $.ajax({
                url: `/${periodId}/estudiantes/getAll`,
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
                        $.each(data.students, function(index, student) {
                            filas += `
                            <tr id="row-${student.id}">
                                <td>${student.code}</td>
                                <td>${student.first_name} ${student.other_names  != null ? student.other_names : ''}</td>
                                <td>${student.surname} ${student.mother_surname  != null ? student.mother_surname : ''}</td>
                                <td>${student.institutional_email}</td>
                                <td>${student.classroom || ''}</td>
                                <td>
                                    <div class="d-flex">
                                        <button class="btn btn-primary btn-sm me-2" onclick="openEditStudentModal(${student.id})">
                                            <span class="tf-icons bx bx-edit-alt"></span>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="openDeleteStudentModal(${student.id})">
                                            <span class="tf-icons bx bx-trash"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                        });
                    }
                    $("#tabla-students tbody").html(filas);
                },
                error: function(xhr, status, error) {
                    let filas = '<tr><td colspan="4" class="text-center">' + xhr.responseJSON.message +
                        '</td></tr>';
                    $("#tabla-students tbody").html(filas);
                }
            });
        }
    </script>

    {{-- SEARCH --}}
    <script>
        $('#search').keyup(function() {
            let value = $('#search').val();
            if (value != '') {
                $.ajax({
                    url: `/${periodId}/estudiantes/search/${value}`,
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
                            $.each(data.students, function(index, student) {
                                filas += `
                            <tr id="row-${student.id}">
                                <td>${student.code}</td>
                                <td>${student.first_name} ${student.other_names  != null ? student.other_names : ''}</td>
                                <td>${student.surname} ${student.mother_surname  != null ? student.mother_surname : ''}</td>
                                <td>${student.institutional_email}</td>
                                <td>${student.classroom || ''}</td>
                                <td>
                                    <div class="d-flex">
                                        <button class="btn btn-primary btn-sm me-2" onclick="openEditStudentModal(${student.id})">
                                            <span class="tf-icons bx bx-edit-alt"></span>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="openDeleteStudentModal(${student.id})">
                                            <span class="tf-icons bx bx-trash"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                            });
                        }
                        $("#tabla-students tbody").html(filas);
                    },
                    error: function(xhr, status, error) {
                        let filas = '<tr><td colspan="4" class="text-center">' + xhr.responseJSON
                            .message +
                            '</td></tr>';
                        $("#tabla-students tbody").html(filas);
                    }
                });
            } else {
                $.ajax({
                    url: `/${periodId}/estudiantes/getAll`,
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
                            $.each(data.students, function(index, student) {
                                filas += `
                            <tr id="row-${student.id}">
                                <td>${student.code}</td>
                                <td>${student.first_name} ${student.other_names  != null ? student.other_names : ''}</td>
                                <td>${student.surname} ${student.mother_surname  != null ? student.mother_surname : ''}</td>
                                <td>${student.institutional_email}</td>
                                <td>${student.classroom || ''}</td>
                                <td>
                                    <div class="d-flex">
                                        <button class="btn btn-primary btn-sm me-2" onclick="openEditStudentModal(${student.id})">
                                            <span class="tf-icons bx bx-edit-alt"></span>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="openDeleteStudentModal(${student.id})">
                                            <span class="tf-icons bx bx-trash"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                            });
                        }
                        $("#tabla-students tbody").html(filas);
                    },
                    error: function(xhr, status, error) {
                        let filas = '<tr><td colspan="4" class="text-center">' + xhr.responseJSON
                            .message +
                            '</td></tr>';
                        $("#tabla-students tbody").html(filas);
                    }
                });
            }
        });
    </script>

    {{-- CREATE --}}
    <script>
        function openCreateStudentModal() {
            $('#createStudentModal').modal('toggle');
        }

        $('#createStudentForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: `/${periodId}/estudiantes/store`,
                method: 'POST',
                dataType: 'json',
                data: new FormData($("#createStudentForm")[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnCreateStudent').attr("disabled", true);
                    $('#btnCreateStudent').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Registrando...'
                    );
                },
                success: function(data) {
                    $('#createStudentModal').modal('hide');
                    $('#createStudentForm')[0].reset();
                    toastr.success('El registro fue creado correctamente.', 'Crear Registro', {
                        timeOut: 3000
                    });

                    if (data.count == 1) {
                        $(`#tabla-students tbody #row-0`).html("");
                    }

                    const fila = `
                        <tr id="row-${data.student.id}">
                            <td>${data.student.code}</td>
                            <td>${data.student.first_name} ${data.student.other_names != null ? data.student.other_names : ''}</td>
                            <td>${data.student.surname} ${data.student.mother_surname != null ? data.student.mother_surname : ''}</td>
                            <td>${data.student.institutional_email}</td>
                            <td>salón</td>
                            <td>
                                <div class="d-flex">
                                    <button class="btn btn-primary btn-sm me-2" onclick="openEditStudentModal(${data.student.id})">
                                        <span class="tf-icons bx bx-edit-alt"></span>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="openDeleteStudentModal(${data.student.id})">
                                        <span class="tf-icons bx bx-trash"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                    $("#tabla-students tbody").append(fila);
                },
                complete: function() {
                    $('#btnCreateStudent').text('Registrar');
                    $('#btnCreateStudent').attr("disabled", false);
                }
            });
        });
    </script>

    {{-- EDIT --}}
    <script>
        function openEditStudentModal(studentId) {
            $.get(`/${periodId}/estudiantes/get/${studentId}`, function(data) {
                $('#e_id').val(data.student.id);
                $('#e_dni').val(data.student.dni);
                $('#e_first_name').val(data.student.first_name);
                $('#e_other_names').val(data.student.other_names);
                $('#e_surname').val(data.student.surname);
                $('#e_mother_surname').val(data.student.mother_surname);
                $('#e_code').val(data.student.code);
                $('#e_institutional_email').val(data.student.institutional_email);
                $('#e_phone').val(data.student.phone);
                $('#e_address').val(data.student.address);
                $('#e_gender').val(data.student.gender);
                $("input[name=_token]").val();
                $('#editStudentModal').modal('toggle');
            });
        }

        $('#editStudentForm').submit(function(e) {
            e.preventDefault();
            var e_id = $('#e_id').val();
            $.ajax({
                url: `/${periodId}/estudiantes/update/${e_id}`,
                method: 'POST',
                dataType: 'json',
                data: new FormData($("#editStudentForm")[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnUpdateStudent').attr("disabled", true);
                    $('#btnUpdateStudent').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Actualizando...'
                    );
                },
                success: function(data) {
                    $('#editStudentModal').modal('hide');
                    toastr.success('El registro fue actualizado correctamente.',
                        'Actualizar Registro', {
                            timeOut: 3000
                        });

                    const fila = `
                        <td>${data.student.code}</td>
                        <td>${data.student.first_name} ${data.student.other_names != null ? data.student.other_names : ''}</td>
                        <td>${data.student.surname} ${data.student.mother_surname != null ? data.student.mother_surname : ''}</td>
                        <td>${data.student.institutional_email}</td>
                        <td>${data.student.classroom || ''}</td>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-primary btn-sm me-2" onclick="openEditStudentModal(${data.student.id})">
                                    <span class="tf-icons bx bx-edit-alt"></span>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="openDeleteStudentModal(${data.student.id})">
                                    <span class="tf-icons bx bx-trash"></span>
                                </button>
                            </div>
                        </td>
                    `;
                    $(`#tabla-students tbody #row-${data.student.id}`).html(fila);
                },
                complete: function() {
                    $('#btnUpdateStudent').text('Actualizar');
                    $('#btnUpdateStudent').attr("disabled", false);
                },
            })

        });
    </script>

    {{-- DELETE --}}
    <script>
        function openDeleteStudentModal(studentId) {
            $.get(`/${periodId}/estudiantes/get/${studentId}`, function(data) {
                $('#d_id').val(data.student.id);
                $('#d_message').html(
                    `Deseas eliminar el usuario <b>${data.student.first_name || ''} ${data.student.surname || ''}</b> de la lista?`
                );
                $('#deleteStudentModal').modal('toggle');
            });
        }

        $('#deleteStudentForm').submit(function(e) {
            e.preventDefault();
            var d_id = $('#d_id').val();

            $.ajax({
                url: `/${periodId}/estudiantes/delete/${d_id}`,
                beforeSend: function() {
                    $('#btnDeleteStudent').attr("disabled", true);
                    $('#btnDeleteStudent').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Eliminando...'
                    );
                },
                success: function(data) {
                    $('#deleteStudentModal').modal('hide');
                    toastr.error('El registro fue eliminado correctamente.',
                        'Eliminar Registro', {
                            timeOut: 3000
                        });

                    $(`#tabla-students tbody #row-${data.student.id}`).html("");

                    if (data.count == 0) {
                        const fila = `
                            <tr id="row-0">
                                <td class="text-center" colspan="6">NO DATA</td>
                            </tr>
                        `;

                        $(`#tabla-students tbody`).html(fila);
                    }
                },
                complete: function() {
                    $('#btnDeleteStudent').text('Eliminar');
                    $('#btnDeleteStudent').attr("disabled", false);
                },
            })
        });
    </script>
@endsection
