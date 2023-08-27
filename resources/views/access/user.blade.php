@extends('layout.template')
@section('content')
    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">Gestión de usuarios</h5>
            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" id="btnCreate">
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
@endsection

@section('js')
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
                            '<button type="button" class="btn rounded-pill btn-icon btn-outline-danger">' +
                            '<span class="tf-icons bx bx-trash"></span></button>' : '';

                        filas += '<tr><td>' + data.data[i].name + '</td>' +
                            '<td>' + data.data[i].email + '</td>' +
                            '<td>' + role + '</td>' +
                            '<td>' +
                            '<button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#modalDeleteRole">' +
                            '<span class="tf-icons bx bx-edit-alt"></span></button>' + deleteButton +
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
@endsection
