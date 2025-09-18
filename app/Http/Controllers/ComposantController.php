<?php

namespace App\Http\Controllers;

use App\Models\Composant;
use App\Models\Categorie;
use App\Models\Emplacement;
use App\Models\Fournisseur;
use App\Models\Fabricant;
use Illuminate\Http\Request;
use App\Models\Projet;
class ComposantController extends Controller
{
    public function index()
    {
        $composants = Composant::with(['categorie', 'emplacement', 'fournisseur', 'fabricant'])->paginate(10);
        $categories = Categorie::all();
        $emplacements = Emplacement::all();
        $fournisseurs = Fournisseur::all();
        $fabricants = Fabricant::all();
        $projets = Projet::all();

        $list = Composant::with(['categorie', 'emplacement', 'fournisseur', 'fabricant'])->paginate(10);
        return view('composants.index', compact('list', 'categories', 'emplacements', 'fournisseurs', 'fabricants', 'projets'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
            'quantite' => 'required|integer|min:0',
            'qte_min' => 'nullable|integer|min:0',
            'emplacement_id' => 'required|exists:emplacements,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'fabricant_id' => 'nullable|exists:fabricants,id',
            'date_achat' => 'nullable|date',
            'cout_achat' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'images' => 'nullable|string|max:255',
        ]);

        Composant::create($validated);

        return redirect()->route('composants.index')
            ->with('success', 'Composant ajouté avec succès.');
    }


    public function update(Request $request, Composant $composant)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
            'quantite' => 'required|integer|min:0',
            'qte_min' => 'nullable|integer|min:0',
            'emplacement_id' => 'required|exists:emplacements,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'fabricant_id' => 'nullable|exists:fabricants,id',
            'date_achat' => 'nullable|date',
            'cout_achat' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'images' => 'nullable|string|max:255',
        ]);

        $composant->update($validated);

        return redirect()->route('composants.index')
            ->with('success', 'Composant mis à jour avec succès.');
    }


    public function destroy(Composant $composant)
    {
        $composant->delete();
        return redirect()->route('composants.index')->with('success', 'Composant supprimé');
    }



    public function entree(Request $request, Composant $composant)
    {
        $composant->quantite += $request->quantite;
        $composant->save();
        return redirect()->route('composants.index')->with('success', 'Stock mis à jour');
    }

    public function sortie(Request $request, Composant $composant)
    {
        $composant->quantite -= $request->quantite;
        $composant->save();
        return redirect()->route('composants.index')->with('success', 'Stock mis à jour');
    }


    public function affecterProjet(Request $request, $id)
    {
        $request->validate([
            'projet_id' => 'required|exists:projets,id'
        ]);

        $composant = Composant::findOrFail($id);
        $projetId = $request->projet_id;

        // Empêche l’attachement en double
        if (!$composant->projets()->where('projet_id', $projetId)->exists()) {
            $composant->projets()->attach($projetId);
        }

        return redirect()->back()->with('success', 'Composant attribué au projet avec succès.');
    }


    public function search(Request $request)
    {
        $q = $request->q;
        $list = Composant::with(['categorie', 'emplacement', 'fournisseur', 'fabricant'])
            ->where('nom', 'like', "%$q%")
            ->paginate(10);

        return view('composants._table', compact('list'));
    }
}
