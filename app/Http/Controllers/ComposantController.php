<?php

namespace App\Http\Controllers;

use App\Models\Composant;
use App\Models\Categorie;
use App\Models\Emplacement;
use App\Models\Fournisseur;
use App\Models\Fabricant;
use Illuminate\Http\Request;

class ComposantController extends Controller
{
    public function index()
    {
        $list = Composant::with(['categorie', 'emplacement', 'fournisseur', 'fabricant'])->paginate(10);
        $categories = Categorie::all();
        $emplacements = Emplacement::all();
        $fournisseurs = Fournisseur::all();
        $fabricants = Fabricant::all();
        return view('composants.index', compact('list', 'categories', 'emplacements', 'fournisseurs', 'fabricants'));
    }

    public function store(Request $request)
    {
        Composant::create($request->all());
        return redirect()->route('composants.index')->with('success', 'Composant ajouté avec succès');
    }

    public function update(Request $request, Composant $composant)
    {
        $composant->update($request->all());
        return redirect()->route('composants.index')->with('success', 'Composant mis à jour');
    }

    public function destroy(Composant $composant)
    {
        $composant->delete();
        return redirect()->route('composants.index')->with('success', 'Composant supprimé');
    }

    public function entree(Request $request, Composant $composant)
    {
        $composant->quantite += $request->quantite;
        $composant->save();
        return redirect()->route('composants.index')->with('success', 'Stock mis à jour');
    }

    public function sortie(Request $request, Composant $composant)
    {
        $composant->quantite -= $request->quantite;
        $composant->save();
        return redirect()->route('composants.index')->with('success', 'Stock mis à jour');
    }

    public function search(Request $request)
    {
        $q = $request->q;
        $list = Composant::with(['categorie', 'emplacement', 'fournisseur', 'fabricant'])
            ->where('nom', 'like', "%$q%")
            ->paginate(10);

        return view('composants._table', compact('list'));
    }
}
