<?php

namespace App\Http\Controllers;

use App\Models\Fabricant;
use Illuminate\Http\Request;

class FabricantController extends Controller
{
    public function index()
    {
        $list = Fabricant::latest()->paginate(10);
        return view('fabricants.index', compact('list'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'url' => 'nullable|url',
            'url_assistance' => 'nullable|url',
            'url_search_garantie' => 'nullable|url',
            'tel_assistance' => 'nullable|string',
            'email_assistance' => 'nullable|email',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('fabricants', 'public');
        }

        Fabricant::create($data);

        return back()->with('success', 'Fabricant ajouté avec succès.');
    }

    public function update(Request $request, Fabricant $fabricant)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'url' => 'nullable|url',
            'url_assistance' => 'nullable|url',
            'url_search_garantie' => 'nullable|url',
            'tel_assistance' => 'nullable|string',
            'email_assistance' => 'nullable|email',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('fabricants', 'public');
        }

        $fabricant->update($data);

        return back()->with('success', 'Fabricant modifié avec succès.');
    }

    public function destroy(Fabricant $fabricant)
    {
        $fabricant->delete();
        return back()->with('success', 'Fabricant supprimé.');
    }

    public function search(Request $request)
    {
        $q = $request->get('q');
        $list = Fabricant::where('nom', 'like', "%$q%")->paginate(10);
        return view('fabricants._table', compact('list'))->render();
    }
}
