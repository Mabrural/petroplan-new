@extends('layouts.main')

@section('container')
    <style>
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .vessel-card {
            transition: transform 0.2s;
            border-left: 4px solid;
            border-radius: 8px;
        }

        .vessel-card:hover {
            transform: translateY(-3px);
        }

        .timeline-item {
            position: relative;
            padding-left: 1.5rem;
            margin-bottom: 1rem;
            border-left: 2px solid #e9ecef;
        }

        .timeline-dot {
            position: absolute;
            left: -8px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            top: 4px;
        }

        @media (max-width: 768px) {
            .chart-container {
                height: 350px;
            }
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <div class="container">
        <div class="page-inner">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Vessel Fuel Refill Tracking</h3>
                    <p class="text-muted mb-0">
                        <i class="bi bi-fuel-pump me-2"></i>Fuel consumption analysis by vessel
                    </p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Total Fuel Consumption by Vessel</h5>
                    <div class="chart-container">
                        <canvas id="mainChart" width="100%" height="400"></canvas>
                    </div>
                </div>
            </div>

            <div class="accordion mb-4" id="vesselAccordion">
                @foreach ($vesselData as $vesselId => $data)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $vesselId }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $vesselId }}" aria-expanded="false"
                                    aria-controls="collapse{{ $vesselId }}">
                                <i class="bi bi-ship me-2" style="color: {{ $data['color'] }}"></i>
                                {{ $data['name'] }} - Total: {{ number_format($data['total_volume']) }} L
                                <span class="badge bg-light text-dark ms-2">{{ count($data['refills']) }} refills</span>
                            </button>
                        </h2>
                        <div id="collapse{{ $vesselId }}" class="accordion-collapse collapse"
                             aria-labelledby="heading{{ $vesselId }}" data-bs-parent="#vesselAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <h6 class="mb-3">Refill Pattern</h6>
                                        <div class="chart-container" style="height: 300px;">
                                            <canvas id="vesselChart{{ $vesselId }}"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-3">Refill History</h6>
                                        <div class="timeline-wrapper">
                                            @foreach ($data['refills'] as $index => $refill)
                                                <div class="timeline-item">
                                                    <div class="timeline-dot" style="background-color: {{ $data['color'] }}"></div>
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <h6 class="mb-0">{{ $refill['date']->format('M j, Y') }}</h6>
                                                        <span class="badge bg-dark text-white">
                                                            {{ number_format($refill['volume']) }} L
                                                        </span>
                                                    </div>
                                                    <p class="mb-1"><i class="bi bi-geo-alt me-1"></i>{{ $refill['location'] }}</p>
                                                    @if ($index > 0)
                                                        <p class="text-muted small mb-0">
                                                            <i class="bi bi-clock-history me-1"></i>
                                                            {{ $refill['date']->diffInDays($data['refills'][$index - 1]['date']) }} days since last
                                                        </p>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Main Chart - Total Volume per Vessel
            new Chart(document.getElementById('mainChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_column($vesselData, 'name')) !!},
                    datasets: [{
                        label: 'Total Fuel Consumption (Liters)',
                        data: {!! json_encode(array_column($vesselData, 'total_volume')) !!},
                        backgroundColor: {!! json_encode(array_column($vesselData, 'color')) !!},
                        borderColor: {!! json_encode(array_column($vesselData, 'color')) !!},
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y.toLocaleString() + ' L';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Total Volume (Liters)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Individual Vessel Charts
            @foreach ($vesselData as $vesselId => $data)
                @if(count($data['refills']) > 1)
                new Chart(document.getElementById('vesselChart{{ $vesselId }}'), {
                    type: 'line',
                    data: {
                        labels: {!! json_encode(array_map(fn($r) => $r['date']->format('M j'), $data['refills'])) !!},
                        datasets: [{
                            label: 'Fuel Volume (L)',
                            data: {!! json_encode(array_column($data['refills'], 'volume')) !!},
                            backgroundColor: '{{ $data['color'] }}20',
                            borderColor: '{{ $data['color'] }}',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Volume (Liters)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
                @endif
            @endforeach
        });
    </script>
@endsection