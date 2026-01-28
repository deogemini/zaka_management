@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Audit Logs</h1>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Model</th>
                            <th>ID</th>
                            <th>IP Address</th>
                            <th>Time</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->user ? $log->user->name : 'System/Unknown' }}</td>
                            <td>{{ $log->action }}</td>
                            <td>{{ class_basename($log->auditable_type) }}</td>
                            <td>{{ $log->auditable_id }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#logModal{{ $log->id }}">
                                    View
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Log Details #{{ $log->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>User Agent:</strong> {{ $log->user_agent }}</p>
                                                <p><strong>Route:</strong> {{ $log->route }}</p>
                                                <p><strong>Method:</strong> {{ $log->method }}</p>
                                                <hr>
                                                <h6>Changes:</h6>
                                                <pre>{{ json_encode($log->changes, JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
