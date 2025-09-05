<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use Illuminate\Http\Request;

class ProjetController extends Controller
{
    /**
     * Afficher la liste des projets avec recherche.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $projets = Projet::when($search, function ($query, $search) {
            $query->where('nom', 'like', "%{$search}%")
                ->orWhere('refprojet', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('projets.index', compact('projets', 'search'));
    }

    /**
     * Formulaire de création d'un projet.
     */
    public function create()
    {
        return view('projets.create');
    }

    /**
     * Enregistrer un projet en base.
     */
    public function store(Request $request)
    {
        $request->validate([
            'refprojet' => 'required|unique:projets,refprojet',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'statut' => 'required|in:en_cours,termine,annule',
            'budget' => 'nullable|numeric|min:0',
        ]);

        Projet::create($request->all());

        return redirect()->route('projets.index')->with('success', 'Projet créé avec succès.');
    }

    /**
     * Formulaire d'édition d'un projet.
     */
    public function edit(Projet $projet)
    {
        return view('projets.edit', compact('projet'));
    }

    /**
     * Mettre à jour un projet.
     */
    public function update(Request $request, Projet $projet)
    {
        $request->validate([
            'refprojet' => 'required|unique:projets,refprojet,' . $projet->id,
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'statut' => 'required|in:en_cours,termine,annule',
            'budget' => 'nullable|numeric|min:0',
        ]);

        $projet->update($request->all());

        return redirect()->route('projets.index')->with('success', 'Projet mis à jour avec succès.');
    }

    /**
     * Supprimer un projet.
     */
    public function destroy(Projet $projet)
    {
        $projet->delete();
        return redirect()->route('projets.index')->with('success', 'Projet supprimé avec succès.');
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
}
