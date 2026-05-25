<?php

namespace App\Models;

use App\Mail\VerifyEmailOtp;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'phone',
        'location',
        'approved_at',
        'email_verification_otp',
        'email_verification_otp_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at' => 'datetime',
            'password' => 'hashed',
            'email_verification_otp_expires_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isFarmer(): bool
    {
        return $this->role === 'farmer';
    }

    public function isConsumer(): bool
    {
        return $this->role === 'consumer';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * @return HasOne<FarmerProfile, $this>
     */
    public function farmerProfile(): HasOne
    {
        return $this->hasOne(FarmerProfile::class);
    }

    /**
     * @return HasOne<ConsumerProfile, $this>
     */
    public function consumerProfile(): HasOne
    {
        return $this->hasOne(ConsumerProfile::class);
    }

    /**
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'farmer_id');
    }

    /**
     * @return HasMany<Bid, $this>
     */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class, 'consumer_id');
    }

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'consumer_id');
    }

    public function generateOtp(): string
    {
        $otp = (string) rand(100000, 999999);
        $this->forceFill([
            'email_verification_otp' => $otp,
            'email_verification_otp_expires_at' => now()->addMinutes(15),
        ])->save();

        return $otp;
    }

    public function sendEmailVerificationNotification(): void
    {
        $otp = $this->generateOtp();
        Mail::to($this->email)->send(new VerifyEmailOtp($otp, $this->name));
    }
}
