@extends('layout.template')

@section('title')
    Tesorería
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4 col-12">
            <div class="card mb-4">
                <div class="d-flex align-items-center justify-content-between ">
                    <h5 class="card-header">Mantenimiento de tesoreria</h5>
                    <div style="padding-right: 1rem">
                        <div>
                            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1"
                                onclick="openCreatePaymentModal()">
                                <span class="tf-icons bx bx-list-plus"></span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table" id="tabla-grades">
                        <thead>
                            <tr>
                                <th class="text-center">Concepto</th>
                                <th class="text-center">Costo</th>
                                <th width="10%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @if ($payments->count() > 0)
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td class="text-center">{{ $payment->description }}</td>
                                        <td class="text-center">{{ $payment->cost }}</td>
                                        <td>
                                            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary"
                                                onclick="openEditPaymentModal({{ $payment->id }})">
                                                <span class="tf-icons bx bx-edit-alt"></span>
                                            </button>
                                            <button type="button" class="btn rounded-pill btn-icon btn-outline-danger"
                                                onclick="openDeletePaymentModal({{ $payment->id }})">
                                                <span class="tf-icons bx bx-trash"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    @include('treasury.treasury-edit-modal', [
                                        'payment' => $payment,
                                    ])
                                    @include('treasury.payment-delete-modal', [
                                        'payment' => $payment,
                                    ])
                                @endforeach
                                <tr>
                                    <td colspan="3">
                                        <div class="d-flex justify-content-center">
                                            <ul class="pagination mb-0">
                                                {{-- Renderizar enlaces a páginas previas y siguientes --}}
                                                @if ($payments->onFirstPage())
                                                    <li class="page-item disabled">
                                                        <span class="page-link">&laquo;</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $payments->previousPageUrl() }}"
                                                            rel="prev">&laquo;</a>
                                                    </li>
                                                @endif

                                                {{-- Renderizar enlaces a páginas individuales --}}
                                                @foreach ($payments->getUrlRange(1, $payments->lastPage()) as $page => $url)
                                                    @if ($page == $payments->currentPage())
                                                        <li class="page-item active" aria-current="page">
                                                            <span class="page-link">{{ $page }}</span>
                                                        </li>
                                                    @else
                                                        <li class="page-item">
                                                            <a class="page-link"
                                                                href="{{ $url }}">{{ $page }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach

                                                {{-- Renderizar enlaces a páginas previas y siguientes --}}
                                                @if ($payments->hasMorePages())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $payments->nextPageUrl() }}"
                                                            rel="next">&raquo;</a>
                                                    </li>
                                                @else
                                                    <li class="page-item disabled">
                                                        <span class="page-link">&raquo;</span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <td colspan="3" class="text-center">NO DATA</td>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-12">
            <div class="card mb-4">
                <div class="d-flex align-items-center justify-content-between ">
                    <h5 class="card-header">Tesoreria</h5>
                    <div style="padding-right: 1rem">
                        <a href="{{ route('treasuries.create', $period->name) }}"
                            class="btn btn-outline-primary me-1">Registrar
                            pago</a>
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
                                        <td class="text-center">{{ $treasury->description }}</td>
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
                                        'period' => $period,
                                    ])
                                @endforeach
                            @else
                                <td colspan="7" class="text-center">NO DATA</td>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mb-4">
                <div class="d-flex align-items-center justify-content-between ">
                    <h5 class="card-header">Morosos</h5>
                    <div style="padding-right: 1rem">
                        <a href="{{ route('treasuries.pdf', $period->name) }}" class="btn btn-outline-primary me-1">PDF</a>
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table" id="tabla-grades">
                        <thead>
                            <tr>
                                <th width="10%" class="text-center">Aula</th>
                                <th width="10%" class="text-center">Moroso</th>
                                <th width="10%" class="text-center">Concepto</th>
                                <th width="10%" class="text-center">Fecha de pago</th>
                                <th width="10%" class="text-center">Monto</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @if ($morosos->count() > 0)
                                @foreach ($morosos as $moroso)
                                    <tr>
                                        <td class="text-center">{{ $moroso->classroom }}</td>
                                        <td class="text-center">
                                            {{ $moroso->first_name }}
                                            {{ $moroso->other_names }}
                                            {{ $moroso->surname }}
                                            {{ $moroso->mother_surname }}
                                        </td>
                                        <td class="text-center">{{ $moroso->description }}</td>
                                        <td class="text-center">{{ $moroso->due_date }}</td>
                                        <td class="text-center">{{ $moroso->cost }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <td colspan="3" class="text-center">NO DATA</td>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('treasury.treasury-create-modal')
@endsection

@section('css')
@endsection

@section('js')
    <script>
        function openCancelTreasuryModal(treasuryId) {
            $(`#cancelTreasuryModal-${treasuryId}`).modal('toggle');
        }

        function openCreatePaymentModal() {
            $('#createTreasuryModal').modal('toggle');
        }

        function openEditPaymentModal(paymentId) {
            $(`#editTreasuryModal_${paymentId}`).modal('toggle');
        }

        function openDeletePaymentModal(paymentId) {
            $(`#deleteTreasuryModal_${paymentId}`).modal('toggle');
        }
    </script>
@endsection
