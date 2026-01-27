@extends('layouts.admin')

@section('content')
<h1 class="h3 mb-3">Rekodi Zaka</h1>

<div class="row">
    <div class="col-12 col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Maelezo ya Zaka</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('zakas.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Chagua Jumuiya</label>
                        <select id="jumuiya-filter" class="form-select">
                            <option value="">-- Chagua Jumuiya --</option>
                            @foreach(($jumuiyas ?? []) as $j)
                                <option value="{{ $j->id }}" {{ (string)($preselectedJumuiyaId ?? '') === (string)$j->id ? 'selected' : '' }}>
                                    {{ $j->jina_la_jumuiya }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tafuta Jina la Mwanajumuiya</label>
                        <input type="text" id="mwana-search" class="form-control" placeholder="Andika jina kutafuta">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mwanajumuiya</label>
                        <select class="form-select @error('mwanajumuiya_id') is-invalid @enderror" name="mwanajumuiya_id">
                            <option selected disabled>Chagua Mwanajumuiya</option>
                            @foreach($wanajumuiya as $mwana)
                                <option value="{{ $mwana->id }}" {{ old('mwanajumuiya_id', $preselectedId ?? null) == $mwana->id ? 'selected' : '' }}>
                                    {{ $mwana->jina_la_mwanajumuiya }} ({{ $mwana->jumuiya->jina_la_jumuiya }})
                                </option>
                            @endforeach
                        </select>
                        @error('mwanajumuiya_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kiasi</label>
                        <input type="number" step="0.01" class="form-control @error('kiasi') is-invalid @enderror" name="kiasi" placeholder="Ingiza kiasi" value="{{ old('kiasi') }}">
                        @error('kiasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Risiti Namba</label>
                        <input type="text" class="form-control @error('risiti_namba') is-invalid @enderror" name="risiti_namba" placeholder="Ingiza risiti namba" value="{{ old('risiti_namba') }}">
                        @error('risiti_namba')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mode ya Malipo</label>
                        <select class="form-select @error('mode_ya_malipo') is-invalid @enderror" name="mode_ya_malipo">
                            <option disabled>Chagua Mode</option>
                            @php($modes = ['cash' => 'Cash', 'mpesa' => 'M-Pesa', 'bank' => 'Bank', 'other' => 'Other'])
                            @foreach($modes as $value => $label)
                                <option value="{{ $value }}" {{ old('mode_ya_malipo', 'cash') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('mode_ya_malipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hali ya Malipo</label>
                        <select class="form-select @error('hali_ya_malipo') is-invalid @enderror" name="hali_ya_malipo">
                            @php($conditions = ['full' => 'Full', 'partial' => 'Partial'])
                            <option value="">-- Hiari --</option>
                            @foreach($conditions as $value => $label)
                                <option value="{{ $value }}" {{ old('hali_ya_malipo', 'full') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('hali_ya_malipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tarehe ya Malipo</label>
                        <input type="date" class="form-control @error('paid_at') is-invalid @enderror" name="paid_at" value="{{ old('paid_at', date('Y-m-d')) }}">
                        @error('paid_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Hifadhi</button>
                    <a href="{{ route('zakas.index') }}" class="btn btn-secondary">Ghairi</a>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    (function(){
        var members = @json(isset($memberData) ? $memberData : (($wanajumuiya ?? collect())->map(function($m){
            return [
                'id' => $m->id,
                'name' => $m->jina_la_mwanajumuiya,
                'jumuiya_id' => $m->jumuiya->id,
                'jumuiya_name' => $m->jumuiya->jina_la_jumuiya,
            ];
        })->values()));
        var jumuiyaSelect = document.getElementById('jumuiya-filter');
        var searchInput = document.getElementById('mwana-search');
        var memberSelect = document.querySelector('select[name="mwanajumuiya_id"]');
        function renderOptions() {
            var jId = jumuiyaSelect ? jumuiyaSelect.value : '';
            var q = (searchInput ? searchInput.value : '').toLowerCase();
            var list = members.filter(function(m){
                var okJ = jId ? (String(m.jumuiya_id) === String(jId)) : true;
                var okQ = q ? (m.name.toLowerCase().includes(q)) : true;
                return okJ && okQ;
            });
            var current = memberSelect.value;
            memberSelect.innerHTML = '';
            var ph = document.createElement('option');
            ph.textContent = 'Chagua Mwanajumuiya';
            ph.disabled = true;
            ph.selected = !current;
            memberSelect.appendChild(ph);
            list.forEach(function(m){
                var opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.name + ' (' + m.jumuiya_name + ')';
                if (String(current) === String(m.id)) opt.selected = true;
                memberSelect.appendChild(opt);
            });
        }
        if (jumuiyaSelect) jumuiyaSelect.addEventListener('change', renderOptions);
        if (searchInput) searchInput.addEventListener('input', renderOptions);
        renderOptions();
    })();
</script>
@endpush
@endsection
