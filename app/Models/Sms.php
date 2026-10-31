<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sms extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'smsgateway_id',
        'senderid_id',
        'group_id',
        'campaign_id',
        'number',
        'total_sms',
        'message_id',
        'charge',
        'range',
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

    public function smsgateway() : BelongsTo
    {
        return $this->belongsTo(Smsgateway::class, 'smsgateway_id');
    }
}
