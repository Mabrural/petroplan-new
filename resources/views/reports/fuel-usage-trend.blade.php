@extends('layouts.main')

@section('container')
    <style>
        #trendChartContainer {
            position: relative;
            height: 600px;
        }

        #trendChartContainer:fullscreen {
            height: 100vh;
            width: 100vw;
            padding: 0;
            margin: 0;
        }

        #trendChart {
            width: 100% !important;
            height: 100% !important;
        }

        .fullscreen-toggle-btn {
            background: white;
            border: 1px solid #198754;
            color: #198754;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fullscreen-toggle-btn:hover {
            background: #198754;
            color: white;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <div class="container">
        <div class="page-inner">
            <div class="d-flex justify-content-between align-items-center py-3">
                <div>
                    <h3 class="fw-bold mb-0">Fuel Usage Trend by Completion Date</h3>
                    <p class="text-muted mb-0">Visual tracking of fuel usage across vessels over time</p>
                </div>
            </div>

            <div class="position-relative border p-5" id="trendChartContainer" style="background: #fff;">
                <button id="fullscreenTrendBtn" class="fullscreen-toggle-btn position-absolute"
                    style="top: 10px; right: 10px; z-index: 10;" title="Toggle Fullscreen">
                    <i id="fullscreenTrendIcon" class="bi bi-arrows-fullscreen"></i>
                </button>
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const trendCanvas = document.getElementById('trendChart');
        const fullscreenTrendBtn = document.getElementById('fullscreenTrendBtn');
        const fullscreenTrendIcon = document.getElementById('fullscreenTrendIcon');
        const trendChartContainer = document.getElementById('trendChartContainer');

        const trendChart = new Chart(trendCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: {!! json_encode($datasets) !!}
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Fuel Usage Over Time'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Completion Date'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Fuel Volume (Liter)'
                        }
                    }
                }
            }
        });

        fullscreenTrendBtn.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                trendChartContainer.requestFullscreen().catch(err => {
                    alert(`Error entering fullscreen: ${err.message}`);
                });
            } else {
                document.exitFullscreen();
            }
        });

        document.addEventListener('fullscreenchange', () => {
            trendChart.resize();
            if (document.fullscreenElement) {
                fullscreenTrendIcon.classList.remove('bi-arrows-fullscreen');
                fullscreenTrendIcon.classList.add('bi-fullscreen-exit');
            } else {
                fullscreenTrendIcon.classList.remove('bi-fullscreen-exit');
                fullscreenTrendIcon.classList.add('bi-arrows-fullscreen');
            }
        });
    </script>
@endsection
