<?php

namespace App\Http\Controllers;

use App\Models\AlerteSeuil;
use Illuminate\Http\Request;

class AlerteSeuilController extends Controller
{
    public function index()
    {
        return AlerteSeuil::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_type' => 'required|string',
            'item_id' => 'required|integer',
            'seuil_min' => 'required|integer|min:0',
            'alerte_envoyee' => 'boolean',
        ]);

        return AlerteSeuil::create($validated);
    }

    public function show($id)
    {
        return AlerteSeuil::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $alerte = AlerteSeuil::findOrFail($id);
        $validated = $request->validate([
            'item_type' => 'required|string',
            'item_id' => 'required|integer',
            'seuil_min' => 'required|integer|min:0',
            'alerte_envoyee' => 'boolean',
        ]);

        $alerte->update($validated);
        return $alerte;
    }

    public function destroy($id)
    {
        $alerte = AlerteSeuil::findOrFail($id);
        $alerte->delete();
        return response()->json(['message' => 'Alerte supprimée']);
    }

    public function verifierSeuil()
    {
        $items = [Actif::all(), Composant::all(), Accessoire::all(), Consommable::all()];

        $alertes = [];
        foreach ($items as $collection) {
            foreach ($collection as $item) {
                if ($item->quantite <= $item->qte_min) {
                    $alertes[] = $item;
                    // Envoyer notification ici si nécessaire
                }
            }
        }

        return response()->json($alertes);
    }

    public function alerteEnvoyee($idAlerte)
    {
        $alerte = AlerteSeuil::findOrFail($idAlerte);
        $alerte->alerte_envoyee = true;
        $alerte->save();

        return response()->json(['message' => 'Alerte marquée comme envoyée']);
    }

}
