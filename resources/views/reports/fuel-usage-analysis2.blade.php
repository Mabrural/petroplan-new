@extends('layouts.main')

@section('container')
    <div class="container">
        <div class="page-inner">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center py-3">
                <div>
                    <h3 class="fw-bold mb-0">Fuel Usage Analysis by Vessel</h3>
                    <p class="text-muted mb-0">Detailed visualization of fuel consumption across vessels</p>
                </div>
            </div>

            <canvas id="fuelUsageChart" height="100"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('fuelUsageChart').getContext('2d');
        const fuelUsageChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Total Volume (Liter)',
                    data: {!! json_encode($volumes) !!},
                    backgroundColor: 'rgba(255, 159, 64, 0.6)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Volume (Liter)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Vessel'
                        }
                    }
                }
            }
        });
    </script>
@endsection
