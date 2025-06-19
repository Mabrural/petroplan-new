@extends('layouts.main')

@section('container')
    <div class="container">
        <div class="page-inner">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center py-3">
                <div>
                    <h3 class="fw-bold mb-0">Vessel Activity Chart</h3>
                    <p class="text-muted mb-0">Visual insights into vessel operations and movement trends</p>
                </div>
            </div>

            <canvas id="vesselActivityChart" height="100"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('vesselActivityChart').getContext('2d');
        const vesselActivityChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Total Shipments',
                    data: {!! json_encode($counts) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Shipments'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Vessels'
                        }
                    }
                }
            }
        });
    </script>
@endsection
