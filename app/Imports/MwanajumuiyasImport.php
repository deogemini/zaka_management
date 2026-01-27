<?php

namespace App\Imports;

use App\Models\Jumuiya;
use App\Models\Mwanajumuiya;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MwanajumuiyasImport implements ToCollection, WithHeadingRow
{
    protected int $created = 0;
    protected int $skipped = 0;
    protected array $errors = [];
    protected ?int $jumuiyaId = null;

    public function __construct(?int $jumuiyaId = null)
    {
        $this->jumuiyaId = $jumuiyaId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                $name = $row['jina_la_mwanajumuiya'] ?? null;
                $kadi = $row['kadi_namba'] ?? null;
                $simu = $row['namba_ya_simu'] ?? null;
                $jumuiyaName = $row['jumuiya'] ?? null;

                if ($this->jumuiyaId) {
                    if (!$name || !$kadi || !$simu) {
                        $this->skipped++;
                        $this->errors[] = "Row ".($index+2).": Missing required fields.";
                        continue;
                    }
                } else {
                    if (!$name || !$kadi || !$simu || !$jumuiyaName) {
                        $this->skipped++;
                        $this->errors[] = "Row ".($index+2).": Missing required fields.";
                        continue;
                    }
                }

                if (Mwanajumuiya::where('kadi_namba', $kadi)->exists()) {
                    $this->skipped++;
                    $this->errors[] = "Row ".($index+2).": Duplicate kadi_namba '{$kadi}'.";
                    continue;
                }

                if (Mwanajumuiya::where('namba_ya_simu', $simu)->exists()) {
                    $this->skipped++;
                    $this->errors[] = "Row ".($index+2).": Duplicate namba_ya_simu '{$simu}'.";
                    continue;
                }

                if ($this->jumuiyaId) {
                    $jumuiya = Jumuiya::find($this->jumuiyaId);
                    if (!$jumuiya) {
                        $this->skipped++;
                        $this->errors[] = "Row ".($index+2).": Selected Jumuiya not found.";
                        continue;
                    }
                } else {
                    $jumuiya = Jumuiya::where('jina_la_jumuiya', $jumuiyaName)->first();
                    if (!$jumuiya) {
                        $this->skipped++;
                        $this->errors[] = "Row ".($index+2).": Jumuiya '{$jumuiyaName}' not found.";
                        continue;
                    }
                }

                Mwanajumuiya::create([
                    'jumuiya_id' => $jumuiya->id,
                    'jina_la_mwanajumuiya' => (string) $name,
                    'kadi_namba' => (string) $kadi,
                    'namba_ya_simu' => (string) $simu,
                ]);

                $this->created++;
            } catch (\Throwable $e) {
                $this->skipped++;
                $this->errors[] = "Row ".($index+2).": ".$e->getMessage();
            }
        }
    }

    public function summary(): array
    {
        return [
            'created' => $this->created,
            'skipped' => $this->skipped,
            'errors' => $this->errors,
        ];
    }
}
