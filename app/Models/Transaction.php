<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_no',
        'user_id',
        'gateway_id',
        'amount',
        'rate',
        'charge',
        'reason',
        'type',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Transaction::max('id') + 1;
            $model->invoice_no = str_pad($model->id, 7, '0', STR_PAD_LEFT);
        });
    }

    public function gateway() : BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
