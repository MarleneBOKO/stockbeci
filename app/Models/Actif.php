<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actif extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventaire', 'serial', 'model_id', 'statut_id', 'notes',
        'emplacement_id', 'sur_demande', 'image', 'nom_actif', 'garantie',
        'date_verification', 'date_achat', 'date_fin_vie', 'fournisseur_id',
        'cout_achat', 'projet_id', 'utilisateur_id', 'valeur_actuelle'
    ];

    public function model() { return $this->belongsTo(ModelMateriel::class); }
    public function statut() { return $this->belongsTo(EtiquetteEtat::class, 'statut_id'); }
    public function emplacement() { return $this->belongsTo(Emplacement::class); }
    public function fournisseur() { return $this->belongsTo(Fournisseur::class); }
    public function projet() { return $this->belongsTo(Projet::class); }
    public function utilisateur() { return $this->belongsTo(Utilisateur::class); }
}
