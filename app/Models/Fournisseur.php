<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'adresse', 'ville', 'etat', 'pays', 'fermeture_eclair',
        'nom_personne_ressource', 'telephone', 'fax', 'messagerie_electronique',
        'url', 'note', 'image'
    ];

    public function composants() { return $this->hasMany(Composant::class); }
    public function consommables() { return $this->hasMany(Consommable::class); }
    public function accessoires() { return $this->hasMany(Accessoire::class); }
    public function actifs() { return $this->hasMany(Actif::class); }
}
