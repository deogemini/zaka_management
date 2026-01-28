@extends('layouts.admin')

@section('content')
<h1 class="h3 mb-3"><strong>Jumuiya</strong> Management</h1>

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
                <h5 class="card-title mb-0">Orodha ya Jumuiya</h5>
                <a href="{{ route('jumuiyas.create') }}" class="btn btn-primary float-end mt-n4">Ongeza Jumuiya</a>
            </div>
            <div class="card-body">
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jina la Jumuiya</th>
                            <th>Kanda</th>
                            <th>Wanajumuiya Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jumuiyas as $jumuiya)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $jumuiya->jina_la_jumuiya }}</td>
                                <td>{{ $jumuiya->kanda->jina_la_kanda }}</td>
                                <td><span class="badge bg-primary">{{ $jumuiya->wanajumuiya_count }}</span></td>
                                <td>
                                    <a href="{{ route('jumuiyas.edit', $jumuiya->id) }}" class="btn btn-sm btn-info">Hariri</a>
                                    <a href="{{ route('mwanajumuiya.create', ['jumuiya_id' => $jumuiya->id]) }}" class="btn btn-sm btn-primary">Ongeza Mwanajumuiya</a>
                                    <form action="{{ route('jumuiyas.destroy', $jumuiya->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Je, una uhakika unataka kufuta jumuiya hii?')">Futa</button>
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
