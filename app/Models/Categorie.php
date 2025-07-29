<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'type', 'description', 'image', 'notes'];

    public function composants() { return $this->hasMany(Composant::class); }
    public function consommables() { return $this->hasMany(Consommable::class); }
    public function accessoires() { return $this->hasMany(Accessoire::class); }
    public function models() { return $this->hasMany(ModelMateriel::class); }
}
