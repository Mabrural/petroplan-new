@extends('layouts.main')

@section('container')
    <style>
        #fuelChartContainer {
            position: relative;
            height: 600px;
        }

        #fuelChartContainer:fullscreen {
            height: 100vh;
            width: 100vw;
            padding: 0;
            margin: 0;
        }

        #fuelUsageChart {
            width: 100% !important;
            height: 100% !important;
        }

        .fullscreen-toggle-btn {
            background: white;
            border: 1px solid #fd7e14;
            color: #fd7e14;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fullscreen-toggle-btn:hover {
            background: #fd7e14;
            color: white;
        }
    </style>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <div class="container">
        <div class="page-inner">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center py-3">
                <div>
                    <h3 class="fw-bold mb-0">Fuel Usage Analysis by Vessel</h3>
                    <p class="text-muted mb-0">Detailed visualization of fuel consumption across vessels</p>
                </div>
            </div>

            <!-- Chart with Fullscreen Button -->
            <div class="position-relative border p-5" id="fuelChartContainer" style="background: #fff; min-height: 300px;">
                <button id="fullscreenFuelBtn" class="fullscreen-toggle-btn position-absolute"
                    style="top: 10px; right: 10px; z-index: 10;" title="Toggle Fullscreen">
                    <i id="fullscreenFuelIcon" class="bi bi-arrows-fullscreen"></i>
                </button>
                <canvas id="fuelUsageChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const fuelCanvas = document.getElementById('fuelUsageChart');
        const fullscreenFuelBtn = document.getElementById('fullscreenFuelBtn');
        const fullscreenFuelIcon = document.getElementById('fullscreenFuelIcon');
        const fuelChartContainer = document.getElementById('fuelChartContainer');

        const fuelUsageChart = new Chart(fuelCanvas.getContext('2d'), {
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
                maintainAspectRatio: false,
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

        fullscreenFuelBtn.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                fuelChartContainer.requestFullscreen().catch(err => {
                    alert(`Error entering fullscreen: ${err.message}`);
                });
            } else {
                document.exitFullscreen();
            }
        });

        document.addEventListener('fullscreenchange', () => {
            fuelUsageChart.resize();
            if (document.fullscreenElement) {
                fullscreenFuelIcon.classList.remove('bi-arrows-fullscreen');
                fullscreenFuelIcon.classList.add('bi-fullscreen-exit');
            } else {
                fullscreenFuelIcon.classList.remove('bi-fullscreen-exit');
                fullscreenFuelIcon.classList.add('bi-arrows-fullscreen');
            }
        });
    </script>
@endsection
