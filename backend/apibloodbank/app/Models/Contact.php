<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'phone',
        'contact_type',
        'status',
        'response_message',
        'response_subject',
        'processed_at',
        'responded_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    /**
     * Vérifier si le message est en attente
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Vérifier si le message est en cours de traitement
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Vérifier si le message a été traité
     */
    public function isProcessed(): bool
    {
        return $this->status === 'processed';
    }

    /**
     * Vérifier si le message a reçu une réponse
     */
    public function isResponded(): bool
    {
        return $this->status === 'responded';
    }
}
