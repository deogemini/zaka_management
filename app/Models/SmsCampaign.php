<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsCampaign extends Model
{
    protected $fillable = [
        'user_id',
        'jumuiya_id',
        'title',
        'message',
        'target_type',
        'status',
        'total_recipients',
        'sent_count',
        'failed_count',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jumuiya()
    {
        return $this->belongsTo(Jumuiya::class);
    }

    public function recipients()
    {
        return $this->hasMany(SmsCampaignRecipient::class);
    }
}
