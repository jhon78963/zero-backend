@extends('layout.template')

@section('title')
    Rol
@endsection

@section('content')
    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">Gestión de roles</h5>
            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" id="btnCreate">
                <span class="tf-icons bx bx-list-plus"></span>
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table" id="tabla-roles">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Permisos</th>
                        <th width="5%">Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">

                </tbody>
            </table>
        </div>
    </div>
    <br>

    <div class="card" id="card_edit" style="display: none;">
        <h5 class="card-header">Gestión de permisos</h5>
        <div class="card-body demo-vertical-spacing demo-only-element" id="card_permissions">
            <form id="frmPermissions">
                @csrf
                @method('PUT')
                <small class="text-light fw-semibold d-block">Rol</small>
                <input type="hidden" id="role_id">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="nombre del rol"
                        aria-label="Recipient's username" aria-describedby="btnUpdate" id="cardRoleName" name="name">
                    <button class="btn btn-outline-primary" type="submit" id="btnUpdate" disabled>Actualizar</button>
                </div>

                <small class="text-light fw-semibold d-block mb-2sol">Permisos</small>
                <button class="btn btn-outline-primary" type="button" id="btnAdd">Agregar</button>
                <div id="permissionsContainer">
                    <div id="permissionsContainer"></div>
                </div>
            </form>
        </div>
    </div>

    <div class="card" id="card_create" style="display: block;">
        <h5 class="card-header">Gestión de permisos</h5>
        <div class="card-body demo-vertical-spacing demo-only-element" id="card_permissionsCreate">
            <form id="frmPermissionsCreate">
                @csrf
                <small class="text-light fw-semibold d-block">Rol</small>
                <input type="hidden" id="role_id">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="nombre del rol" name="name">
                    <button class="btn btn-outline-primary" type="submit" id="btnStore">Registrar</button>
                </div>

                <small class="text-light fw-semibold d-block mb-2sol">Permisos</small>
                <button class="btn btn-outline-primary" type="button" id="btnAddCreate">Agregar</button>
                <div id="permissionsContainerCreate">
                    <div id="permissionsContainerCreate"></div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete Role-->
    <div class="modal fade" id="modalDeleteRole" aria-labelledby="modalDeleteRoleLabel" tabindex="-1" style="display: none"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteRoleLabel">Eliminar rol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estas seguro de eliminar el rol seleccionado?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-danger" id="btnDeleteRole">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .badge {
            text-transform: none !important;
            font-size: 0.85rem;
        }
    </style>
@endsection

@section('js')
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>

    {{-- LIST --}}
    <script>
        window.onload = function() {
            let roleData;
            $.ajax({
                url: "{{ route('admin.roles.getall') }}",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    roleData = data;
                    let filas = "";
                    for (const item of data.data) {
                        const permissionsNames = item.permissions
                            .map(permission =>
                                `<span class="badge bg-label-primary me-1">${permission.name.replace('pages.', '')}</span>`
                            ).join(' ');

                        filas += `
                            <tr>
                                <td>${item.name}</td>
                                <td>${permissionsNames}</td>
                                <td>
                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1"
                                        id="btnEdit" data-role-id="${item.id}" data-role-name="${item.name}">
                                    <span class="tf-icons bx bx-edit-alt"></span>
                                    </button>`;

                        if (item.id !== 1) {
                            filas += `<button type="button" class="btn rounded-pill btn-icon btn-outline-danger" data-role-id="${item.id}" id="btnDelete" data-bs-toggle="modal" data-bs-target="#modalDeleteRole">
                                        <span class="tf-icons bx bx-trash"></span>
                                      </button>`;
                        }

                        filas += `</td></tr>`;
                    }

                    $("#tabla-roles tbody").html(filas);
                },
                error: function(xhr, status, error) {
                    var filas = '<tr><td colspan="3" class="text-center" id="error">' + xhr.responseJSON
                        .message + '</td></tr>';

                    $("#tabla-roles tbody").html(filas);

                    if ($("#error").length > 0) {
                        $("#card_permissionsCreate").hide();
                        const errorMessage = $("<div>").text(xhr.responseJSON.message)
                            .addClass("text-center mb-2");
                        errorMessage.appendTo($("#card_permissionsCreate").parent());
                    }
                }
            });

            const permissionsContainer = $('#permissionsContainerCreate');
            $.get('/api/role/permissions', function(data) {
                for (const all_permission of data) {
                    const checkboxHtml =
                        `<div class="input-group mt-2">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" value="${all_permission.name}" aria-label="Checkbox for following text input" name="permissions[]">
                                </div>
                                <input type="text" class="form-control" aria-label="Text input with checkbox" value="${all_permission.name}" readonly name="permissions-text[]"
                                style="background-color: white;color: #697a8d;">
                            </div>`;
                    permissionsContainer.append(checkboxHtml);
                }
            });

            $(document).on('click', '#btnCreate', function() {
                // TODO
                $('#card_edit').hide();
                $('#card_create').show();
            });

            $(document).on('click', '#btnAddCreate', function() {
                const permissionsContainer = $('#permissionsContainerCreate');

                const checkboxHtml = `<div class="input-group mt-2">
                                <div class="input-group-text">
                                  <input class="form-check-input mt-0" type="checkbox" checked
                                    aria-label="Checkbox for following text input" name="permissions[]">
                                </div>
                                <input type="text" class="form-control" aria-label="Text input with checkbox"
                                    placeholder="ingrese nuevo permiso e: pages.rol.permiso" name="permissions-text[]" required>
                                <button class="btn btn-outline-danger" type="button" id="btnAddedDelete">
                                    <i class="bx bx-trash cursor-pointer"></i>
                                </button>
                              </div>`;
                permissionsContainer.append(checkboxHtml);
            });

            $(document).on('click', '#btnAdd', function() {
                const permissionsContainer = $('#permissionsContainer');

                const checkboxHtml = `<div class="input-group mt-2">
                                <div class="input-group-text">
                                  <input class="form-check-input mt-0" type="checkbox" value="" checked
                                    aria-label="Checkbox for following text input" name="permissions[]">
                                </div>
                                <input type="text" class="form-control" aria-label="Text input with checkbox"
                                    placeholder="ingrese nuevo permiso e: pages.rol.permiso" name="permissions-text[]" required>
                                <button class="btn btn-outline-danger" type="button" id="btnAddedDelete">
                                    <i class="bx bx-trash cursor-pointer"></i>
                                </button>
                              </div>`;
                permissionsContainer.append(checkboxHtml);
            });

            $(document).on('click', '#btnEdit', function() {
                $('#card_edit').show();
                $('#card_create').hide();
                $('#btnUpdate').attr("disabled", false);
                const roleId = $(this).data('role-id');
                const roleName = $(this).data('role-name');

                $("#role_id").val(roleId);

                // Populate the card with role information
                $('#cardRoleName').val(roleName);

                // Clear any existing permissions checkboxes and add new ones based on data.permissions
                const permissions = roleData.data.find(item => item.id === roleId).permissions;
                const permissionsContainer = $('#permissionsContainer');
                permissionsContainer.empty();
                $.get('/api/role/permissions', function(data) {
                    permissionsContainer.empty();
                    for (const all_permission of data) {
                        const permissionName = all_permission.name;
                        const isChecked = permissions.some(permission => permission.name ===
                            permissionName);

                        const checkboxHtml =
                            `<div class="input-group mt-2">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" value="${all_permission.name}" ${isChecked ? 'checked' : ''} aria-label="Checkbox for following text input" name="permissions[]">
                                </div>
                                <input type="text" class="form-control" aria-label="Text input with checkbox" value="${permissionName}" readonly name="permissions-text[]"
                                style="background-color: white;color: #697a8d;">
                            </div>`;
                        permissionsContainer.append(checkboxHtml);
                    }
                });
            });

            $(document).on('click', '#btnAddedDelete', function() {
                $(this).closest('.input-group').remove();
            });

            $(document).on('input', 'input[name="permissions-text[]"]', function() {
                const inputValue = $(this).val();
                $(this).siblings('div.input-group-text').find('input[type="checkbox"]').val(
                    inputValue);
            });

            $(document).on('input', 'input[name="permissions-text[]"]', function() {
                const inputValue = $(this).val();
                $(this).siblings('div.input-group-text').find('input[type="checkbox"]').val(
                    inputValue);
            });

            $(document).ready(function() {
                $('#permissionsContainer').sortable();
                $('#permissionsContainer').disableSelection();
            });
        }
    </script>

    {{-- CREATE --}}
    <script>
        let roleData;
        $("#frmPermissionsCreate").on("submit", function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('admin.roles.create') }}",
                method: 'POST',
                dataType: 'json',
                data: new FormData($("#frmPermissionsCreate")[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnStore').attr("disabled", true);
                    $('#btnStore').html(
                        '<div class="spinner-border spinner-border-sm text-primary" role="status"></div> Registrando...'
                    );
                },
                success: function(data) {
                    toastr.success('¡Los datos han sido registrados!',
                        'Registro exitoso', {
                            timeOut: 3000
                        });

                    $.ajax({
                        url: "{{ route('admin.roles.getall') }}",
                        method: "GET",
                        dataType: "json",
                        success: function(data) {
                            roleData = data;
                            let filas = "";
                            for (const item of data.data) {
                                const permissionsNames = item.permissions
                                    .map(permission =>
                                        `<span class="badge bg-label-primary me-1">${permission.name.replace('pages.', '')}</span>`
                                    ).join(' ');

                                filas += `<tr><td>${item.name}</td>
                                  <td>${permissionsNames}</td>
                                  <td>
                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1"
                                        id="btnEdit" data-role-id="${item.id}" data-role-name="${item.name}">
                                    <span class="tf-icons bx bx-edit-alt"></span>
                                    </button>`;
                                if (item.id !== 1) {
                                    filas += `<button type="button" class="btn rounded-pill btn-icon btn-outline-danger" data-role-id="${item.id}" id="btnDelete" data-bs-toggle="modal" data-bs-target="#modalDeleteRole">
                                        <span class="tf-icons bx bx-trash"></span>
                                      </button>`;
                                }

                                filas += `</td></tr>`;
                            }

                            $("#tabla-roles tbody").html(filas);

                            location.reload();
                        }
                    });
                },
                complete: function() {
                    $('#btnStore').attr("disabled", false);
                    $('#btnStore').text('Registrar');
                },
            });
        });
    </script>

    {{-- UPDATE --}}
    <script>
        $("#frmPermissions").on("submit", function(e) {
            e.preventDefault();
            var role_id = $("#role_id").val();
            $.ajax({
                url: '/admin/roles/update/' + role_id,
                method: 'POST',
                dataType: 'json',
                data: new FormData($("#frmPermissions")[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnUpdate').attr("disabled", true);
                    $('#btnUpdate').html(
                        '<div class="spinner-border spinner-border-sm text-primary" role="status"></div> Actualizando...'
                    );
                },
                success: function(data) {

                    toastr.success('¡Los datos han sido actualizados!', 'Actualización exitosa', {
                        timeOut: 3000
                    });

                    $.ajax({
                        url: "{{ route('admin.roles.getall') }}",
                        method: "GET",
                        dataType: "json",
                        success: function(data) {
                            roleData = data;
                            let filas = "";
                            for (const item of data.data) {
                                const permissionsNames = item.permissions
                                    .map(permission =>
                                        `<span class="badge bg-label-primary me-1">${permission.name.replace('pages.', '')}</span>`
                                    ).join(' ');

                                filas += `<tr><td>${item.name}</td>
                                  <td>${permissionsNames}</td>
                                  <td>
                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1"
                                        id="btnEdit" data-role-id="${item.id}" data-role-name="${item.name}">
                                    <span class="tf-icons bx bx-edit-alt"></span>
                                    </button>`;
                                if (item.id !== 1) {
                                    filas += `<button type="button" class="btn rounded-pill btn-icon btn-outline-danger" data-role-id="${item.id}" id="btnDelete" data-bs-toggle="modal" data-bs-target="#modalDeleteRole">
                                        <span class="tf-icons bx bx-trash"></span>
                                      </button>`;
                                }

                                filas += `</td></tr>`;
                            }

                            $("#tabla-roles tbody").html(filas);
                        }
                    });

                    $(document).on('click', '#btnEdit', function() {
                        const roleId = $(this).data('role-id');
                        const roleName = $(this).data('role-name');

                        $("#role_id").val(roleId);

                        // Populate the card with role information
                        $('#cardRoleName').val(roleName);

                        // Clear any existing permissions checkboxes and add new ones based on data.permissions
                        const permissions = roleData.data.find(item => item.id === roleId)
                            .permissions;
                        const permissionsContainer = $('#permissionsContainer');
                        $.get('/api/role/permissions', function(data) {
                            permissionsContainer.empty();
                            for (const all_permission of data) {
                                const permissionName = all_permission.name;
                                const isChecked = permissions.some(permission =>
                                    permission.name ===
                                    permissionName);

                                const checkboxHtml =
                                    `<div class="input-group mt-2">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" value="${all_permission.name}" ${isChecked ? 'checked' : ''} aria-label="Checkbox for following text input" name="permissions[]">
                                </div>
                                <input type="text" class="form-control" aria-label="Text input with checkbox" value="${permissionName}" readonly name="permissions-text[]"
                                style="background-color: white;color: #697a8d;">
                            </div>`;
                                permissionsContainer.append(checkboxHtml);
                            }
                        });
                    });
                },
                error: function(data) {
                    $('#btnUpdate').text('Actualizar');
                    $('#btnUpdate').attr("disabled", false);
                },
                complete: function() {
                    $('#btnUpdate').text('Actualizar');
                    $('#btnUpdate').attr("disabled", false);
                },
            });
        });
    </script>

    {{-- DELETE --}}
    <script>
        var roleId;
        $(document).on('click', '#btnDelete', function() {
            roleId = $(this).data('role-id');
        });

        $('#btnDeleteRole').click(function() {
            $.ajax({
                url: "/admin/roles/delete/" + roleId,
                beforeSend: function() {
                    $('#btnDeleteRole').attr("disabled", true);
                    $('#btnDeleteRole').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Eliminando...'
                    );
                },
                success: function(data) {
                    toastr.error('El rol fue eliminado correctamente.',
                        'Eliminar Registro', {
                            timeOut: 3000
                        });

                    $.ajax({
                        url: "{{ route('admin.roles.getall') }}",
                        method: "GET",
                        dataType: "json",
                        success: function(data) {
                            roleData = data;
                            let filas = "";
                            for (const item of data.data) {
                                const permissionsNames = item.permissions
                                    .map(permission =>
                                        `<span class="badge bg-label-primary me-1">${permission.name.replace('pages.', '')}</span>`
                                    ).join(' ');

                                filas += `<tr><td>${item.name}</td>
                                  <td>${permissionsNames}</td>
                                  <td>
                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1"
                                        id="btnEdit" data-role-id="${item.id}" data-role-name="${item.name}">
                                    <span class="tf-icons bx bx-edit-alt"></span>
                                    </button>`;
                                if (item.id !== 1) {
                                    filas += `<button type="button" class="btn rounded-pill btn-icon btn-outline-danger" data-role-id="${item.id}" id="btnDelete" data-bs-toggle="modal" data-bs-target="#modalDeleteRole">
                                        <span class="tf-icons bx bx-trash"></span>
                                      </button>`;
                                }

                                filas += `</td></tr>`;
                            }

                            $("#tabla-roles tbody").html(filas);
                        }
                    });

                    $('#modalDeleteRole').modal('hide');
                    $('#btnDeleteRole').attr("disabled", false);
                    $('#btnDeleteRole').text("Eliminar");
                }
            });
        });
    </script>
@endsection
