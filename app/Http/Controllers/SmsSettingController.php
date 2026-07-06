<?php

namespace App\Http\Controllers;

use App\Models\SmsSetting;
use App\Models\User;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use App\Services\AuditService;
use App\Services\FlexSmsService;
use Illuminate\Validation\ValidationException;

class SmsSettingController extends Controller
{
    public function index()
    {
        $setting = SmsSetting::firstOrCreate([], [
            'base_url' => 'https://sms.flex.co.tz',
            'sender_id' => 'Flex',
            'is_enabled' => true,
        ]);
        $users = User::orderBy('name')->get();
        $templates = SmsTemplate::where('is_active', true)->orderBy('name')->get();

        return view('settings.sms', compact('setting', 'users', 'templates'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'base_url' => 'required|url',
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'sender_id' => 'required|string',
            'is_enabled' => 'boolean',
            'sms_enabled_users' => 'nullable|array',
            'sms_enabled_users.*' => 'integer|exists:users,id',
        ]);

        $setting = SmsSetting::firstOrCreate([], [
            'base_url' => 'https://sms.flex.co.tz',
            'sender_id' => 'Flex',
            'is_enabled' => true,
        ]);
        $original = $setting->getOriginal();
        
        $data = $request->only([
            'base_url',
            'client_id',
            'client_secret',
            'sender_id',
        ]);
        $data['is_enabled'] = $request->has('is_enabled');

        $setting->update($data);

        $enabledUserIds = collect($request->input('sms_enabled_users', []))->map(fn ($id) => (int) $id)->all();
        $userChanges = [];
        foreach (User::orderBy('name')->get() as $user) {
            $smsEnabled = in_array($user->id, $enabledUserIds, true);

            if ($user->sms_enabled !== $smsEnabled) {
                $userChanges[$user->id] = [
                    'name' => $user->name,
                    'from' => $user->sms_enabled,
                    'to' => $smsEnabled,
                ];
                $user->update(['sms_enabled' => $smsEnabled]);
            }
        }

        $changes = [];
        foreach ($setting->getChanges() as $key => $value) {
            $changes[$key] = ['from' => $original[$key] ?? null, 'to' => $value];
        }
        if (!empty($userChanges)) {
            $changes['user_sms_permissions'] = $userChanges;
        }
        AuditService::log('sms_setting.update', $setting, $changes);

        return redirect()->back()->with('success', 'SMS Settings updated successfully.');
    }

    public function sendSingle(Request $request, FlexSmsService $smsService)
    {
        $data = $request->validate([
            'recipient' => ['required', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        if (!$request->user()->sms_enabled) {
            throw ValidationException::withMessages([
                'message' => 'SMS sending is disabled for your user account.',
            ]);
        }

        $recipient = $smsService->formattedRecipient($data['recipient']);

        if (!$smsService->isValidRecipient($recipient)) {
            throw ValidationException::withMessages([
                'recipient' => 'Enter a valid Tanzania phone number, for example 0712345678 or 255712345678.',
            ]);
        }

        $sent = $smsService->sendSms($recipient, $data['message']);

        AuditService::log('sms_single.send', null, [
            'recipient' => $recipient,
            'sent' => $sent,
        ]);

        if (!$sent) {
            throw ValidationException::withMessages([
                'message' => 'SMS could not be sent. Check the gateway settings and try again.',
            ]);
        }

        return redirect()->back()->with('success', 'SMS sent successfully to ' . $recipient . '.');
    }
}
