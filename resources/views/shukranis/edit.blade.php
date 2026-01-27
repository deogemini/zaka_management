@extends('layouts.admin')

@section('content')
<h1 class="h3 mb-3">Hariri Shukrani</h1>

<div class="row">
    <div class="col-12 col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Maelezo ya Shukrani</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('shukranis.update', $shukrani->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Mtoto</label>
                        <select class="form-select @error('watoto_id') is-invalid @enderror" name="watoto_id">
                            @foreach($watoto as $c)
                                <option value="{{ $c->id }}" {{ old('watoto_id', $shukrani->watoto_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->jina_la_mtoto }} ({{ $c->jumuiya->jina_la_jumuiya }})
                                </option>
                            @endforeach
                        </select>
                        @error('watoto_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kiasi</label>
                        <input type="number" step="0.01" class="form-control @error('kiasi') is-invalid @enderror" name="kiasi" value="{{ old('kiasi', $shukrani->kiasi) }}">
                        @error('kiasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mode ya Malipo</label>
                        @php($modes = ['cash' => 'Cash', 'mpesa' => 'M-Pesa', 'bank' => 'Bank', 'other' => 'Other'])
                        <select class="form-select @error('mode_ya_malipo') is-invalid @enderror" name="mode_ya_malipo">
                            @foreach($modes as $value => $label)
                                <option value="{{ $value }}" {{ old('mode_ya_malipo', $shukrani->mode_ya_malipo ?? 'cash') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('mode_ya_malipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hali ya Malipo</label>
                        @php($conditions = ['full' => 'Full', 'partial' => 'Partial'])
                        <select class="form-select @error('hali_ya_malipo') is-invalid @enderror" name="hali_ya_malipo">
                            @foreach($conditions as $value => $label)
                                <option value="{{ $value }}" {{ old('hali_ya_malipo', $shukrani->hali_ya_malipo ?? 'full') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('hali_ya_malipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tarehe ya Malipo</label>
                        <input type="date" class="form-control @error('paid_at') is-invalid @enderror" name="paid_at" value="{{ old('paid_at', optional($shukrani->paid_at)->format('Y-m-d')) }}">
                        @error('paid_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Hifadhi</button>
                    <a href="{{ route('shukranis.index') }}" class="btn btn-secondary">Ghairi</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
