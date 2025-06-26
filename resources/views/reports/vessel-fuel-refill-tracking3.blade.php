@extends('layouts.main')

@section('container')
    <style>
        :root {
            --primary-color: #2e59d9;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }

        .summary-card {
            border-left: 4px solid;
            transition: transform 0.2s;
            height: 100%;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .summary-card:hover {
            transform: translateY(-3px);
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        #mainChartContainer {
            position: relative;
            height: 400px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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

        .text-small {
            font-size: 0.85rem;
        }

        .text-xsmall {
            font-size: 0.75rem;
        }

        .badge-pill {
            border-radius: 20px;
            padding: 5px 10px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .chart-container {
                height: 250px;
            }

            #mainChartContainer {
                height: 350px;
            }

            .vessel-details {
                flex-direction: column;
            }
        }

        @media (max-width: 576px) {
            .chart-container {
                height: 200px;
            }

            #mainChartContainer {
                height: 300px;
            }
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <div class="container ">
        <div class="page-inner">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Vessel Fuel Refill Tracking</h3>
                    <p class="text-muted mb-0">
                        <i class="bi bi-speedometer2 me-2"></i>Monitor fuel consumption patterns
                    </p>
                </div>
            </div>
    
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4 mb-4">
                @foreach ($vesselData as $vesselId => $data)
                    @php
                        $refillCount = count($data['refills']);
                        $lastRefill = $refillCount > 0 ? end($data['refills'])['date'] : null;
                        $avgDaysBetween = null;
                        $nextPredicted = null;
                        $accuracyPercentage = 'Low';
    
                        if ($refillCount > 1) {
                            $totalDays = 0;
                            for ($i = 1; $i < $refillCount; $i++) {
                                $totalDays += $data['refills'][$i]['date']->diffInDays($data['refills'][$i - 1]['date']);
                            }
                            $avgDaysBetween = round($totalDays / ($refillCount - 1));
                            if ($lastRefill) {
                                $nextPredicted = $lastRefill->copy()->addDays($avgDaysBetween);
                                if ($nextPredicted < now()) {
                                    $nextPredicted = now()->addDay();
                                }
                            }
                        }
                    @endphp
                    <div class="col">
                        <div class="card summary-card h-100" style="border-left-color: {{ $data['color'] }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="mb-0">{{ $data['name'] }}</h5>
                                        <span class="text-small text-muted">
                                            <i class="bi bi-fuel-pump me-1"></i>{{ $data['fuel_type'] }}
                                        </span>
                                    </div>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px; background-color: {{ $data['color'] }}; color: white;">
                                        <i class="fas fa-ship"></i>
                                    </div>
                                </div>
    
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted text-small">Refills:</span>
                                    <strong>{{ $refillCount }}</strong>
                                </div>
    
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted text-small">Total Volume:</span>
                                    <strong>{{ number_format($data['total_volume']) }} L</strong>
                                </div>
    
                                @if ($avgDaysBetween)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted text-small">Avg. Interval:</span>
                                        <strong>{{ $avgDaysBetween }} days</strong>
                                    </div>
    
                                    @if ($nextPredicted)
                                        <div class="mt-3 pt-2 border-top">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-muted text-small">Next Predicted:</span>
                                                <span class="badge-pill" style="background-color: {{ $data['color'] }}; color: white;">
                                                    {{ $nextPredicted->format('M d') }}
                                                </span>
                                            </div>
                                            <div class="text-end text-xsmall text-muted mt-1">
                                                {{ $accuracyPercentage }} confidence
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
    
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Fuel Refill Overview</h5>
                    <div id="mainChartContainer">
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
                                {{ $data['name'] }} - {{ $data['fuel_type'] }}
                                <span class="badge bg-light text-dark ms-2">{{ count($data['refills']) }} refills</span>
                            </button>
                        </h2>
                        <div id="collapse{{ $vesselId }}" class="accordion-collapse collapse"
                             aria-labelledby="heading{{ $vesselId }}" data-bs-parent="#vesselAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <h6 class="mb-3">Consumption Pattern</h6>
                                        <div class="chart-container">
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
                                                    <p class="mb-1 text-small text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $refill['location'] }}</p>
                                                    @if ($index > 0)
                                                        <p class="mb-0 text-xsmall">
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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctxMain = document.getElementById('mainChart').getContext('2d');
            new Chart(ctxMain, {
                type: 'bar',
                data: {
                    datasets: [
                        @foreach ($chartData['datasets'] as $dataset)
                        {
                            label: '{{ $dataset['label'] }}',
                            data: {!! json_encode($dataset['data']) !!},
                            backgroundColor: '{{ $dataset['backgroundColor'] }}',
                            borderColor: '{{ $dataset['borderColor'] }}',
                            borderWidth: 1,
                            borderRadius: 4
                        },
                        @endforeach
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                tooltipFormat: 'MMM d, yyyy'
                            },
                            grid: { display: false }
                        },
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Volume (Liters)' }
                        }
                    },
                    plugins: {
                        legend: { display: true },
                        tooltip: {
                            callbacks: {
                                label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y} L`
                            }
                        }
                    }
                }
            });

            @foreach ($vesselData as $vesselId => $data)
                @if(count($data['refills']) > 0)
                new Chart(document.getElementById('vesselChart{{ $vesselId }}'), {
                    type: 'line',
                    data: {
                        labels: {!! json_encode(array_map(fn($r) => $r['date']->format('M j'), $data['refills'])) !!},
                        datasets: [{
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
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true },
                            x: { grid: { display: false } }
                        }
                    }
                });
                @endif
            @endforeach
        });
    </script>
@endsection
