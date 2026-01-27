<?php

namespace App\Http\Controllers;

use App\Models\Zaka;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function zaka(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month');

        $query = Zaka::with('mwanajumuiya.jumuiya')
            ->whereYear('paid_at', $year);

        if ($month) {
            $query->whereMonth('paid_at', $month);
        }

        $zakas = $query->orderBy('paid_at', 'desc')->get();
        $total = $zakas->sum('kiasi');

        return view('reports.zaka', compact('zakas', 'total', 'year', 'month'));
    }

    public function jumuiya(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month');

        $query = DB::table('jumuiyas')
            ->join('mwanajumuiyas', 'jumuiyas.id', '=', 'mwanajumuiyas.jumuiya_id')
            ->join('zakas', 'mwanajumuiyas.id', '=', 'zakas.mwanajumuiya_id')
            ->select('jumuiyas.jina_la_jumuiya', DB::raw('SUM(zakas.kiasi) as total'))
            ->whereYear('zakas.paid_at', $year)
            ->groupBy('jumuiyas.id', 'jumuiyas.jina_la_jumuiya')
            ->orderByDesc('total');

        if ($month) {
            $query->whereMonth('zakas.paid_at', $month);
        }

        $data = $query->get();
        $total = $data->sum('total');

        return view('reports.jumuiya', compact('data', 'total', 'year', 'month'));
    }

    public function kanda(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month');

        $query = DB::table('kandas')
            ->join('jumuiyas', 'kandas.id', '=', 'jumuiyas.kanda_id')
            ->join('mwanajumuiyas', 'jumuiyas.id', '=', 'mwanajumuiyas.jumuiya_id')
            ->join('zakas', 'mwanajumuiyas.id', '=', 'zakas.mwanajumuiya_id')
            ->select('kandas.jina_la_kanda', DB::raw('SUM(zakas.kiasi) as total'))
            ->whereYear('zakas.paid_at', $year)
            ->groupBy('kandas.id', 'kandas.jina_la_kanda')
            ->orderByDesc('total');

        if ($month) {
            $query->whereMonth('zakas.paid_at', $month);
        }

        $data = $query->get();
        $total = $data->sum('total');

        return view('reports.kanda', compact('data', 'total', 'year', 'month'));
    }
}
