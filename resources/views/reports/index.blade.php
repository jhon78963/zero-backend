@extends('layout.template')

@section('title')
    Reportes
@endsection

@section('content')
    <div class="card card-center mb-4" style="padding-right: 1rem">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Reportes</h5>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6 col-12">
            <div class="card mb-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-header mt-3">Alumnos matriculados por aula</h5>
                    <div style="padding-right: 1rem">
                        <a href="{{ route('reports.classroom-pdf', $period->name) }}" target="_blank"
                            class="btn btn-primary me-1 m-0">PDF</a>
                    </div>
                </div>

                <table class="table">
                    <thead>
                        <tr class="text-center">
                            <th>Aula</th>
                            <th>Matriculados</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registrationClassrooms as $registration)
                            <tr class="text-center">
                                <td>{{ $registration->description }}</td>
                                <td>{{ $registration->registration }}</td>
                                <td>
                                    <a href="{{ route('reports.registration.classroom-pdf', [$period->name, $registration->classroom_id]) }}"
                                        target="_blank" class="btn btn-primary btn-sm">
                                        <i class='bx bxs-file-pdf'></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card mb-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-header mt-3">Alumnos matriculados por grado</h5>
                    <div style="padding-right: 1rem">
                        <a href="{{ route('reports.grade-pdf', $period->name) }}" target="_blank"
                            class="btn btn-primary me-1 m-0">PDF
                        </a>
                    </div>
                </div>

                <table class="table">
                    <thead>
                        <tr class="text-center">
                            <th>Grado</th>
                            <th>Matriculados</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registrationGrades as $registration)
                            <tr class="text-center">
                                <td>{{ $registration->description }}</td>
                                <td>{{ $registration->registration }}</td>
                                <td>
                                    <a href="{{ route('reports.registration.grade-pdf', [$period->name, $registration->grade_id]) }}"
                                        target="_blank" class="btn btn-primary btn-sm">
                                        <i class='bx bxs-file-pdf'></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6 col-12 p-1">
            <div class="card card-center mb-3" style="width: 680px; height: 340px;">
                <canvas id="genderChart" width="200" height="340"></canvas>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card card-center mb-3" style="width: 680px; height: 340px;">
                <canvas id="roleChart" width="200" height="340"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-12 p-1">
            <div class="card mb-3 justify-content-center">
                <canvas id="studentByGradeChart" width="200" height="100"></canvas>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card mb-3">
                <canvas id="limitClassroomChart" width="200" height="100"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .card-center {
            text-align: center;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('genderChart').getContext('2d');
        var data = @json($students);

        var labels = data.map(item => item.gender);
        var values = data.map(item => item.count);

        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Cantidad de Alumnos por Género',
                    data: values,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                    ]
                }]
            },
            options: {
                responsive: true,
            }
        });
    </script>
    <script>
        var ctx = document.getElementById('roleChart').getContext('2d');
        var data = @json($roles);

        var labels = data.map(item => item.name);
        var values = data.map(item => item.count);

        var chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total de personal por cargo',
                    data: values,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                    ]
                }]
            },
            options: {
                responsive: true,
            }
        });
    </script>
    <script>
        var ctx = document.getElementById('studentByGradeChart').getContext('2d');
        var data = @json($studentByGrade);

        var labels = data.map(item => item.description);
        var values = data.map(item => item.count);

        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total de matriculados por aula',
                    data: values,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                    ]
                }]
            },
            options: {
                responsive: true,
            }
        });
    </script>
    <script>
        var ctx = document.getElementById('limitClassroomChart').getContext('2d');
        var data = @json($limitClassrooms);

        var labels = data.map(item => item.description);
        var values = data.map(item => item.vacante);

        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total de vacantes disponibles por aula',
                    data: values,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                    ]
                }]
            },
            options: {
                responsive: true,
            }
        });
    </script>
@endsection
