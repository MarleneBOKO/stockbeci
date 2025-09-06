<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $table = 'fournisseurs';

    protected $fillable = [
        'nom',
        'adresse',
        'ville',
        'etat',
        'pays',
        'contact',        // Personne ressource
        'telephone',
        'fax',
        'email',          // Messagerie électronique
        'site_web',       // URL
        'notes',          // Note (au pluriel)
        'image'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Accessor pour l'URL complète de l'image
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }


    public function composants() { return $this->hasMany(Composant::class); }
    public function consommables() { return $this->hasMany(Consommable::class); }
    public function accessoires() { return $this->hasMany(Accessoire::class); }
    public function actifs() { return $this->hasMany(Actif::class); }
}
