<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     */
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at'
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Les types de notifications disponibles.
     */
    const TYPES = [
        'appointment' => 'Rendez-vous',
        'reminder' => 'Rappel',
        'donation_completed' => 'Don complété',
        'system' => 'Système'
    ];

    /**
     * Relation avec l'utilisateur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour les notifications non lues.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope pour les notifications lues.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope pour filtrer par type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope pour les notifications récentes.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Vérifier si la notification est lue.
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Vérifier si la notification est non lue.
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * Marquer la notification comme lue.
     */
    public function markAsRead(): bool
    {
        return $this->update(['read_at' => now()]);
    }

    /**
     * Marquer la notification comme non lue.
     */
    public function markAsUnread(): bool
    {
        return $this->update(['read_at' => null]);
    }

    /**
     * Obtenir le label du type de notification.
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /**
     * Obtenir le temps écoulé depuis la création.
     */
    public function getTimeAgoAttribute(): string
    {
        $diff = now()->diff($this->created_at);

        if ($diff->y > 0) {
            return $diff->y . ' an' . ($diff->y > 1 ? 's' : '');
        }

        if ($diff->m > 0) {
            return $diff->m . ' mois';
        }

        if ($diff->d > 0) {
            return $diff->d . ' jour' . ($diff->d > 1 ? 's' : '');
        }

        if ($diff->h > 0) {
            return $diff->h . ' heure' . ($diff->h > 1 ? 's' : '');
        }

        if ($diff->i > 0) {
            return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
        }

        return 'À l\'instant';
    }

    /**
     * Obtenir une valeur depuis les données JSON.
     */
    public function getDataValue(string $key, $default = null)
    {
        return data_get($this->data, $key, $default);
    }

    /**
     * Définir une valeur dans les données JSON.
     */
    public function setDataValue(string $key, $value): bool
    {
        $data = $this->data ?? [];
        data_set($data, $key, $value);
        return $this->update(['data' => $data]);
    }

    /**
     * Boot du modèle.
     */
    protected static function boot()
    {
        parent::boot();

        // Événements du modèle
        static::created(function ($notification) {
            // Ici on pourrait ajouter des événements comme l'envoi d'emails/SMS
            // ou l'envoi de notifications push
        });

        static::updated(function ($notification) {
            // Événements lors de la mise à jour
        });

        static::deleted(function ($notification) {
            // Événements lors de la suppression
        });
    }

    /**
     * Obtenir les notifications pour un utilisateur avec pagination.
     */
    public static function forUser(int $userId, int $perPage = 20)
    {
        return static::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Obtenir le nombre de notifications non lues pour un utilisateur.
     */
    public static function unreadCountForUser(int $userId): int
    {
        return static::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Créer une notification de rappel de rendez-vous.
     */
    public static function createAppointmentReminder(int $userId, array $appointmentData): self
    {
        return static::create([
            'user_id' => $userId,
            'type' => 'reminder',
            'title' => 'Rappel de rendez-vous',
            'message' => "N'oubliez pas votre rendez-vous de don de sang le " .
                        date('d/m/Y à H:i', strtotime($appointmentData['scheduled_at'])) .
                        " à " . $appointmentData['blood_bank_name'],
            'data' => [
                'appointment_id' => $appointmentData['id'],
                'scheduled_at' => $appointmentData['scheduled_at'],
                'blood_bank_name' => $appointmentData['blood_bank_name']
            ]
        ]);
    }

    /**
     * Créer une notification de confirmation de don.
     */
    public static function createDonationCompleted(int $userId, array $donationData): self
    {
        return static::create([
            'user_id' => $userId,
            'type' => 'donation_completed',
            'title' => 'Don de sang complété',
            'message' => "Votre don de sang a été complété avec succès. Merci pour votre générosité ! " .
                        "Vous pouvez faire un nouveau don dans " . $donationData['next_donation_days'] . " jours.",
            'data' => [
                'donation_id' => $donationData['id'],
                'donation_date' => $donationData['donation_date'],
                'next_donation_date' => $donationData['next_donation_date'],
                'blood_type' => $donationData['blood_type']
            ]
        ]);
    }

    /**
     * Créer une notification de confirmation de rendez-vous.
     */
    public static function createAppointmentConfirmation(int $userId, array $appointmentData): self
    {
        return static::create([
            'user_id' => $userId,
            'type' => 'appointment',
            'title' => 'Rendez-vous confirmé',
            'message' => "Votre rendez-vous de don de sang a été confirmé pour le " .
                        date('d/m/Y à H:i', strtotime($appointmentData['scheduled_at'])) .
                        " à " . $appointmentData['blood_bank_name'] . ". " .
                        "N'oubliez pas d'apporter une pièce d'identité.",
            'data' => [
                'appointment_id' => $appointmentData['id'],
                'scheduled_at' => $appointmentData['scheduled_at'],
                'blood_bank_name' => $appointmentData['blood_bank_name'],
                'blood_bank_address' => $appointmentData['blood_bank_address']
            ]
        ]);
    }

    /**
     * Créer une notification de rappel d'éligibilité.
     */
    public static function createEligibilityReminder(int $userId, array $eligibilityData): self
    {
        return static::create([
            'user_id' => $userId,
            'type' => 'reminder',
            'title' => 'Vous pouvez faire un don !',
            'message' => "Vous êtes maintenant éligible pour faire un nouveau don de sang. " .
                        "Votre dernier don remonte à " . $eligibilityData['days_since_last_donation'] . " jours.",
            'data' => [
                'last_donation_date' => $eligibilityData['last_donation_date'],
                'days_since_last_donation' => $eligibilityData['days_since_last_donation'],
                'blood_type' => $eligibilityData['blood_type']
            ]
        ]);
    }

    /**
     * Créer une notification système.
     */
    public static function createSystem(int $userId, string $title, string $message, array $data = []): self
    {
        return static::create([
            'user_id' => $userId,
            'type' => 'system',
            'title' => $title,
            'message' => $message,
            'data' => $data
        ]);
    }
}
