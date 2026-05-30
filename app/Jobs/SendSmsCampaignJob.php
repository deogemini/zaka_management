<?php

namespace App\Jobs;

use App\Models\SmsCampaign;
use App\Models\User;
use App\Services\FlexSmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendSmsCampaignJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected SmsCampaign $campaign,
        protected ?int $senderUserId = null
    ) {
    }

    public function handle(FlexSmsService $smsService): void
    {
        $campaign = SmsCampaign::with('recipients')->find($this->campaign->id);

        if (!$campaign) {
            return;
        }

        if ($this->senderUserId) {
            $sender = User::find($this->senderUserId);

            if (!$sender || !$sender->sms_enabled) {
                $campaign->recipients()->where('status', 'pending')->update([
                    'status' => 'failed',
                    'error_message' => 'SMS sending is disabled for this user.',
                ]);
                $this->refreshCampaignCounts($campaign);
                Log::info('SMS Campaign Skipped: user SMS sending is disabled or user is missing for User ID ' . $this->senderUserId . ' on Campaign ID ' . $campaign->id);
                return;
            }
        }

        $campaign->update(['status' => 'sending']);

        $pendingRecipients = $campaign->recipients()->where('status', 'pending')->get();
        $recipientsWithPhone = $pendingRecipients->filter(fn ($recipient) => filled($recipient->phone));
        $recipientsWithoutPhone = $pendingRecipients->reject(fn ($recipient) => filled($recipient->phone));

        foreach ($recipientsWithoutPhone as $recipient) {
            $recipient->update([
                'status' => 'failed',
                'error_message' => 'Phone number is missing.',
            ]);
        }

        $validRecipients = $recipientsWithPhone->filter(function ($recipient) use ($smsService) {
            $formattedPhone = $smsService->formattedRecipient((string) $recipient->phone);

            if (!$smsService->isValidRecipient($formattedPhone)) {
                $recipient->update([
                    'status' => 'failed',
                    'error_message' => 'Phone number format is invalid.',
                ]);

                return false;
            }

            return true;
        });

        if ($validRecipients->isNotEmpty()) {
            $sent = $smsService->sendBulkSms(
                $validRecipients->pluck('phone')->all(),
                $campaign->message
            );

            foreach ($validRecipients as $recipient) {
                $recipient->update([
                    'status' => $sent ? 'sent' : 'failed',
                    'error_message' => $sent ? null : 'SMS gateway rejected or failed to send this campaign.',
                    'sent_at' => $sent ? now() : null,
                ]);
            }
        }

        $this->refreshCampaignCounts($campaign);
    }

    protected function refreshCampaignCounts(SmsCampaign $campaign): void
    {
        $sentCount = $campaign->recipients()->where('status', 'sent')->count();
        $failedCount = $campaign->recipients()->where('status', 'failed')->count();
        $pendingCount = $campaign->recipients()->where('status', 'pending')->count();

        $campaign->update([
            'sent_count' => $sentCount,
            'failed_count' => $failedCount,
            'status' => $pendingCount > 0 ? 'sending' : ($failedCount > 0 ? 'completed_with_errors' : 'completed'),
            'sent_at' => $pendingCount > 0 ? null : now(),
        ]);
    }
}
