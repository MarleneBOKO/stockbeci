<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emplacement extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'ville', 'pays'];

    public function actifs() { return $this->hasMany(Actif::class); }
    public function composants() { return $this->hasMany(Composant::class); }
    public function consommables() { return $this->hasMany(Consommable::class); }
    public function accessoires() { return $this->hasMany(Accessoire::class); }
}
