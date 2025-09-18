<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kit extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'images'];

    public function items()
    {
        return $this->hasMany(KitItem::class);
    }

    public function projets()
    {
        return $this->belongsToMany(Projet::class, 'projet_kit');
    }

}
