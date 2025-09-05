<?php

namespace App\Http\Controllers;

use App\Models\Emplacement;
use Illuminate\Http\Request;

class EmplacementController extends Controller
{
    public function index()
    {
        return Emplacement::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'ville' => 'required|string',
            'pays' => 'required|string',
        ]);

        return Emplacement::create($validated);
    }

    public function show($id)
    {
        return Emplacement::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $emplacement = Emplacement::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'required|string',
            'ville' => 'required|string',
            'pays' => 'required|string',
        ]);

        $emplacement->update($validated);
        return $emplacement;
    }

    public function destroy($id)
    {
        $emplacement = Emplacement::findOrFail($id);
        $emplacement->delete();
        return response()->json(['message' => 'Emplacement supprimé']);
    }
}
