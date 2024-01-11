@extends('layout.template')

@section('title')
    Matriculas
@endsection

@section('content')
    <div class="card mb-4">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Gestión de Matriculas</h5>
            <div style="padding-right: 1rem">
                <a href="{{ route('school-registration.register', $academic_period->name) }}"
                    class="btn rounded-pill btn-icon btn-outline-primary me-1">
                    <span class="tf-icons bx bx-list-plus"></span>
                </a>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table" id="tabla-registration">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th class="text-center">Alumno</th>
                        <th class="text-center">Aula</th>
                        <th class="text-center">Estado</th>
                        <th width="10%">Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0"></tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script>
        window.onload = function() {
            $.ajax({
                url: "{{ route('school-registration.getall') }}",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    let filas = "";
                    if (data.maxCount == 0) {
                        filas += `
                            <tr id="row-0">
                                <td class="text-center" colspan="3">NO DATA</td>
                            </tr>
                        `;
                    } else {
                        $.each(data.schoolRegistration, function(index, registration) {
                            filas += `
                            <tr id="row-${registration.id}">
                                <td>${++index}</td>
                                <td class="text-center">${registration.student.first_name || ''} ${registration.student.surname || ''}</td>
                                <td class="text-center">${registration.classroom.description}</td>
                                <td class="text-center">${registration.status}</td>
                                <td>
                                    ${generateButtons(registration.id, registration.status)}
                                </td>
                            </tr>
                        `;
                        });
                    }
                    $("#tabla-registration tbody").html(filas);
                },
                error: function(xhr, status, error) {
                    let filas = '<tr><td colspan="3" class="text-center">' + xhr.responseJSON.message +
                        '</td></tr>';
                    $("#tabla-registration tbody").html(filas);
                }
            });
        }

        function generateButtons(registrationId, status) {
            let buttons = `
                <button type="button" class="btn rounded-pill btn-icon btn-outline-warning me-1" onclick="openShowRegistrationModal(${registrationId})">
                    <span class="tf-icons bx bx-show"></span>
                </button>
            `;
            if (status == 'ACTIVO') {
                buttons += `
                    <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="openChangeClasroomModal(${registrationId})">
                        <span class="tf-icons bx bx-reset"></span>
                    </button>
                    <button type="button" class="btn rounded-pill btn-icon btn-outline-danger me-1" onclick="openDenyRegistrationModal(${registrationId})">
                        <span class="tf-icons bx bx-trash"></span>
                    </button>
                    <button type="button" class="btn rounded-pill btn-icon btn-outline-secondary me-1" onclick="openDownloadRegistrationModal(${registrationId})">
                        <span class="tf-icons bx bx-download"></span>
                    </button>
                `;
            }
            return buttons;
        }
    </script>
@endsection
