<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // Relations
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Méthodes utilitaires
    public static function getDonorRole()
    {
        return static::where('name', 'donor')->first();
    }

    public static function getBloodBankRole()
    {
        return static::where('name', 'blood_bank')->first();
    }

    public static function getDoctorRole()
    {
        return static::where('name', 'doctor')->first();
    }

    public static function getAdminRole()
    {
        return static::where('name', 'admin')->first();
    }
}
