<?php

namespace App\Http\Controllers;

use App\Models\Kit;
use App\Models\KitItem;
use Illuminate\Http\Request;
use App\Models\Projet;

class KitController extends Controller
{
    public function index()
    {
        // Récupérer les kits qui ne sont liés à aucun projet
        $kits = Kit::whereDoesntHave('projets')->with('items')->paginate(10);
        $projets = Projet::all();
        return view('kits.index', compact('kits', 'projets'));
    }


    public function create()
    {
        // Ici tu pourras passer les données nécessaires (actifs, composants, etc.)
        return view('kits.create');
    }

   /* public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_type' => 'required|string',
            'items.*.item_id' => 'required|integer',
            'items.*.quantite' => 'required|integer|min:1',
        ]);
        // Créer le kit sans les items
        $kit = Kit::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'images' => $validated['images'] ?? null,
        ]);

        // Ajouter les items au kit
        foreach ($validated['items'] as $item) {
            $kit->items()->create($item);
        }

        return redirect()->back()->with('success', 'Kit créé avec succès.');
    }*/





        public function store(Request $request)
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'description' => 'nullable|string',
        'images' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.item_type' => 'required|string',
        'items.*.item_id' => 'required|integer',
        'items.*.quantite' => 'required|integer|min:1',
    ]);

    // Vérifier la disponibilité des éléments
    foreach ($validated['items'] as $item) {
        $itemType = $item['item_type'];
        $itemId = $item['item_id'];
        $quantiteDemandee = $item['quantite'];

        switch ($itemType) {
            case 'Actif':
                // Actif unique, on vérifie qu'il existe et n'est pas déjà affecté (optionnel)
                $actif = \App\Models\Actif::find($itemId);
                if (!$actif) {
                    return back()->withErrors("L'actif ID $itemId n'existe pas.");
                }
                // Optionnel : vérifier qu'il n'est pas déjà affecté à un kit ou projet
                break;

            case 'Composant':
                $composant = \App\Models\Composant::find($itemId);
                if (!$composant) {
                    return back()->withErrors("Le composant ID $itemId n'existe pas.");
                }
                if ($composant->quantite < $quantiteDemandee) {
                    return back()->withErrors("Stock insuffisant pour le composant ID $itemId. Disponible : {$composant->quantite}, demandé : $quantiteDemandee.");
                }
                break;

            case 'Consommable':
                $consommable = \App\Models\Consommable::find($itemId);
                if (!$consommable) {
                    return back()->withErrors("Le consommable ID $itemId n'existe pas.");
                }
                if ($consommable->quantite < $quantiteDemandee) {
                    return back()->withErrors("Stock insuffisant pour le consommable ID $itemId. Disponible : {$consommable->quantite}, demandé : $quantiteDemandee.");
                }
                break;

            // Ajouter d'autres cas si besoin

            default:
                return back()->withErrors("Type d'élément inconnu : $itemType");
        }
    }

    // Si tout est ok, créer le kit
    $kit = Kit::create([
        'nom' => $validated['nom'],
        'description' => $validated['description'],
        'images' => $validated['images'] ?? null,
    ]);

    // Ajouter les items au kit
    foreach ($validated['items'] as $item) {
        $kit->items()->create($item);
    }

    // Optionnel : diminuer les stocks des composants et consommables
    foreach ($validated['items'] as $item) {
        $itemType = $item['item_type'];
        $itemId = $item['item_id'];
        $quantiteDemandee = $item['quantite'];

        switch ($itemType) {
            case 'Composant':
                $composant = \App\Models\Composant::find($itemId);
                $composant->decrement('quantite', $quantiteDemandee);
                break;

            case 'Consommable':
                $consommable = \App\Models\Consommable::find($itemId);
                $consommable->decrement('quantite', $quantiteDemandee);
                break;

            // Pour Actif, pas de stock à diminuer (élément unique)
        }
    }

    return redirect()->back()->with('success', 'Kit créé avec succès.');
}


    public function edit(Kit $kit)
    {
        $kit->load('items');
        return view('kits.edit', compact('kit'));
    }

    public function update(Request $request, Kit $kit)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_type' => 'required|string',
            'items.*.item_id' => 'required|integer',
            'items.*.quantite' => 'required|integer|min:1',
        ]);

        // Mettre à jour les informations du kit
        $kit->update([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'images' => $validated['images'] ?? null,
        ]);

        // Supprimer les anciens items et recréer
        $kit->items()->delete();
        foreach ($validated['items'] as $item) {
            $kit->items()->create($item);
        }

        return redirect()->route('kits.index')->with('success', 'Kit mis à jour avec succès.');
    }

    public function destroy(Kit $kit)
    {
        // Supprimer d'abord les items associés
        $kit->items()->delete();
        // Puis supprimer le kit
        $kit->delete();

        return redirect()->route('kits.index')->with('success', 'Kit supprimé avec succès.');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            // Si pas de recherche, retourner tous les kits
            $kits = Kit::with('items')->paginate(10);
        } else {
            // Recherche dans le nom et la description
            $kits = Kit::with('items')
                ->where('nom', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->paginate(10);
        }

        // Générer directement le HTML de la table
        $html = '<div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-small-font table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Éléments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';

        if ($kits->count() > 0) {
            foreach ($kits as $kit) {
                $html .= '<tr>';
                $html .= '<td>' . e($kit->nom) . '</td>';
                $html .= '<td>' . ($kit->description ? e($kit->description) : '-') . '</td>';
                $html .= '<td>';

                if ($kit->items->count() > 0) {
                    $html .= '<div class="kit-elements">';
                    foreach ($kit->items as $item) {
                        $html .= '<div class="element-item" style="margin-bottom: 3px; font-size: 12px;">';
                        $html .= '<span class="badge" style="background-color: #5bc0de; color: white; display: inline-block; min-width: 80px; text-align: center; margin-right: 5px; padding: 2px 6px; font-size: 11px; font-weight: bold; border-radius: 3px;">' . e($item->item_type) . '</span>';
                        $html .= '<strong>(ID: ' . e($item->item_id) . ')</strong> x ' . e($item->quantite);
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                } else {
                    $html .= '<em>Aucun élément</em>';
                }

                $html .= '</td>';
                $html .= '<td>';

                if (in_array("update_kit", session("auto_action", []))) {
                    $html .= '<button class="btn btn-primary btn-circle btn-xs" title="Modifier">';
                    $html .= '<a href="' . route('kits.edit', $kit) . '" style="color:white;"><i class="ico fa fa-edit"></i></a>';
                    $html .= '</button> ';
                }

                if (in_array("delete_kit", session("auto_action", []))) {
                    $html .= '<button class="btn btn-danger btn-circle btn-xs" title="Supprimer">';
                    $html .= '<a href="' . route('kits.destroy', $kit) . '" style="color:white" onclick="event.preventDefault(); if(confirm(\'Êtes-vous sûr de vouloir supprimer ce kit ?\')) { document.getElementById(\'delete-' . $kit->id . '\').submit(); }"><i class="ico fa fa-trash"></i></a>';
                    $html .= '</button>';
                    $html .= '<form id="delete-' . $kit->id . '" action="' . route('kits.destroy', $kit) . '" method="POST" style="display:none;">';
                    $html .= csrf_field() . method_field('DELETE');
                    $html .= '</form>';
                }

                $html .= '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="4"><center>Pas de kit enregistré !!!</center></td></tr>';
        }

        $html .= '</tbody></table>';
        $html .= $kits->links()->render();
        $html .= '</div>';

        return $html;
    }

    public function affecterProjet(Request $request, $kitId)
    {
        $request->validate([
            'projet_id' => 'required|exists:projets,id',
        ]);

        $kit = Kit::findOrFail($kitId);
        $projetId = $request->input('projet_id');

        // Attacher le kit au projet (many-to-many)
        $kit->projets()->attach($projetId);

        return redirect()->back()->with('success', 'Kit attribué au projet avec succès.');
    }



}
