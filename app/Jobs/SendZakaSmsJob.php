<?php

namespace App\Jobs;

use App\Models\Zaka;
use App\Services\FlexSmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendZakaSmsJob implements ShouldQueue
{
    use Queueable;

    protected $zaka;

    /**
     * Create a new job instance.
     */
    public function __construct(Zaka $zaka)
    {
        $this->zaka = $zaka->load('mwanajumuiya');
    }

    /**
     * Execute the job.
     */
    public function handle(FlexSmsService $smsService): void
    {
        Log::info('SendZakaSmsJob handle started for Zaka ID ' . $this->zaka->id);
        $mwanajumuiya = $this->zaka->mwanajumuiya;

        if (!$mwanajumuiya || !$mwanajumuiya->namba_ya_simu) {
            Log::info('SMS Skipped: Mwanajumuiya or phone number missing for Zaka ID ' . $this->zaka->id);
            return;
        }

        $amount = number_format($this->zaka->kiasi);
        $receiptNumber = $this->zaka->risiti_namba;

        $message = "Asante! Parokia ya Bombambili imepokea zaka yako ya Tsh $amount (Risiti: $receiptNumber ).\n\"Apandaye kwa ukarimu, atavuna kwa ukarimu.\" 2Wakorintho 9:6";

        if ($smsService->sendSms($mwanajumuiya->namba_ya_simu, $message)) {
            $this->zaka->update(['sms_sent' => true]);
        }
    }
}
