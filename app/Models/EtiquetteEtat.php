<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtiquetteEtat extends Model
{
    use HasFactory;

     protected $fillable = ['nom', 'type', 'couleur', 'note'];

    public function actifs() { return $this->hasMany(Actif::class, 'statut_id'); }
}
