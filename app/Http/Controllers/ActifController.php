<?php

namespace App\Http\Controllers;

use App\Models\Actif;
use Illuminate\Http\Request;
use App\Models\Emplacement;
use App\Models\Fournisseur;
use App\Models\HistoriqueActif;
use App\Models\Maintenance;
use App\Models\Projet;
use App\Models\User;
use App\Models\ModelMateriel;
use App\Models\EtiquetteEtat;
use Illuminate\Support\Facades\Auth;

class ActifController extends Controller
{
    public function index()
    {
        $list = Actif::with(['model', 'emplacement', 'fournisseur', 'projet', 'utilisateur'])->paginate(10); // ou get()

        // Pour les selects dans le modal
        $models = ModelMateriel::all();
        $emplacements = Emplacement::all();
        $projets = Projet::all();
        $users = User::all();

        return view('actifs.index', compact('list', 'models', 'emplacements', 'projets', 'users'));
    }



    public function store(Request $request)
    {
        // Vérifier la permission d'ajout
        if (!in_array("add_actif", session("auto_action", []))) {
            return back()->withErrors(['error' => 'Accès non autorisé pour créer un actif.']);
        }

        // Règles de validation
        $validatedData = $request->validate([
            'nom_actif' => 'required|string|max:255',
            'inventaire' => 'nullable|string|max:100|unique:actifs,inventaire',
            'model_id' => 'required|exists:models,id',
            'statut' => 'nullable|string|in:Liste,Déployé,Prêt à être déployé,En instance,Archivés,Sur demande,Audit,Supprimé',
            'emplacement_id' => 'required|exists:emplacements,id',
            'date_achat' => 'nullable|date',
            'date_fin_vie' => 'nullable|date|after_or_equal:date_achat',
            'garantie' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'cout_achat' => 'nullable|numeric|min:0',
            'valeur_actuelle' => 'nullable|numeric|min:0',
            'image' => 'nullable|string|max:500',
        ], [
            'nom_actif.required' => 'Le nom de l\'actif est obligatoire.',
            'model_id.required' => 'Veuillez sélectionner un modèle.',
            'emplacement_id.required' => 'Veuillez sélectionner un emplacement.',
            'inventaire.unique' => 'Ce numéro d\'inventaire existe déjà.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
        ]);

        // Ajouter l'utilisateur connecté
        $utilisateur = Auth::user();
        $validatedData['utilisateur_id'] = $utilisateur ? $utilisateur->idUser : null;

        if (!$validatedData['utilisateur_id']) {
            return back()->withErrors(['error' => 'Utilisateur non authentifié.'])->withInput();
        }


        $validatedData['statut'] = $validatedData['statut'] ?: 'Liste';

        try {
            Actif::create($validatedData);
            return redirect()->route('actifs.index')->with('success', 'Actif créé avec succès !');
        } catch (\Exception $e) {
            \Log::error('Erreur création actif : ' . $e->getMessage());
            return back()->withErrors(['error' => 'Erreur lors de la création : ' . $e->getMessage()])->withInput();
        }
    }




    public function show($id)
    {
        return Actif::with(['model', 'statut', 'emplacement', 'fournisseur', 'projet', 'utilisateur'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $actif = Actif::findOrFail($id);
        $validated = $request->validate([
            'inventaire' => 'nullable|string',
            'serial' => 'nullable|string',
            'model_id' => 'required|exists:models,id',
            'statut_id' => 'nullable|exists:etiquette_etats,id',
            'notes' => 'nullable|string',
            'emplacement_id' => 'required|exists:emplacements,id',
            'sur_demande' => 'boolean',
            'image' => 'nullable|string',
            'nom_actif' => 'nullable|string',
            'garantie' => 'nullable|string',
            'date_verification' => 'nullable|date',
            'date_achat' => 'nullable|date',
            'date_fin_vie' => 'nullable|date',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'cout_achat' => 'nullable|numeric',
            'projet_id' => 'nullable|exists:projets,id',
            'idUser' => 'nullable|exists:users,idUser',
            'valeur_actuelle' => 'nullable|numeric',
        ]);

        $actif->update($validated);
        return redirect()->route('actifs.index')->with('success', 'Actif mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $actif = Actif::findOrFail($id);
        $actif->delete();
        return redirect()->route('actifs.index')->with('success', 'Actif supprimé avec succès.');
    }

    public function affecterProjet(Request $request, $id)
    {
        $request->validate([
            'projet_id' => 'required|exists:projets,id'
        ]);

        $actif = Actif::findOrFail($id);
        $actif->projet_id = $request->projet_id;
        $actif->save();

        return redirect()->back()->with('success', 'Actif affecté au projet avec succès.');
    }


    public function changerStatut($idActif, $statut)
    {
        $actif = Actif::findOrFail($idActif);
        $actif->statut_id = $statut;
        $actif->save();

        return response()->json(['message' => 'Statut modifié avec succès', 'actif' => $actif]);
    }

    public function search(Request $request)
    {
        $q = $request->query('q');

        $list = Actif::with(['model', 'statut', 'emplacement', 'fournisseur', 'projet', 'utilisateur'])
            ->where('nom_actif', 'like', "%{$q}%")
            ->orWhere('inventaire', 'like', "%{$q}%")
            ->paginate(10);

        return view('actifs.partials.table', compact('list'))->render();
    }


    public function historiqueMouvements($idActif)
    {
        $actif = Actif::with('projet', 'emplacement', 'fournisseur')->findOrFail($idActif);
        // Ici on peut créer un modèle historique si tu veux suivre toutes les affectations/maintenance
        $historique = HistoriqueActif::where('actif_id', $idActif)->get();

        return response()->json($historique);
    }

    public function maintenancePreventive($idActif, $data)
    {
        $maintenance = Maintenance::create([
            'actif_id' => $idActif,
            'date' => $data['date'],
            'type' => $data['type'],
            'note' => $data['note'] ?? null
        ]);

        return response()->json(['message' => 'Maintenance planifiée', 'maintenance' => $maintenance]);
    }

}
