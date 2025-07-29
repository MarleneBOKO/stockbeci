<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amortissement extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'nombremois', 'valeur_min_apres_amortissement', 'type_valeur'];

    public function models() { return $this->hasMany(ModelMateriel::class); }
}
