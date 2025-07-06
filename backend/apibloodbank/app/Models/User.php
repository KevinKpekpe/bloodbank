<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'city',
        'postal_code',
        'blood_type_id',
        'date_of_birth',
        'gender',
        'is_eligible_donor',
        'role_id',
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
            'date_of_birth' => 'date',
            'is_eligible_donor' => 'boolean',
        ];
    }

    // Relations
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'donor_id');
    }

    public function managedBloodBanks()
    {
        return $this->hasMany(BloodBank::class, 'admin_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Méthodes utilitaires
    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function isDonor()
    {
        return $this->hasRole('donor');
    }

    public function isBloodBank()
    {
        return $this->hasRole('blood_bank');
    }

    public function isDoctor()
    {
        return $this->hasRole('doctor');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }
}
