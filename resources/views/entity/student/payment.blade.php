@extends('layout.template')

@section('title')
    Estudiante
@endsection

@section('content')
    <div class="card mb-3">
        <h5 class="card-header text-center" style="padding: 10px 1.5rem !important;">
            <strong>REPORTE DE PAGOS</strong>
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
            <div class="d-flex">
                <a href="{{ route('attendance.admin.pdf', [$period->name, $classroom->id, $student->id]) }}"
                    class="btn btn-primary me-3" style="padding-top: 25px !important;" target="_blank">
                    <i class="bx bxs-file-pdf"></i>
                </a>

                <a href="{{ route('students.show', [$period->name, $student->id]) }}" class="btn btn-primary me-3"
                    style="padding-top: 25px !important;">
                    <i class='bx bx-arrow-back'></i>
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="d-flex justify-content-center mb-3">
            <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                    <i class="bx bx-search fs-4 lh-0"></i>
                    <input type="text" name="search" id="search" class="form-control border-search shadow-none"
                        placeholder="Buscar..." style="width: 500px;">
                </div>
            </div>
        </div>

        <table class="table table-bordered" id="table-payments">
            <thead>
                <tr class="text-center">
                    <th>FECHA</th>
                    <th>CONCEPTO</th>
                    <th>MONTO</th>
                </tr>
            </thead>
            <tbody>
                {{-- @if ($payments->count() > 0)
                    @foreach ($payments as $payment)
                        <tr>
                            <td class="text-center">{{ $payment->date }}</td>
                            <td class="ms-3">{{ $payment->description }}</td>
                            <td class="text-center">{{ $payment->cost }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" class="text-center">Sin pagos</td>
                    </tr>
                @endif --}}
            </tbody>
        </table>
    </div>

    <input type="hidden" value="{{ $period->id }}" id="period_id" name="period_id">
    <input type="hidden" value="{{ $student->id }}" id="student_id" name="student_id">
@endsection

@section('css')
    <style>
        .border-search {
            border-top: 1px;
            border-right: 1px;
            border-left: 1px;
            border-radius: 0;
        }
    </style>
@endsection

@section('js')
    <script>
        const periodId = $('#period_id').val();
        const studentId = $('#student_id').val();

        window.onload = function() {
            $.ajax({
                url: `/${periodId}/estudiantes/pagos/getAll/${studentId}`,
                method: "GET",
                dataType: "json",
                success: function(data) {
                    let filas = "";
                    $.each(data, function(index, payment) {
                        filas += `
                            <tr id="row-${index}">
                                <td class="text-center">${payment.date}</td>
                                <td>${payment.description}</td>
                                <td class="text-center">${payment.cost}</td>
                            </tr>
                        `;
                    });
                    $("#table-payments tbody").html(filas);
                },
                error: function(xhr, status, error) {
                    let filas = '<tr><td colspan="3" class="text-center">' + xhr.responseJSON.message +
                        '</td></tr>';
                    $("#table-payments tbody").html(filas);
                }
            });
        }
    </script>

    <script>
        $('#search').keyup(function() {
            let value = $('#search').val();

            if (value != '') {
                $.ajax({
                    url: `/${periodId}/estudiantes/pagos/search/${studentId}/${value}`,
                    method: "GET",
                    dataType: "json",
                    success: function(data) {
                        let filas = "";
                        $.each(data, function(index, payment) {
                            filas += `
                            <tr id="row-${index}">
                                <td class="text-center">${payment.date}</td>
                                <td>${payment.description}</td>
                                <td class="text-center">${payment.cost}</td>
                            </tr>
                        `;
                        });
                        $("#table-payments tbody").html(filas);
                    },
                    error: function(xhr, status, error) {
                        let filas = '<tr><td colspan="3" class="text-center">' + xhr.responseJSON
                            .message +
                            '</td></tr>';
                        $("#table-payments tbody").html(filas);
                    }
                });
            } else {
                $.ajax({
                    url: `/${periodId}/estudiantes/pagos/getAll/${studentId}`,
                    method: "GET",
                    dataType: "json",
                    success: function(data) {
                        let filas = "";
                        $.each(data, function(index, payment) {
                            filas += `
                            <tr id="row-${index}">
                                <td class="text-center">${payment.date}</td>
                                <td>${payment.description}</td>
                                <td class="text-center">${payment.cost}</td>
                            </tr>
                        `;
                        });
                        $("#table-payments tbody").html(filas);
                    },
                    error: function(xhr, status, error) {
                        let filas = '<tr><td colspan="3" class="text-center">' + xhr.responseJSON
                            .message +
                            '</td></tr>';
                        $("#table-payments tbody").html(filas);
                    }
                });
            }
        });
    </script>
@endsection
