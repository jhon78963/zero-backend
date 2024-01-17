<div class="modal fade" id="cancelTreasuryModal-{{ $treasury->id }}" data-backdrop="static" data-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="cancelTreasuryForm" action="{{ route('treasuries.cancel', $treasury->id) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Anular Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="d_id" name="treasury_id" value="{{ $treasury->id }}">
                    <p>¿Estas seguro de anular el pago correspondiente a la
                        boleta electrónica <br><b>{{ $treasury->serie }}-{{ $treasury->numero }}</b> ?
                    </p>
                    <p style="color: red;">* se eliminarán todos los items correspondiente a la boleta electrónica</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-danger">Anular</button>
                </div>
            </div>
        </form>
    </div>
</div>
