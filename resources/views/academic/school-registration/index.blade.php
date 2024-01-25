@extends('layout.template')

@section('title')
    Matriculas
@endsection

@section('content')
    <div class="card mb-4">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Gestión de Matriculas</h5>
            @if ($calendar_matriculas != null)
                @if ($calendar_matriculas->activity == 'Matrículas')
                    <div style="padding-right: 1rem">
                        <a href="{{ route('school-registration.create', $period->name) }}"
                            class="btn rounded-pill btn-icon btn-outline-primary me-1">
                            <span class="tf-icons bx bx-list-plus"></span>
                        </a>
                    </div>
                @else
                    <p class="me-4">Está fuera del periodo de matrículas</p>
                @endif
            @else
                <p class="me-4">Está fuera del periodo de matrículas</p>
            @endif

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

    <input type="hidden" value="{{ $period->id }}" id="period_id" name="period_id">
@endsection

@section('js')
    <script>
        const periodId = $('#period_id').val();
        window.onload = function() {
            $.ajax({
                url: `/${periodId}/matriculas/getAll`,
                method: "GET",
                dataType: "json",
                success: function(data) {
                    let filas = "";
                    if (data.maxCount == 0) {
                        filas += `
                            <tr id="row-0">
                                <td class="text-center" colspan="5">NO DATA</td>
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
                                    ${generateButtons(registration.id, registration.status, registration)}
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

        function generateButtons(registrationId, status, registration) {
            let buttons = `
                <button type="button" class="btn rounded-pill btn-icon btn-outline-warning me-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEnd_${registrationId}" aria-controls="offcanvasEnd">
                    <span class="tf-icons bx bx-show"></span>
                </button>

                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd_${registrationId}" aria-labelledby="offcanvasEndLabel">
                    <div class="offcanvas-header">
                        <h5 id="offcanvasEndLabel" class="offcanvas-title">Detalles de la matrículas</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body my-auto mx-0 flex-grow-0">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>${registrationId}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Estuiante</td>
                                    <td>${registration.student.first_name || ''} ${registration.student.surname || ''}</td>
                                </tr>
                                <tr>
                                    <td>Género</td>
                                    <td>M</td>
                                </tr>
                                <tr>
                                    <td>Nivel</td>
                                    <td>Primaria</td>
                                </tr>
                                <tr>
                                    <td>Grado</td>
                                    <td>${registration.classroom.description}</td>
                                </tr>
                                <tr>
                                    <td>Contacto</td>
                                    <td>${registration.student.phone}</td>
                                </tr>
                            </tbody>
                        </table>

                        <table>
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>${registrationId}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Estuiante</td>
                                    <td>${registration.student.first_name || ''} ${registration.student.surname || ''}</td>
                                </tr>
                                <tr>
                                    <td>Género</td>
                                    <td>M</td>
                                </tr>
                                <tr>
                                    <td>Nivel</td>
                                    <td>Primaria</td>
                                </tr>
                                <tr>
                                    <td>Grado</td>
                                    <td>${registration.classroom.description}</td>
                                </tr>
                                <tr>
                                    <td>Contacto</td>
                                    <td>${registration.student.phone}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
