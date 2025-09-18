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
        $actifs = Actif::all();
        $consommables = Consommable::all();
        $composants = Composant::all();
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
            'quantite' => 'required|integer|min:1',
        ]);

        $actif = Actif::findOrFail($request->actif_id);

        if ($actif->quantite_stock < $request->quantite) {
            return back()->withErrors(['quantite' => 'Stock insuffisant pour cet actif.']);
        }

        // Attribuer l'actif au projet avec la quantité
        $projet->actifs()->attach($actif->id, ['quantite' => $request->quantite]);

        // Mettre à jour le stock
        $actif->quantite_stock -= $request->quantite;
        $actif->save();

        return redirect()->route('projets.index')->with('success', 'Actif attribué avec succès.');
    }




    public function assignItem(Request $request, Projet $projet)
    {
        // Validation des champs
        $request->validate([
            'type' => 'required|in:actif,consommable,composant,kit',
            'item_id' => 'required|integer',
            'quantite' => 'required|integer|min:1',
        ]);

        $type = $request->input('type');
        $itemId = $request->input('item_id');
        $quantite = $request->input('quantite');

        // Vérification du stock disponible
        switch ($type) {
            case 'actif':
                $item = Actif::findOrFail($itemId);
                break;

            case 'consommable':
                $item = Consommable::findOrFail($itemId);
                break;

            case 'composant':
                $item = Composant::findOrFail($itemId);
                break;

            case 'kit':
                $item = Kit::findOrFail($itemId);
                break;

            default:
                return back()->withErrors(['type' => 'Type d’élément invalide.']);
        }

        // Vérification du stock
        if ($item->quantite_stock < $quantite) {
            return back()->withErrors(['quantite' => 'Stock insuffisant pour cet élément.']);
        }

        // Déduction du stock
        $item->quantite_stock -= $quantite;
        $item->save();

        // Enregistrement de l’attribution
        // Tu dois adapter cette partie selon ton modèle/pivot
        // Exemple générique (si relation ManyToMany avec table pivot personnalisée)

        switch ($type) {
            case 'actif':
                $projet->actifs()->attach($itemId, ['quantite' => $quantite]);
                break;

            case 'consommable':
                $projet->consommables()->attach($itemId, ['quantite' => $quantite]);
                break;

            case 'composant':
                $projet->composants()->attach($itemId, ['quantite' => $quantite]);
                break;

            case 'kit':
                $projet->kits()->attach($itemId, ['quantite' => $quantite]);
                break;
        }

        return redirect()->route('projets.index')->with('success', 'Élément attribué avec succès !');
    }
}




