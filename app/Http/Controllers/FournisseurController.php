<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index()
    {
        return Fournisseur::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string',
            'etat' => 'nullable|string',
            'pays' => 'nullable|string',
            'fermeture_eclair' => 'nullable|string',
            'nom_personne_ressource' => 'nullable|string',
            'telephone' => 'nullable|string',
            'fax' => 'nullable|string',
            'messagerie_electronique' => 'nullable|string',
            'url' => 'nullable|string',
            'note' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        return Fournisseur::create($validated);
    }

    public function show($id)
    {
        return Fournisseur::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'required|string',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string',
            'etat' => 'nullable|string',
            'pays' => 'nullable|string',
            'fermeture_eclair' => 'nullable|string',
            'nom_personne_ressource' => 'nullable|string',
            'telephone' => 'nullable|string',
            'fax' => 'nullable|string',
            'messagerie_electronique' => 'nullable|string',
            'url' => 'nullable|string',
            'note' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        $fournisseur->update($validated);
        return $fournisseur;
    }

    public function destroy($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->delete();
        return response()->json(['message' => 'Fournisseur supprimé']);
    }
}
