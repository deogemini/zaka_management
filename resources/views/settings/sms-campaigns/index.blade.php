@extends('layouts.admin')

@section('content')
<div class="mb-3">
    <h1 class="h3 d-inline align-middle">SMS Campaigns</h1>
    <a href="{{ route('settings.sms-campaigns.create') }}" class="btn btn-primary float-end">New Campaign</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Campaign History</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover my-0 align-middle">
                <thead>
                    <tr>
                        <th>Campaign</th>
                        <th>Target</th>
                        <th>Status</th>
                        <th>Recipients</th>
                        <th>Sent</th>
                        <th>Failed</th>
                        <th>Created By</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                        <tr>
                            <td>{{ $campaign->title }}</td>
                            <td>
                                @if($campaign->target_type === 'jumuiya')
                                    {{ optional($campaign->jumuiya)->jina_la_jumuiya ?? 'Jumuiya removed' }}
                                @else
                                    All Members
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = match ($campaign->status) {
                                        'completed' => 'bg-success',
                                        'completed_with_errors' => 'bg-warning text-dark',
                                        'sending' => 'bg-info',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ str_replace('_', ' ', $campaign->status) }}</span>
                            </td>
                            <td>{{ number_format($campaign->total_recipients) }}</td>
                            <td>{{ number_format($campaign->sent_count) }}</td>
                            <td>{{ number_format($campaign->failed_count) }}</td>
                            <td>{{ optional($campaign->user)->name ?? 'Unknown' }}</td>
                            <td>{{ $campaign->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('settings.sms-campaigns.show', $campaign) }}" class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-muted">No SMS campaigns have been created.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
