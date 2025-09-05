<?php

namespace App\Http\Controllers;

use App\Models\Amortissement;
use Illuminate\Http\Request;

class AmortissementController extends Controller
{
    public function index()
    {
        return Amortissement::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'nombremois' => 'required|integer',
            'valeur_min_apres_amortissement' => 'required|numeric',
            'type_valeur' => 'required|in:Quantite,Pourcentage',
        ]);

        return Amortissement::create($validated);
    }

    public function show($id)
    {
        return Amortissement::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $amortissement = Amortissement::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'required|string',
            'nombremois' => 'required|integer',
            'valeur_min_apres_amortissement' => 'required|numeric',
            'type_valeur' => 'required|in:Quantite,Pourcentage',
        ]);

        $amortissement->update($validated);
        return $amortissement;
    }

    public function destroy($id)
    {
        $amortissement = Amortissement::findOrFail($id);
        $amortissement->delete();
        return response()->json(['message' => 'Amortissement supprimé']);
    }
}
    