<?php

namespace App\Http\Controllers;

use App\Models\Consommable;
use Illuminate\Http\Request;

class ConsommableController extends Controller
{
    public function index()
    {
        return Consommable::with(['categorie', 'emplacement', 'fournisseur', 'fabricant'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'categorie_id' => 'required|exists:categories,id',
            'numero_model' => 'nullable|string',
            'numero_article' => 'nullable|string',
            'qte_min' => 'nullable|integer',
            'emplacement_id' => 'required|exists:emplacements,id',
            'num_commande' => 'nullable|string',
            'date_achat' => 'nullable|date',
            'cout_achat' => 'nullable|numeric',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'fabricant_id' => 'nullable|exists:fabricants,id',
            'quantite' => 'integer',
            'notes' => 'nullable|string',
            'images' => 'nullable|string',
        ]);

        return Consommable::create($validated);
    }

    public function show($id)
    {
        return Consommable::with(['categorie', 'emplacement', 'fournisseur', 'fabricant'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $consommable = Consommable::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'required|string',
            'categorie_id' => 'required|exists:categories,id',
            'numero_model' => 'nullable|string',
            'numero_article' => 'nullable|string',
            'qte_min' => 'nullable|integer',
            'emplacement_id' => 'required|exists:emplacements,id',
            'num_commande' => 'nullable|string',
            'date_achat' => 'nullable|date',
            'cout_achat' => 'nullable|numeric',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'fabricant_id' => 'nullable|exists:fabricants,id',
            'quantite' => 'integer',
            'notes' => 'nullable|string',
            'images' => 'nullable|string',
        ]);

        $consommable->update($validated);
        return $consommable;
    }

    public function destroy($id)
    {
        $consommable = Consommable::findOrFail($id);
        $consommable->delete();
        return response()->json(['message' => 'Consommable supprimé']);
    }

    public function sortieProjet($idConsommable, $quantite, $idProjet)
    {
        $consommable = Consommable::findOrFail($idConsommable);

        if ($consommable->quantite < $quantite) {
            return response()->json(['message' => 'Quantité insuffisante'], 400);
        }

        $consommable->quantite -= $quantite;
        $consommable->save();

        // Historique sortie
        ConsommableProjet::create([
            'consommable_id' => $idConsommable,
            'projet_id' => $idProjet,
            'quantite' => $quantite
        ]);

        return response()->json(['message' => 'Sortie de consommable enregistrée']);
    }

    public function alerteSeuil($idConsommable)
    {
        $consommable = Consommable::findOrFail($idConsommable);
        if ($consommable->quantite <= $consommable->qte_min) {
            // Notification ici (email ou app)
            return response()->json(['alerte' => true, 'message' => 'Quantité en dessous du seuil']);
        }

        return response()->json(['alerte' => false]);
    }

}
