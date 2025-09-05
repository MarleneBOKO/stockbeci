<?php

namespace App\Http\Controllers;

use App\Models\KitItem;
use Illuminate\Http\Request;

class KitItemController extends Controller
{
    public function index()
    {
        return KitItem::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kit_id' => 'required|exists:kits,id',
            'item_type' => 'required|string', // Actif, Composant, Accessoire, Consommable
            'item_id' => 'required|integer',
            'quantite' => 'required|integer|min:1',
        ]);

        return KitItem::create($validated);
    }

    public function show($id)
    {
        return KitItem::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $kitItem = KitItem::findOrFail($id);
        $validated = $request->validate([
            'kit_id' => 'required|exists:kits,id',
            'item_type' => 'required|string',
            'item_id' => 'required|integer',
            'quantite' => 'required|integer|min:1',
        ]);

        $kitItem->update($validated);
        return $kitItem;
    }

    public function destroy($id)
    {
        $kitItem = KitItem::findOrFail($id);
        $kitItem->delete();
        return response()->json(['message' => 'Élément du kit supprimé']);
    }
}
