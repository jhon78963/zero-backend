@extends('layout.template')

@section('title')
    Sílabus
@endsection

@section('content')
    <div class="card mb-4" style="padding-right: 1rem">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Asistencias</h5>
        </div>
    </div>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center me-4">
            <h5 class="card-header">Marcar Asistencia</h5>
            @if ($attendanceCheck == false)
                <form method="post" action="{{ route('attendance.mark', $fecha) }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Marcar Asistencia</button>
                </form>
            @else
                <p>Asistencia registrada hoy: {{ $fecha }}</p>
            @endif


        </div>


    </div>
@endsection

@section('js')
@endsection
