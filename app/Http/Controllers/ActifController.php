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

class ActifController extends Controller
{
    public function index()
    {
        $list = Actif::with(['model', 'statut', 'emplacement', 'fournisseur', 'projet', 'utilisateur'])->paginate(10); // ou get()

        // Pour les selects dans le modal
        $models = ModelMateriel::all();
        $statuts = EtiquetteEtat::all();
        $emplacements = Emplacement::all();
        $projets = Projet::all();
        $users = User::all();

        return view('actifs.index', compact('list', 'models', 'statuts', 'emplacements', 'projets', 'users'));
    }


    public function store(Request $request)
    {
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
            'utilisateur_id' => 'nullable|exists:users,idUser',
            'valeur_actuelle' => 'nullable|numeric',
        ]);

        return Actif::create($validated);
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
            'utilisateur_id' => 'nullable|exists:users,idUser',
            'valeur_actuelle' => 'nullable|numeric',
        ]);

        $actif->update($validated);
        return $actif;
    }

    public function destroy($id)
    {
        $actif = Actif::findOrFail($id);
        $actif->delete();
        return response()->json(['message' => 'Actif supprimé']);
    }

    public function affecterProjet($idActif, $idProjet)
    {
        $actif = Actif::findOrFail($idActif);
        $actif->projet_id = $idProjet;
        $actif->save();

        return response()->json(['message' => 'Actif affecté au projet avec succès', 'actif' => $actif]);
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
