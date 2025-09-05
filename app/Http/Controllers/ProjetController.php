<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use Illuminate\Http\Request;

class ProjetController extends Controller
{
    public function index()
    {
        return Projet::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'refprojet' => 'required|string|unique:projets,refprojet',
        ]);

        return Projet::create($validated);
    }

    public function show($id)
    {
        return Projet::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $projet = Projet::findOrFail($id);
        $validated = $request->validate([
            'refprojet' => 'required|string|unique:projets,refprojet,' . $id,
        ]);

        $projet->update($validated);
        return $projet;
    }

    public function destroy($id)
    {
        $projet = Projet::findOrFail($id);
        $projet->delete();
        return response()->json(['message' => 'Projet supprimé']);
    }
}
