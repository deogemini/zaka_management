<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zaka extends Model
{
    protected $fillable = [
        'mwanajumuiya_id',
        'kiasi',
        'risiti_namba',
        'mode_ya_malipo',
        'hali_ya_malipo',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'date',
        'kiasi' => 'decimal:2',
    ];

    public function mwanajumuiya()
    {
        return $this->belongsTo(Mwanajumuiya::class);
    }
}
