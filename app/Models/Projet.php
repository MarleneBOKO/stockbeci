<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    use HasFactory;

    protected $fillable = ['refprojet', 'statut'];
    public function actifs()
    {
        return $this->belongsToMany(Actif::class, 'projet_actif');
    }

    public function consommables()
    {
        return $this->belongsToMany(Consommable::class, 'projet_consommable');
    }

    public function projets()
    {
        return $this->belongsToMany(Projet::class, 'projet_kit');
    }


    public function composants()
    {
        return $this->belongsToMany(Composant::class, 'projet_composant');
    }

    public function kits()
    {
        return $this->belongsToMany(Kit::class, 'projet_kit');
    }
}

