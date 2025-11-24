<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use Illuminate\Http\Request;
use App\Models\Actif;
use App\Models\Consommable;
use App\Models\Composant;
use App\Models\Kit;

class ProjetController extends Controller
{


    public function index()
    {
        $projets = Projet::orderBy('created_at', 'desc')->paginate(10);
        $actifs = Actif::all();
        $consommables = Consommable::all();
        $composants = Composant::all();
        $kits = Kit::all();

        return view('projets.index', compact('projets', 'actifs', 'consommables', 'composants', 'kits'));
    }


    // Formulaire création
    public function create()
    {
        return view('projets.create');
    }

    // Enregistrer le projet
    public function store(Request $request)
    {
        $request->validate([
            'refprojet' => 'required|unique:projets,refprojet',
            'statut' => 'required|in:en_cours,termine,annule'
        ]);

        Projet::create($request->all());
        return redirect()->route('projets.index')->with('success', 'Projet créé avec succès.');
    }

    // Formulaire édition
    public function edit(Projet $projet)
    {
        return view('projets.edit', compact('projet'));
    }

    // Mettre à jour
    public function update(Request $request, Projet $projet)
    {
        $request->validate([
            'refprojet' => 'required|unique:projets,refprojet,' . $projet->id,
            'statut' => 'required|in:en_cours,termine,annule'
        ]);

        $projet->update($request->all());
        return redirect()->route('projets.index')->with('success', 'Projet mis à jour.');
    }

    // Supprimer
    public function destroy(Projet $projet)
    {
        $projet->delete();
        return redirect()->route('projets.index')->with('success', 'Projet supprimé.');
    }

    /**
     * Activer/Désactiver un projet.
     */
    public function toggleActif(Projet $projet)
    {
        $projet->actif = !$projet->actif;
        $projet->save();

        return redirect()->route('projets.index')->with('success', 'Statut du projet mis à jour.');
    }


    public function showAssignForm(Projet $projet)
    {
        // On ne récupère que les actifs disponibles (Statut 'Liste', 'En stock', etc.)
        // On peut utiliser la méthode du modèle ou une requête brute pour la performance
        $actifs = Actif::whereIn('statut', ['Liste', 'Prêt à être déployé', 'En stock', 'Restitué'])->get();
        
        $consommables = Consommable::where('quantite_stock', '>', 0)->get();
        $composants = Composant::where('quantite_stock', '>', 0)->get();
        $kits = Kit::all();

        return view('projets.assign', compact('projet', 'actifs', 'consommables', 'composants', 'kits'));
    }

    /**
     * Enregistrer les attributions des éléments au projet
     */
    public function assignItems(Request $request, Projet $projet)
    {
        // Valider les quantités (optionnel, mais conseillé)
        $request->validate([
            'actifs' => 'array',
            'actifs.*.quantite' => 'nullable|integer|min:1',
            'consommables' => 'array',
            'consommables.*.quantite' => 'nullable|integer|min:1',
            'composants' => 'array',
            'composants.*.quantite' => 'nullable|integer|min:1',
            'kits' => 'array',
            'kits.*.quantite' => 'nullable|integer|min:1',
        ]);

        // Sync Actifs
        $actifs = collect($request->input('actifs', []))
            ->filter(fn($item) => isset($item['quantite']) && $item['quantite'] > 0)
            ->mapWithKeys(fn($item, $key) => [$key => ['quantite' => $item['quantite']]])
            ->toArray();

        $projet->actifs()->sync($actifs);

        // Sync Consommables
        $consommables = collect($request->input('consommables', []))
            ->filter(fn($item) => isset($item['quantite']) && $item['quantite'] > 0)
            ->mapWithKeys(fn($item, $key) => [$key => ['quantite' => $item['quantite']]])
            ->toArray();

        $projet->consommables()->sync($consommables);

        // Sync Composants
        $composants = collect($request->input('composants', []))
            ->filter(fn($item) => isset($item['quantite']) && $item['quantite'] > 0)
            ->mapWithKeys(fn($item, $key) => [$key => ['quantite' => $item['quantite']]])
            ->toArray();

        $projet->composants()->sync($composants);

        // Sync Kits
        $kits = collect($request->input('kits', []))
            ->filter(fn($item) => isset($item['quantite']) && $item['quantite'] > 0)
            ->mapWithKeys(fn($item, $key) => [$key => ['quantite' => $item['quantite']]])
            ->toArray();

        $projet->kits()->sync($kits);

        return redirect()->route('projets.index')->with('success', 'Attribution des éléments mise à jour.');
    }

    public function assignActif(Request $request, Projet $projet)
    {
        $request->validate([
            'actif_id' => 'required|exists:actifs,id',
        ]);

        $actif = Actif::findOrFail($request->actif_id);

        // Vérification de la disponibilité
        if (!$actif->estDisponible()) {
            return back()->withErrors(['actif_id' => 'Cet actif n\'est pas disponible (Statut actuel : ' . $actif->statut . ').']);
        }

        // Affectation directe (1 actif = 1 projet)
        $actif->projet_id = $projet->id;
        $actif->statut = 'Déployé';
        $actif->save();

        // Optionnel : Ajouter une trace/historique ici si nécessaire
        // Trace::create([...]);

        return redirect()->route('projets.index')->with('success', 'Actif affecté au projet avec succès.');
    }

    public function assignItem(Request $request, Projet $projet)
    {
        // Validation des champs
        $request->validate([
            'type' => 'required|in:consommable,composant,kit', // Actif est géré séparément
            'item_id' => 'required|integer',
            'quantite' => 'required|integer|min:1',
        ]);

        $type = $request->input('type');
        $itemId = $request->input('item_id');
        $quantite = $request->input('quantite');

        $item = null;
        $relation = null;

        // Sélection du modèle et de la relation
        switch ($type) {
            case 'consommable':
                $item = Consommable::findOrFail($itemId);
                $relation = 'consommables';
                break;

            case 'composant':
                $item = Composant::findOrFail($itemId);
                $relation = 'composants';
                break;

            case 'kit':
                $item = Kit::findOrFail($itemId);
                $relation = 'kits';
                break;
            
            default:
                return back()->withErrors(['type' => 'Type d’élément invalide.']);
        }

        // Vérification du stock
        if ($type !== 'kit') {
            if ($item->quantite < $quantite) {
                return back()->withErrors(['quantite' => "Stock insuffisant pour " . ($item->nom ?? $item->libelle) . ". (Disponible : " . $item->quantite . ")"]);
            }

            // Déduction du stock
            $item->quantite -= $quantite;
            $item->save();
        } else {
            // Pour les kits, on ne décrémente pas de stock global (car pas de colonne quantite)
            // Idéalement, il faudrait décrémenter les composants du kit ici.
            // TODO: Implémenter la décomposition du kit et la sortie des composants.
        }

        // Enregistrement de l’attribution dans la table pivot
        // On utilise attach() ou syncWithoutDetaching() pour ajouter sans écraser
        
        $existingPivot = $projet->{$relation}()->where('id', $itemId)->first();

        if ($existingPivot) {
            // Si déjà présent, on augmente la quantité
            $newPivotQty = $existingPivot->pivot->quantite + $quantite;
            $projet->{$relation}()->updateExistingPivot($itemId, ['quantite' => $newPivotQty]);
        } else {
            // Sinon on attache
            $projet->{$relation}()->attach($itemId, ['quantite' => $quantite]);
        }

        return redirect()->route('projets.index')->with('success', 'Élément attribué avec succès ! Stock mis à jour.');
    }
}




