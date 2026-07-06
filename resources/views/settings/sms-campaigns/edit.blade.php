@extends('layouts.admin')

@section('content')
<div class="mb-3">
    <h1 class="h3 d-inline align-middle">Edit SMS Text</h1>
    <a href="{{ route('settings.sms-campaigns.show', $campaign) }}" class="btn btn-secondary float-end">Back</a>
</div>

<div class="row">
    <div class="col-12 col-xl-7">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Campaign Message</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.sms-campaigns.update', $campaign) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Campaign Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $campaign->title) }}" placeholder="Example: Meeting Reminder">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea id="campaign_message" name="message" rows="7" class="form-control @error('message') is-invalid @enderror" maxlength="160" placeholder="Write the SMS campaign text here">{{ old('message', $campaign->message) }}</textarea>
                        @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted"><span id="campaign_message_count">0</span>/160 characters. This text will be used for future sends or resending failed recipients.</small>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('settings.sms-campaigns.show', $campaign) }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-5">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Campaign Details</h5>
            </div>
            <div class="card-body">
                <p><strong>Status:</strong> {{ str_replace('_', ' ', $campaign->status) }}</p>
                <p><strong>Recipients:</strong> {{ number_format($campaign->total_recipients) }}</p>
                <p><strong>Sent:</strong> {{ number_format($campaign->sent_count) }}</p>
                <p class="mb-0"><strong>Failed:</strong> {{ number_format($campaign->failed_count) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const message = document.getElementById('campaign_message');
    const count = document.getElementById('campaign_message_count');
    const updateCount = () => count.textContent = message.value.length;
    message.addEventListener('input', updateCount);
    updateCount();
});
</script>
@endpush
