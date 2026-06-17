<?php

namespace App\Imports;

use App\Services\FlexSmsService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SmsCampaignRecipientsImport implements ToCollection, WithHeadingRow
{
    protected array $recipients = [];
    protected int $skipped = 0;
    protected array $errors = [];

    public function __construct(protected FlexSmsService $smsService)
    {
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $data = is_array($row) ? $row : $row->toArray();
            $name = $this->getFirstValue($data, ['name', 'jina', 'majina', 'jina_la_mwanajumuiya', 'mwanajumuiya']);
            $phone = $this->getFirstValue($data, ['phone', 'phone_number', 'mobile', 'mobile_number', 'simu', 'namba', 'contact', 'namba_ya_simu']);
            $formattedPhone = $this->smsService->formattedRecipient((string) $phone);

            if (!$this->smsService->isValidRecipient($formattedPhone)) {
                $this->skipped++;
                $this->errors[] = 'Row ' . ($index + 2) . ': Phone number is missing or invalid.';
                continue;
            }

            if (isset($this->recipients[$formattedPhone])) {
                $this->skipped++;
                $this->errors[] = 'Row ' . ($index + 2) . ': Duplicate phone number ' . $formattedPhone . '.';
                continue;
            }

            $this->recipients[$formattedPhone] = [
                'name' => $name ?: $formattedPhone,
                'phone' => $formattedPhone,
            ];
        }
    }

    protected function getFirstValue(array $row, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (isset($row[$key]) && $row[$key] !== null && $row[$key] !== '') {
                return trim((string) $row[$key]);
            }
        }

        return null;
    }

    public function recipients(): array
    {
        return array_values($this->recipients);
    }

    public function summary(): array
    {
        return [
            'imported' => count($this->recipients),
            'skipped' => $this->skipped,
            'errors' => $this->errors,
        ];
    }
}
