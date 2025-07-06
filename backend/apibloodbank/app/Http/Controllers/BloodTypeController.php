<?php

namespace App\Http\Controllers;

use App\Models\BloodType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BloodTypeController extends Controller
{
    /**
     * Récupérer tous les types de sang
     */
    public function index()
    {
        $bloodTypes = BloodType::getAllTypes();

        return response()->json([
            'blood_types' => $bloodTypes
        ]);
    }

    /**
     * Récupérer un type de sang spécifique
     */
    public function show($id)
    {
        $bloodType = BloodType::find($id);

        if (!$bloodType) {
            return response()->json([
                'message' => 'Type de sang non trouvé'
            ], 404);
        }

        return response()->json([
            'blood_type' => $bloodType
        ]);
    }

    /**
     * Récupérer les types compatibles pour un type donné
     */
    public function getCompatibleTypes($id)
    {
        $bloodType = BloodType::find($id);

        if (!$bloodType) {
            return response()->json([
                'message' => 'Type de sang non trouvé'
            ], 404);
        }

        $compatibleTypes = BloodType::whereIn('name', $bloodType->getCompatibleTypes())->get();

        return response()->json([
            'blood_type' => $bloodType,
            'compatible_types' => $compatibleTypes
        ]);
    }

    /**
     * Créer un nouveau type de sang (Admin seulement)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:10|unique:blood_types',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $bloodType = BloodType::create($request->all());

        return response()->json([
            'message' => 'Type de sang créé avec succès',
            'blood_type' => $bloodType
        ], 201);
    }

    /**
     * Mettre à jour un type de sang (Admin seulement)
     */
    public function update(Request $request, $id)
    {
        $bloodType = BloodType::find($id);

        if (!$bloodType) {
            return response()->json([
                'message' => 'Type de sang non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:10|unique:blood_types,name,' . $id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $bloodType->update($request->all());

        return response()->json([
            'message' => 'Type de sang mis à jour avec succès',
            'blood_type' => $bloodType
        ]);
    }

    /**
     * Supprimer un type de sang (Admin seulement)
     */
    public function destroy($id)
    {
        $bloodType = BloodType::find($id);

        if (!$bloodType) {
            return response()->json([
                'message' => 'Type de sang non trouvé'
            ], 404);
        }

        // Vérifier s'il y a des utilisateurs ou dons associés
        if ($bloodType->users()->exists() || $bloodType->donations()->exists()) {
            return response()->json([
                'message' => 'Impossible de supprimer ce type de sang car il est utilisé'
            ], 400);
        }

        $bloodType->delete();

        return response()->json([
            'message' => 'Type de sang supprimé avec succès'
        ]);
    }
}
