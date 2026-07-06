<div class="mb-3">
    <label class="form-label">Template Name</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $smsTemplate->name ?? '') }}" maxlength="255" placeholder="Example: Meeting Reminder">
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Message</label>
    <textarea id="template_message" name="message" rows="8" class="form-control @error('message') is-invalid @enderror" maxlength="160" placeholder="Write the reusable SMS text here">{{ old('message', $smsTemplate->message ?? '') }}</textarea>
    @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <small class="text-muted"><span id="message_count">0</span>/160 characters</small>
</div>

<div class="mb-3">
    <label class="form-check form-switch">
        <input type="hidden" name="is_active" value="0">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $smsTemplate->is_active ?? true) ? 'checked' : '' }}>
        <span class="form-check-label">Active</span>
    </label>
    <small class="text-muted">Only active templates appear when composing an SMS.</small>
</div>

<button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
<a href="{{ route('settings.sms-templates.index') }}" class="btn btn-secondary">Cancel</a>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const message = document.getElementById('template_message');
    const count = document.getElementById('message_count');
    const updateCount = () => count.textContent = message.value.length;
    message.addEventListener('input', updateCount);
    updateCount();
});
</script>
@endpush
