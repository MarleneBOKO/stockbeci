<?php

namespace App\Http\Controllers;

use App\Models\ModelMateriel;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function index(Request $request)
    {
        $query = ModelMateriel::with(['categorie', 'fabricant']);

        if ($request->has('q')) {
            $search = $request->q;
            $query->where('nom', 'like', "%{$search}%");
        }

        $models = $query->paginate(10); // Pagination avec 10 éléments par page

        return view('models.index', ['modeles' => $models]);
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

        return ModelMateriel::create($validated);
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
