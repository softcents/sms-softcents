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
        'username',
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
        'parent_id',
        'company_name',
        'white_label_title',
        'white_label_logo',
        'white_label_favicon',
        'white_label_phone',
        'white_label_email',
        'white_label_footer',
        'custom_domain',
        'mail_settings',
    ];

    public function gateways()
    {
        return $this->hasMany(Gateway::class, 'user_id');
    }

    public function resellerRecharges()
    {
        return $this->hasMany(Recharge::class, 'reseller_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function ssps()
    {
        return $this->hasMany(User::class, 'parent_id')->where('role', 'ssp');
    }

    public function customers()
    {
        return $this->hasMany(User::class, 'parent_id')->where('role', 'user');
    }

    public function isSuperAdmin(): bool
    {
        return in_array($this->role, ['superadmin', 'admin']);
    }

    public function isSP(): bool
    {
        return $this->role === 'sp';
    }

    public function isSSP(): bool
    {
        return $this->role === 'ssp';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'user';
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->client_id)) {
                $maxId = (int) User::max('id') + 1;
                $candidate = str_pad($maxId, 6, '0', STR_PAD_LEFT);
                while (User::where('client_id', $candidate)->exists()) {
                    $maxId++;
                    $candidate = str_pad($maxId, 6, '0', STR_PAD_LEFT);
                }
                $model->client_id = $candidate;
            }

            if (empty($model->username)) {
                $base = $model->email ? explode('@', $model->email)[0] : ($model->phone ?: 'user');
                $base = preg_replace('/[^a-zA-Z0-9_]/', '', strtolower($base)) ?: 'user';
                $candidate = $base;
                $counter = 1;
                while (User::where('username', $candidate)->exists()) {
                    $candidate = $base . '_' . $counter++;
                }
                $model->username = $candidate;
            }
        });

        static::created(function ($model) {
            Senderid::firstOrCreate([
                'user_id'    => $model->id,
                'sender'     => '8809639228873',
            ], [
                'gateway_id' => 31,
                'status'     => true,
                'is_default' => true,
            ]);
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
        'mail_settings' => 'array',
    ];

    public function senderIps()
    {
        return $this->hasMany(SenderIp::class);
    }
}
