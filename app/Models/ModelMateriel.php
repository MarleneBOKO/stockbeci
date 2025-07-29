<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelMateriel extends Model
{
    use HasFactory;

    protected $table = 'models';

    protected $fillable = [
        'nom', 'categorie_id', 'fabricant_id', 'model_num', 'amortissement_id',
        'qte_min', 'findevie', 'notes', 'ensemble_champs', 'images'
    ];

    public function categorie() { return $this->belongsTo(Categorie::class); }
    public function fabricant() { return $this->belongsTo(Fabricant::class); }
    public function amortissement() { return $this->belongsTo(Amortissement::class); }
    public function actifs() { return $this->hasMany(Actif::class); }
}
