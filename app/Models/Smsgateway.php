<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Smsgateway extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type_id',
        'priority',
        'status',
        'params',
        'driver',
        'namespace',
        'non_masking_rate',
        'masking_rate',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'params' => 'json',
        'status_params' => 'json',
    ];

    public function type()
    {
        return $this->belongsTo(GatewayType::class, 'type_id');
    }

    public function senderids()
    {
        return $this->hasMany(Senderid::class, 'gateway_id');
    }
}
