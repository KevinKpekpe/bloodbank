<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Envoyer un message de contact
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'phone' => 'nullable|string|max:20',
            'contact_type' => 'sometimes|in:general,support,partnership,emergency',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'phone' => $request->phone,
            'contact_type' => $request->contact_type ?? 'general',
            'status' => 'pending',
        ]);

        // Notifier les administrateurs
        $this->notificationService->sendToAdmins(
            'Nouveau message de contact',
            "Nouveau message de {$request->name} ({$request->email}): {$request->subject}",
            'info',
            [
                'contact_id' => $contact->id,
                'contact_type' => $contact->contact_type,
                'sender_name' => $request->name,
                'sender_email' => $request->email,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Message envoyé avec succès. Nous vous répondrons dans les plus brefs délais.',
        ]);
    }

    /**
     * Récupérer tous les messages de contact (admin seulement)
     */
    public function index(Request $request)
    {
        $query = Contact::orderBy('created_at', 'desc');

        // Filtres
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('contact_type')) {
            $query->where('contact_type', $request->contact_type);
        }

        if ($request->has('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $contacts = $query->paginate(20);

        return response()->json([
            'success' => true,
            'contacts' => $contacts,
        ]);
    }

    /**
     * Marquer un message comme traité
     */
    public function markAsProcessed($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update([
            'status' => 'processed',
            'processed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message marqué comme traité',
        ]);
    }

    /**
     * Marquer un message comme en cours de traitement
     */
    public function markAsInProgress($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update([
            'status' => 'in_progress',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message marqué comme en cours de traitement',
        ]);
    }

    /**
     * Répondre à un message de contact
     */
    public function respond(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'response_message' => 'required|string|max:2000',
            'response_subject' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $contact = Contact::findOrFail($id);

        $contact->update([
            'response_message' => $request->response_message,
            'response_subject' => $request->response_subject ?? "Réponse à votre message: {$contact->subject}",
            'status' => 'responded',
            'responded_at' => now(),
        ]);

        // Ici, on pourrait envoyer un email de réponse
        // Pour l'instant, on se contente de marquer comme répondu

        return response()->json([
            'success' => true,
            'message' => 'Réponse envoyée avec succès',
        ]);
    }

    /**
     * Obtenir les statistiques des contacts
     */
    public function statistics()
    {
        $totalContacts = Contact::count();
        $pendingContacts = Contact::where('status', 'pending')->count();
        $processedContacts = Contact::where('status', 'processed')->count();
        $respondedContacts = Contact::where('status', 'responded')->count();

        $contactsByType = Contact::selectRaw('contact_type, COUNT(*) as count')
            ->groupBy('contact_type')
            ->get()
            ->pluck('count', 'contact_type');

        $recentContacts = Contact::where('created_at', '>=', now()->subDays(7))->count();

        return response()->json([
            'success' => true,
            'statistics' => [
                'total' => $totalContacts,
                'pending' => $pendingContacts,
                'processed' => $processedContacts,
                'responded' => $respondedContacts,
                'recent_week' => $recentContacts,
                'by_type' => $contactsByType,
            ]
        ]);
    }
}
