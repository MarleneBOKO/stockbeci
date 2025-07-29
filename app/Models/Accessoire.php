<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessoire extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'categorie_id', 'quantite', 'qte_min', 'fabricant_id', 'numero_model',
        'emplacement_id', 'fournisseur_id', 'num_commande', 'date_achat', 'cout_achat',
        'notes', 'images'
    ];

    public function categorie() { return $this->belongsTo(Categorie::class); }
    public function fabricant() { return $this->belongsTo(Fabricant::class); }
    public function emplacement() { return $this->belongsTo(Emplacement::class); }
    public function fournisseur() { return $this->belongsTo(Fournisseur::class); }
}
