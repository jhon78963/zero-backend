@extends('layout.template')

@section('title')
    Carga horaria
@endsection

@section('content')
    <div class="card mb-4 p-4">
        <form action="{{ route('treasuries.store') }}" method="POST">
            @csrf
            <div class="d-flex justify-content-between align-content-items mb-2">
                <h5 class="card-header">Registrar pago</h5>
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
                    <button type="button" class="btn btn-primary" onclick="addRow()">
                        + Agregar Concepto
                    </button>

                    <select name="student_id" id="student_id" class="form-control text-center" style="width: 500px;">
                        <option value="">Seleccione estudiante</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->surname }}</option>
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
                                <th width="5%">Cantidad</th>
                                <th width="60%">Concepto</th>
                                <th width="10%">P. Unit</th>
                                <th width="10%">Total</th>
                                <th width="10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="5%">
                                    <input type="text" name="quantity[]" value="1" class="form-control text-center">
                                </td>
                                <td width="60%">
                                    <input type="text" name="description[]" class="form-control">
                                </td>
                                <td width="10%">
                                    <input type="text" name="price[]" class="form-control text-center">
                                </td>
                                <td width="5%">
                                    <input type="text" name="total[]" class="form-control text-center">
                                </td>
                                <td width="10%"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('treasuries.index', $academic_period->name) }}"
                    class="btn btn-secondary me-2">Regresar</a>
                <button type="submit" class="btn btn-success">Registrar</button>
            </div>
        </form>
    </div>
@endsection

@section('css')
    <style>
    </style>
@endsection

@section('js')
    <script>
        function addRow() {
            fila = `
                <tr>
                    <td width="5%"><input type="text" name="quantity[]" value="1" class="form-control text-center"></td>
                    <td width="60%"><input type="text" name="description[]" class="form-control"></td>
                    <td width="10%"><input type="text" name="price[]" class="form-control text-center"></td>
                    <td width="5%"><input type="text" name="total[]" class="form-control text-center"></td>
                    <td>
                        <button type="button" class="btn btn-danger" onclick="deleteRow(this)"><i class='bx bx-trash-alt'></i></button>
                    </td>
                </tr>
            `;

            $("#table-payment tbody").append(fila);
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
    </script>
@endsection
