@extends('layout.template')

@section('title')
    Carga horaria
@endsection

@section('content')
    <div class="card mb-4">
        <h5 class="card-header">Carga horaria para el docente</h5>

        <div class="table-responsive text-nowrap">
            <table class="table" id="tabla-workload">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th width="20%">Docente</th>
                        <th width="60%">Aula/Curso</th>
                        <th width="10%">Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                </tbody>
            </table>
        </div>
    </div>

    {{-- @include('academic.course.course-assign-modal') --}}
@endsection

@section('js')
    {{-- LISTS --}}
    <script>
        window.onload = function() {
            $.ajax({
                url: "{{ route('workload.getall') }}",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    let filas = "";
                    if (data.maxCount == 0) {
                        filas += `
                            <tr id="row-0">
                                <td  colspan="4">NO DATA</td>
                            </tr>
                        `;
                    } else {
                        $.each(data.teachers, function(index, teacher) {
                            filas += `
                                <tr id="row-${teacher.id}">
                                    <td>${++index}</td>
                                    <td>${teacher.name}</td>
                                    <td>
                                        <div class="d-flex flex-wrap">
                                            ${getClassrooms(teacher.classrooms)}
                                            ${getCourses(teacher.courses)}
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn rounded-pill btn-icon btn-outline-warning me-1" onclick="openAssignCourseModal(${teacher.id})">
                                            <span class="tf-icons bx bx-check-square"></span>
                                        </button>
                                        <button type="button" class="btn rounded-pill btn-icon btn-outline-danger me-1" onclick="openDeleteCourseModal(${teacher.id})">
                                            <span class="tf-icons bx bx-trash"></span>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    $("#tabla-workload tbody").html(filas);
                },
                error: function(xhr, status, error) {
                    let filas = '<tr><td colspan="4" >' + xhr.responseJSON.message +
                        '</td></tr>';
                    $("#tabla-workload tbody").html(filas);
                }
            });
        }

        function getClassrooms(classrooms) {
            if (classrooms === undefined) {
                return '';
            }
            const classroomNames = classrooms.map(classroom =>
                `<span class="badge bg-label-primary mb-1 me-1">${classroom}</span>`
            ).join(' ');
            return classroomNames;
        }

        function getCourses(courses) {
            if (courses === undefined) {
                return '';
            }

            const courseNames = courses.map(course =>
                `<span class="badge bg-label-primary mb-1 me-1">${course}</span>`
            ).join(' ');
            return courseNames;
        }
    </script>
@endsection
