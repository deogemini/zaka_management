@extends('layouts.admin')

@section('content')
<h1 class="h3 mb-3"><strong>Mwanajumuiya</strong> Management</h1>

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
                <h5 class="card-title mb-0">Orodha ya Wanajumuiya</h5>
                <div class="float-end mt-n4">
                    <a href="{{ route('mwanajumuiya.import.form') }}" class="btn btn-outline-primary">Import Excel</a>
                    <a href="{{ route('mwanajumuiya.sample') }}" class="btn btn-outline-secondary">Pakua Template</a>
                    <a href="{{ route('mwanajumuiya.export') }}" class="btn btn-outline-success">Pakua Orodha</a>
                    <a href="{{ route('mwanajumuiya.create') }}" class="btn btn-primary">Ongeza Mwanajumuiya</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jina la Mwanajumuiya</th>
                            <th>Kadi Namba</th>
                            <th>Namba ya Simu</th>
                            <th>Jumuiya</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wanajumuiya as $mwana)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $mwana->jina_la_mwanajumuiya }}</td>
                                <td>{{ $mwana->kadi_namba }}</td>
                                <td>{{ $mwana->namba_ya_simu }}</td>
                                <td>{{ $mwana->jumuiya->jina_la_jumuiya }}</td>
                                <td>
                                    <a href="{{ route('mwanajumuiya.edit', $mwana->id) }}" class="btn btn-sm btn-info">Hariri</a>
                                    <form action="{{ route('mwanajumuiya.destroy', $mwana->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Je, una uhakika unataka kufuta mwanajumuiya huyu?')">Futa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
