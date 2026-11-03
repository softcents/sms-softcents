<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InboxMessage extends Model
{
    use HasFactory;

    protected $table = 'inbox_messages';

    protected $fillable = [
        'user_id',
        'gateway_id',
        'gateway_type',
        'sender',
        'receiver',
        'message',
        'message_id',
        'sms_status',
        'is_read',
        'raw_data',
        'received_at',
    ];

    protected $casts = [
        'is_read'     => 'boolean',
        'raw_data'    => 'array',
        'received_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Smsgateway::class, 'gateway_id');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
