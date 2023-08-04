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
                    let filas = "";

                    for (const item of data.data) {
                        const permissionsNames = item.permissions
                            .map(permission =>
                                `<span class="badge bg-label-primary me-1">${permission.name}</span>`);

                        filas += `<tr><td>${item.name}</td>
                                  <td>${permissionsNames}</td>
                                  <td>
                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1">
                                    <span class="tf-icons bx bx-edit-alt"></span>
                                    </button>`;
                        if (item.id !== 1) {
                            filas += `<button type="button" class="btn rounded-pill btn-icon btn-outline-danger">
                                        <span class="tf-icons bx bx-trash"></span>
                                      </button>`;
                        }

                        filas += `</td></tr>`;
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
            var currentUrl = window.location.href;
            var menuLinks = $('.menu-link');

            menuLinks.each(function() {
                var linkUrl = $(this).attr('href');
                if (currentUrl.includes(linkUrl)) {
                    $(this).addClass('active');
                    $(this).parents('li.menu-item').addClass('active open');
                }
            });
        });
    </script>
@endsection
