@extends('layout.template')

@section('title')
    Usuario
@endsection

@section('content')
    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">Gestión de usuarios</h5>
            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" data-bs-toggle="modal"
                data-bs-target="#modalCreateUser">
                <span class="tf-icons bx bx-list-plus"></span>
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table" id="tabla-users">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">

                </tbody>
            </table>
        </div>
    </div>

    @include('access.user.user-assign-modal')
    @include('access.user.user-create-modal')
    @include('access.user.user-delete-modal')
    @include('access.user.user-edit-modal')
@endsection

@section('js')
    {{-- LIST --}}
    <script>
        window.onload = function() {
            $.ajax({
                url: "{{ route('users.getall') }}",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    let filas = "";
                    $.each(data.users, function(index, user) {
                        filas += `
                            <tr id="row-${user.id}">
                                <td>${user.name} ${user.surname || ''}</td>
                                <td>${user.email}</td>
                                <td>
                                    <div class="d-flex flex-wrap">
                                        ${getRolesName(user.roles)}
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="editUser(${user.id})">
                                        <span class="tf-icons bx bx-edit-alt"></span>
                                    </button>
                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-warning me-1" onclick="assignUser(${user.id})">
                                        <span class="tf-icons bx bx-user-check"></span>
                                    </button>
                                    ${getDeleteButtonNonAdmin(user.id)}
                                </td>
                            </tr>
                        `;
                    });

                    $("#tabla-users tbody").html(filas);
                },
                error: function(xhr, status, error) {
                    let filas = '<tr><td colspan="4" class="text-center">' + xhr.responseJSON.message +
                        '</td></tr>';
                    $("#tabla-users tbody").html(filas);
                }
            });
        }

        function getDeleteButtonNonAdmin(userId) {
            if (userId !== 1) {
                return `
                    <button type="button" class="btn rounded-pill btn-icon btn-outline-danger" onclick="deleteUser(${userId})">
                        <span class="tf-icons bx bx-trash"></span>
                    </button>
                    `;
            } else {
                return '';
            }
        }

        function getRolesName(roles) {
            const rolesNames = roles.map(role =>
                `<span class="badge bg-label-primary mb-1 me-1">${role}</span>`
            ).join(' ');
            return rolesNames;
        }

        function getRolesNameTransaction(roles) {
            const rolesNames = roles.map(role =>
                `<span class="badge bg-label-primary mb-1 me-1">${role.role.name}</span>`
            ).join(' ');
            return rolesNames;
        }
    </script>

    {{-- CREATE --}}
    <script>
        $('#userCreateForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('users.create') }}",
                method: 'POST',
                dataType: 'json',
                data: new FormData($("#userCreateForm")[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnCreateUser').attr("disabled", true);
                    $('#btnCreateUser').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Registrando...'
                    );
                },
                success: function(data) {
                    $('#modalCreateUser').modal('hide');
                    $('#userCreateForm')[0].reset();
                    toastr.success('El registro fue creado correctamente.',
                        'Crear Registro', {
                            timeOut: 3000
                        });

                    let fila = `
                        <tr id="row-${data.user.id}">
                            <td>${data.user.name} ${data.user.surname || ''}</td>
                            <td>${data.user.email}</td>
                            <td>
                                <div class="d-flex flex-wrap">
                                    ${getRolesName(data.user.user_roles)}
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="editUser(${data.user.id})">
                                    <span class="tf-icons bx bx-edit-alt"></span>
                                </button>
                                <button type="button" class="btn rounded-pill btn-icon btn-outline-warning me-1" onclick="assignUser(${data.user.id})">
                                    <span class="tf-icons bx bx-user-check"></span>
                                </button>
                                ${getDeleteButtonNonAdmin(data.user.id)}
                            </td>
                        </tr>
                    `;

                    $("#tabla-users tbody").append(fila);
                },
                complete: function() {
                    $('#btnCreateUser').text('Registrar');
                    $('#btnCreateUser').attr("disabled", false);
                }
            });
        });
    </script>

    {{-- EDIT --}}
    <script>
        function editUser(user_id) {
            $.get('/users/get/' + user_id, function(data) {
                $('#e_id').val(data.user.id);
                $('#e_username').val(data.user.username);
                $('#e_role').val(data.user.roles[0]);
                $('#e_name').val(data.user.name);
                $('#e_surname').val(data.user.surname);
                $('#e_email').val(data.user.email);
                $('#e_phoneNumber').val(data.user.phoneNumber);
                $("input[name=_token]").val();
                $('#modalUpdateUser').modal('toggle');
            })
        }

        $('#userUpdateForm').submit(function(e) {
            e.preventDefault();
            var e_id = $('#e_id').val();
            $.ajax({
                url: "/users/update/" + e_id,
                method: 'POST',
                dataType: 'json',
                data: new FormData($("#userUpdateForm")[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnUpdateUser').attr("disabled", true);
                    $('#btnUpdateUser').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Actualizando...'
                    );
                },
                success: function(data) {
                    $('#modalUpdateUser').modal('hide');
                    toastr.success('El registro fue actualizado correctamente.',
                        'Actualizar Registro', {
                            timeOut: 3000
                        });

                    let fila = `
                        <td>${data.user.name} ${data.user.surname || ''}</td>
                        <td>${data.user.email}</td>
                        <td>
                            <div class="d-flex flex-wrap">
                                ${getRolesNameTransaction(data.user.user_roles)}
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="editUser(${data.user.id})">
                                <span class="tf-icons bx bx-edit-alt"></span>
                            </button>
                            <button type="button" class="btn rounded-pill btn-icon btn-outline-warning me-1" onclick="assignUser(${data.user.id})">
                                <span class="tf-icons bx bx-user-check"></span>
                            </button>
                            ${getDeleteButtonNonAdmin(data.user.id)}
                        </td>
                    `;

                    $(`#tabla-users tbody #row-${data.user.id}`).html(fila);
                },
                complete: function() {
                    $('#btnUpdateUser').text('Actualizar');
                    $('#btnUpdateUser').attr("disabled", false);
                },
            })

        });
    </script>

    {{-- Assign --}}
    <script>
        function assignUser(user_id) {
            $.get('/users/get/' + user_id, function(userData) {
                console.log(userData);
                $('#a_id').val(userData.user.id);
                $('#a_message').html(`Selecciona un <b>rol</b> de la lista a asignar`);
                const rolesContainer = $('#rolesContainerCreate');

                $.get('/roles/getAll', function(data) {
                    rolesContainer.empty()
                    for (const role of data.roles) {
                        let checkboxHtml = `
                            <div class="input-group mt-2">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" value="${role.id}" aria-label="Checkbox for following text input" name="roleId" ${role.id == userData.user.role_id[0] ? 'checked' : ''}>
                                </div>
                                <input type="text" class="form-control" aria-label="Text input with checkbox" value="${role.name}" readonly name="roles-text[]" style="background-color: white;color: #697a8d;">
                            </div>`;
                        rolesContainer.append(checkboxHtml);
                    }
                });

                $("input[name=_token]").val();
                $('#modalAssignUser').modal('toggle');
            })
        }
    </script>

    {{-- Assign store --}}
    <script>
        $('#userAssignForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('users.assign') }}",
                method: 'POST',
                dataType: 'json',
                data: new FormData($("#userAssignForm")[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnAssignUser').attr("disabled", true);
                    $('#btnAssignUser').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Asignando...'
                    );
                },
                success: function(data) {
                    $('#modalAssignUser').modal('hide');
                    $('#userAssignForm')[0].reset();
                    toastr.success('El role fue asignado correctamente.',
                        'Rol Asignado', {
                            timeOut: 3000
                        });

                    let fila = `
                        <td>${data.user.name} ${data.user.surname || ''}</td>
                        <td>${data.user.email}</td>
                        <td>
                            <div class="d-flex flex-wrap">
                                ${getRolesNameTransaction(data.user.user_roles)}
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="editUser(${data.user.id})">
                                <span class="tf-icons bx bx-edit-alt"></span>
                            </button>
                            <button type="button" class="btn rounded-pill btn-icon btn-outline-warning me-1" onclick="assignUser(${data.user.id})">
                                <span class="tf-icons bx bx-user-check"></span>
                            </button>
                            ${getDeleteButtonNonAdmin(data.user.id)}
                        </td>
                    `;

                    $(`#tabla-users tbody #row-${data.user.id}`).html(fila);
                },
                complete: function() {
                    $('#btnAssignUser').text('Asignar');
                    $('#btnAssignUser').attr("disabled", false);
                }
            });
        });
    </script>

    {{-- DELETE --}}
    <script>
        function deleteUser(user_id) {
            $.get('users/get/' + user_id, function(data) {
                $('#d_id').val(data.user.id);
                $('#d_message').html(
                    `Deseas eliminar el usuario <b>${data.user.name || ''} ${data.user.surname || ''}</b> de la lista?`
                );
                $('#modalDeleteUser').modal('toggle');
            })
        }

        $('#userDeleteForm').submit(function(e) {
            e.preventDefault();
            var d_id = $('#d_id').val();

            $.ajax({
                url: "/users/delete/" + d_id,
                beforeSend: function() {
                    $('#btnDeleteUser').attr("disabled", true);
                    $('#btnDeleteUser').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Eliminando...'
                    );
                },
                success: function(data) {
                    $('#modalDeleteUser').modal('hide');
                    toastr.error('El registro fue eliminado correctamente.',
                        'Eliminar Registro', {
                            timeOut: 3000
                        });

                    $(`#tabla-users tbody #row-${data.user.id}`).html("");
                },
                complete: function() {
                    $('#btnDeleteUser').text('Eliminar');
                    $('#btnDeleteUser').attr("disabled", false);
                },
            })
        });
    </script>
@endsection
