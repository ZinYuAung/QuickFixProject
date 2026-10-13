<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'users';

    protected $fillable = [
        'name',
        'profile_image',
        'email',
        'password',
        'phone',
        'role',
        'is_active',
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
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    //role check
     public function isSuperAdmin(){
        return $this->role==='superadmin';
    }
    public function isAdmin(){
        return $this->role==='admin';
    }
    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function isProvider()
    {
        return $this->role === 'provider';
    }

    //scope query
    public function scopeCustomer($query)
    {
        return $query->where('role', 'customer');
    }

    public function scopeProvider($query)
    {
        return $query->where('role', 'provider');
    }

    public function scopeAdmin($query)
    {
        return $query->whereIn('role', ['admin', 'superadmin']);
    }

    // Profile Relationships
    public function adminProfile() {
        return $this->hasOne(Admin::class, 'user_id');
    }

    public function customerProfile() {
        return $this->hasOne(Customer::class, 'user_id');
    }

    public function providerProfile() {
        return $this->hasOne(Provider::class, 'user_id');
    }

    //booking from customer
    public function bookingsAsCustomer()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    //booking for Provider
    public function bookingsAsProvider()
    {
        return $this->hasMany(Booking::class, 'provider_id');
    }

    // Provider can do services
    public function services()
    {
        return $this->hasMany(Service::class, 'provider_id');
    }
    public function customer()
{
    return $this->hasOne(Customer::class, 'user_id');
}
}
