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

        #mainChartContainer {
            position: relative;
            height: 600px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            width: 100%;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            #mainChartContainer {
                height: 400px;
            }
        }

        #mainChartContainer:fullscreen {
            height: 100vh;
            width: 100vw;
            padding: 20px;
            background: white;
        }

        .fullscreen-toggle-btn {
            background: white;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 100;
        }

        .fullscreen-toggle-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }

        .summary-card {
            border-left: 4px solid;
            transition: all 0.3s ease;
            height: 100%;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 576px) {
            .summary-card {
                margin-bottom: 15px;
            }
        }

        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .vessel-name {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .fuel-type {
            font-size: 0.85rem;
            color: var(--secondary-color);
            background-color: rgba(108, 117, 125, 0.1);
            padding: 2px 8px;
            border-radius: 10px;
            display: inline-block;
        }

        .timeline-item {
            position: relative;
            padding-left: 30px;
            margin-bottom: 20px;
            border-left: 2px solid #dee2e6;
        }

        .timeline-dot {
            position: absolute;
            left: -8px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            top: 4px;
        }

        .prediction-badge {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .chart-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
            padding: 10px;
            background: rgba(46, 89, 217, 0.05);
            border-radius: 8px;
        }

        @media (max-width: 576px) {
            .chart-legend {
                gap: 8px;
                font-size: 0.8rem;
            }
        }

        .legend-item {
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            padding: 5px 10px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            flex-wrap: nowrap;
        }

        .legend-color {
            width: 15px;
            height: 15px;
            border-radius: 3px;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .vessel-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .summary-card:hover .vessel-icon {
            transform: rotate(15deg) scale(1.1);
        }

        .consumption-rate {
            font-size: 0.8rem;
            background: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
            padding: 2px 8px;
            border-radius: 10px;
            display: inline-block;
            font-weight: 500;
        }

        .prediction-accuracy {
            font-size: 0.75rem;
            color: var(--secondary-color);
            margin-top: 3px;
        }

        .timeline-date {
            font-weight: 600;
            color: var(--primary-color);
        }

        .timeline-volume {
            background: rgba(46, 89, 217, 0.1);
            color: var(--primary-color);
            font-weight: 600;
        }

        .days-difference {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
            font-weight: 500;
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 10px;
            display: inline-block;
        }

        .chart-tooltip {
            opacity: 1;
            position: absolute;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            pointer-events: none;
            font-size: 0.8rem;
            z-index: 100;
            transition: all 0.1s ease;
            max-width: 200px;
        }

        .accuracy-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .high-accuracy {
            background-color: var(--success-color);
        }

        .medium-accuracy {
            background-color: var(--warning-color);
        }

        .low-accuracy {
            background-color: var(--danger-color);
        }

        .pattern-analysis {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            border-left: 3px solid var(--info-color);
        }

        .pattern-analysis h6 {
            color: var(--info-color);
            font-weight: 600;
        }

        /* Responsive adjustments for charts */
        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }

        @media (max-width: 768px) {
            .chart-container {
                height: 200px;
            }
        }
    </style>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <div class="container">
        <div class="page-inner">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center py-3 mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Vessel Fuel Refill Tracking</h3>
                    <p class="text-muted mb-0">
                        <i class="bi bi-speedometer2 me-2"></i>Monitor fuel consumption patterns and predict future refills
                    </p>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                @foreach ($vesselData as $vesselId => $data)
                    @php
                        // Enhanced prediction calculation with proper date handling
                        $daysBetween = [];
                        $totalVolume = 0;
                        $totalDays = 0;
                        $refillDates = [];
                        $refillVolumes = [];

                        // Collect refill dates and volumes
                        foreach ($data['refills'] as $refill) {
                            $refillDates[] = $refill['date'];
                            $refillVolumes[] = $refill['volume'];
                        }

                        // Calculate days between refills and total consumption
                        if (count($refillDates) > 1) {
                            for ($i = 1; $i < count($refillDates); $i++) {
                                $diff = $refillDates[$i]->diffInDays($refillDates[$i - 1]);
                                $daysBetween[] = $diff;
                                $totalDays += $diff;
                                $totalVolume += $refillVolumes[$i - 1];
                            }

                            $avgDaysBetween = round($totalDays / count($daysBetween));
                            $avgConsumption = $totalVolume / $totalDays;

                            // Calculate standard deviation for accuracy estimation
                            $variance = 0;
                            foreach ($daysBetween as $days) {
                                $variance += pow($days - $avgDaysBetween, 2);
                            }
                            $stdDev = count($daysBetween) > 1 ? sqrt($variance / (count($daysBetween) - 1)) : 0;

                            // Determine accuracy level based on standard deviation
                            $accuracyPercentage = $stdDev > 7 ? ($stdDev > 14 ? 'Low' : 'Medium') : 'High';

                            // Get the most recent refill date
                            $lastRefill = end($refillDates);

                            // Calculate days since last refill
                            $daysSinceLast = now()->diffInDays($lastRefill);

                            // Calculate next predicted date (must be in the future)
                            $nextPredicted = $lastRefill->copy()->addDays($avgDaysBetween);

                            // Adjust prediction if it's in the past
                            if ($nextPredicted < now()) {
                                // Estimate current fuel level based on average consumption
                                $estimatedCurrentLevel = end($refillVolumes) - $daysSinceLast * $avgConsumption;

                                // Calculate remaining days based on current level
                                $remainingDays =
                                    $estimatedCurrentLevel > 0 ? round($estimatedCurrentLevel / $avgConsumption) : 0;

                                // Set new prediction (today + remaining days + buffer)
                                $nextPredicted = now()->addDays(max(1, $remainingDays));
                            }

                            // Ensure prediction is at least 1 day in the future
                            if ($nextPredicted <= now()) {
                                $nextPredicted = now()->addDay();
                            }
                        }
                    @endphp
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card summary-card h-100" style="border-left-color: {{ $data['color'] }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="vessel-name">{{ $data['name'] }}</span>
                                        <div class="d-flex align-items-center mt-1">
                                            <span class="fuel-type me-2">
                                                <i class="bi bi-fuel-pump me-1"></i>{{ $data['fuel_type'] }}
                                            </span>
                                            @if (isset($avgConsumption))
                                                <span class="consumption-rate">
                                                    <i class="bi bi-speedometer me-1"></i>{{ round($avgConsumption, 1) }}
                                                    L/day
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="vessel-icon" style="background-color: {{ $data['color'] }}">
                                        <i class="fas fa-ship"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Refills:</span>
                                        <strong>{{ count($data['refills']) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Total Volume:</span>
                                        <strong>{{ number_format($data['total_volume']) }} L</strong>
                                    </div>
                                    @if (isset($avgDaysBetween) && isset($nextPredicted))
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Avg. Interval:</span>
                                            <strong>{{ $avgDaysBetween }} days</strong>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div>
                                                <span class="text-muted">Next Predicted:</span>
                                                <div class="prediction-accuracy">
                                                    <span
                                                        class="accuracy-indicator {{ strtolower($accuracyPercentage) }}-accuracy"></span>
                                                    {{ $accuracyPercentage }} accuracy
                                                </div>
                                            </div>
                                            <span class="prediction-badge"
                                                style="background-color: {{ $data['color'] }}; color: white;">
                                                {{ $nextPredicted->format('M d, Y') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Main Chart -->
            <div class="position-relative p-3 mb-4" id="mainChartContainer">
                <button id="fullscreenBtn" class="fullscreen-toggle-btn position-absolute" style="top: 15px; right: 15px;"
                    title="Toggle Fullscreen">
                    <i id="fullscreenIcon" class="bi bi-arrows-fullscreen"></i>
                </button>
                <div class="chart-legend">
                    @foreach ($vesselData as $vesselId => $data)
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: {{ $data['color'] }}"></div>
                            <span>{{ $data['name'] }}</span>
                            <span class="ms-2 text-muted">{{ $data['total_volume'] }} L total</span>
                        </div>
                    @endforeach
                </div>
                <canvas id="mainChart"></canvas>
                <div id="chartTooltip" class="chart-tooltip"></div>
            </div>

            <!-- Detailed Timeline for Each Vessel -->
            @foreach ($vesselData as $vesselId => $data)
                @php
                    // Calculate consumption statistics for this vessel
                    $consumptionStats = [];
                    $refillDates = array_column($data['refills'], 'date');
                    $refillVolumes = array_column($data['refills'], 'volume');

                    if (count($refillDates) > 1) {
                        for ($i = 1; $i < count($refillDates); $i++) {
                            $days = $refillDates[$i]->diffInDays($refillDates[$i - 1]);
                            $consumption = $refillVolumes[$i - 1] / $days;
                            $consumptionStats[] = [
                                'days' => $days,
                                'consumption' => $consumption,
                                'start_date' => $refillDates[$i - 1],
                                'end_date' => $refillDates[$i],
                            ];
                        }

                        $avgConsumption =
                            array_sum(array_column($consumptionStats, 'consumption')) / count($consumptionStats);
                        $sumSquares = 0;

                        foreach ($consumptionStats as $stat) {
                            $sumSquares += pow($stat['consumption'] - $avgConsumption, 2);
                        }

                        $stdDev = count($consumptionStats) > 1 ? sqrt($sumSquares / (count($consumptionStats) - 1)) : 0;

                        // Determine pattern if we have enough data
                        $patternAnalysis = '';
                        if (count($consumptionStats) > 3) {
                            if ($stdDev < $avgConsumption * 0.2) {
                                $patternAnalysis =
                                    'This vessel shows consistent fuel consumption patterns with low variability. Predictions should be reliable.';
                            } elseif ($stdDev < $avgConsumption * 0.4) {
                                $patternAnalysis =
                                    'Moderate variability in consumption patterns. Predictions may need adjustment based on recent trends.';
                            } else {
                                $patternAnalysis =
                                    'High variability in consumption patterns. Consider additional factors for accurate predictions.';
                            }
                        }

                        // Recalculate prediction for display in this section
                        $lastRefill = end($refillDates);
                        $daysSinceLast = now()->diffInDays($lastRefill);
                        $avgDaysBetween = round(
                            array_sum(array_column($consumptionStats, 'days')) / count($consumptionStats),
                        );
                        $nextPredicted = $lastRefill->copy()->addDays($avgDaysBetween);

                        // Adjust if prediction is in the past
                        if ($nextPredicted < now()) {
                            $estimatedCurrentLevel = end($refillVolumes) - $daysSinceLast * $avgConsumption;
                            $remainingDays =
                                $estimatedCurrentLevel > 0 ? round($estimatedCurrentLevel / $avgConsumption) : 0;
                            $nextPredicted = now()->addDays(max(1, $remainingDays));
                        }

                        // Final check to ensure prediction is in the future
                        if ($nextPredicted <= now()) {
                            $nextPredicted = now()->addDay();
                        }

                        // Recalculate accuracy for display
                        $accuracyPercentage = $stdDev > 7 ? ($stdDev > 14 ? 'Low' : 'Medium') : 'High';
                    }
                @endphp
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center"
                        style="border-left: 4px solid {{ $data['color'] }}">
                        <h6 class="m-0 font-weight-bold">
                            <span style="color: {{ $data['color'] }}">{{ $data['name'] }}</span> - Refill History &
                            Analysis
                        </h6>
                        <div>
                            <span class="badge me-2"
                                style="background-color: {{ $data['color'] }}20; color: {{ $data['color'] }};">
                                <i class="bi bi-fuel-pump me-1"></i>{{ $data['fuel_type'] }}
                            </span>
                            <span class="badge" style="background-color: {{ $data['color'] }}; color: white;">
                                <i class="bi bi-speedometer me-1"></i>{{ count($data['refills']) }} Refills
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-graph-up me-2"></i>Refill Pattern Analysis
                                </h6>
                                <div class="chart-container">
                                    <canvas id="vesselChart{{ $vesselId }}"></canvas>
                                </div>

                                @if (count($consumptionStats) > 0)
                                    <div class="pattern-analysis mt-3">
                                        <h6><i class="bi bi-lightbulb me-2"></i>Pattern Insights</h6>
                                        <p class="mb-1"><small>
                                                Average consumption: <strong>{{ round($avgConsumption, 1) }}
                                                    L/day</strong><br>
                                                Standard deviation: <strong>{{ round($stdDev, 1) }} days</strong><br>
                                                @if ($patternAnalysis)
                                                    {{ $patternAnalysis }}
                                                @else
                                                    Collecting more data will improve prediction accuracy.
                                                @endif
                                            </small></p>
                                    </div>
                                @endif

                                @if (count($consumptionStats) > 0)
                                    <div class="mt-4">
                                        <h6 class="text-primary mb-3">
                                            <i class="bi bi-magic me-2"></i>Refill Prediction
                                        </h6>
                                        <div class="alert alert-light">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>Next Predicted Refill:</strong><br>
                                                    {{ $nextPredicted->format('F j, Y') }}
                                                    <div class="mt-1">
                                                        <small class="text-muted">
                                                            <i class="bi bi-calendar me-1"></i>
                                                            {{ $nextPredicted->diffForHumans() }}
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="prediction-badge"
                                                        style="background-color: {{ $data['color'] }}; color: white;">
                                                        {{ $avgDaysBetween }} day cycle
                                                    </div>
                                                    <div class="prediction-accuracy mt-1">
                                                        <span
                                                            class="accuracy-indicator {{ strtolower($accuracyPercentage) }}-accuracy"></span>
                                                        {{ $accuracyPercentage }} confidence
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($daysSinceLast > $avgDaysBetween)
                                                <div class="mt-2 alert alert-warning p-2">
                                                    <small><i class="bi bi-exclamation-triangle me-1"></i>
                                                        This vessel is overdue for refill by
                                                        {{ $daysSinceLast - $avgDaysBetween }} days
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-clock-history me-2"></i>Refill History Timeline
                                </h6>
                                <div class="timeline-wrapper">
                                    @foreach ($data['refills'] as $index => $refill)
                                        <div class="timeline-item">
                                            <div class="timeline-dot" style="background-color: {{ $data['color'] }}"></div>
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h6 class="mb-0 timeline-date">
                                                    <i class="bi bi-calendar-event me-1"></i>
                                                    {{ $refill['date']->format('M j, Y') }}
                                                </h6>
                                                <span class="badge timeline-volume">
                                                    <i class="bi bi-droplet me-1"></i>{{ $refill['volume'] }} L
                                                </span>
                                            </div>
                                            <p class="mb-1"><small class="text-muted">
                                                    <i class="bi bi-geo-alt me-1"></i>{{ $refill['location'] }}
                                                </small></p>
                                            @if ($index > 0)
                                                <p class="mb-0">
                                                    <span class="days-difference">
                                                        <i class="bi bi-clock-history me-1"></i>
                                                        {{ $refill['date']->diffInDays($data['refills'][$index - 1]['date']) }}
                                                        days since last refill
                                                    </span>
                                                </p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-crosshair"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <script>
        // Main Chart
        function initializeMainChart() {
            const mainCtx = document.getElementById('mainChart').getContext('2d');
            const chartTooltip = document.getElementById('chartTooltip');

            // Destroy previous chart instance if it exists
            if (window.mainChartInstance) {
                window.mainChartInstance.destroy();
            }

            window.mainChartInstance = new Chart(mainCtx, {
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
                                tooltipFormat: 'PP',
                                displayFormats: {
                                    day: 'MMM d'
                                }
                            },
                            grid: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Refill Date',
                                color: '#6c757d'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Fuel Volume (Liters)',
                                color: '#6c757d'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            enabled: false,
                            external: function(context) {
                                // Tooltip Element
                                const tooltipEl = chartTooltip;

                                // Hide if no tooltip
                                if (context.tooltip.opacity === 0) {
                                    tooltipEl.style.opacity = 0;
                                    return;
                                }

                                // Set Text
                                if (context.tooltip.dataPoints.length > 0) {
                                    const dataPoint = context.tooltip.dataPoints[0];
                                    const dataset = dataPoint.dataset;
                                    const value = dataset.data[dataPoint.dataIndex];

                                    tooltipEl.innerHTML = `
                                        <div><strong>${dataset.label}</strong></div>
                                        <div>Date: ${new Date(value.x).toLocaleDateString()}</div>
                                        <div>Volume: ${value.y} liters</div>
                                    `;
                                }

                                const position = context.chart.canvas.getBoundingClientRect();

                                // Display, position, and set styles for font
                                tooltipEl.style.opacity = 1;
                                tooltipEl.style.left = position.left + context.tooltip.caretX + 'px';
                                tooltipEl.style.top = position.top + context.tooltip.caretY + 'px';
                                tooltipEl.style.font = context.tooltip.options.bodyFont.string;
                                tooltipEl.style.padding = context.tooltip.options.padding + 'px ' + context
                                    .tooltip.options.padding + 'px';
                            },
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + ' liters';
                                }
                            }
                        },
                        legend: {
                            display: false
                        },
                        crosshair: {
                            line: {
                                color: '#666',
                                width: 1,
                                dashPattern: [5, 5]
                            },
                            sync: {
                                enabled: false
                            },
                            zoom: {
                                enabled: false
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    },
                    onResize: function(chart, size) {
                        // Adjust font sizes on resize
                        const fontSize = size.height < 400 ? 10 : 12;
                        Chart.defaults.font.size = fontSize;
                        chart.update();
                    }
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                window.mainChartInstance.resize();
            });
        }

        // Individual Vessel Charts
        function initializeVesselCharts() {
            @foreach ($vesselData as $vesselId => $data)
                new Chart(
                    document.getElementById('vesselChart{{ $vesselId }}').getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: {!! json_encode(
                                array_map(function ($refill) {
                                    return $refill['date']->format('M j');
                                }, $data['refills']),
                            ) !!},
                            datasets: [{
                                label: 'Fuel Volume (L)',
                                data: {!! json_encode(array_column($data['refills'], 'volume')) !!},
                                backgroundColor: '{{ $data['color'] }}20',
                                borderColor: '{{ $data['color'] }}',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: true,
                                pointBackgroundColor: '{{ $data['color'] }}',
                                pointRadius: 5,
                                pointHoverRadius: 7
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
                                            return context.parsed.y + ' liters';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    },
                                    title: {
                                        display: true,
                                        text: 'Volume (L)'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    title: {
                                        display: true,
                                        text: 'Date'
                                    }
                                }
                            }
                        }
                    }
                );
            @endforeach
        }

        // Fullscreen functionality
        function setupFullscreenToggle() {
            const fullscreenBtn = document.getElementById('fullscreenBtn');
            const fullscreenIcon = document.getElementById('fullscreenIcon');
            const mainChartContainer = document.getElementById('mainChartContainer');

            fullscreenBtn.addEventListener('click', () => {
                if (!document.fullscreenElement) {
                    mainChartContainer.requestFullscreen().catch(err => {
                        alert(`Error entering fullscreen: ${err.message}`);
                    });
                } else {
                    document.exitFullscreen();
                }
            });

            document.addEventListener('fullscreenchange', () => {
                if (document.fullscreenElement) {
                    fullscreenIcon.classList.remove('bi-arrows-fullscreen');
                    fullscreenIcon.classList.add('bi-fullscreen-exit');
                    // Resize chart when entering fullscreen
                    setTimeout(() => {
                        window.mainChartInstance.resize();
                    }, 100);
                } else {
                    fullscreenIcon.classList.remove('bi-fullscreen-exit');
                    fullscreenIcon.classList.add('bi-arrows-fullscreen');
                    // Resize chart when exiting fullscreen
                    setTimeout(() => {
                        window.mainChartInstance.resize();
                    }, 100);
                }
            });
        }

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeMainChart();
            initializeVesselCharts();
            setupFullscreenToggle();

            // Handle chart resizing when tabs are shown (if using tabbed interface)
            const tabEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
            tabEls.forEach(tabEl => {
                tabEl.addEventListener('shown.bs.tab', function() {
                    window.mainChartInstance.resize();
                });
            });
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.mainChartInstance) {
                window.mainChartInstance.resize();
            }
        });
    </script>
@endsection
