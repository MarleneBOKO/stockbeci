<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use Illuminate\Http\Request;

class ProjetController extends Controller
{
    // Afficher la liste des projets
    public function index()
    {
        $projets = Projet::orderBy('created_at', 'desc')->paginate(10); // paginate au lieu de get
        return view('projets.index', compact('projets'));
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
}




