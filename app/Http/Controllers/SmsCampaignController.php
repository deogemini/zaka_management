<?php

namespace App\Http\Controllers;

use App\Jobs\SendSmsCampaignJob;
use App\Models\Jumuiya;
use App\Models\Mwanajumuiya;
use App\Models\SmsCampaign;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SmsCampaignController extends Controller
{
    public function index()
    {
        $campaigns = SmsCampaign::with(['user', 'jumuiya'])
            ->latest()
            ->get();

        return view('settings.sms-campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $jumuiyas = Jumuiya::orderBy('jina_la_jumuiya')->get();

        return view('settings.sms-campaigns.create', compact('jumuiyas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:1000'],
            'target_type' => ['required', Rule::in(['all', 'jumuiya'])],
            'jumuiya_id' => ['nullable', 'required_if:target_type,jumuiya', 'exists:jumuiyas,id'],
        ]);

        if (!$request->user()->sms_enabled) {
            throw ValidationException::withMessages([
                'message' => 'SMS sending is disabled for your user account.',
            ]);
        }

        $members = Mwanajumuiya::query()
            ->when($data['target_type'] === 'jumuiya', fn ($query) => $query->where('jumuiya_id', $data['jumuiya_id']))
            ->whereNotNull('namba_ya_simu')
            ->where('namba_ya_simu', '!=', '')
            ->orderBy('jina_la_mwanajumuiya')
            ->get();

        if ($members->isEmpty()) {
            throw ValidationException::withMessages([
                'target_type' => 'No members with phone numbers were found for this campaign target.',
            ]);
        }

        $campaign = SmsCampaign::create([
            'user_id' => $request->user()->id,
            'jumuiya_id' => $data['target_type'] === 'jumuiya' ? $data['jumuiya_id'] : null,
            'title' => $data['title'],
            'message' => $data['message'],
            'target_type' => $data['target_type'],
            'status' => 'pending',
            'total_recipients' => $members->count(),
        ]);

        foreach ($members as $member) {
            $campaign->recipients()->create([
                'mwanajumuiya_id' => $member->id,
                'name' => $member->jina_la_mwanajumuiya,
                'phone' => $member->namba_ya_simu,
                'status' => 'pending',
            ]);
        }

        AuditService::log('sms_campaign.create', $campaign, [
            'target_type' => $campaign->target_type,
            'jumuiya_id' => $campaign->jumuiya_id,
            'total_recipients' => $campaign->total_recipients,
        ]);

        SendSmsCampaignJob::dispatch($campaign, $request->user()->id)->afterResponse();

        return redirect()->route('settings.sms-campaigns.show', $campaign)->with('success', 'SMS campaign queued successfully.');
    }

    public function show(SmsCampaign $smsCampaign)
    {
        $smsCampaign->load(['user', 'jumuiya', 'recipients.mwanajumuiya.jumuiya']);

        return view('settings.sms-campaigns.show', ['campaign' => $smsCampaign]);
    }

    public function edit(SmsCampaign $smsCampaign)
    {
        return view('settings.sms-campaigns.edit', ['campaign' => $smsCampaign]);
    }

    public function update(Request $request, SmsCampaign $smsCampaign)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $smsCampaign->update($data);

        AuditService::log('sms_campaign.update', $smsCampaign, [
            'title' => $smsCampaign->title,
        ]);

        return redirect()->route('settings.sms-campaigns.show', $smsCampaign)->with('success', 'SMS campaign text updated successfully.');
    }

    public function resendFailed(Request $request, SmsCampaign $smsCampaign)
    {
        if (!$request->user()->sms_enabled) {
            throw ValidationException::withMessages([
                'message' => 'SMS sending is disabled for your user account.',
            ]);
        }

        $failedCount = $smsCampaign->recipients()->where('status', 'failed')->count();

        if ($failedCount === 0) {
            return back()->with('success', 'There are no failed SMS recipients to resend.');
        }

        $smsCampaign->recipients()->where('status', 'failed')->update([
            'status' => 'pending',
            'error_message' => null,
            'sent_at' => null,
        ]);

        $smsCampaign->update([
            'status' => 'sending',
            'failed_count' => 0,
            'sent_at' => null,
        ]);

        AuditService::log('sms_campaign.resend_failed', $smsCampaign, [
            'failed_recipients' => $failedCount,
        ]);

        SendSmsCampaignJob::dispatch($smsCampaign, $request->user()->id)->afterResponse();

        return back()->with('success', 'Failed SMS recipients queued for resend.');
    }
}
