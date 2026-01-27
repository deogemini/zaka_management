@extends('layouts.admin')

@section('content')
<h1 class="h3 mb-3">Import Wanajumuiya kutoka Excel</h1>

<div class="row">
    <div class="col-12 col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Upload Faili ya Excel</h5>
                <div>
                    <a href="{{ route('mwanajumuiya.sample') }}" class="btn btn-outline-secondary btn-sm">Pakua Template</a>
                    <a href="{{ route('mwanajumuiya.export') }}" class="btn btn-outline-primary btn-sm">Pakua Orodha</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('mwanajumuiya.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Faili (.xlsx, .xls, .csv)</label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" accept=".xlsx,.xls,.csv">
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Kolamu: jina_la_mwanajumuiya, kadi_namba, namba_ya_simu, jumuiya (jina kamili la jumuiya).
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Import</button>
                    <a href="{{ route('mwanajumuiya.index') }}" class="btn btn-secondary">Rudi Orodha</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
