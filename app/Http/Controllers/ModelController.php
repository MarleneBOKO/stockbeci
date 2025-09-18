<?php

namespace App\Http\Controllers;

use App\Models\ModelMateriel;
use Illuminate\Http\Request;
use App\Models\Categorie;
use App\Models\Fabricant;

class ModelController extends Controller
{
    public function index(Request $request)
    {
        $query = ModelMateriel::with(['categorie', 'fabricant']);
        // On récupère toutes les catégories et fabricants pour les <select>
        $categories = Categorie::all();
        $fabricants = Fabricant::all();
        if ($request->has('q')) {
            $search = $request->q;
            $query->where('nom', 'like', "%{$search}%");
        }

        $models = $query->paginate(10); // Pagination avec 10 éléments par page

        return view('models.index', compact('models', 'categories', 'fabricants'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'categorie_id' => 'nullable|exists:categories,id',
            'fabricant_id' => 'nullable|exists:fabricants,id',
            'model_num' => 'nullable|string',
            'qte_min' => 'nullable|integer',
            'findevie' => 'nullable|integer',
            'notes' => 'nullable|string',
            'ensemble_champs' => 'nullable|string',
            'images' => 'nullable|string',
        ]);

        ModelMateriel::create($validated);
        return redirect()->back()->with('success', 'Model créé avec succès.');

    }

    public function show($id)
    {
        return ModelMateriel::with(['categorie', 'fabricant'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $model = ModelMateriel::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'required|string',
            'categorie_id' => 'nullable|exists:categories,id',
            'fabricant_id' => 'nullable|exists:fabricants,id',
            'model_num' => 'nullable|string',
            'qte_min' => 'nullable|integer',
            'findevie' => 'nullable|integer',
            'notes' => 'nullable|string',
            'ensemble_champs' => 'nullable|string',
            'images' => 'nullable|string',
        ]);

        $model->update($validated);
        return $model;
    }

    public function destroy($id)
    {
        $model = ModelMateriel::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Modèle supprimé']);
    }
}
