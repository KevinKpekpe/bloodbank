<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodType extends Model
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

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function bloodStocks()
    {
        return $this->hasMany(BloodStock::class);
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Méthodes utilitaires
    public static function getAllTypes()
    {
        return static::orderBy('name')->get();
    }

    public function getCompatibleTypes()
    {
        // Logique de compatibilité des groupes sanguins
        $compatibility = [
            'A+' => ['A+', 'AB+'],
            'A-' => ['A+', 'A-', 'AB+', 'AB-'],
            'B+' => ['B+', 'AB+'],
            'B-' => ['B+', 'B-', 'AB+', 'AB-'],
            'AB+' => ['AB+'],
            'AB-' => ['AB+', 'AB-'],
            'O+' => ['A+', 'B+', 'AB+', 'O+'],
            'O-' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
        ];

        return $compatibility[$this->name] ?? [];
    }
}
