@extends('layouts.admin')

@section('content')
<div class="mb-3">
    <h1 class="h3 d-inline align-middle">{{ $campaign->title }}</h1>
    <a href="{{ route('settings.sms-campaigns.index') }}" class="btn btn-secondary float-end">Back</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-12 col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Campaign Summary</h5>
            </div>
            <div class="card-body">
                @php
                    $statusClass = match ($campaign->status) {
                        'completed' => 'bg-success',
                        'completed_with_errors' => 'bg-warning text-dark',
                        'sending' => 'bg-info',
                        default => 'bg-secondary',
                    };
                @endphp
                <p><strong>Status:</strong> <span class="badge {{ $statusClass }}">{{ str_replace('_', ' ', $campaign->status) }}</span></p>
                <p><strong>Target:</strong>
                    @if($campaign->target_type === 'jumuiya')
                        {{ optional($campaign->jumuiya)->jina_la_jumuiya ?? 'Jumuiya removed' }}
                    @else
                        All Members
                    @endif
                </p>
                <p><strong>Recipients:</strong> {{ number_format($campaign->total_recipients) }}</p>
                <p><strong>Sent:</strong> {{ number_format($campaign->sent_count) }}</p>
                <p><strong>Failed:</strong> {{ number_format($campaign->failed_count) }}</p>
                <p><strong>Created By:</strong> {{ optional($campaign->user)->name ?? 'Unknown' }}</p>
                <p class="mb-0"><strong>Created:</strong> {{ $campaign->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Message</h5>
            </div>
            <div class="card-body">
                <p class="mb-0" style="white-space: pre-wrap;">{{ $campaign->message }}</p>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recipient Status</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover my-0 align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Jumuiya</th>
                                <th>Status</th>
                                <th>Sent At</th>
                                <th>Error</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($campaign->recipients as $recipient)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $recipient->name }}</td>
                                    <td>{{ $recipient->phone }}</td>
                                    <td>{{ optional(optional($recipient->mwanajumuiya)->jumuiya)->jina_la_jumuiya ?? '-' }}</td>
                                    <td>
                                        @php
                                            $recipientClass = match ($recipient->status) {
                                                'sent' => 'bg-success',
                                                'failed' => 'bg-danger',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $recipientClass }}">{{ $recipient->status }}</span>
                                    </td>
                                    <td>{{ $recipient->sent_at ? $recipient->sent_at->format('Y-m-d H:i') : '-' }}</td>
                                    <td>{{ $recipient->error_message ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
