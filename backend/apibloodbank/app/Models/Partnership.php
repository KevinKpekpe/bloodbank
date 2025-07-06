<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partnership extends Model
{
    protected $fillable = [
        'requesting_bank_id',
        'responding_bank_id',
        'partnership_type',
        'description',
        'terms',
        'status',
        'response_notes',
        'responded_at',
        'terminated_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'terminated_at' => 'datetime',
    ];

    /**
     * La banque qui demande le partenariat
     */
    public function requestingBank(): BelongsTo
    {
        return $this->belongsTo(BloodBank::class, 'requesting_bank_id');
    }

    /**
     * La banque qui répond au partenariat
     */
    public function respondingBank(): BelongsTo
    {
        return $this->belongsTo(BloodBank::class, 'responding_bank_id');
    }

    /**
     * Vérifier si le partenariat est actif
     */
    public function isActive(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Vérifier si le partenariat est en attente
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Vérifier si le partenariat est terminé
     */
    public function isTerminated(): bool
    {
        return $this->status === 'terminated';
    }
}
