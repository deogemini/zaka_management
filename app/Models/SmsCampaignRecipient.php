<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsCampaignRecipient extends Model
{
    protected $fillable = [
        'sms_campaign_id',
        'mwanajumuiya_id',
        'name',
        'phone',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(SmsCampaign::class, 'sms_campaign_id');
    }

    public function mwanajumuiya()
    {
        return $this->belongsTo(Mwanajumuiya::class);
    }
}
