<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::paginate(10); // Pagination pour gérer l'affichage
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'type' => 'required|in:Actif,Accessoire,Consommable,Composant',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        Categorie::create($validated);

        return redirect()->back()->with('success', 'Catégorie créé avec succès.');

    }

    public function show($id)
    {
        return Categorie::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $categorie = Categorie::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'required|string',
            'type' => 'required|in:Actif,Accessoire,Consommable,Composant',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $categorie->update($validated);
        return $categorie;
    }

    public function destroy($id)
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->delete();
        return response()->json(['message' => 'Catégorie supprimée']);
    }
}
