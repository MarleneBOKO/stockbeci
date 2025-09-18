<?php

namespace App\Http\Controllers;

use App\Models\Consommable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Projet;
use App\Models\Categorie;
use App\Models\Emplacement;
use App\Models\Fournisseur;
use App\Models\Fabricant;
class ConsommableController extends Controller
{
    // Liste des consommables
    public function index(Request $request)
    {
        $projets = Projet::all();
        $categories = Categorie::all();
        $emplacements = Emplacement::all();
        $fournisseurs = Fournisseur::all();
        $fabricants = Fabricant::all();
        $list = Consommable::with('item')
            ->orderBy('nom')
            ->paginate(10);

        if ($request->ajax()) {
            return view('consommables._table', compact('list'))->render();
        }

        return view('consommables.index', compact('list', 'projets', 'categories', 'emplacements', 'fournisseurs', 'fabricants'));
    }

    // Recherche AJAX
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $list = Consommable::with('item')
            ->where('nom', 'like', "%$q%")
            ->orWhereHas('item', function ($query) use ($q) {
                $query->where('nom', 'like', "%$q%");
            })
            ->orderBy('nom')
            ->paginate(10);

        return view('consommables._table', compact('list'))->render();
    }

    // Ajouter un consommable
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories,id',
            'quantite' => 'required|integer|min:0',
            'qte_min' => 'nullable|integer|min:0',
            'numero_model' => 'nullable|string|max:255',
            'numero_article' => 'nullable|string|max:255',
            'emplacement_id' => 'required|exists:emplacements,id',
            'num_commande' => 'nullable|string|max:255',
            'date_achat' => 'nullable|date',
            'cout_achat' => 'nullable|numeric|min:0',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'fabricant_id' => 'nullable|exists:fabricants,id',
            'notes' => 'nullable|string',
            'images' => 'nullable|string|max:255',
        ]);

        Consommable::create($validated);

        flash("Consommable ajouté avec succès.")->success();
        return back();
    }


    // Modifier un consommable
    public function update(Request $request, $id)
    {
        $consommable = Consommable::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'quantite' => 'required|integer|min:0',
            'qte_min' => 'nullable|integer|min:0',
            'item_type' => 'nullable|string',
            'item_id' => 'nullable|integer',
        ]);

        $consommable->update($request->all());

        flash("Consommable mis à jour avec succès.")->success();
        return back();
    }

    // Supprimer
    public function destroy($id)
    {
        Consommable::findOrFail($id)->delete();

        flash("Consommable supprimé.")->success();
        return back();
    }

    public function affecterProjet(Request $request, $id)
    {
        $request->validate([
            'projet_id' => 'required|exists:projets,id'
        ]);

        $consommable = Consommable::findOrFail($id);
        $projetId = $request->projet_id;

        if (!$consommable->projets()->where('projet_id', $projetId)->exists()) {
            $consommable->projets()->attach($projetId);
        }

        return redirect()->back()->with('success', 'Consommable attribué au projet avec succès.');
    }



    // Entrée de stock
    public function entreeStock(Request $request, $id)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1',
        ]);

        $consommable = Consommable::findOrFail($id);
        $consommable->increment('quantite', $request->quantite);

        flash("Stock augmenté de {$request->quantite} unités.")->success();
        return back();
    }

    // Sortie de stock (par projet)
    public function sortieStock(Request $request, $id)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1',
            'projet' => 'nullable|string|max:255'
        ]);

        $consommable = Consommable::findOrFail($id);

        if ($consommable->quantite < $request->quantite) {
            flash("Stock insuffisant pour cette sortie.")->error();
            return back();
        }

        $consommable->decrement('quantite', $request->quantite);

        // Vérification du seuil
        if ($consommable->qte_min && $consommable->quantite <= $consommable->qte_min) {
            flash("⚠ Le stock du consommable '{$consommable->nom}' est sous le seuil minimal !")->warning();
        } else {
            flash("Stock réduit de {$request->quantite} unités.")->success();
        }

        return back();
    }
}
