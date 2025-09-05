<?php

namespace App\Http\Controllers;

use App\Models\Composant;
use Illuminate\Http\Request;

class ComposantController extends Controller
{
    public function index()
    {
        return Composant::with(['categorie', 'emplacement', 'fournisseur', 'fabricant'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'categorie_id' => 'required|exists:categories,id',
            'quantite' => 'integer',
            'qte_min' => 'nullable|integer',
            'serial' => 'nullable|string',
            'fabricant_id' => 'nullable|exists:fabricants,id',
            'numero_model' => 'nullable|string',
            'emplacement_id' => 'required|exists:emplacements,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'num_commande' => 'nullable|string',
            'date_achat' => 'nullable|date',
            'cout_achat' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'images' => 'nullable|string',
        ]);

        return Composant::create($validated);
    }

    public function show($id)
    {
        return Composant::with(['categorie', 'emplacement', 'fournisseur', 'fabricant'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $composant = Composant::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'required|string',
            'categorie_id' => 'required|exists:categories,id',
            'quantite' => 'integer',
            'qte_min' => 'nullable|integer',
            'serial' => 'nullable|string',
            'fabricant_id' => 'nullable|exists:fabricants,id',
            'numero_model' => 'nullable|string',
            'emplacement_id' => 'required|exists:emplacements,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'num_commande' => 'nullable|string',
            'date_achat' => 'nullable|date',
            'cout_achat' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'images' => 'nullable|string',
        ]);

        $composant->update($validated);
        return $composant;
    }

    public function destroy($id)
    {
        $composant = Composant::findOrFail($id);
        $composant->delete();
        return response()->json(['message' => 'Composant supprimé']);
    }

    public function associerKit($idComposant, $idKit, $quantite = 1)
    {
        KitItem::create([
            'kit_id' => $idKit,
            'item_type' => 'Composant',
            'item_id' => $idComposant,
            'quantite' => $quantite
        ]);

        return response()->json(['message' => 'Composant ajouté au kit']);
    }

    public function suiviParProjet($idComposant)
    {
        $historique = ComposantProjet::where('composant_id', $idComposant)->get();
        return response()->json($historique);
    }

}
