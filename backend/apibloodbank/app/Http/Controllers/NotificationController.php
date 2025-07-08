<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Récupérer les notifications de l'utilisateur connecté
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // Pagination
        $perPage = $request->get('per_page', 20);
        $notifications = $query->paginate($perPage);

        return response()->json([
            'data' => $notifications->items(),
            'total' => $notifications->total(),
            'current_page' => $notifications->currentPage(),
            'per_page' => $notifications->perPage(),
            'last_page' => $notifications->lastPage()
        ]);
    }

    /**
     * Récupérer le nombre de notifications non lues
     */
    public function unreadCount()
    {
        $user = Auth::user();

        $count = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        $user = Auth::user();

        $notification = Notification::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Notification non trouvée'], 404);
        }

        $notification->update(['read_at' => now()]);

        return response()->json([
            'message' => 'Notification marquée comme lue',
            'notification' => $notification
        ]);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Toutes les notifications ont été marquées comme lues']);
    }

    /**
     * Supprimer une notification
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $notification = Notification::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Notification non trouvée'], 404);
        }

        $notification->delete();

        return response()->json(['message' => 'Notification supprimée']);
    }

    /**
     * Créer une notification (pour les admins/système)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:appointment,reminder,donation_completed,system',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'data' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'title' => $request->title,
            'message' => $request->message,
            'data' => $request->data ?? []
        ]);

        return response()->json([
            'message' => 'Notification créée',
            'notification' => $notification
        ], 201);
    }

    /**
     * Créer une notification de rappel de rendez-vous
     */
    public function createAppointmentReminder($userId, $appointmentData)
    {
        $notification = Notification::create([
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

        return $notification;
    }

    /**
     * Créer une notification de confirmation de don
     */
    public function createDonationCompletedNotification($userId, $donationData)
    {
        $notification = Notification::create([
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

        return $notification;
    }

    /**
     * Créer une notification de confirmation de rendez-vous
     */
    public function createAppointmentConfirmation($userId, $appointmentData)
    {
        $notification = Notification::create([
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

        return $notification;
    }

    /**
     * Créer une notification de rappel d'éligibilité
     */
    public function createEligibilityReminder($userId, $eligibilityData)
    {
        $notification = Notification::create([
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

        return $notification;
    }

    /**
     * Créer une notification système
     */
    public function createSystemNotification($userId, $title, $message, $data = [])
    {
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => 'system',
            'title' => $title,
            'message' => $message,
            'data' => $data
        ]);

        return $notification;
    }

    /**
     * Envoyer des notifications en masse (pour les admins)
     */
    public function sendBulkNotifications(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'type' => 'required|in:appointment,reminder,donation_completed,system',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'data' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $notifications = [];

        foreach ($request->user_ids as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => $request->type,
                'title' => $request->title,
                'message' => $request->message,
                'data' => $request->data ?? [],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        Notification::insert($notifications);

        return response()->json([
            'message' => count($request->user_ids) . ' notifications envoyées'
        ]);
    }

    /**
     * Obtenir les statistiques des notifications
     */
    public function statistics()
    {
        $user = Auth::user();

        $stats = [
            'total' => Notification::where('user_id', $user->id)->count(),
            'unread' => Notification::where('user_id', $user->id)->whereNull('read_at')->count(),
            'read' => Notification::where('user_id', $user->id)->whereNotNull('read_at')->count(),
            'today' => Notification::where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->count(),
            'by_type' => Notification::where('user_id', $user->id)
                ->select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray()
        ];

        return response()->json($stats);
    }

    /**
     * Marquer plusieurs notifications comme lues
     */
    public function markMultipleAsRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'integer|exists:notifications,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        Notification::where('user_id', $user->id)
            ->whereIn('id', $request->notification_ids)
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Notifications marquées comme lues']);
    }

    /**
     * Supprimer plusieurs notifications
     */
    public function deleteMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'integer|exists:notifications,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        Notification::where('user_id', $user->id)
            ->whereIn('id', $request->notification_ids)
            ->delete();

        return response()->json(['message' => 'Notifications supprimées']);
    }

    /**
     * Affiche la page de gestion des notifications (vue Blade)
     */
    public function showNotificationsPage()
    {
        $user = Auth::user();

        $notifications = \App\Models\Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = \App\Models\Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Marque une notification comme lue (version web)
     */
    public function markAsReadWeb($id)
    {
        $user = Auth::user();

        $notification = \App\Models\Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$notification) {
            return back()->with('error', 'Notification non trouvée.');
        }

        $notification->update(['read_at' => now()]);

        return back()->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Marque toutes les notifications comme lues (version web)
     */
    public function markAllAsReadWeb()
    {
        $user = Auth::user();

        \App\Models\Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Supprime une notification (version web)
     */
    public function destroyWeb($id)
    {
        $user = Auth::user();

        $notification = \App\Models\Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$notification) {
            return back()->with('error', 'Notification non trouvée.');
        }

        $notification->delete();

        return back()->with('success', 'Notification supprimée.');
    }
}
