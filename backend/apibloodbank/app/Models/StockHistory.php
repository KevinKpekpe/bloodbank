<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'old_quantity',
        'new_quantity',
        'reason',
        'updated_by'
    ];

    protected $casts = [
        'old_quantity' => 'integer',
        'new_quantity' => 'integer',
    ];

    /**
     * Relation avec le stock
     */
    public function stock()
    {
        return $this->belongsTo(BloodStock::class, 'stock_id');
    }

    /**
     * Relation avec l'utilisateur qui a fait la modification
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Calculer la différence de quantité
     */
    public function getQuantityDifferenceAttribute()
    {
        return $this->new_quantity - $this->old_quantity;
    }

    /**
     * Vérifier si c'est un ajout ou un retrait
     */
    public function getMovementTypeAttribute()
    {
        $difference = $this->quantity_difference;
        if ($difference > 0) {
            return 'in';
        } elseif ($difference < 0) {
            return 'out';
        }
        return 'adjustment';
    }
}