<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'phone',
        'image',
        'notes',
        'status',
        'plan_id',
        'balance',
        'address',
        'password',
        'country',
        'allow_api',
        'client_id',
        'will_expire',
        'masking_rate',
        'low_blnc_alrt',
        'client_secret',
        'non_masking_rate',
        'email_verified_at',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $count = User::where('role', 'user')->count() + 1;
            $client_id = str_pad($count, 6, '0', STR_PAD_LEFT);
            $model->client_id = $client_id;
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
