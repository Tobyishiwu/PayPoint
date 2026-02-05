<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'is_admin',
        'transaction_pin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'transaction_pin',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'         => 'hashed',
        'transaction_pin'  => 'hashed',
        'is_admin'         => 'boolean',
    ];

    /* =====================
        Relationships
    ====================== */

    public function account()
    {
        return $this->hasOne(Account::class);
    }

    public function transactions()
    {
        return $this->hasManyThrough(
            Transaction::class,
            Account::class,
            'user_id',
            'account_id'
        )->latest();
    }

    /* =====================
        Helpers
    ====================== */

    public function getPayTagAttribute()
    {
        return $this->username ? '@' . strtolower($this->username) : null;
    }

    public function hasTransactionPin(): bool
    {
        return !empty($this->transaction_pin);
    }
}
