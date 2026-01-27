@extends('layouts.admin')

@section('content')
<h1 class="h3 mb-3"><strong>Watoto</strong> Management</h1>

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
                <h5 class="card-title mb-0">Orodha ya Watoto</h5>
                <div class="float-end mt-n4">
                    <a href="{{ route('watotos.create') }}" class="btn btn-primary">Ongeza Mtoto</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jina la Mtoto</th>
                            <th>Tarehe ya Kuzaliwa</th>
                            <th>Namba ya Mzazi</th>
                            <th>Jumuiya</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($watotos as $mtoto)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $mtoto->jina_la_mtoto }}</td>
                                <td>{{ $mtoto->tarehe_ya_kuzaliwa ?? '-' }}</td>
                                <td>{{ $mtoto->namba_ya_mzazi ?? '-' }}</td>
                                <td>{{ $mtoto->jumuiya?->jina_la_jumuiya }}</td>
                                <td>
                                    <a href="{{ route('watotos.edit', $mtoto->id) }}" class="btn btn-sm btn-info">Hariri</a>
                                    <form action="{{ route('watotos.destroy', $mtoto->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Je, una uhakika unataka kufuta mtoto huyu?')">Futa</button>
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
