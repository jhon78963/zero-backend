@extends('layout.template')

@section('title')
    Carga horaria
@endsection

@section('content')
    <div class="card mb-4" style="padding-right: 1rem">

        <div class="d-flex align-items-center justify-content-between">
            <h5 class="card-header">Registrar pago</h5>
        </div>
    </div>

    <div class="card mb-4 p-4">
        <form action="{{ route('treasuries.store', $period->id) }}" method="POST">
            @csrf
            <div class="d-flex justify-content-between align-content-items mb-2">
                <div class="d-flex">
                    <div class="form-group me-2 mb-3">
                        <label for="numero_documento_cliente">DNI</label>
                        <div class="d-flex">
                            <input name="numero_documento_cliente" id="numero_documento_cliente" class="form-control me-2">
                            <button class="btn btn-primary btn-sm" type="button" onclick="searchDni()">
                                <i class='bx bx-search-alt-2'></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="me-2">
                        <label for="serie">Serie</label>
                        <input type="text" class="form-control" id="serie" name="serie" readonly
                            value="{{ $invoice->serie }}">
                    </div>
                    <div>
                        <label for="numero">Número</label>
                        <input type="text" class="form-control" id="numero" name="numero" readonly
                            value="{{ $invoice->initial_number }}">
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6 form-group">
                    <label for="nombre_cliente">Nombre de Cliente</label>
                    <input name="nombre_cliente" id="nombre_cliente" class="form-control">
                </div>

                <div class="col-6 form-group">
                    <label for="nombre_cliente">Dirección de Cliente</label>
                    <input name="direccion_cliente" id="direccion_cliente" class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="d-flex justify-content-between mb-2">
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-primary me-2" onclick="addRow()">
                            + Agregar Concepto
                        </button>

                        <button type="button" class="btn btn-default p-0" onclick="newPayment()">
                            [+ nuevo]
                        </button>
                    </div>

                    <select name="student_id" id="student_id" class="form-control text-center" style="width: 500px;">
                        <option value="">Seleccione estudiante</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->surname }} {{ $student->mother_surname }}
                                {{ $student->first_name }} {{ $student->other_names }}
                            </option>
                        @endforeach
                    </select>

                    <select name="payment_method" id="" class="form-control" style="width: 201.68px;">
                        <option value="CONTADO">Contado</option>
                        <option value="POS">POS</option>
                        <option value="yape_plin">Yape/Plin</option>
                        <option value="transferencia">Transferencia</option>
                    </select>
                </div>

                <div class="table-responsive mt-2">
                    <table class="table" id="table-payment">
                        <thead>
                            <tr>
                                <th width="60%">Concepto</th>
                                <th width="10%">P. Unit</th>
                                <th width="10%">Total</th>
                                <th width="10%"></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('treasuries.index', $period->name) }}" class="btn btn-secondary me-2">Regresar</a>
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </form>
    </div>
    <input type="hidden" value="{{ $period->id }}" id="period_id" name="period_id">
    @include('treasury.treasury-create-modal')
@endsection

@section('css')
    <style>
    </style>
@endsection

@section('js')
    <script>
        const periodId = $('#period_id').val();
        let studentId;
        $("#payment-principal").on("change", function() {
            const [id, cost] = $(this).val().split('_');
            $('#cost-principal').val(cost);
            $('#total-principal').val(cost);
        });

        $("#student_id").on("change", function() {
            $("#table-payment tbody").empty();
            studentId = $('#student_id').val();
        });
    </script>
    <script>
        function newPayment() {
            $('#createTreasuryModal').modal('toggle');
        }

        function addRow() {
            fila = `
                <tr>
                    <td width="60%">
                        ${generateConcepts(periodId, studentId)}
                    </td>
                    <td width="10%"><input type="text" name="price[]" class="form-control text-center" readonly></td>
                    <td width="5%"><input type="text" name="total[]" class="form-control text-center" readonly></td>
                    <td>
                        <button type="button" class="btn btn-danger" onclick="deleteRow(this)"><i class='bx bx-trash-alt'></i></button>
                    </td>
                </tr>
            `;
            if (studentId == undefined) {
                alert('¡Debes seleccionar un alumno!');
                return
            } else {
                $("#table-payment tbody").append(fila);
            }
        }

        function deleteRow(button) {
            var row = button.parentNode.parentNode;
            var table = document.getElementById("table-payment");
            table.deleteRow(row.rowIndex);
        }

        function searchDni() {
            const dni = $('#numero_documento_cliente').val();
            if (dni.length == 8) {
                $.get('/api/consulta-dni/' + dni, function(data) {
                    $('#nombre_cliente').val(data.nombre);
                    $('#direccion_cliente').val(data.direccion);
                });
            }
        }

        function generateConcepts(periodId, studentId) {
            let select =
                `<select name="description[]" class="form-control" onchange="generateCost(this)" id="payment-select">`;

            $.ajax({
                url: `/api/${periodId}/payments/${studentId}`,
                type: 'GET',
                async: false,
                success: function(payments) {
                    select += '<option value="">Selecione conceptop de pago</option>';
                    $.each(payments, function(index, payment) {
                        select +=
                            `<option value="${payment.id}_${payment.cost}_${payment.payment_id}">${payment.description}</option>`;
                    });
                }
            });

            select += `</select>`;
            return select;
        }

        function generateCost(payment) {
            const [id, cost] = payment.value.split('_');
            const row = $(payment).closest('tr');
            const priceInput = row.find('input[name="price[]"]').val(cost);
            const totalInput = row.find('input[name="total[]"]').val(cost);
        }
    </script>

    {{-- let selectCreateElement = $(`#payment-select`);
                $.each(payments, function(index, payment) {
                    let options = $('<option>', {
                        value: `${payment.id}_${payment.cost}`,
                        text: payment.description
                    });
                    selectCreateElement.append(options);
                }); --}}
@endsection
