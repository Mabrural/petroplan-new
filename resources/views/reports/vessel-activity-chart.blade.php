@extends('layouts.main')

@section('container')
    <style>
        #chartContainer {
            position: relative;
            height: 400px;
        }

        #chartContainer:fullscreen {
            height: 100vh;
            width: 100vw;
            padding: 0;
            margin: 0;
        }

        #vesselActivityChart {
            width: 100% !important;
            height: 100% !important;
        }

        .fullscreen-toggle-btn {
            background: white;
            border: 1px solid #0d6efd;
            color: #0d6efd;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fullscreen-toggle-btn:hover {
            background: #0d6efd;
            color: white;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


    <div class="container">
        <div class="page-inner">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center py-3">
                <div>
                    <h3 class="fw-bold mb-0">Vessel Activity Chart</h3>
                    <p class="text-muted mb-0">Visual insights into vessel operations and movement trends</p>
                </div>
            </div>

            <!-- Chart Container with Fullscreen Button -->
            <div class="position-relative border p-5" id="chartContainer" style="background: #fff; min-height: 300px;">
                <button id="fullscreenBtn" class="fullscreen-toggle-btn position-absolute"
                    style="top: 10px; right: 10px; z-index: 10;" title="Toggle Fullscreen">
                    <i id="fullscreenIcon" class="bi bi-arrows-fullscreen"></i>
                </button>
                <canvas id="vesselActivityChart"></canvas>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const chartCanvas = document.getElementById('vesselActivityChart');
        const fullscreenBtn = document.getElementById('fullscreenBtn');
        const fullscreenIcon = document.getElementById('fullscreenIcon');
        const chartContainer = document.getElementById('chartContainer');

        const vesselActivityChart = new Chart(chartCanvas.getContext('2d'), {
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
                maintainAspectRatio: false,
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

        fullscreenBtn.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                chartContainer.requestFullscreen().catch(err => {
                    alert(`Error entering fullscreen: ${err.message}`);
                });
            } else {
                document.exitFullscreen();
            }
        });

        document.addEventListener('fullscreenchange', () => {
            vesselActivityChart.resize();
            if (document.fullscreenElement) {
                fullscreenIcon.classList.remove('bi-arrows-fullscreen');
                fullscreenIcon.classList.add('bi-fullscreen-exit');
            } else {
                fullscreenIcon.classList.remove('bi-fullscreen-exit');
                fullscreenIcon.classList.add('bi-arrows-fullscreen');
            }
        });
    </script>
@endsection
