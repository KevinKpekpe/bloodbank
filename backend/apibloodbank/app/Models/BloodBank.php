<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'phone',
        'email',
        'website',
        'address',
        'city',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'is_verified',
        'is_active',
        'partnership_level',
        'admin_id',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relations
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function bloodStocks()
    {
        return $this->hasMany(BloodStock::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function requestFulfillments()
    {
        return $this->hasMany(RequestFulfillment::class);
    }

    // Méthodes de géolocalisation
    public function scopeNearby($query, $latitude, $longitude, $radius = 50)
    {
        // Formule de Haversine pour calculer la distance
        $haversine = "(6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude))))";

        return $query->select('*')
                    ->selectRaw("$haversine AS distance")
                    ->whereRaw("$haversine < ?", [$radius])
                    ->orderBy('distance');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_verified', true);
    }

    public function scopeByPartnershipLevel($query, $level)
    {
        return $query->where('partnership_level', $level);
    }

    // Méthodes utilitaires
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->postal_code} {$this->city}, {$this->country}";
    }

    public function getDistanceFrom($latitude, $longitude)
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        $earthRadius = 6371; // Rayon de la Terre en km

        $latDelta = deg2rad($latitude - $this->latitude);
        $lonDelta = deg2rad($longitude - $this->longitude);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($this->latitude)) * cos(deg2rad($latitude)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function getStockByType($bloodTypeId)
    {
        return $this->bloodStocks()->where('blood_type_id', $bloodTypeId)->first();
    }

    public function getTotalStock()
    {
        return $this->bloodStocks()->sum('quantity_ml');
    }

    public function hasLowStock()
    {
        return $this->bloodStocks()
                   ->whereRaw('quantity_ml <= minimum_threshold')
                   ->exists();
    }
}
