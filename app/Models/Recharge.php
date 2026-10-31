<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recharge extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'notes',
        'amount',
        'status',
        'user_id',
        'invoice_no',
        'attachment',
        'gateway_id',
        'manual_data',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gateway() : BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $id = Recharge::max('id') + 1;
            $invoice_no = "RECH - " . str_pad($id, 4, '0', STR_PAD_LEFT);
            $model->invoice_no = $invoice_no;
        });
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'manual_data' => 'json',
    ];
}
