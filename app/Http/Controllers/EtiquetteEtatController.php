<?php

namespace App\Http\Controllers;

use App\Models\EtiquetteEtat;
use Illuminate\Http\Request;

class EtiquetteEtatController extends Controller
{
    public function index()
    {
        return EtiquetteEtat::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'type' => 'required|in:Déployable,En instance,Indéployable,Archivés',
            'couleur' => 'required|string',
            'note' => 'nullable|string',
        ]);

        return EtiquetteEtat::create($validated);
    }

    public function show($id)
    {
        return EtiquetteEtat::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $etat = EtiquetteEtat::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'required|string',
            'type' => 'required|in:Déployable,En instance,Indéployable,Archivés',
            'couleur' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $etat->update($validated);
        return $etat;
    }

    public function destroy($id)
    {
        $etat = EtiquetteEtat::findOrFail($id);
        $etat->delete();
        return response()->json(['message' => 'Étiquette supprimée']);
    }
}
