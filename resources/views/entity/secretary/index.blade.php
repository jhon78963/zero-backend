@extends('layout.template')

@section('title')
    Estudiante
@endsection

@section('content')
    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">Gestión de Secretarias</h5>
            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1"
                onclick="openCreateSecretaryModal()">
                <span class="tf-icons bx bx-list-plus"></span>
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table" id="tabla-secretaries">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">

                </tbody>
            </table>
        </div>
    </div>
    @include('entity.secretary.secretary-create-modal')
    @include('entity.secretary.secretary-edit-modal')
    @include('entity.secretary.secretary-delete-modal')
@endsection

@section('js')
    {{-- LIST --}}
    <script>
        window.onload = function() {
            $.ajax({
                url: "{{ route('admin.secretaries.getall') }}",
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
                        $.each(data.secretaries, function(index, secretary) {
                            filas += `
                            <tr id="row-${secretary.id}">
                                <td>${secretary.code}</td>
                                <td>${secretary.first_name} ${secretary.other_names != null ? secretary.other_names : ''}</td>
                                <td>${secretary.surname} ${secretary.mother_surname != null ? secretary.mother_surname : ''}</td>
                                <td>${secretary.institutional_email}</td>
                                <td>salon</td>
                                <td>
                                    <div class="d-flex">
                                        <button class="btn btn-primary btn-sm me-2" onclick="openEditSecretaryModal(${secretary.id})">
                                            <span class="tf-icons bx bx-edit-alt"></span>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="openDeleteSecretaryModal(${secretary.id})">
                                            <span class="tf-icons bx bx-trash"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                        });
                    }
                    $("#tabla-secretaries tbody").html(filas);
                },
                error: function(xhr, status, error) {
                    let filas = '<tr><td colspan="4" class="text-center">' + xhr.responseJSON.message +
                        '</td></tr>';
                    $("#tabla-secretaries tbody").html(filas);
                }
            });
        }
    </script>

    {{-- CREATE --}}
    <script>
        function openCreateSecretaryModal() {
            $('#createSecretaryModal').modal('toggle');
        }

        $('#createSecretaryForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('admin.secretaries.create') }}",
                method: 'POST',
                dataType: 'json',
                data: new FormData($("#createSecretaryForm")[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnCreateSecretary').attr("disabled", true);
                    $('#btnCreateSecretary').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Registrando...'
                    );
                },
                success: function(data) {
                    $('#createSecretaryModal').modal('hide');
                    $('#createSecretaryForm')[0].reset();
                    toastr.success('El registro fue creado correctamente.', 'Crear Registro', {
                        timeOut: 3000
                    });

                    if(data.count == 1){
                        $(`#tabla-secretaries tbody #row-0`).html("");
                    }

                    const fila = `
                        <tr id="row-${data.secretary.id}">
                            <td>${data.secretary.code}</td>
                            <td>${data.secretary.first_name} ${data.secretary.other_names != null ? data.secretary.other_names : ''}</td>
                            <td>${data.secretary.surname} ${data.secretary.mother_surname != null ? data.secretary.mother_surname : ''}</td>
                            <td>${data.secretary.institutional_email}</td>
                            <td>salón</td>
                            <td>
                                <div class="d-flex">
                                    <button class="btn btn-primary btn-sm me-2" onclick="openEditSecretaryModal(${data.secretary.id})">
                                        <span class="tf-icons bx bx-edit-alt"></span>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="openDeleteSecretaryModal(${data.secretary.id})">
                                        <span class="tf-icons bx bx-trash"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                    $("#tabla-secretaries tbody").append(fila);
                },
                complete: function() {
                    $('#btnCreateSecretary').text('Registrar');
                    $('#btnCreateSecretary').attr("disabled", false);
                }
            });
        });
    </script>

    {{-- EDIT --}}
    <script>
        function openEditSecretaryModal(secretaryId) {
            $.get('/admin/secretarias/get/' + secretaryId, function(data) {
                $('#e_id').val(data.secretary.id);
                $('#e_dni').val(data.secretary.dni);
                $('#e_first_name').val(data.secretary.first_name);
                $('#e_other_names').val(data.secretary.other_names);
                $('#e_surname').val(data.secretary.surname);
                $('#e_mother_surname').val(data.secretary.mother_surname);
                $('#e_code').val(data.secretary.code);
                $('#e_institutional_email').val(data.secretary.institutional_email);
                $('#e_phone').val(data.secretary.phone);
                $('#e_address').val(data.secretary.address);
                $("input[name=_token]").val();
                $('#editSecretaryModal').modal('toggle');
            })
        }

        $('#editSecretaryForm').submit(function(e) {
            e.preventDefault();
            var e_id = $('#e_id').val();
            $.ajax({
                url: "/admin/secretarias/update/" + e_id,
                method: 'POST',
                dataType: 'json',
                data: new FormData($("#editSecretaryForm")[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnUpdateSecretary').attr("disabled", true);
                    $('#btnUpdateSecretary').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Actualizando...'
                    );
                },
                success: function(data) {
                    $('#editSecretaryModal').modal('hide');
                    toastr.success('El registro fue actualizado correctamente.',
                        'Actualizar Registro', {
                            timeOut: 3000
                        });

                    const fila = `
                        <td>${data.secretary.code}</td>
                        <td>${data.secretary.first_name} ${data.secretary.other_names != null ? data.secretary.other_names : ''}</td>
                        <td>${data.secretary.surname} ${data.secretary.mother_surname != null ? data.secretary.mother_surname : ''}</td>
                        <td>${data.secretary.institutional_email}</td>
                        <td>salón</td>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-primary btn-sm me-2" onclick="openEditSecretaryModal(${data.secretary.id})">
                                    <span class="tf-icons bx bx-edit-alt"></span>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="openDeleteSecretaryModal(${data.secretary.id})">
                                    <span class="tf-icons bx bx-trash"></span>
                                </button>
                            </div>
                        </td>
                    `;
                    $(`#tabla-secretaries tbody #row-${data.secretary.id}`).html(fila);
                },
                complete: function() {
                    $('#btnUpdateSecretary').text('Actualizar');
                    $('#btnUpdateSecretary').attr("disabled", false);
                },
            })

        });
    </script>

    {{-- DELETE --}}
    <script>
        function openDeleteSecretaryModal(secretaryId) {
            $.get('/admin/secretarias/get/' + secretaryId, function(data) {
                $('#d_id').val(data.secretary.id);
                $('#d_message').html(
                    `Deseas eliminar el usuario <b>${data.secretary.first_name || ''} ${data.secretary.surname || ''}</b> de la lista?`
                );
                $('#deleteSecretaryModal').modal('toggle');
            });
        }

        $('#deleteSecretaryForm').submit(function(e) {
            e.preventDefault();
            var d_id = $('#d_id').val();

            $.ajax({
                url: "/admin/secretarias/delete/" + d_id,
                beforeSend: function() {
                    $('#btnDeleteSecretary').attr("disabled", true);
                    $('#btnDeleteSecretary').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Eliminando...'
                    );
                },
                success: function(data) {
                    $('#deleteSecretaryModal').modal('hide');
                    toastr.error('El registro fue eliminado correctamente.',
                        'Eliminar Registro', {
                            timeOut: 3000
                        });

                    $(`#tabla-secretaries tbody #row-${data.secretary.id}`).html("");

                    if(data.count == 0){
                        const fila = `
                            <tr id="row-0">
                                <td class="text-center" colspan="6">NO DATA</td>
                            </tr>
                        `;

                        $(`#tabla-secretaries tbody`).html(fila);
                    }
                },
                complete: function() {
                    $('#btnDeleteSecretary').text('Eliminar');
                    $('#btnDeleteSecretary').attr("disabled", false);
                },
            })
        });
    </script>
@endsection
