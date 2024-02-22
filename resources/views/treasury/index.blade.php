@extends('layout.template')

@section('title')
    Tesorería
@endsection

@section('content')
    <div class="row">
        <div class="col-md-5 col-12">
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
                                <th class="text-center">Fe. de Venc.</th>
                                <th width="10%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @if ($payments->count() > 0)
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td class="text-center">{{ $payment->description }}</td>
                                        <td class="text-center">{{ $payment->cost }}</td>
                                        <td class="text-center">{{ $payment->due_date }}</td>
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
        <div class="col-md-7 col-12">
            <div class="card mb-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-header">Tesoreria</h5>
                    <form action="{{ route('treasuries.index', $period->name) }}" method="GET">
                        <div class="d-flex">
                            <select name="payment_year" id="year" class="form-select me-1">
                                @foreach ($treasuryYear as $year)
                                    <option value="{{ $year->year }}"
                                        {{ $year->year == $paymentYear ? 'selected' : '' }}>{{ $year->year }}</option>
                                @endforeach
                            </select>
                            <select name="payment_month" id="month" class="form-select me-1">
                                @foreach ($treasuryMonth as $month)
                                    @if ($month->month == 1)
                                        <option value="{{ $month->month }}"
                                            {{ $month->month == $paymentMonth ? 'selected' : '' }}>Enero</option>
                                    @elseif ($month->month == 2)
                                        <option value="{{ $month->month }}"
                                            {{ $month->month == $paymentMonth ? 'selected' : '' }}>Febrero</option>
                                    @elseif ($month->month == 3)
                                        <option value="{{ $month->month }}"
                                            {{ $month->month == $paymentMonth ? 'selected' : '' }}>Marzo</option>
                                    @elseif ($month->month == 4)
                                        <option value="{{ $month->month }}"
                                            {{ $month->month == $paymentMonth ? 'selected' : '' }}>Abril</option>
                                    @elseif ($month->month == 5)
                                        <option value="{{ $month->month }}"
                                            {{ $month->month == $paymentMonth ? 'selected' : '' }}>Mayo</option>
                                    @elseif ($month->month == 6)
                                        <option value="{{ $month->month }}"
                                            {{ $month->month == $paymentMonth ? 'selected' : '' }}>Junio</option>
                                    @elseif ($month->month == 7)
                                        <option value="{{ $month->month }}"
                                            {{ $month->month == $paymentMonth ? 'selected' : '' }}>Julio</option>
                                    @elseif ($month->month == 8)
                                        <option value="{{ $month->month }}"
                                            {{ $month->month == $paymentMonth ? 'selected' : '' }}>Agosto</option>
                                    @elseif ($month->month == 9)
                                        <option value="{{ $month->month }}"
                                            {{ $month->month == $paymentMonth ? 'selected' : '' }}>Septiembre</option>
                                    @elseif ($month->month == 10)
                                        <option value="{{ $month->month }}"
                                            {{ $month->month == $paymentMonth ? 'selected' : '' }}>Octubre</option>
                                    @elseif ($month->month == 11)
                                        <option value="{{ $month->month }}"
                                            {{ $month->month == $paymentMonth ? 'selected' : '' }}>Noviembre</option>
                                    @elseif ($month->month == 12)
                                        <option value="{{ $month->month }}"
                                            {{ $month->month == $paymentMonth ? 'selected' : '' }}>Diciembre</option>
                                    @endif
                                @endforeach
                            </select>
                            <select name="payment_grade_id" id="grade_id" class="form-select me-1" style="width: 10rem;">
                                @foreach ($treasuryGrade as $grade)
                                    <option value="{{ $grade->grade_id }}"
                                        {{ $grade->grade_id == $paymentGradeId ? 'selected' : '' }}>{{ $grade->grade }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary btn-sm">
                                <i class="bx bx-search-alt"></i>
                            </button>
                        </div>
                    </form>

                    <div style="padding-right: 1rem">
                        <a href="#" target="_blank" class="btn btn-outline-primary me-1">
                            PDF
                        </a>

                        <a href="{{ route('treasuries.create', $period->name) }}" class="btn btn-outline-primary me-1">
                            <i class='bx bx-plus-medical'></i>
                        </a>
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered" id="tabla-grades">
                        <thead>
                            <tr>
                                <th width="10%" class="text-center">Fecha</th>
                                <th width="20%" class="text-center">Estudiante</th>
                                <th width="30%" class="text-center">Concepto</th>
                                <th width="20%" class="text-center">Total</th>
                                <th width="20%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @if ($treasuries->count() > 0)
                                @php $printedTreasuryStudents = []; @endphp
                                @foreach ($treasuries as $treasury)
                                    @if (!in_array($treasury->treasury_id, $printedTreasuryStudents))
                                        @php $printedTreasuryStudents[] = $treasury->treasury_id; @endphp
                                        @if ($treasuriesCount[$treasury->treasury_id] > 1)
                                            <tr class="text-center">
                                                <td rowspan="{{ $treasuriesCount[$treasury->treasury_id] }}">
                                                    {{ $treasury->fecha_emision }}
                                                </td>
                                                <td rowspan="{{ $treasuriesCount[$treasury->treasury_id] }}">
                                                    {{ $treasury->student_surname }}
                                                    {{ $treasury->student_mother_surname }}
                                                    {{ $treasury->student_first_name }}
                                                    {{ $treasury->student_other_names }}
                                                </td>
                                                <td>{{ $treasury->description }}</td>
                                                <td>{{ $treasury->monto_total }}</td>
                                                <td rowspan="{{ $treasuriesCount[$treasury->treasury_id] }}">
                                                    <a href="{{ route('treasuries.voucher', [$period->name, $treasury->treasury_id]) }}"
                                                        target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="bx bxs-file-pdf"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="openCancelTreasuryModal({{ $treasury->treasury_id }})">
                                                        Anular
                                                    </button>
                                                </td>
                                            </tr>
                                            @for ($i = 1; $i < $treasuriesCount[$treasury->treasury_id]; $i++)
                                                <tr class="text-center">
                                                    <td>{{ $treasuries[$loop->index + $i]->description }}</td>
                                                    <td>{{ $treasuries[$loop->index + $i]->monto_total }}</td>
                                                </tr>
                                            @endfor
                                        @else
                                            <tr class="text-center">
                                                <td>{{ $treasury->fecha_emision }}</td>
                                                <td>
                                                    {{ $treasury->student_surname }}
                                                    {{ $treasury->student_mother_surname }}
                                                    {{ $treasury->student_first_name }}
                                                    {{ $treasury->student_other_names }}
                                                </td>
                                                <td>{{ $treasury->description }}</td>
                                                <td>{{ $treasury->monto_total }}</td>
                                                <td>
                                                    <a href="{{ route('treasuries.voucher', [$period->name, $treasury->treasury_id]) }}"
                                                        target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="bx bxs-file-pdf"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="openCancelTreasuryModal({{ $treasury->treasury_id }})">
                                                        Anular
                                                    </button>
                                                </td>
                                            </tr>
                                        @endif
                                    @endif

                                    @include('treasury.treasury-delete-modal', [
                                        'treasury' => $treasury,
                                        'period' => $period,
                                    ])
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">NO DATA</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mb-4">
                <div class="d-flex align-items-center justify-content-between ">
                    <h5 class="card-header">Morosos</h5>
                    <form action="{{ route('treasuries.index', $period->name) }}" method="GET">
                        <div class="d-flex">
                            <select name="moroso_year" id="year" class="form-select me-1">
                                @foreach ($morosoYear as $moroso)
                                    <option value="{{ $moroso->year }}"
                                        {{ $moroso->year == $morosoYearSelected ? 'selected' : '' }}>{{ $moroso->year }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="moroso_month" id="month" class="form-select me-1">
                                @foreach ($morosoMonth as $moroso)
                                    @if ($moroso->month == 1)
                                        <option value="{{ $moroso->month }}"
                                            {{ $moroso->month == $morosoMonthSelected ? 'selected' : '' }}>Enero</option>
                                    @elseif ($moroso->month == 2)
                                        <option value="{{ $moroso->month }}"
                                            {{ $moroso->month == $morosoMonthSelected ? 'selected' : '' }}>Febrero</option>
                                    @elseif ($moroso->month == 3)
                                        <option value="{{ $moroso->month }}"
                                            {{ $moroso->month == $morosoMonthSelected ? 'selected' : '' }}>
                                            Marzo</option>
                                    @elseif ($moroso->month == 4)
                                        <option value="{{ $moroso->month }}"
                                            {{ $moroso->month == $morosoMonthSelected ? 'selected' : '' }}>
                                            Abril</option>
                                    @elseif ($moroso->month == 5)
                                        <option value="{{ $moroso->month }}"
                                            {{ $moroso->month == $morosoMonthSelected ? 'selected' : '' }}>
                                            Mayo</option>
                                    @elseif ($moroso->month == 6)
                                        <option value="{{ $moroso->month }}"
                                            {{ $moroso->month == $morosoMonthSelected ? 'selected' : '' }}>
                                            Junio</option>
                                    @elseif ($moroso->month == 7)
                                        <option value="{{ $moroso->month }}"
                                            {{ $moroso->month == $morosoMonthSelected ? 'selected' : '' }}>
                                            Julio</option>
                                    @elseif ($moroso->month == 8)
                                        <option value="{{ $moroso->month }}"
                                            {{ $moroso->month == $morosoMonthSelected ? 'selected' : '' }}>
                                            Agosto</option>
                                    @elseif ($moroso->month == 9)
                                        <option value="{{ $moroso->month }}"
                                            {{ $moroso->month == $morosoMonthSelected ? 'selected' : '' }}>
                                            Septiembre</option>
                                    @elseif ($moroso->month == 10)
                                        <option value="{{ $moroso->month }}"
                                            {{ $moroso->month == $morosoMonthSelected ? 'selected' : '' }}>
                                            Octubre</option>
                                    @elseif ($moroso->month == 11)
                                        <option value="{{ $moroso->month }}"
                                            {{ $moroso->month == $morosoMonthSelected ? 'selected' : '' }}>
                                            Noviembre</option>
                                    @elseif ($moroso->month == 12)
                                        <option value="{{ $moroso->month }}"
                                            {{ $moroso->month == $morosoMonthSelected ? 'selected' : '' }}>
                                            Diciembre</option>
                                    @endif
                                @endforeach
                            </select>
                            <select name="moroso_grade_id" id="grade_id" class="form-select me-1"
                                style="width: 10rem;">
                                @foreach ($morosoGrade as $moroso)
                                    <option value="{{ $moroso->grade_id }}"
                                        {{ $moroso->grade_id == $morosoGradeIdSelected ? 'selected' : '' }}>
                                        {{ $moroso->grade }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bx bx-search-alt"></i>
                            </button>
                        </div>
                    </form>

                    <div style="padding-right: 1rem">
                        <a href="{{ route('treasuries.pdf', $period->name) }}" target="_blank"
                            class="btn btn-outline-primary me-1">
                            PDF
                        </a>
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="10%" class="text-center">AULA</th>
                                <th width="10%" class="text-center">ESTUDIANTE</th>
                                <th width="10%" class="text-center">CONCEPTO</th>
                                <th width="10%" class="text-center">FE. DE VENC.</th>
                                <th width="10%" class="text-center">MONTO</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @if ($morosos->count() > 0)
                                @php $printedStudents = []; @endphp
                                @foreach ($morosos as $moroso)
                                    @if (!in_array($moroso->student_id, $printedStudents))
                                        @php $printedStudents[] = $moroso->student_id; @endphp
                                        @if ($conteoPorEstudiante[$moroso->student_id] > 1)
                                            <tr class="text-center">
                                                <td rowspan="{{ $conteoPorEstudiante[$moroso->student_id] }}">
                                                    {{ $moroso->classroom }}
                                                </td>
                                                <td rowspan="{{ $conteoPorEstudiante[$moroso->student_id] }}">
                                                    {{ $moroso->surname }}
                                                    {{ $moroso->mother_surname }}
                                                    {{ $moroso->first_name }}
                                                    {{ $moroso->other_names }}
                                                </td>
                                                <td>{{ $moroso->description }}</td>
                                                <td>{{ $moroso->due_date }}</td>
                                                <td>{{ $moroso->cost }}</td>
                                            </tr>
                                            @for ($i = 1; $i < $conteoPorEstudiante[$moroso->student_id]; $i++)
                                                <tr class="text-center">
                                                    <td>{{ $morosos[$loop->index + $i]->description }}</td>
                                                    <td>{{ $morosos[$loop->index + $i]->due_date }}</td>
                                                    <td>{{ $morosos[$loop->index + $i]->cost }}</td>
                                                </tr>
                                            @endfor
                                        @else
                                            <tr class="text-center">
                                                <td>{{ $moroso->classroom }}</td>
                                                <td>
                                                    {{ $moroso->surname }}
                                                    {{ $moroso->mother_surname }}
                                                    {{ $moroso->first_name }}
                                                    {{ $moroso->other_names }}
                                                </td>
                                                <td>{{ $moroso->description }}</td>
                                                <td>{{ $moroso->due_date }}</td>
                                                <td>{{ $moroso->cost }}</td>
                                            </tr>
                                        @endif
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">NO DATA</td>
                                </tr>
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
    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
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

    <script>
        const costCreate = document.getElementById('cost-create');
        const costEdit = document.getElementById('cost-edit');

        function validarInput(evento, inputElement) {
            const teclaPresionada = evento.key;
            const teclaPresionadaEsUnNumero = Number.isInteger(parseInt(teclaPresionada));

            const sePresionoUnaTeclaNoAdmitida =
                teclaPresionada !== 'ArrowDown' &&
                teclaPresionada !== 'ArrowUp' &&
                teclaPresionada !== 'ArrowLeft' &&
                teclaPresionada !== 'ArrowRight' &&
                teclaPresionada !== 'Backspace' &&
                teclaPresionada !== 'Delete' &&
                teclaPresionada !== 'Enter' &&
                teclaPresionada !== '.' &&
                !teclaPresionadaEsUnNumero;

            const comienzaPorCero =
                inputElement.value.length === 0 &&
                teclaPresionada === '0';

            if (sePresionoUnaTeclaNoAdmitida || comienzaPorCero) {
                evento.preventDefault();
            }
        }

        costCreate.addEventListener('keydown', function(evento) {
            validarInput(evento, costCreate);
        });

        costEdit.addEventListener('keydown', function(evento) {
            validarInput(evento, costEdit);
        });
    </script>
@endsection
