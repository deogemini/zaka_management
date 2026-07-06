@extends('layouts.admin')

@section('content')
<div class="mb-3">
    <h1 class="h3 d-inline align-middle">SMS Gateway Settings</h1>
</div>

<div class="row">
    <div class="col-12 col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Flex SMS Gateway Configuration</h5>
                <h6 class="card-subtitle text-muted">Configure your SMS gateway credentials and status.</h6>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <div class="alert-message">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @php
                    $selectedSmsUsers = collect(old('sms_enabled_users', $users->where('sms_enabled', true)->pluck('id')->all()))
                        ->map(fn ($id) => (int) $id)
                        ->all();
                @endphp

                <form action="{{ route('settings.sms.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Base URL</label>
                        <input type="text" name="base_url" class="form-control @error('base_url') is-invalid @enderror" value="{{ old('base_url', $setting->base_url) }}" placeholder="https://sms.flex.co.tz">
                        @error('base_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Client ID</label>
                        <input type="text" name="client_id" class="form-control @error('client_id') is-invalid @enderror" value="{{ old('client_id', $setting->client_id) }}" placeholder="F00102">
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Client Secret</label>
                        <input type="password" name="client_secret" class="form-control @error('client_secret') is-invalid @enderror" value="{{ old('client_secret', $setting->client_secret) }}" placeholder="Your Client Secret">
                        @error('client_secret')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sender ID</label>
                        <input type="text" name="sender_id" class="form-control @error('sender_id') is-invalid @enderror" value="{{ old('sender_id', $setting->sender_id) }}" placeholder="Flex">
                        @error('sender_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_enabled" value="1" {{ old('is_enabled', $setting->is_enabled) ? 'checked' : '' }}>
                            <span class="form-check-label">Enable SMS Sending</span>
                        </label>
                        <small class="text-muted">Turn off to stop all outgoing SMS notifications.</small>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label">Users Allowed to Send SMS</label>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th class="text-end">SMS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $user->name }}</div>
                                                <div class="small text-muted">{{ $user->email }}</div>
                                            </td>
                                            <td>
                                                <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-secondary' }}">{{ $user->role }}</span>
                                            </td>
                                            <td class="text-end">
                                                <label class="form-check form-switch d-inline-flex align-items-center gap-2 mb-0">
                                                    <input class="form-check-input" type="checkbox" name="sms_enabled_users[]" value="{{ $user->id }}" {{ in_array($user->id, $selectedSmsUsers, true) ? 'checked' : '' }}>
                                                    <span class="form-check-label">Allowed</span>
                                                </label>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-muted">No users found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @error('sms_enabled_users')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        @error('sms_enabled_users.*')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">When a user is not allowed, zaka SMS created, imported, or resent by that user will be skipped.</small>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Send Single SMS</h5>
                <h6 class="card-subtitle text-muted">Send one text message to one phone number.</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.sms.send-single') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="recipient" class="form-control @error('recipient') is-invalid @enderror" value="{{ old('recipient') }}" placeholder="0712345678">
                        @error('recipient')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="single_sms_template" class="form-label">SMS Template</label>
                        <select id="single_sms_template" class="form-select">
                            <option value="">-- Write a custom message --</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" data-message="{{ $template->message }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Selecting a template will fill the message below.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea id="single_sms_message" name="message" rows="6" maxlength="160" class="form-control @error('message') is-invalid @enderror" placeholder="Write the SMS text here">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted"><span id="single_sms_count">0</span>/160 characters.</small>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="align-middle" data-feather="send"></i>
                        Send SMS
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Instructions</h5>
            </div>
            <div class="card-body">
                <p>To get your credentials, please visit <a href="https://sms.flex.co.tz" target="_blank">Flex SMS Dashboard</a>.</p>
                <ul>
                    <li><strong>Base URL:</strong> The main API endpoint (usually <code>https://sms.flex.co.tz</code>).</li>
                    <li><strong>Client ID:</strong> Your unique identification provided by Flex SMS.</li>
                    <li><strong>Client Secret:</strong> Your API secret key.</li>
                    <li><strong>Sender ID:</strong> The name that appears as the sender (e.g., <code>Flex</code> or your Parish Name).</li>
                </ul>
                <div class="alert alert-warning">
                    <i class="align-middle" data-feather="alert-triangle"></i>
                    Changing these settings will immediately affect the SMS notification system.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const template = document.getElementById('single_sms_template');
    const message = document.getElementById('single_sms_message');
    const count = document.getElementById('single_sms_count');
    const updateCount = () => count.textContent = message.value.length;

    message.addEventListener('input', updateCount);

    template.addEventListener('change', function () {
        const option = this.options[this.selectedIndex];
        if (option.value) {
            message.value = (option.dataset.message || '').slice(0, 160);
            updateCount();
        }
    });
    updateCount();
});
</script>
@endpush
