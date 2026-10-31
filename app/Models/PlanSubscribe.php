<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanSubscribe extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id',
        'gateway_id',
        'user_id',
        'invoice_no',
        'price',
        'status',
        'masking_rate',
        'non_masking_rate',
        'total_sms',
        'will_expire',
        'plan_data',
        'manual_data',
        'attachment',
    ];

    public function plan() : BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

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
            $id = PlanSubscribe::max('id') + 1;
            $invoice_no = "PLAN - " . str_pad($id, 4, '0', STR_PAD_LEFT);
            $model->invoice_no = $invoice_no;
        });
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'plan_data' => 'json',
        'manual_data' => 'json',
    ];
}
