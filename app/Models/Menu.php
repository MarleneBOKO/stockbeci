<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelleMenu',
        'titre_page',
        'controller',
        'route',
        'Topmenu_id',
        'user_action',
        'num_ordre',
        'order_ss',
        'iconee',
        'element_menu',
        'statut'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_action');
    }

    public function topmenu()
    {
        return $this->belongsTo(Menu::class, 'Topmenu_id');
    }

    public function submenus()
    {
        return $this->hasMany(Menu::class, 'Topmenu_id');
    }
}
