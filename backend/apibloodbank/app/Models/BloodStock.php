<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_bank_id',
        'blood_type_id',
        'quantity_ml',
        'minimum_threshold',
        'maximum_capacity',
        'last_updated'
    ];

    protected $casts = [
        'quantity_ml' => 'integer',
        'minimum_threshold' => 'integer',
        'maximum_capacity' => 'integer',
        'last_updated' => 'datetime',
    ];

    // Relations
    public function bloodBank()
    {
        return $this->belongsTo(BloodBank::class);
    }

    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('quantity_ml', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity_ml <= minimum_threshold');
    }

    // Méthodes utilitaires
    public function isLowStock()
    {
        return $this->quantity_ml <= $this->minimum_threshold;
    }

    public function isAvailable()
    {
        return $this->quantity_ml > 0;
    }

    public function getStatusAttribute()
    {
        if ($this->quantity_ml === 0) {
            return 'empty';
        } elseif ($this->isLowStock()) {
            return 'low';
        } else {
            return 'available';
        }
    }
}
