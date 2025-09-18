<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitItem extends Model
{
    use HasFactory;

    protected $fillable = ['kit_id', 'item_type', 'item_id', 'quantite'];

    public function kit()
    {
        return $this->belongsTo(Kit::class);
    }

    // Relation polymorphique simplifiée (optionnel)
    public function item()
    {
        switch ($this->item_type) {
            case 'Actif':
                return $this->belongsTo(Actif::class, 'item_id');
            case 'Accessoire':
                return $this->belongsTo(Accessoire::class, 'item_id');
            case 'Composant':
                return $this->belongsTo(Composant::class, 'item_id');
            case 'Consommable':
                return $this->belongsTo(Consommable::class, 'item_id');
            default:
                return null;
        }
    }
}
