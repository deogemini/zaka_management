@extends('layouts.admin')

@section('content')
<div class="mb-3">
    <h1 class="h3 d-inline align-middle">SMS Templates</h1>
    <a href="{{ route('settings.sms-templates.create') }}" class="btn btn-primary float-end">New Template</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Reusable Messages</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $template)
                    <tr>
                        <td class="fw-semibold">{{ $template->name }}</td>
                        <td style="max-width: 560px; white-space: pre-wrap;">{{ $template->message }}</td>
                        <td>
                            <span class="badge {{ $template->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $template->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('settings.sms-templates.edit', $template) }}" class="btn btn-sm btn-info">Edit</a>
                            <form action="{{ route('settings.sms-templates.destroy', $template) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this SMS template?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No SMS templates have been created.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
