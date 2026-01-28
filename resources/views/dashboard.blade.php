@extends('layouts.admin')

@section('content')
<h1 class="h3 mb-3"><strong>Zaka</strong> Dashboard</h1>

<div class="row">
    <div class="col-xl-6 col-xxl-5 d-flex">
        <div class="w-100">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Jumla ya Zaka (Tsh)</h5>
                                </div>
                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="dollar-sign"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ number_format(array_sum($amounts)) }}</h1>
                            <div class="mb-0">
                                <span class="text-muted">Kwa mwaka {{ $year }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Miamala ya Zaka</h5>
                                </div>

                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="activity"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ number_format(array_sum($counts)) }}</h1>
                            <div class="mb-0">
                                <span class="text-muted">Kwa mwaka {{ $year }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Wanajumuiya Registered</h5>
                                </div>
                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="users"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ number_format($totalWanajumuiya) }}</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Watoto Registered</h5>
                                </div>
                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="smile"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ number_format($totalWatoto) }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-xxl-7">
        <div class="card flex-fill w-100">
            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Zaka Trend ({{ $year }})</h5>
                    <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2">
                        <select name="mwanajumuiya_id" class="form-select form-select-sm">
                            <option value="">Wote</option>
                            @foreach($wanajumuiya as $mwana)
                                <option value="{{ $mwana->id }}" {{ (string) $mwanaId === (string) $mwana->id ? 'selected' : '' }}>
                                    {{ $mwana->jina_la_mwanajumuiya }} ({{ $mwana->jumuiya->jina_la_jumuiya }})
                                </option>
                            @endforeach
                        </select>
                        <input type="number" name="year" class="form-control form-control-sm" value="{{ $year }}" min="2000" max="2100">
                        <button class="btn btn-sm btn-primary" type="submit">Tazama</button>
                    </form>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="chart chart-sm">
                    <canvas id="chartjs-dashboard-line"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8 col-xxl-9 d-flex">
        <div class="card flex-fill">
            <div class="card-header">

                <h5 class="card-title mb-0">Top Contributors ({{ $year }})</h5>
            </div>
            <table class="table table-hover my-0">
                <thead>
                    <tr>
                        <th>Jina</th>
                        <th class="d-none d-xl-table-cell">Last Paid</th>
                        <th class="d-none d-xl-table-cell">Jumuiya</th>
                        <th class="d-none d-md-table-cell">Total Zaka</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topMembers as $mwana)
                    <tr>
                        <td>{{ $mwana->mwanajumuiya->jina_la_mwanajumuiya }}</td>
                        <td class="d-none d-xl-table-cell">
                            {{ optional($mwana->last_paid) ? \Carbon\Carbon::parse($mwana->last_paid)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="d-none d-xl-table-cell">{{ $mwana->mwanajumuiya->jumuiya->jina_la_jumuiya }}</td>
                        <td class="d-none d-md-table-cell">{{ number_format($mwana->total) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12 col-lg-6">
        <div class="card flex-fill">
            <div class="card-header">
                <h5 class="card-title mb-0">Zaka kwa Jumuiya ({{ $year }})</h5>
            </div>
            <div class="card-body py-3">
                <div class="chart chart-sm">
                    <canvas id="chartjs-jumuiya-bar"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="card flex-fill">
            <div class="card-header">
                <h5 class="card-title mb-0">Zaka kwa Kanda ({{ $year }})</h5>
            </div>
            <div class="card-body py-3">
                <div class="chart chart-sm">
                    <canvas id="chartjs-kanda-bar"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");
        var gradient = ctx.createLinearGradient(0, 0, 0, 225);
        gradient.addColorStop(0, "rgba(59, 125, 221, 0.15)"); // Primary color with opacity
        gradient.addColorStop(1, "rgba(59, 125, 221, 0)");

        // Line chart
        new Chart(document.getElementById("chartjs-dashboard-line"), {
            type: "line",
            data: {
                labels: @json($labels),
                datasets: [{
                    label: "Zaka (TZS)",
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: "#3b7ddd", // window.theme.primary default
                    data: @json($amounts),
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    intersect: false
                },
                hover: {
                    intersect: true
                },
                plugins: {
                    filler: {
                        propagate: false
                    }
                },
                scales: {
                    xAxes: [{
                        reverse: true,
                        gridLines: {
                            color: "rgba(0,0,0,0.0)"
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            stepSize: 1000
                        },
                        display: true,
                        borderDash: [3, 3],
                        gridLines: {
                            color: "rgba(0,0,0,0.0)"
                        }
                    }]
                }
            }
        });

        // Watoto Shukrani Line chart
        var ctx2 = document.getElementById("chartjs-shukrani-line").getContext("2d");
        var gradient2 = ctx2.createLinearGradient(0, 0, 0, 225);
        gradient2.addColorStop(0, "rgba(28, 187, 140, 0.15)"); // Success color with opacity
        gradient2.addColorStop(1, "rgba(28, 187, 140, 0)");

        new Chart(document.getElementById("chartjs-shukrani-line"), {
            type: "line",
            data: {
                labels: @json($labels),
                datasets: [{
                    label: "Shukrani (TZS)",
                    fill: true,
                    backgroundColor: gradient2,
                    borderColor: "#1cbb8c", // success color
                    data: @json($shukraniAmounts),
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    intersect: false
                },
                hover: {
                    intersect: true
                },
                plugins: {
                    filler: {
                        propagate: false
                    }
                },
                scales: {
                    xAxes: [{
                        reverse: true,
                        gridLines: {
                            color: "rgba(0,0,0,0.0)"
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            stepSize: 1000
                        },
                        display: true,
                        borderDash: [3, 3],
                        gridLines: {
                            color: "rgba(0,0,0,0.0)"
                        }
                    }]
                }
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Jumuiya bar
        new Chart(document.getElementById("chartjs-jumuiya-bar"), {
            type: "bar",
            data: {
                labels: @json($jumuiyaLabels),
                datasets: [{
                    label: "Zaka (TZS)",
                    backgroundColor: "#3b7ddd",
                    borderColor: "#3b7ddd",
                    data: @json($jumuiyaTotals)
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        gridLines: { color: "rgba(0,0,0,0.0)" }
                    }],
                    yAxes: [{
                        gridLines: { color: "rgba(0,0,0,0.0)" },
                        ticks: { beginAtZero: true }
                    }]
                },
                legend: { display: false }
            }
        });

        // Kanda bar
        new Chart(document.getElementById("chartjs-kanda-bar"), {
            type: "bar",
            data: {
                labels: @json($kandaLabels),
                datasets: [{
                    label: "Zaka (TZS)",
                    backgroundColor: "#28a745",
                    borderColor: "#28a745",
                    data: @json($kandaTotals)
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        gridLines: { color: "rgba(0,0,0,0.0)" }
                    }],
                    yAxes: [{
                        gridLines: { color: "rgba(0,0,0,0.0)" },
                        ticks: { beginAtZero: true }
                    }]
                },
                legend: { display: false }
            }
        });
    });
</script>
@endpush
