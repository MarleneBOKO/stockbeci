<?php

namespace App\Http\Controllers;

use App\Models\Consommable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsommableController extends Controller
{
    // Liste des consommables
    public function index(Request $request)
    {
        $list = Consommable::with('item')
            ->orderBy('nom')
            ->paginate(10);

        if ($request->ajax()) {
            return view('consommables._table', compact('list'))->render();
        }

        return view('consommables.index', compact('list'));
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
        $request->validate([
            'nom' => 'required|string|max:255',
            'quantite' => 'required|integer|min:0',
            'qte_min' => 'nullable|integer|min:0',
            'item_type' => 'nullable|string',
            'item_id' => 'nullable|integer',
        ]);

        Consommable::create($request->all());

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
