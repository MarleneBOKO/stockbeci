<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; 

class Actif extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventaire',
        'serial',
        'model_id', 
        'statut', 
        'notes',
        'emplacement_id',
        'sur_demande',
        'image',
        'nom_actif',
        'garantie',
        'date_verification',
        'date_achat',
        'date_fin_vie',
        'fournisseur_id',
        'cout_achat',
        'projet_id',
        'utilisateur_id',
        'valeur_actuelle' // ← 'utilisateur_id' déjà présent, bon
    ];

    // Relations
    public function model()
    {
        return $this->belongsTo(ModelMateriel::class);
    }

    // ← SUPPRIMEZ cette méthode (inutile pour string simple)
    // public function statut() { return $this->belongsTo(EtiquetteEtat::class, 'statut_id'); }

    public function emplacement()
    {
        return $this->belongsTo(Emplacement::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }

    // ← CORRECTION : Renommez en 'utilisateur' et spécifiez les clés pour idUser
    public function utilisateur()
    {  // ← Changé de 'user()' à 'utilisateur()' pour matcher le template
        return $this->belongsTo(User::class, 'utilisateur_id', 'idUser'); // foreign key → owner key
    }

    /**
     * Vérifie si l'actif est disponible pour être affecté.
     */
    public function estDisponible()
    {
        // Liste des statuts considérés comme disponibles
        return in_array($this->statut, ['Liste', 'Prêt à être déployé', 'En stock', 'Restitué']);
    }
}
