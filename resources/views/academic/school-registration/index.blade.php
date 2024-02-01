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

        const classrooms = @json($classrooms);

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
                    <div class="offcanvas-body  flex-grow-0">
                        <form method="POST" action="/${periodId}/matriculas/promoted/${registrationId}">
                            @csrf
                            <input type="hidden" name="alum_id" value="${registration.student.id}">
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
                                        <td>
                                            <select name="aula_id" id="aula_id_${registrationId}" class="form-control text-center">
                                                ${generateClassroomStudent(registration.student.id, registration.classroom.id, registrationId)}
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Contacto</td>
                                        <td>${registration.student.phone}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row mt-3">
                                <div class="col-6 text-end">Cuota de inscripción</div>
                                <div class="col-6 text-end">S/ 1600</div>
                            </div>
                            <div class="row">
                                <div class="col-6 text-end">Costo de matrícula</div>
                                <div class="col-6 text-end">S/ 750</div>
                            </div>
                            <div class="row">
                                <div class="col-6 text-end">Mensualidad</div>
                                <div class="col-6 text-end">S/ 750</div>
                            </div>

                            <div class="row">
                                <div class="col-6 text-end">Total Mensualidad (10 meses)</div>
                                <div class="col-6 text-end">S/ 75000</div>
                            </div>

                            ${generateSchoolRegistrationButton(registration.student.id, status)}
                        </form>
                    </div>
                </div>
            `;
            if (status == 'ACTIVO') {
                buttons += `
                    <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" onclick="openChangeClasroomModal(${registrationId}, ${periodId})">
                        <span class="tf-icons bx bx-reset"></span>
                    </button>

                    <div class="modal fade" id="changeClassRoomModal_${registrationId}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form method="POST" action="/${periodId}/matriculas/${registrationId}/cambiar-aula">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Cambiar Aula</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="student_id" value="${registration.student_id}">
                                        <div class="row">
                                            <label for="classroom_id" class="form-label">Aula</label>
                                            <select name="classroom_id" id="classroom_id" class="form-control">
                                                <option value="">Seleccione ...</option>
                                                ${generateClassroom(registration.classroom_id, classrooms)}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary" id="btnCreateClassRoom">Cambiar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <button type="button" class="btn rounded-pill btn-icon btn-outline-danger me-1" onclick="openDenyRegistrationModal(${registrationId})">
                        <span class="tf-icons bx bx-trash"></span>
                    </button>

                    <div class="modal fade" id="denyRegistrationModal_${registrationId}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form method="POST" action="/${periodId}/matriculas/${registrationId}/anular">
                                @csrf
                                @method('DELETE')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Anular Matrícula del alumno <span class="text-warning">${registration.student.first_name || ''} ${registration.student.surname || ''}</span></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Segura que quiere anular la matricula seleccionada?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary" id="btnCreateClassRoom">Anular</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <button type="button" class="btn rounded-pill btn-icon btn-outline-secondary me-1" onclick="openDownloadRegistrationModal(${registrationId})">
                        <span class="tf-icons bx bx-download"></span>
                    </button>
                `;
            }
            return buttons;
        }

        function openChangeClasroomModal(registrationId, periodId) {
            $(`#changeClassRoomModal_${registrationId}`).modal('toggle');
        }

        function generateClassroom(classroomId, classrooms) {
            let options = '';
            $.each(classrooms, function(index, classroom) {
                options += `
                    <option value="${classroom.id}" ${classroom.id == classroomId ? 'selected' : ''}>${classroom.description}</option>
                `;
            });
            return options;
        }

        function generateClassroomStudent(studentId, classroomId, registrationId) {
            $.get(`/${periodId}/matriculas/aulas/${studentId}`, function(data) {
                $.each(data.classrooms, function(index, classroom) {
                    let selectCreateElement = $(`#aula_id_${registrationId}`);
                    let option = $('<option>', {
                        value: classroom.id,
                        text: classroom.description
                    });

                    if (classroom.id == classroomId) {
                        option.prop('selected', true);
                    }
                    selectCreateElement.append(option);
                });
            });
        }

        function generateSchoolRegistrationButton(studentId, status) {
            if (status == 'CONTINUA') {
                return `
                <div class="d-flex justify-content-center mt-3">
                    <button type="submit" class="btn btn-primary">Matricular</button>
                </div>
                `;
            }
            return '';
        }

        function openDenyRegistrationModal(registrationId) {
            $(`#denyRegistrationModal_${registrationId}`).modal('toggle');
        }
    </script>
@endsection
