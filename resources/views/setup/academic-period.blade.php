@extends('layout.template')
@section('content')
    <div class="card mb-4" style="padding-right: 1rem">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Aperturar Periodo Académico</h5>
            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" data-bs-toggle="modal"
                data-bs-target="#modalCreatePeriod">
                <span class="tf-icons bx bx-list-plus"></span>
            </button>
        </div>
    </div>

    <div class="modal fade" id="modalCreatePeriod" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('periods.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Registrar Periodo Académico</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="name" class="form-label">Año del Periodo Académico</label>
                                <input type="month" id="c_name" name="name" class="form-control"
                                    placeholder="ej: PA-2023" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="name" class="form-label">Nombre del Año</label>
                                <input type="text" id="c_yearName" name="yearName" class="form-control"
                                    placeholder="Nombre del año" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cerrar
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnCreateUser">Registrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @foreach ($academic_periods as $academic_period)
            <div class="col-md-6 mx-auto">
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img class="card-img card-img-left"
                                src="{{ asset('assets/img/elements/periodo-academico.jpg') }}" alt="Card image">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Perido Académico {{ $academic_period->year }}</h5>
                                <p class="card-text">
                                    {{ $academic_period->yearName }}
                                </p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <a class="text-muted"
                                            href="{{ route('periods.home', $academic_period->name) }}">Ingrese
                                            aquí</a>
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('js')
@endsection
