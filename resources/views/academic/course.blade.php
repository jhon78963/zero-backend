@extends('layout.template')

@section('title')
    Cursos
@endsection

@section('content')
    <div class="card mb-4" style="padding-right: 1rem">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Cursos</h5>
            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary me-1" data-bs-toggle="modal"
                data-bs-target="#modalCreateCalendar">
                <span class="tf-icons bx bx-list-plus"></span>
            </button>
        </div>
    </div>
@endsection

@section('js')
@endsection
