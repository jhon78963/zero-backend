@extends('layout.template')

@section('title')
    Carga horaria
@endsection

@section('content')
    <div class="card mb-4">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Tesoreria</h5>
            <div style="padding-right: 1rem">
                <a href="{{ route('treasuries.create', $period->name) }}"
                    class="btn btn-outline-primary me-1">Registrar pago</a>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table" id="tabla-grades">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th width="10%" class="text-center">Fecha</th>
                        <th width="10%" class="text-center">Hora</th>
                        <th class="text-center">Estudiante</th>
                        <th class="text-center">Concepto</th>
                        <th class="text-center">Total</th>
                        <th width="10%">Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if ($treasuries->count() > 0)
                        @foreach ($treasuries as $treasury)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $treasury->fecha_emision }}</td>
                                <td class="text-center">{{ $treasury->hora_emision }}</td>
                                <td class="text-center">
                                    {{ $treasury->student_first_name }}
                                    {{ $treasury->student_other_names }}
                                    {{ $treasury->student_surname }}
                                    {{ $treasury->student_mother_surname }}
                                </td>
                                <td class="text-center">{{ $treasury->concepto }}</td>
                                <td class="text-center">{{ $treasury->monto_total }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="openCancelTreasuryModal({{ $treasury->id }})">
                                        Anular
                                    </button>
                                </td>
                            </tr>
                            @include('treasury.treasury-delete-modal', [
                                'treasury' => $treasury,
                                'period' => $period
                            ])
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
    <script>
        function openCancelTreasuryModal(treasuryId) {
            $(`#cancelTreasuryModal-${treasuryId}`).modal('toggle');
        }
    </script>
@endsection
