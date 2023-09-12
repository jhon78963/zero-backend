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
            <form action="{{ route('admin.periods.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Registrar Periodo Académico</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="name" class="form-label">Nombre del Periodo Académico</label>
                                <input type="text" id="c_domain" name="domain" class="form-control"
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
        <div class="col-md-6 mx-auto">
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img class="card-img card-img-left" src="../assets/img/elements/periodo-academico.jpg"
                            alt="Card image">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Perido Académico 2023</h5>
                            <p class="card-text">
                                Año de la unidad, la paz y el desarrollo
                            </p>
                            <p class="card-text">
                                <small class="text-muted">Ingrese
                                    <a class="text-muted" href="#">aquí</a>
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mx-auto">
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Perido Académico 2022</h5>
                            <p class="card-text">
                                Año del Fortalecimiento de la Soberanía Nacional
                            </p>
                            <p class="card-text">
                                <small class="text-muted">Ingrese
                                    <a class="text-muted" href="#">aquí</a>
                                </small>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <img class="card-img card-img-right" src="../assets/img/elements/periodo-academico.jpg"
                            alt="Card image">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mx-auto">
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img class="card-img card-img-left" src="../assets/img/elements/periodo-academico.jpg"
                            alt="Card image">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Perido Académico 2021</h5>
                            <p class="card-text">
                                Año del Bicentenario del Perú: 200 años de Independencia
                            </p>
                            <p class="card-text">
                                <small class="text-muted">Ingrese
                                    <a class="text-muted" href="#">aquí</a>
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mx-auto">
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Perido Académico 2020</h5>
                            <p class="card-text">
                                Año de la universalización de la salud
                            </p>
                            <p class="card-text">
                                <small class="text-muted">Ingrese
                                    <a class="text-muted" href="#">aquí</a>
                                </small>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <img class="card-img card-img-right" src="../assets/img/elements/periodo-academico.jpg"
                            alt="Card image">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
