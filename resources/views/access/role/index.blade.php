@extends('layout.template')

@section('title')
    Rol
@endsection

@section('content')
    <div class="card mb-2">
        <div class="d-flex align-items-center">
            <h5 class="card-header">Gestión de roles</h5>
            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="onCreateRole()">
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

    <div class="card">
        <h5 class="card-header">Gestión de permisos</h5>
        <div class="card-body demo-vertical-spacing demo-only-element">
            <form id="createPermissionsForm">
                @csrf
                <input type="hidden" id="role_id">
                <div id="cardPermissions"></div>
            </form>
        </div>
    </div>

    @include('access.role.role-delete-modal')
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
            $.ajax({
                url: "{{ route('roles.getall') }}",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    let filas = "";
                    $.each(data.roles, function(index, role) {
                        filas += `
                            <tr id="row-${role.id}">
                                <td>${role.name}</td>
                                <td>
                                    <div class="d-flex flex-wrap">
                                        ${getPermissionsName(role.permissions)}
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="onRoleUpdate(${role.id})">
                                        <span class="tf-icons bx bx-edit-alt"></span>
                                    </button>
                                    ${getDeleteButtonNonAdmin(role.id)}
                                </td>
                            </tr>
                        `;
                    });

                    $("#tabla-roles tbody").html(filas);
                    $('#cardPermissions').html(getCardPermissions(data.all_permissions));
                },
                error: function(xhr, status, error) {
                    var filas = `
                        <tr>
                            <td colspan="3" class="text-center" id="error">${xhr.responseJSON.message}</td>
                        </tr>
                    `;
                    $("#tabla-roles tbody").html(filas);
                }
            });
        }

        function getDeleteButtonNonAdmin(roleId) {
            if (roleId !== 1) {
                return `
                        <button type="button" class="btn rounded-pill btn-icon btn-outline-danger" onclick="onRoleDelete(${roleId})">
                            <span class="tf-icons bx bx-trash"></span>
                        </button>
                    `;
            } else {
                return '';
            }
        }

        function getPermissionsName(permissions) {
            const permissionsNames = permissions.map(permission =>
                `<span class="badge bg-label-primary mb-1 me-1">${permission.name.replace('pages.', '')}</span>`
            ).join(' ');
            return permissionsNames;
        }

        function getCardPermissions(allPermissions) {
            return `
                <small class="text-light fw-semibold d-block">Rol</small>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="nombre del rol" name="name" id="role-name">
                    <button class="btn btn-outline-primary" type="submit" id="btnStore">Registrar</button>
                </div>
                <small class="text-light fw-semibold d-block mb-1">Permisos</small>
                <button class="btn btn-outline-primary mb-1" type="button" onclick="addPermissions()">Agregar</button>
                ${getAllPermissionsContainer(allPermissions)}
            `;
        }

        function getAllPermissionsContainer(allPermissions) {
            let html = '';
            $.each(allPermissions, function(index, permission) {
                const checkboxHtml = `
                    <div class="input-group mt-2">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="checkbox" value="${permission.name}" id="perm-check-${permission.name.replace(/\./g, '-')}" name="permissions[]">
                        </div>
                        <input type="text" class="form-control" value="${permission.name}" readonly name="permissions-text[]"
                        style="background-color: white;color: #697a8d;">
                    </div>
                `;
                html += checkboxHtml;
            });
            return html;
        }

        function addPermissions() {
            const permissionsContainer = $('#cardPermissions');
            const checkboxHtml = `
                <div class="input-group mt-2">
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
        }

        $(document).on('click', '#btnAddedDelete', function() {
            $(this).closest('.input-group').remove();
        });

        $(document).on('input', 'input[name="permissions-text[]"]', function() {
            const inputValue = $(this).val();
            $(this).siblings('div.input-group-text').find('input[type="checkbox"]').val(inputValue);
        });
    </script>

    {{-- CREATE --}}
    <script>
        function onCreateRole() {
            $('#role-name').val("");
            $('#role_id').val("");
            $('input[name^="permissions"]').prop('checked', false);

            let form = $("#createPermissionsForm");
            if (form.length > 0) {
                let methodInput = form.find('input[name="_method"]');
                if (methodInput.length !== 0) {
                    methodInput.remove();
                }
            }
        }
    </script>

    {{-- EDIT --}}
    <script>
        function onRoleUpdate(roleId) {
            $.get('/roles/get/' + roleId, function(data) {
                $('#role_id').val(data.role.id);
                $('#role-name').val(data.role.name);

                $('input[name^="permissions"]').prop('checked', false);
                $.each(data.role.permissions, function(index, permission) {
                    $(`#perm-check-${permission.name.replace(/\./g, '-')}`).prop('checked', true);
                });
            });

            let form = $("#createPermissionsForm");
            if (form.length > 0) {
                if (form.find('input[name="_method"]').length === 0) {
                    form.append(`@method('PUT')`);
                }
            }
        }
    </script>

    {{-- FORM --}}
    <script>
        function createRoleRow(data) {
            return `
                <tr id="row-${data.role.id}">
                    <td>${data.role.name}</td>
                    <td>
                        <div class="d-flex flex-wrap">
                            ${getPermissionsName(data.role.permissions)}
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="onRoleUpdate(${data.role.id})">
                            <span class="tf-icons bx bx-edit-alt"></span>
                        </button>
                        ${getDeleteButtonNonAdmin(data.role.id)}
                    </td>
                </tr>
            `;
        }

        function updateRoleRow(data) {
            return `
                <td>${data.role.name}</td>
                <td>
                    <div class="d-flex flex-wrap">
                        ${getPermissionsName(data.role.permissions)}
                    </div>
                </td>
                <td>
                    <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="onRoleUpdate(${data.role.id})">
                        <span class="tf-icons bx bx-edit-alt"></span>
                    </button>
                    ${getDeleteButtonNonAdmin(data.role.id)}
                </td>
            `;
        }
        $("#createPermissionsForm").on("submit", function(e) {
            e.preventDefault();

            let roleId = $('#role_id').val();

            // Configuración común para ambas solicitudes
            let ajaxConfig = {
                method: 'POST',
                dataType: 'json',
                data: new FormData($(this)[0]),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnStore').attr("disabled", true)
                        .html(
                            '<div class="spinner-border spinner-border-sm text-primary" role="status"></div> Registrando...'
                        );
                },
                success: function(data) {
                    toastr.success('¡Los datos han sido registrados!', 'Registro exitoso', {
                        timeOut: 3000
                    });

                    let filaCreate = createRoleRow(data);
                    let filaUpdate = updateRoleRow(data);

                    if (!roleId) {
                        $("#tabla-roles tbody").append(filaCreate);
                    } else {
                        $(`#tabla-roles tbody #row-${data.role.id}`).html(filaUpdate);
                    }
                },
                complete: function() {
                    $('#btnStore').attr("disabled", false).text('Registrar');
                },
            };

            let ajaxUrl = (!roleId) ? "{{ route('roles.create') }}" : `/roles/update/${roleId}`;

            $.ajax({
                url: ajaxUrl,
                ...ajaxConfig,
            });
        });
    </script>

    {{-- DELETE --}}
    <script>
        function onRoleDelete(roleId) {
            $.get('/roles/get/' + roleId, function(data) {
                $('#d_id').val(data.role.id);
                $('#d_message').html(
                    `Deseas eliminar el rol <b>${data.role.name}</b> de la lista?`
                );
                $('#deleteRoleModal').modal('toggle');
            });
        }

        $('#deleteRoleModal').submit(function(e) {
            e.preventDefault();
            var d_id = $('#d_id').val();

            $.ajax({
                url: "/roles/delete/" + d_id,
                beforeSend: function() {
                    $('#btnDeleteRole').attr("disabled", true);
                    $('#btnDeleteRole').html(
                        '<div class="spinner-border spinner-border-sm text-white" role="status"></div> Eliminando...'
                    );
                },
                success: function(data) {
                    $('#deleteRoleModal').modal('hide');
                    toastr.error('El registro fue eliminado correctamente.',
                        'Eliminar Registro', {
                            timeOut: 3000
                        });

                    $(`#tabla-roles tbody #row-${data.role.id}`).html("");
                },
                complete: function() {
                    $('#btnDeleteRole').text('Eliminar');
                    $('#btnDeleteRole').attr("disabled", false);
                },
            })
        });
    </script>
@endsection
