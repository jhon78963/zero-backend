@extends('layout.template')

@section('title')
    Matriculas
@endsection

@section('content')
    <div class="card mb-4">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Registro de Matriculas</h5>
        </div>
    </div>

    <div class="">
        <form method="POST" action="{{ route('school-registration.create') }}">
            @csrf
            <div class="row">
                <div class="col-md-7">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Alumno</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <div class="form-group">
                                <label for="alum_id">Alumno</label>
                                <select class="form-control select2 select2-hidden-accessible selectpicker"
                                    style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true"
                                    data-live-search="true" name="alum_id" id="select-alum_id">
                                    <option value="0">Nuevo alumno</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}">
                                            {{ $student->first_name }}
                                            {{ $student->other_names }}
                                            {{ $student->surname }} {{ $student->mother_surname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group mb-2">
                                        <label for="nombres">DNI: </label>
                                        <input type="text" name="alum_dni" id="alum_dni" class="form-control"
                                            placeholder="nro dni" required="required">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <label for="nombres">Nombres: </label>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="text" name="alum_primerNombre" id="alum_primerNombre"
                                            class="form-control" placeholder="primer nombre" required="required">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="alum_otrosNombres" id="alum_otrosNombres"
                                            class="form-control" placeholder="otros nombres" required="required">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <label for="apellidos">Apellidos: </label>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="text" name="alum_apellidoPaterno" id="alum_apellidoPaterno"
                                            class="form-control" placeholder="apellido paterno" required="required">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="alum_apellidoMaterno" id="alum_apellidoMaterno"
                                            class="form-control" placeholder="apellido materno" required="required">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nombres">Dirección: </label>
                                <input type="text" name="alum_direccion" id="alum_direccion" class="form-control mt-2"
                                    placeholder="dirección" required="required">
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mb-2">
                                        <label for="nombres">Celular: </label>
                                        <input type="text" name="alum_celular" id="alum_celular" class="form-control"
                                            placeholder="nro celular" required="required">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <h2 class="text-center">(*) Alumno nuevo llenar manualmente</h2>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Matricula</h3>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">

                            <div class="form-group mb-2">
                                <label for="nombres">Año Académico: </label>
                                <input type="text" name="matr_año_ingreso" id="matr_año_ingreso"
                                    class="form-control text-center" placeholder="año academico" value="2024"
                                    required="required" readonly>
                            </div>

                            <div class="form-group">
                                <label for="nombres">Grado y Sección: </label>
                                <div class="row">
                                    <div class="col-md-6 col-sm mt-2">
                                        <select name="grado_id" id="grado_id" class="form-control">
                                            <option value="0">Seleccione ...</option>
                                            @foreach ($grades as $grade)
                                                <option value="{{ $grade->id }}">{{ $grade->description }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-sm mt-2">
                                        <select name="secc_id" id="secc_id" class="form-control">
                                            <option value="0">Seleccione ...</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="text" name="aula_id" id="aula_id" class="form-control text-center"
                                    required="required" hidden="true" readonly>
                            </div>

                            <div class="form-group">
                                <input type="text" name="alum_id" id="alum_id" class="form-control text-center"
                                    required="required" hidden="true" readonly>
                            </div>

                            <div class="form-group mb-2">
                                <label for="nombres">Vacantes: </label>
                                <input type="text" id="aula_capacidad" class="form-control text-center"
                                    required="required" readonly>
                            </div>

                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-success">Guardar</button>
                            <a href="#" class="btn btn-secondary"> Regresar</a>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        $(function() {
            $('#grado_id').on('change', gradoCambio);
            $('#secc_id').on('change', vacanteCambio);
            $('#select-alum_id').on('change', alumnoChange);
        });

        function gradoCambio() {
            var grade_id = $(this).val();

            $.get(`/api/grade/${grade_id}/section`, function(sections) {
                $('#aula_capacidad').val("");
                $('#aula_id').val("");

                let selectElement = $('#secc_id');
                $('option', selectElement).not(':first').remove();

                $.each(sections, function(index, section) {
                    selectElement.append($('<option>', {
                        value: section.id,
                        text: section.description
                    }));
                });
            });
        }

        function vacanteCambio() {
            var grado_id = $('#grado_id').val();
            var secc_id = $(this).val();

            $.get('/api/class-room/' + grado_id + '/' + secc_id, function(data) {
                let vacante = data.limit - data.students_number;
                $('#aula_id').val(data.id);
                $('#aula_capacidad').val(vacante);
            });
        }

        function alumnoChange() {
            var alum_id = $(this).val();

            $.get('/estudiantes/get/' + alum_id, function(data) {
                $('#alum_id').val(data.student.id);
                $('#alum_dni').val(data.student.dni);
                $('#alum_primerNombre').val(data.student.first_name);
                $('#alum_otrosNombres').val(data.student.other_names);
                $('#alum_apellidoPaterno').val(data.student.surname);
                $('#alum_apellidoMaterno').val(data.student.mother_surname);
                $('#alum_direccion').val(data.student.address);
                $('#alum_celular').val(data.student.phone);
            });
        }
    </script>
@endsection
