<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\BloodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    /**
     * Récupérer tous les patients (avec filtres)
     */
    public function index(Request $request)
    {
        $query = Patient::with(['bloodType', 'bloodRequests']);

        // Filtres
        if ($request->has('blood_type_id')) {
            $query->where('blood_type_id', $request->blood_type_id);
        }

        if ($request->has('urgency_level')) {
            $query->where('urgency_level', $request->urgency_level);
        }

        if ($request->has('hospital_name')) {
            $query->where('hospital_name', 'like', '%' . $request->hospital_name . '%');
        }

        if ($request->has('doctor_name')) {
            $query->where('doctor_name', 'like', '%' . $request->doctor_name . '%');
        }

        $patients = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'patients' => $patients
        ]);
    }

    /**
     * Récupérer un patient spécifique
     */
    public function show($id)
    {
        $patient = Patient::with(['bloodType', 'bloodRequests'])->find($id);

        if (!$patient) {
            return response()->json([
                'message' => 'Patient non trouvé'
            ], 404);
        }

        return response()->json([
            'patient' => $patient
        ]);
    }

    /**
     * Créer un nouveau patient
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_type_id' => 'required|exists:blood_types,id',
            'doctor_name' => 'required|string|max:255',
            'hospital_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'urgency_level' => 'required|in:normal,urgent,critical',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $patient = Patient::create($request->all());

        return response()->json([
            'message' => 'Patient créé avec succès',
            'patient' => $patient->load('bloodType')
        ], 201);
    }

    /**
     * Mettre à jour un patient
     */
    public function update(Request $request, $id)
    {
        $patient = Patient::find($id);

        if (!$patient) {
            return response()->json([
                'message' => 'Patient non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'date_of_birth' => 'sometimes|required|date|before:today',
            'gender' => 'sometimes|required|in:male,female,other',
            'blood_type_id' => 'sometimes|required|exists:blood_types,id',
            'doctor_name' => 'sometimes|required|string|max:255',
            'hospital_name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email',
            'urgency_level' => 'sometimes|required|in:normal,urgent,critical',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $patient->update($request->all());

        return response()->json([
            'message' => 'Patient mis à jour avec succès',
            'patient' => $patient->load('bloodType')
        ]);
    }

    /**
     * Supprimer un patient
     */
    public function destroy($id)
    {
        $patient = Patient::find($id);

        if (!$patient) {
            return response()->json([
                'message' => 'Patient non trouvé'
            ], 404);
        }

        // Vérifier s'il y a des demandes de sang associées
        if ($patient->bloodRequests()->exists()) {
            return response()->json([
                'message' => 'Impossible de supprimer ce patient car il a des demandes de sang associées'
            ], 400);
        }

        $patient->delete();

        return response()->json([
            'message' => 'Patient supprimé avec succès'
        ]);
    }

    /**
     * Récupérer les statistiques des patients
     */
    public function statistics(Request $request)
    {
        $query = Patient::query();

        if ($request->has('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $statistics = [
            'total_patients' => $query->count(),
            'by_urgency_level' => $query->selectRaw('urgency_level, COUNT(*) as count')
                                      ->groupBy('urgency_level')
                                      ->get(),
            'by_blood_type' => $query->join('blood_types', 'patients.blood_type_id', '=', 'blood_types.id')
                                   ->selectRaw('blood_types.name, COUNT(*) as count')
                                   ->groupBy('blood_types.id', 'blood_types.name')
                                   ->get(),
            'by_hospital' => $query->selectRaw('hospital_name, COUNT(*) as count')
                                 ->groupBy('hospital_name')
                                 ->orderBy('count', 'desc')
                                 ->limit(10)
                                 ->get(),
        ];

        return response()->json([
            'statistics' => $statistics
        ]);
    }

    /**
     * Rechercher des patients
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = $request->get('query');

        $patients = Patient::with(['bloodType'])
                          ->where('name', 'like', '%' . $query . '%')
                          ->orWhere('doctor_name', 'like', '%' . $query . '%')
                          ->orWhere('hospital_name', 'like', '%' . $query . '%')
                          ->orWhere('email', 'like', '%' . $query . '%')
                          ->orWhere('phone', 'like', '%' . $query . '%')
                          ->orderBy('created_at', 'desc')
                          ->limit(20)
                          ->get();

        return response()->json([
            'patients' => $patients,
            'search_query' => $query
        ]);
    }
}
