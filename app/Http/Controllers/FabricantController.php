<?php

namespace App\Http\Controllers;

use App\Models\Fabricant;
use Illuminate\Http\Request;

class FabricantController extends Controller
{
    public function index()
    {
        return Fabricant::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'contact' => 'nullable|string',
        ]);

        return Fabricant::create($validated);
    }

    public function show($id)
    {
        return Fabricant::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $fabricant = Fabricant::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'required|string',
            'contact' => 'nullable|string',
        ]);

        $fabricant->update($validated);
        return $fabricant;
    }

    public function destroy($id)
    {
        $fabricant = Fabricant::findOrFail($id);
        $fabricant->delete();
        return response()->json(['message' => 'Fabricant supprimé']);
    }
}
