<?php

namespace App\Http\Controllers;

use App\Models\Kit;
use App\Models\KitItem;
use Illuminate\Http\Request;

class KitController extends Controller
{
    public function index()
    {
        $kits = Kit::with('items')->paginate(10);
        return view('kits.index', compact('kits'));
    }


    public function create()
    {
        // Ici tu pourras passer les données nécessaires (actifs, composants, etc.)
        return view('kits.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images' => 'nullable|string',
            'items' => 'required|array',
            'items.*.item_type' => 'required|string',
            'items.*.item_id' => 'required|integer',
            'items.*.quantite' => 'required|integer|min:1',
        ]);

        $kit = Kit::create($validated);

        foreach ($validated['items'] as $item) {
            $kit->items()->create($item);
        }

        return redirect()->route('kits.index')->with('success', 'Kit créé avec succès.');
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
            'items' => 'required|array',
            'items.*.item_type' => 'required|string',
            'items.*.item_id' => 'required|integer',
            'items.*.quantite' => 'required|integer|min:1',
        ]);

        $kit->update($validated);

        // Supprimer les anciens items et recréer
        $kit->items()->delete();
        foreach ($validated['items'] as $item) {
            $kit->items()->create($item);
        }

        return redirect()->route('kits.index')->with('success', 'Kit mis à jour avec succès.');
    }

    public function destroy(Kit $kit)
    {
        $kit->delete();
        return redirect()->route('kits.index')->with('success', 'Kit supprimé avec succès.');
    }
    public function search(Request $request)
    {
        $query = $request->input('q');
        $kits = Kit::with('items')
            ->where('nom', 'like', "%{$query}%")
            ->paginate(10);
        // Retourner une vue partielle avec uniquement la table (correspondant à #data)
        return view('kits.partials.kit_table', compact('kits'))->render();
    }
}
