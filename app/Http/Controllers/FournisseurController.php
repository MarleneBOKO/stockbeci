<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index(Request $request)
    {
        $query = Fournisseur::query();

        // Gestion de la recherche
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                    ->orWhere('contact', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('telephone', 'like', "%$search%");
            });
        }

        $list = $query->latest()->paginate(10);

        // Si c'est une requête AJAX pour la recherche
        if ($request->ajax() || $request->has('search')) {
            return view('fournisseurs._table', compact('list'))->render();
        }

        return view('fournisseurs.index', compact('list'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nom' => 'required|string|max:255',
                'adresse' => 'nullable|string|max:255',
                'ville' => 'nullable|string|max:100',
                'etat' => 'nullable|string|max:100',
                'pays' => 'nullable|string|max:100',
                'nom_personne_ressource' => 'nullable|string|max:255',
                'telephone' => 'nullable|string|max:20',
                'fax' => 'nullable|string|max:20',
                'messagerie_electronique' => 'nullable|email|max:255',
                'url' => 'nullable|url|max:255',
                'note' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Mapping des champs pour correspondre aux colonnes de la base de données
            $fournisseurData = [
                'nom' => $data['nom'],
                'adresse' => $data['adresse'] ?? null,
                'ville' => $data['ville'] ?? null,
                'etat' => $data['etat'] ?? null,
                'pays' => $data['pays'] ?? null,
                'contact' => $data['nom_personne_ressource'] ?? null, // Mapping contact
                'telephone' => $data['telephone'] ?? null,
                'fax' => $data['fax'] ?? null,
                'email' => $data['messagerie_electronique'] ?? null, // Mapping email
                'site_web' => $data['url'] ?? null, // Mapping site_web
                'notes' => $data['note'] ?? null, // Mapping notes (pluriel)
            ];

            // Gestion de l'upload d'image
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('fournisseurs', $imageName, 'public');
                $fournisseurData['image'] = $imagePath;
            }

            Fournisseur::create($fournisseurData);

            return back()->with('success', 'Fournisseur ajouté avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'ajout : ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        try {
            $data = $request->validate([
                'nom' => 'required|string|max:255',
                'adresse' => 'nullable|string|max:255',
                'ville' => 'nullable|string|max:100',
                'etat' => 'nullable|string|max:100',
                'pays' => 'nullable|string|max:100',
                'nom_personne_ressource' => 'nullable|string|max:255',
                'telephone' => 'nullable|string|max:20',
                'fax' => 'nullable|string|max:20',
                'messagerie_electronique' => 'nullable|email|max:255',
                'url' => 'nullable|url|max:255',
                'note' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Mapping des champs pour correspondre aux colonnes de la base de données
            $fournisseurData = [
                'nom' => $data['nom'],
                'adresse' => $data['adresse'] ?? null,
                'ville' => $data['ville'] ?? null,
                'etat' => $data['etat'] ?? null,
                'pays' => $data['pays'] ?? null,
                'contact' => $data['nom_personne_ressource'] ?? null,
                'telephone' => $data['telephone'] ?? null,
                'fax' => $data['fax'] ?? null,
                'email' => $data['messagerie_electronique'] ?? null,
                'site_web' => $data['url'] ?? null,
                'notes' => $data['note'] ?? null,
            ];

            // Gestion de l'upload d'image
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('fournisseurs', $imageName, 'public');
                $fournisseurData['image'] = $imagePath;
            }

            $fournisseur->update($fournisseurData);

            return back()->with('success', 'Fournisseur modifié avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la modification : ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Fournisseur $fournisseur)
    {
        try {
            // Supprimer l'image si elle existe
            if ($fournisseur->image && \Storage::disk('public')->exists($fournisseur->image)) {
                \Storage::disk('public')->delete($fournisseur->image);
            }

            $fournisseur->delete();
            return back()->with('success', 'Fournisseur supprimé avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
