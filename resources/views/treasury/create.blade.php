@extends('layout.template')

@section('title')
    Carga horaria
@endsection

@section('content')
    <div class="card mb-4">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Registrar pago</h5>
        </div>
    </div>

    <div class="card mb-4 p-4">
        <div class="d-flex align-items-center justify-content-between ">
            <form action="">
                <div class="d-flex">
                    <div class="form-group me-2">
                        <label for="">Serie</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Número</label>
                        <input type="text" class="form-control">
                    </div>
                </div>

                <div class="row d-flex">
                    <div class="form-group">
                        <label for="numero_documento_cliente">DNI</label>
                        <input name="numero_documento_cliente" id="numero_documento_cliente" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nombre_cliente">Nombre de Cliente</label>
                        <input name="nombre_cliente" id="nombre_cliente" class="form-control">
                    </div>


                </div>
            </form>
        </div>
    </div>
@endsection

@section('css')
@endsection

@section('js')
@endsection
