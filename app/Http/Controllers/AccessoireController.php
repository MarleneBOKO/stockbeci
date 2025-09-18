<?php

namespace App\Http\Controllers;

use App\Models\Accessoire;
use Illuminate\Http\Request;
use App\Models\Categorie;
use App\Models\Emplacement;
use App\Models\Fournisseur;
use App\Models\Fabricant;

class AccessoireController extends Controller
{
    public function index()
    {
        $list = Accessoire::with(['categorie', 'emplacement', 'fournisseur', 'fabricant'])->paginate(10);

        // Pour les selects dans le modal
        $categories = Categorie::all();
        $emplacements = Emplacement::all();
        $fournisseurs = Fournisseur::all();
        $fabricants = Fabricant::all();

        return view('accessoires.index', compact('list', 'categories', 'emplacements', 'fournisseurs', 'fabricants'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'categorie_id' => 'nullable|exists:categories,id',
            'quantite' => 'integer',
            'qte_min' => 'nullable|integer',
            'fabricant_id' => 'nullable|exists:fabricants,id',
            'numero_model' => 'nullable|string',
            'emplacement_id' => 'nullable|exists:emplacements,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'num_commande' => 'nullable|string',
            'date_achat' => 'nullable|date',
            'cout_achat' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'images' => 'nullable|string',
        ]);

         Accessoire::create($validated);
        return redirect()->back()->with('success', 'Accessoire créé avec succès.');

    }

    public function show($id)
    {
        return Accessoire::with(['categorie', 'emplacement', 'fournisseur', 'fabricant'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $accessoire = Accessoire::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'required|string',
            'categorie_id' => 'nullable|exists:categories,id',
            'quantite' => 'integer',
            'qte_min' => 'nullable|integer',
            'fabricant_id' => 'nullable|exists:fabricants,id',
            'numero_model' => 'nullable|string',
            'emplacement_id' => 'nullable|exists:emplacements,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'num_commande' => 'nullable|string',
            'date_achat' => 'nullable|date',
            'cout_achat' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'images' => 'nullable|string',
        ]);

        $accessoire->update($validated);
        return $accessoire;
    }

    public function destroy($id)
    {
        $accessoire = Accessoire::findOrFail($id);
        $accessoire->delete();
        return response()->json(['message' => 'Accessoire supprimé']);
    }

    public function associerActif($idAccessoire, $idActif)
    {
        $accessoire = Accessoire::findOrFail($idAccessoire);
        $accessoire->actif_id = $idActif;
        $accessoire->save();

        return response()->json(['message' => 'Accessoire associé à l’actif']);
    }


    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $list = Accessoire::with(['categorie', 'emplacement', 'fournisseur', 'fabricant'])
            ->where('nom', 'like', "%{$query}%")
            ->paginate(10);

        // Retourner le HTML partiel de la table
        return view('accessoires.partials.table', compact('list'))->render();
    }

    public function historiqueUsage($idAccessoire)
    {
        $historique = AccessoireHistorique::where('accessoire_id', $idAccessoire)->get();
        return response()->json($historique);
    }

}
