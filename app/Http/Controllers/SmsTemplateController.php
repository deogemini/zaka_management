<?php

namespace App\Http\Controllers;

use App\Models\SmsTemplate;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SmsTemplateController extends Controller
{
    public function index()
    {
        $templates = SmsTemplate::orderBy('name')->get();

        return view('settings.sms-templates.index', compact('templates'));
    }

    public function create()
    {
        return view('settings.sms-templates.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $template = SmsTemplate::create($data);

        AuditService::log('sms_template.create', $template, $template->getAttributes());

        return redirect()->route('settings.sms-templates.index')->with('success', 'SMS template created successfully.');
    }

    public function edit(SmsTemplate $smsTemplate)
    {
        return view('settings.sms-templates.edit', compact('smsTemplate'));
    }

    public function update(Request $request, SmsTemplate $smsTemplate)
    {
        $original = $smsTemplate->getOriginal();
        $smsTemplate->update($this->validatedData($request, $smsTemplate));

        $changes = [];
        foreach ($smsTemplate->getChanges() as $key => $value) {
            $changes[$key] = ['from' => $original[$key] ?? null, 'to' => $value];
        }
        AuditService::log('sms_template.update', $smsTemplate, $changes);

        return redirect()->route('settings.sms-templates.index')->with('success', 'SMS template updated successfully.');
    }

    public function destroy(SmsTemplate $smsTemplate)
    {
        AuditService::log('sms_template.delete', $smsTemplate, ['deleted' => true]);
        $smsTemplate->delete();

        return redirect()->route('settings.sms-templates.index')->with('success', 'SMS template deleted successfully.');
    }

    private function validatedData(Request $request, ?SmsTemplate $smsTemplate = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('sms_templates', 'name')->ignore($smsTemplate)],
            'message' => ['required', 'string', 'max:160'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
