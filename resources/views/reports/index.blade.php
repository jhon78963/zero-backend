@extends('layout.template')

@section('title')
    Reportes
@endsection

@section('content')
    <div class="card mb-4" style="padding-right: 1rem">
        <div class="d-flex align-items-center justify-content-between ">
            <h5 class="card-header">Reportes</h5>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6 col-12">
            <div class="card mb-3" style="width: 680px; height: 340px;">
                <canvas id="genderChart" width="200" height="340"></canvas>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card mb-3" style="width: 680px; height: 340px;">
                <canvas id="roleChart" width="200" height="340"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-12">
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
        .card {
            text-align: center;
            align-items: center;
            justify-content: center;
            /* Optionally, set a specific height or use 100% if you want it to fill the parent container */
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
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
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
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
