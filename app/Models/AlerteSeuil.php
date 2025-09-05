<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlerteSeuil extends Model
{
    use HasFactory;

    protected $table = 'alertes_seuil';

    protected $fillable = ['item_type', 'item_id', 'seuil_min', 'alerte_envoyee'];

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
        }
    }
}
