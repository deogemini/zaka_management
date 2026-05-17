<?php

namespace App\Http\Controllers;

use App\Models\SmsSetting;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AuditService;

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

        return view('settings.sms', compact('setting', 'users'));
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
}
