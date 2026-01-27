@extends('layouts.admin')

@section('content')
<h1 class="h3 mb-3"><strong>Users</strong> Management</h1>

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
                <h5 class="card-title mb-0">Orodha ya Watumiaji</h5>
                <a href="{{ route('users.create') }}" class="btn btn-primary float-end mt-n4">Ongeza Mtumiaji</a>
            </div>
            <div class="card-body">
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jina</th>
                            <th>Email</th>
                            <th>Namba ya Simu</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->phone }}</td>
                            <td><span class="badge {{ $u->role === 'admin' ? 'bg-danger' : 'bg-secondary' }}">{{ $u->role }}</span></td>
                            <td>
                                <a href="{{ route('users.edit', $u->id) }}" class="btn btn-sm btn-info">Hariri</a>
                                <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Je, una uhakika unataka kufuta mtumiaji huyu?')">Futa</button>
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
