@extends('layouts.admin')

@section('content')
<h1 class="h3 mb-3"><strong>Kanda</strong> Management</h1>

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
                <h5 class="card-title mb-0">Orodha ya Kanda</h5>
                <a href="{{ route('kandas.create') }}" class="btn btn-primary float-end mt-n4">Ongeza Kanda</a>
            </div>
            <div class="card-body">
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jina la Kanda</th>
                            <th>Eneo la Kanda</th>
                            <th>Tarehe ya Kuundwa</th>
                            <th>Jumuiya Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kandas as $kanda)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $kanda->jina_la_kanda }}</td>
                                <td>{{ $kanda->eneo_la_kanda }}</td>
                                <td>{{ optional($kanda->created_at)->format('Y-m-d') }}</td>
                                <td><span class="badge bg-primary">{{ $kanda->jumuiyas_count }}</span></td>
                                <td>
                                    <a href="{{ route('jumuiyas.index', ['kanda_id' => $kanda->id]) }}" class="btn btn-sm btn-secondary">Ona Jumuiya</a>
                                    <a href="{{ route('jumuiyas.create', ['kanda_id' => $kanda->id]) }}" class="btn btn-sm btn-primary">Ongeza Jumuiya</a>
                                    <a href="{{ route('kandas.edit', $kanda->id) }}" class="btn btn-sm btn-info">Hariri</a>
                                    <form action="{{ route('kandas.destroy', $kanda->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Je, una uhakika unataka kufuta kanda hii?')">Futa</button>
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
