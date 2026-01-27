@extends('layouts.admin')

@section('content')
<h1 class="h3 mb-3"><strong>Zaka</strong> Management</h1>

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
                <h5 class="card-title mb-0">Orodha ya Zaka</h5>
                <div class="float-end mt-n4">
                    <a href="{{ route('zakas.import.form') }}" class="btn btn-outline-primary">Import Excel</a>
                    <a href="{{ route('zakas.sample') }}" class="btn btn-outline-secondary">Pakua Template</a>
                    <a href="{{ route('zakas.create') }}" class="btn btn-primary">Rekodi Zaka</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mwanajumuiya</th>
                            <th>Jumuiya</th>
                            <th>Kiasi</th>
                            <th>Risiti Namba</th>
                            <th>Mode ya Malipo</th>
                            <th>Hali ya Malipo</th>
                            <th>Muda wa Malipo</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($zakas as $zaka)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $zaka->mwanajumuiya->jina_la_mwanajumuiya }}</td>
                                <td>{{ $zaka->mwanajumuiya->jumuiya->jina_la_jumuiya }}</td>
                                <td>{{ number_format($zaka->kiasi, 2) }}</td>
                                <td>{{ $zaka->risiti_namba }}</td>
                                <td>{{ $zaka->mode_ya_malipo }}</td>
                                <td>{{ $zaka->hali_ya_malipo ?? '-' }}</td>
                                <td>{{ optional($zaka->paid_at)->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('zakas.edit', $zaka->id) }}" class="btn btn-sm btn-info">Hariri</a>
                                    <form action="{{ route('zakas.destroy', $zaka->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Je, una uhakika unataka kufuta rekodi hii ya zaka?')">Futa</button>
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
