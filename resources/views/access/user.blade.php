@extends('layout.template')
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

    <div class="modal fade" id="modalCreateUser" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="userCreateForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Registrar Usuarios</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="name" class="form-label">Username</label>
                                <input type="text" id="c_username" name="username" class="form-control"
                                    placeholder="Username" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="name" class="form-label">Nombres</label>
                                <input type="text" id="c_name" name="name" class="form-control"
                                    placeholder="Nombre" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="dobBasic" class="form-label">Apellidos</label>
                                <input type="text" id="c_surname" name="surname" class="form-control"
                                    placeholder="Apellidos" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" id="c_email" name="email" class="form-control"
                                    placeholder="xxxx@xxx.xx" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="phoneNumber" class="form-label">Celular</label>
                                <input type="text" id="c_phoneNumber" name="phoneNumber" class="form-control"
                                    placeholder="Número de celular" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col mb-0">
                                <div class="form-password-toggle">
                                    <label class="form-label" for="basic-default-password31">Contraseña</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" class="form-control" id="c_password" placeholder="Contraseña"
                                            name="password">
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-0">
                                <div class="form-password-toggle">
                                    <label class="form-label" for="basic-default-password32">Confirmar contraseña</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" class="form-control" id="c_password_confirmation"
                                            placeholder="Contraseña" name="password_confirmation">
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cerrar
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnCreateUser">Registrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalUpdateUser" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="userUpdateForm">
                @csrf
                @method('put')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Editar Usuarios</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="e_id">
                        <div class="row g-2">
                            <div class="col mb-3">
                                <label for="name" class="form-label">Rol</label>
                                <input type="text" id="e_role" class="form-control" placeholder="Rol" readonly />
                            </div>
                            <div class="col mb-3">
                                <label for="name" class="form-label">Username</label>
                                <input type="text" id="e_username" class="form-control" placeholder="Username"
                                    readonly />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="name" class="form-label">Nombres</label>
                                <input type="text" id="e_name" name="name" class="form-control"
                                    placeholder="Nombre" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="dobBasic" class="form-label">Apellidos</label>
                                <input type="text" id="e_surname" name="surname" class="form-control"
                                    placeholder="Apellidos" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" id="e_email" class="form-control" placeholder="xxxx@xxx.xx"
                                    readonly />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-0">
                                <label for="phoneNumber" class="form-label">Celular</label>
                                <input type="text" id="e_phoneNumber" name="phoneNumber" class="form-control"
                                    placeholder="Número de celular" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cerrar
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnUpdateUser">Actualizar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalDeleteUser" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="userDeleteForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Eliminar Usuarios</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="d_id">
                        <p id="d_message"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cerrar
                        </button>
                        <button type="submit" class="btn btn-danger" id="btnDeleteUser">Eliminar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalAssignUser" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="userAssignForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Asignar Roles</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="a_id" name="userId">

                        <p id="a_message"></p>

                        <div id="rolesContainerCreate"></div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cerrar
                        </button>
                        <button type="submit" class="btn btn-warning" id="btnAssignUser">Asignar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    {{-- LIST --}}
    <script>
        window.onload = function() {
            $.ajax({
                url: "{{ route('admin.users.getall') }}",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    let filas = "";
                    for (var i = 0; i < data.maxCount; i++) {
                        var role = data.data[i].roles[0] || 'Sin asignar';

                        var deleteButton = (role !== 'Admin') ?
                            '<button type="button" class="btn rounded-pill btn-icon btn-outline-danger" onclick="deleteUser(' +
                            data.data[i].id + ')">' +
                            '<span class="tf-icons bx bx-trash"></span></button>' :
                            '';

                        filas += '<tr><td>' + data.data[i].name + '</td>' +
                            '<td>' + data.data[i].email + '</td>' +
                            '<td>' + role + '</td>' +
                            '<td>' +
                            '<button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="editUser(' +
                            data.data[i].id + ')">' +
                            '<span class="tf-icons bx bx-edit-alt"></span></button>' +
                            '<button type="button" class="btn rounded-pill btn-icon btn-outline-warning me-1" onclick="assignUser(' +
                            data.data[i].id + ')">' +
                            '<span class="tf-icons bx bx-user-check"></span></button>' + deleteButton +
                            '</td></tr>';
                    }
                    $("#tabla-users tbody").html(filas);
                },
                error: function(xhr, status, error) {
                    let filas = '<tr><td colspan="4" class="text-center">' + xhr.responseJSON.message +
                        '</td></tr>';
                    $("#tabla-users tbody").html(filas);
                }
            });
        }
    </script>

    {{-- CREATE --}}
    <script>
        $('#userCreateForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "/admin/users/store/",
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
                    toastr.info('El registro fue creado correctamente.',
                        'Crear Registro', {
                            timeOut: 3000
                        });

                    $.ajax({
                        url: "{{ route('admin.users.getall') }}",
                        method: "GET",
                        dataType: "json",
                        success: function(data) {
                            let filas = "";
                            for (var i = 0; i < data.maxCount; i++) {
                                var role = data.data[i].roles[0] || 'Sin asignar';

                                var deleteButton = (role !== 'Admin') ?
                                    '<button type="button" class="btn rounded-pill btn-icon btn-outline-danger" onclick="deleteUser(' +
                                    data.data[i].id + ')">' +
                                    '<span class="tf-icons bx bx-trash"></span></button>' :
                                    '';

                                filas += '<tr><td>' + data.data[i].name + '</td>' +
                                    '<td>' + data.data[i].email + '</td>' +
                                    '<td>' + role + '</td>' +
                                    '<td>' +
                                    '<button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="editUser(' +
                                    data.data[i].id + ')">' +
                                    '<span class="tf-icons bx bx-edit-alt"></span></button>' +
                                    '<button type="button" class="btn rounded-pill btn-icon btn-outline-warning me-1" onclick="assignUser(' +
                                    data.data[i].id + ')">' +
                                    '<span class="tf-icons bx bx-user-check"></span></button>' +
                                    deleteButton +
                                    '</td></tr>';
                            }
                            $("#tabla-users tbody").html(filas);
                        }
                    });
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
            $.get('/admin/users/get/' + user_id, function(user) {
                $('#e_id').val(user.data.id);
                $('#e_username').val(user.data.username);
                $('#e_role').val(user.data.roles[0]);
                $('#e_name').val(user.data.name);
                $('#e_surname').val(user.data.surname);
                $('#e_email').val(user.data.email);
                $('#e_phoneNumber').val(user.data.phoneNumber);
                $("input[name=_token]").val();
                $('#modalUpdateUser').modal('toggle');
            })
        }
    </script>

    {{-- ACTUALIZAR --}}
    <script>
        $('#userUpdateForm').submit(function(e) {

            e.preventDefault();
            var e_id = $('#e_id').val();

            $.ajax({
                url: "/admin/users/update/" + e_id,
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
                    toastr.info('El registro fue actualizado correctamente.',
                        'Actualizar Registro', {
                            timeOut: 3000
                        });

                    $.ajax({
                        url: "{{ route('admin.users.getall') }}",
                        method: "GET",
                        dataType: "json",
                        success: function(data) {
                            let filas = "";
                            for (var i = 0; i < data.maxCount; i++) {
                                var role = data.data[i].roles[0] || 'Sin asignar';

                                var deleteButton = (role !== 'Admin') ?
                                    '<button type="button" class="btn rounded-pill btn-icon btn-outline-danger" onclick="deleteUser(' +
                                    data.data[i].id + ')">' +
                                    '<span class="tf-icons bx bx-trash"></span></button>' :
                                    '';

                                filas += '<tr><td>' + data.data[i].name + '</td>' +
                                    '<td>' + data.data[i].email + '</td>' +
                                    '<td>' + role + '</td>' +
                                    '<td>' +
                                    '<button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="editUser(' +
                                    data.data[i].id + ')">' +
                                    '<span class="tf-icons bx bx-edit-alt"></span></button>' +
                                    '<button type="button" class="btn rounded-pill btn-icon btn-outline-warning me-1" onclick="assignUser(' +
                                    data.data[i].id + ')">' +
                                    '<span class="tf-icons bx bx-user-check"></span></button>' +
                                    deleteButton +
                                    '</td></tr>';
                            }
                            $("#tabla-users tbody").html(filas);
                        }
                    });
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
            $.get('/admin/users/get/' + user_id, function(user) {
                $('#a_id').val(user.data.id);
                $('#a_message').html(`Selecciona un <b>rol</b> de la lista a asignar`);
                const rolesContainer = $('#rolesContainerCreate');
                $.get('/admin/roles/getAll', function(data) {
                    rolesContainer.empty()
                    for (const role of data.data) {
                        let checkboxHtml = null;
                        if (role.id == user.data.role_id[0]) {
                            checkboxHtml =
                                `<div class="input-group mt-2">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" value="${role.id}" aria-label="Checkbox for following text input" name="roleId" checked>
                                </div>
                                <input type="text" class="form-control" aria-label="Text input with checkbox" value="${role.name}" readonly name="roles-text[]"
                                style="background-color: white;color: #697a8d;">
                            </div>`;
                            rolesContainer.append(checkboxHtml);
                        } else {
                            checkboxHtml =
                                `<div class="input-group mt-2">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" value="${role.id}" aria-label="Checkbox for following text input" name="roleId">
                                </div>
                                <input type="text" class="form-control" aria-label="Text input with checkbox" value="${role.name}" readonly name="roles-text[]"
                                style="background-color: white;color: #697a8d;">
                            </div>`;
                            rolesContainer.append(checkboxHtml);
                        }
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
                url: "{{ route('admin.users.assign') }}",
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

                    $.ajax({
                        url: "{{ route('admin.users.getall') }}",
                        method: "GET",
                        dataType: "json",
                        success: function(data) {
                            let filas = "";
                            for (var i = 0; i < data.maxCount; i++) {
                                var role = data.data[i].roles[0] || 'Sin asignar';

                                var deleteButton = (role !== 'Admin') ?
                                    '<button type="button" class="btn rounded-pill btn-icon btn-outline-danger" onclick="deleteUser(' +
                                    data.data[i].id + ')">' +
                                    '<span class="tf-icons bx bx-trash"></span></button>' :
                                    '';

                                filas += '<tr><td>' + data.data[i].name + '</td>' +
                                    '<td>' + data.data[i].email + '</td>' +
                                    '<td>' + role + '</td>' +
                                    '<td>' +
                                    '<button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="editUser(' +
                                    data.data[i].id + ')">' +
                                    '<span class="tf-icons bx bx-edit-alt"></span></button>' +
                                    '<button type="button" class="btn rounded-pill btn-icon btn-outline-warning me-1" onclick="assignUser(' +
                                    data.data[i].id + ')">' +
                                    '<span class="tf-icons bx bx-user-check"></span></button>' +
                                    deleteButton +
                                    '</td></tr>';
                            }
                            $("#tabla-users tbody").html(filas);
                        }
                    });
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
            $.get('users/' + user_id + '/get', function(user) {
                $('#d_id').val(user.data.id);
                $('#d_message').html(
                    `Deseas eliminar el usuario <b>${user.data.name || ''} ${user.data.surname || ''}</b> de la lista?`
                );
                $('#modalDeleteUser').modal('toggle');
            })
        }
    </script>

    {{-- DESTROY --}}
    <script>
        $('#userDeleteForm').submit(function(e) {

            e.preventDefault();
            var d_id = $('#d_id').val();

            $.ajax({
                url: "/admin/users/delete/" + d_id,
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

                    $.ajax({
                        url: "{{ route('admin.users.getall') }}",
                        method: "GET",
                        dataType: "json",
                        success: function(data) {
                            let filas = "";
                            for (var i = 0; i < data.maxCount; i++) {
                                var role = data.data[i].roles[0] || 'Sin asignar';

                                var deleteButton = (role !== 'Admin') ?
                                    '<button type="button" class="btn rounded-pill btn-icon btn-outline-danger" onclick="deleteUser(' +
                                    data.data[i].id + ')">' +
                                    '<span class="tf-icons bx bx-trash"></span></button>' :
                                    '';

                                filas += '<tr><td>' + data.data[i].name + '</td>' +
                                    '<td>' + data.data[i].email + '</td>' +
                                    '<td>' + role + '</td>' +
                                    '<td>' +
                                    '<button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="editUser(' +
                                    data.data[i].id + ')">' +
                                    '<span class="tf-icons bx bx-edit-alt"></span></button>' +
                                    '<button type="button" class="btn rounded-pill btn-icon btn-outline-warning me-1" onclick="assignUser(' +
                                    data.data[i].id + ')">' +
                                    '<span class="tf-icons bx bx-user-check"></span></button>' +
                                    deleteButton +
                                    '</td></tr>';
                            }
                            $("#tabla-users tbody").html(filas);
                        }
                    });
                },
                complete: function() {
                    $('#btnDeleteUser').text('Eliminar');
                    $('#btnDeleteUser').attr("disabled", false);
                },
            })
        });
    </script>
@endsection
