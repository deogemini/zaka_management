<?php

namespace App\Exports;

use App\Models\Mwanajumuiya;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MwanajumuiyaExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'jina_la_mwanajumuiya',
            'kadi_namba',
            'namba_ya_simu',
            'jumuiya',
            'kanda',
        ];
    }

    public function collection()
    {
        return Mwanajumuiya::with('jumuiya.kanda')
            ->get()
            ->map(function ($m) {
                return [
                    $m->jina_la_mwanajumuiya,
                    $m->kadi_namba,
                    $m->namba_ya_simu,
                    optional($m->jumuiya)->jina_la_jumuiya,
                    optional(optional($m->jumuiya)->kanda)->jina_la_kanda,
                ];
            });
    }
}
