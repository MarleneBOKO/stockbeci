<?php

namespace App\Http\Controllers;

use App\Models\Fabricant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FabricantController extends Controller
{
    public function index(Request $request)
    {
        // Gestion de la recherche AJAX
        if ($request->has('search')) {
            $search = $request->get('search');
            $list = Fabricant::where('nom', 'like', "%$search%")
                            ->orWhere('email_assistance', 'like', "%$search%")
                            ->orWhere('tel_assistance', 'like', "%$search%")
                            ->latest()
                            ->paginate(10);

            // Si c'est une requête AJAX, retourner seulement la table
            if ($request->ajax()) {
                return view('fabricants._table', compact('list'))->render();
            }
        } else {
            $list = Fabricant::latest()->paginate(10);
        }

        return view('fabricants.index', compact('list'));
    }


    public function store(Request $request)
    {
        Log::info('Début méthode store Fabricant');

        try {
            // Validation des données
            $data = $request->validate([
                'nom' => 'required|string|max:255',
                'url' => 'nullable|url',
                'url_assistance' => 'nullable|url',
                'url_search_garantie' => 'nullable|url',
                'tel_assistance' => 'nullable|string',
                'email_assistance' => 'nullable|email',
                'notes' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
            ]);
            Log::info('Validation OK', $data);

            // Gestion upload image
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('fabricants', 'public');
                Log::info('Image uploadée', ['path' => $data['image']]);
            }

            // Création en base
            $fabricant = Fabricant::create($data);
            Log::info('Fabricant créé', ['id' => $fabricant->id]);

            return redirect()->route('fabricants')->with('success', 'Fabricant ajouté avec succès.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur validation', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du fabricant', ['message' => $e->getMessage()]);
            return back()->with('error', 'Une erreur est survenue lors de l\'enregistrement.')->withInput();
        }
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
        $list = Fabricant::where('nom', 'like', "%$q%")
                        ->orWhere('email_assistance', 'like', "%$q%")
                        ->orWhere('tel_assistance', 'like', "%$q%")
                        ->latest()
                        ->paginate(10);

        return view('fabricants._table', compact('list'))->render();
    }
}
