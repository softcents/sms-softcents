<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CampaignSms extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'senderid_id',
        'campaign_id',
        'numbers',
        'charges',
        'total_sms',
        'ip_address',
        'is_unicode',
        'contents',
        'schedule',
        'notes',
        'status',
    ];

    public function senderid() : BelongsTo
    {
        return $this->belongsTo(Senderid::class);
    }

    public function campaign() : BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
