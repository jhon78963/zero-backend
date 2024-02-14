<div class="modal fade" id="editTreasuryModal_{{ $payment->id }}" data-backdrop="static" data-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('payments.update', [$period->id, $payment->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Editar pagos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="description">Concepto</label>
                        <input type="text" name="description" id="description" class="form-control"
                            value="{{ $payment->description }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="cost-edit">Costo</label>
                        <input type="number" name="cost" id="cost-edit" class="form-control"
                            value="{{ $payment->cost }}"min="1" pattern="^[0-9]+" required>
                    </div>

                    <div class="form-group">
                        <label for="due_date">Fecha de vencimiento</label>
                        <input type="date" name="due_date" id="due_date" class="form-control"
                            value="{{ $payment->due_date }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
