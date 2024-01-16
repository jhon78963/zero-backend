@extends('layout.template')

@section('title')
    Carga horaria
@endsection

@section('content')
    <div class="card mb-4">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Tesoreria</h5>
            <div style="padding-right: 1rem">
                <a href="{{ route('treasuries.create', $academic_period->name) }}" class="btn btn-outline-primary me-1">Registrar pago</a>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table" id="tabla-grades">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th width="10%">Fecha</th>
                        <th width="10%">Hora</th>
                        <th class="text-center">Estudiante</th>
                        <th class="text-center">Concepto</th>
                        <th class="text-center">Total</th>
                        <th width="10%">Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if ($treasuries->count() > 0)
                        @foreach ($treasuries as $treasury)
                            <td>{{ $loop->index }}</td>
                            <td>{{ $treasury->fecha_emision }}</td>
                            <td>{{ $treasury->hora_emision }}</td>
                            <td>{{ $treasury->student->first_name }} {{ $treasury->student->surname }}</td>
                            <td>{{ $treasury->concepto }}</td>
                            <td>{{ $treasury->total }}</td>
                        @endforeach
                    @else
                        <td colspan="7" class="text-center">NO DATA</td>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('css')
@endsection

@section('js')
@endsection
