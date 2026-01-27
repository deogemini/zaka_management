@extends('layouts.admin')

@section('content')
<h1 class="h3 mb-3"><strong>Shukrani</strong> Management</h1>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Orodha ya Shukrani</h5>
                <div class="float-end mt-n4">
                    <a href="{{ route('shukranis.create') }}" class="btn btn-primary">Ongeza Shukrani</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mtoto</th>
                            <th>Jumuiya</th>
                            <th>Kiasi</th>
                            <th>Mode</th>
                            <th>Hali</th>
                            <th>Tarehe</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shukranis as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->mtoto?->jina_la_mtoto }}</td>
                                <td>{{ $row->mtoto?->jumuiya?->jina_la_jumuiya }}</td>
                                <td>{{ number_format($row->kiasi) }}</td>
                                <td>{{ $row->mode_ya_malipo ?? '-' }}</td>
                                <td>{{ $row->hali_ya_malipo ?? '-' }}</td>
                                <td>{{ optional($row->paid_at)?->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('shukranis.edit', $row->id) }}" class="btn btn-sm btn-info">Hariri</a>
                                    <form action="{{ route('shukranis.destroy', $row->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Je, una uhakika unataka kufuta rekodi hii?')">Futa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
