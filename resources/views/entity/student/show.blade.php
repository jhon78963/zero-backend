@extends('layout.template')

@section('title')
    Estudiante
@endsection

@section('content')
    <div class="card mb-3">
        <h5 class="card-header text-center" style="padding: 10px 1.5rem !important;">
            <strong>INFORMACIÓN DEL ESTUDIANTE</strong>
        </h5>
        <div class="d-flex justify-content-between items-align-center mb-2">
            <div class="d-flex flex-column">
                <h5 class="card-header" style="padding: 10px 1.5rem !important;">
                    <strong>Estudiante: </strong>
                    {{ $student->surname }} {{ $student->mother_surname }}
                    {{ $student->first_name }} {{ $student->other_names }}
                </h5>
                <h5 class="card-header" style="padding: 10px 1.5rem !important;">
                    <strong>Aula: </strong>
                    {{ $classroom->description }}
                </h5>
            </div>

            <a href="{{ route('students.index', $period->name) }}" class="btn btn-primary me-3"
                style="padding-top: 25px !important;">
                <i class='bx bx-arrow-back'></i>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-12">
            <div class="card">
                <h5 class="card-header text-center">
                    NOTAS
                </h5>

                <div class="d-flex justify-content-center pb-4">
                    <a href="{{ route('grade.admin.show', [$period->name, $student->id]) }}" target="_blank"
                        class="btn btn-primary me-2">
                        <i class='bx bx-show'></i>
                    </a>

                    <a href="{{ route('grade.admin.pdf', [$period->name, $student->id]) }}" target="_blank"
                        class="btn btn-primary">
                        <i class='bx bxs-file-pdf'></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card">
                <h5 class="card-header text-center">
                    ASISTENCIAS
                </h5>

                <div class="d-flex justify-content-center pb-4">

                    <a href="{{ route('students.missing', [$period->name, $student->id]) }}" class="btn btn-primary me-2">
                        <i class='bx bx-show'></i>
                    </a>

                    <a href="{{ route('attendance.admin.pdf', [$period->name, $classroom->id, $student->id]) }}"
                        class="btn btn-primary" target="_blank">
                        <i class="bx bxs-file-pdf"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card">
                <h5 class="card-header text-center">
                    PAGOS
                </h5>

                <div class="d-flex justify-content-center pb-4">
                    <a href="{{ route('students.payment', [$period->name, $student->id]) }}" class="btn btn-primary me-2">
                        <i class='bx bx-show'></i>
                    </a>

                    <a href="{{ route('students.payment.pdf', [$period->name, $student->id]) }}" target="_blank"
                        class="btn btn-primary">
                        <i class='bx bxs-file-pdf'></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
@endsection

@section('js')
@endsection
