@extends('layouts.admin')

@section('content')
<h1 class="h3 mb-3">Create SMS Campaign</h1>

<div class="row">
    <div class="col-12 col-xl-7">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Campaign Message</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.sms-campaigns.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Campaign Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Example: Meeting Reminder">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Recipients</label>
                        <select name="target_type" id="target_type" class="form-select @error('target_type') is-invalid @enderror">
                            <option value="all" {{ old('target_type', 'all') === 'all' ? 'selected' : '' }}>All members</option>
                            <option value="jumuiya" {{ old('target_type') === 'jumuiya' ? 'selected' : '' }}>Specific jumuiya</option>
                            <option value="excel" {{ old('target_type') === 'excel' ? 'selected' : '' }}>Upload Excel file</option>
                        </select>
                        @error('target_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3" id="jumuiya-group">
                        <label class="form-label">Jumuiya</label>
                        <select name="jumuiya_id" class="form-select @error('jumuiya_id') is-invalid @enderror">
                            <option value="">Choose jumuiya</option>
                            @foreach($jumuiyas as $jumuiya)
                                <option value="{{ $jumuiya->id }}" {{ (string) old('jumuiya_id') === (string) $jumuiya->id ? 'selected' : '' }}>
                                    {{ $jumuiya->jina_la_jumuiya }}
                                </option>
                            @endforeach
                        </select>
                        @error('jumuiya_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3" id="recipients-file-group">
                        <label class="form-label">Recipients Excel File</label>
                        <input type="file" name="recipients_file" class="form-control @error('recipients_file') is-invalid @enderror" accept=".xlsx,.xls,.csv">
                        @error('recipients_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Use columns named <code>name</code> and <code>phone</code>. Phone can also be <code>phone_number</code> or <code>namba_ya_simu</code>.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" rows="7" class="form-control @error('message') is-invalid @enderror" maxlength="1000" placeholder="Write the SMS campaign text here">{{ old('message') }}</textarea>
                        @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">This message will be sent to every selected recipient with a phone number.</small>
                    </div>

                    <button type="submit" class="btn btn-primary">Send Campaign</button>
                    <a href="{{ route('settings.sms-campaigns.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-5">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Delivery Tracking</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">After sending, the campaign page will show each recipient as pending, sent, or failed. Excel uploads accept .xlsx, .xls, or .csv files.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const targetType = document.getElementById('target_type');
    const jumuiyaGroup = document.getElementById('jumuiya-group');
    const recipientsFileGroup = document.getElementById('recipients-file-group');

    function toggleRecipientFields() {
        jumuiyaGroup.style.display = targetType.value === 'jumuiya' ? '' : 'none';
        recipientsFileGroup.style.display = targetType.value === 'excel' ? '' : 'none';
    }

    targetType.addEventListener('change', toggleRecipientFields);
    toggleRecipientFields();
});
</script>
@endpush
