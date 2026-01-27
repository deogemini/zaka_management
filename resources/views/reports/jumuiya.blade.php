@extends('layouts.admin')

@section('content')
<h1 class="h3 mb-3"><strong>Jumuiya</strong> Reports</h1>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Filter Report</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.jumuiya') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="year" class="form-label">Year</label>
                        <select id="year" name="year" class="form-select">
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="month" class="form-label">Month</label>
                        <select id="month" name="month" class="form-select">
                            <option value="">All Months</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title mb-0">Zaka by Jumuiya</h5>
                <h5 class="card-title mb-0 text-primary">Total: {{ number_format($total) }} TZS</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>Jumuiya Name</th>
                            <th>Total Zaka (TZS)</th>
                            <th>Contribution %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                        <tr>
                            <td>{{ $row->jina_la_jumuiya }}</td>
                            <td>{{ number_format($row->total) }}</td>
                            <td>
                                @if($total > 0)
                                    {{ number_format(($row->total / $total) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
