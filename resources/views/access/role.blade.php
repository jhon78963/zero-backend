@extends('layout.template')
@section('content')
    <div class="card">
        <div class="d-flex align-items-end">
            <div class="card-body">
                <h5 class="card-title text-primary">Gestión de roles</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table" id="tabla-roles">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Permisos</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        window.onload = function() {
            $.ajax({
                url: "{{ route('admin.roles.getall') }}",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    var filas = "";

                    for (var i = 0; i < data.maxCount; i++) {
                        var permissionsNames = "";
                        for (var j = 0; j < data.data[i].permissions.length; j++) {
                            if (j == data.data[i].permissions.length - 1) {
                                permissionsNames += '<span class="badge bg-label-primary me-1">' +
                                    data.data[i].permissions[j].name + '</span>';
                            } else {
                                permissionsNames += '<span class="badge bg-label-primary me-1">' +
                                    data.data[i].permissions[j].name + '</span>' + " ";
                            }
                        }
                        filas += '<tr><td>' + data.data[i].name + '</td>' +
                            '<td>' + permissionsNames + '</td>' +
                            '<td>' +
                            '<button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1">' +
                            '<span class="tf-icons bx bx-edit-alt"></span></button>' +
                            '<button type="button" class="btn rounded-pill btn-icon btn-outline-danger">' +
                            '<span class="tf-icons bx bx-trash"></span></button>' +
                            '</td></tr>';
                    }
                    $("#tabla-roles tbody").html(filas);
                },
                error: function(xhr, status, error) {
                    var filas = '<tr><td colspan="3" class="text-center">' + xhr.responseJSON.message +
                        '</td></tr>';
                    $("#tabla-roles tbody").html(filas);
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            // Obtener la URL actual
            var currentUrl = window.location.href;

            // Obtener todos los elementos de menú con enlaces
            var menuLinks = $('.menu-link');

            // Iterar sobre cada enlace del menú
            menuLinks.each(function() {
                var linkUrl = $(this).attr('href');

                // Verificar si la URL actual coincide con la URL del enlace del menú
                if (currentUrl.includes(linkUrl)) {
                    // Agregar la clase 'active' al enlace y a su elemento padre 'li'
                    $(this).addClass('active');
                    $(this).parents('li.menu-item').addClass('active open');
                }
            });
        });
    </script>
@endsection
