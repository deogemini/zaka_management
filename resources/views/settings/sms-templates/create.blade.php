@extends('layouts.admin')

@section('content')
<h1 class="h3 mb-3">Create SMS Template</h1>

<div class="row">
    <div class="col-12 col-xl-7">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Template Details</h5></div>
            <div class="card-body">
                <form action="{{ route('settings.sms-templates.store') }}" method="POST">
                    @csrf
                    @include('settings.sms-templates._form', ['submitLabel' => 'Create Template'])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
