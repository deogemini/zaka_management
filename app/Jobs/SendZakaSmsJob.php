<?php

namespace App\Jobs;

use App\Models\Zaka;
use App\Models\User;
use App\Services\FlexSmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendZakaSmsJob implements ShouldQueue
{
    use Queueable;

    protected $zaka;
    protected ?int $senderUserId;

    /**
     * Create a new job instance.
     */
    public function __construct(Zaka $zaka, ?User $sender = null)
    {
        $this->zaka = $zaka->load('mwanajumuiya');
        $this->senderUserId = $sender?->id;
    }

    /**
     * Execute the job.
     */
    public function handle(FlexSmsService $smsService): void
    {
        if ($this->senderUserId) {
            $sender = User::find($this->senderUserId);

            if (!$sender || !$sender->sms_enabled) {
                Log::info('SMS Skipped: user SMS sending is disabled or user is missing for User ID ' . $this->senderUserId . ' on Zaka ID ' . $this->zaka->id);
                return;
            }
        }

        $mwanajumuiya = $this->zaka->mwanajumuiya;

        if (!$mwanajumuiya || !$mwanajumuiya->namba_ya_simu) {
            Log::info('SMS Skipped: Mwanajumuiya or phone number missing for Zaka ID ' . $this->zaka->id);
            return;
        }

        $amount = number_format($this->zaka->kiasi);
        $receiptNumber = $this->zaka->risiti_namba;
        $date = $this->zaka->paid_at ? $this->zaka->paid_at->format('d/m/Y') : 'N/A';

        $message = "Asante! Parokia ya Bombambili imepokea zaka yako ya Tsh $amount (Risiti: $receiptNumber, Tarehe: $date ).\n\"Apandaye kwa ukarimu, atavuna kwa ukarimu.\" 2Wakorintho 9:6";

        if ($smsService->sendSms($mwanajumuiya->namba_ya_simu, $message)) {
            $this->zaka->update(['sms_sent' => true]);
        }
    }
}
