<?php

namespace App\Http\Controllers;

use App\Models\Zaka;
use App\Models\Mwanajumuiya;
use App\Models\Watoto;
use App\Models\Shukrani;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) ($request->input('year') ?: now()->year);
        $mwanaId = $request->input('mwanajumuiya_id');

        $baseQuery = Zaka::query()->whereYear('paid_at', $year);
        if ($mwanaId) {
            $baseQuery->where('mwanajumuiya_id', $mwanaId);
        }

        $rows = $baseQuery->selectRaw('MONTH(paid_at) as m, COUNT(*) as c, SUM(kiasi) as s')
            ->groupBy('m')
            ->orderBy('m')
            ->get()
            ->keyBy('m');

        $labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $amounts = [];
        $counts = [];
        for ($i = 1; $i <= 12; $i++) {
            $amounts[] = (float) ($rows[$i]->s ?? 0);
            $counts[] = (int) ($rows[$i]->c ?? 0);
        }

        // Watoto Shukrani Logic
        $shukraniQuery = Shukrani::query()->whereYear('paid_at', $year);
        $shukraniRows = $shukraniQuery->selectRaw('MONTH(paid_at) as m, SUM(kiasi) as s')
            ->groupBy('m')
            ->orderBy('m')
            ->get()
            ->keyBy('m');

        $shukraniAmounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $shukraniAmounts[] = (float) ($shukraniRows[$i]->s ?? 0);
        }

        $topMembers = Zaka::query()
            ->whereYear('paid_at', $year)
            ->selectRaw('mwanajumuiya_id, SUM(kiasi) as total, MAX(paid_at) as last_paid')
            ->groupBy('mwanajumuiya_id')
            ->orderByDesc('total')
            ->with('mwanajumuiya.jumuiya')
            ->limit(10)
            ->get();

        $byJumuiya = Zaka::query()
            ->whereYear('paid_at', $year)
            ->join('mwanajumuiyas', 'zakas.mwanajumuiya_id', '=', 'mwanajumuiyas.id')
            ->join('jumuiyas', 'mwanajumuiyas.jumuiya_id', '=', 'jumuiyas.id')
            ->selectRaw('jumuiyas.jina_la_jumuiya as name, SUM(zakas.kiasi) as total')
            ->groupBy('jumuiyas.jina_la_jumuiya')
            ->orderByDesc('total')
            ->limit(8)
            ->get();
        $jumuiyaLabels = $byJumuiya->pluck('name')->toArray();
        $jumuiyaTotals = $byJumuiya->pluck('total')->map(fn($v) => (float) $v)->toArray();

        $byKanda = Zaka::query()
            ->whereYear('paid_at', $year)
            ->join('mwanajumuiyas', 'zakas.mwanajumuiya_id', '=', 'mwanajumuiyas.id')
            ->join('jumuiyas', 'mwanajumuiyas.jumuiya_id', '=', 'jumuiyas.id')
            ->join('kandas', 'jumuiyas.kanda_id', '=', 'kandas.id')
            ->selectRaw('kandas.jina_la_kanda as name, SUM(zakas.kiasi) as total')
            ->groupBy('kandas.jina_la_kanda')
            ->orderByDesc('total')
            ->limit(8)
            ->get();
        $kandaLabels = $byKanda->pluck('name')->toArray();
        $kandaTotals = $byKanda->pluck('total')->map(fn($v) => (float) $v)->toArray();

        $wanajumuiya = Mwanajumuiya::with('jumuiya')->orderBy('jina_la_mwanajumuiya')->get();
        $totalWanajumuiya = Mwanajumuiya::count();
        $totalWatoto = Watoto::count();

        return view('dashboard', compact(
            'labels',
            'amounts',
            'counts',
            'topMembers',
            'wanajumuiya',
            'year',
            'mwanaId',
            'totalWanajumuiya',
            'totalWatoto',
            'jumuiyaLabels',
            'jumuiyaTotals',
            'kandaLabels',
            'kandaTotals',
            'shukraniAmounts'
        ));
    }
}
