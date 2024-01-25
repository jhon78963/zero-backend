@extends('layout.template')

@section('title')
    Asistencia
@endsection

@section('content')
    <div class="card mb-4" style="padding-right: 1rem">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Asistencias</h5>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive text-nowrap mt-1">
            <table class="table" id="tabla-roles">
                <thead>
                    <tr>
                        <th width="15%">Fecha</th>
                        <th class="text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if ($attendances->count() > 0)
                        @foreach ($attendances as $attendace)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($attendace->CreationTime)->format('d/m/Y') }}</td>
                                <td class="text-center">{{ $attendace->status }}</td>
                                <td class="text-center"></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center">NO DATA</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
@endsection
