<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmplacementAndModelMenusSeeder extends Seeder
{
    public function run(): void
    {
        // ----- NOUVEAUX MENUS -----
        DB::table('menus')->insert([
            // Emplacements
            [
                'idMenu' => 40,
                'libelleMenu' => 'Emplacements',
                'titre_page' => 'Gestion des Emplacements',
                'route' => 'emplacements.index',
                'Topmenu_id' => 0,
                'user_action' => 1,
                'num_ordre' => 19,
                'iconee' => 'menu-icon mdi mdi-map-marker',
                'statut' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Modèles
            [
                'idMenu' => 41,
                'libelleMenu' => 'Modèles',
                'titre_page' => 'Gestion des Modèles de Matériel',
                'route' => 'models.index',
                'Topmenu_id' => 0,
                'user_action' => 1,
                'num_ordre' => 20,
                'iconee' => 'menu-icon mdi mdi-cube-outline',
                'statut' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // ----- ACTIONS POUR LES MENUS -----
        DB::table('action_menus')->insert([
            // Actions Emplacements (Menu 40)
            ['id' => 137, 'Menu' => 40, 'action' => 'Créer Emplacement', 'code_dev' => 'add_emplacement', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 138, 'Menu' => 40, 'action' => 'Modifier Emplacement', 'code_dev' => 'update_emplacement', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 139, 'Menu' => 40, 'action' => 'Supprimer Emplacement', 'code_dev' => 'delete_emplacement', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 140, 'Menu' => 40, 'action' => 'Rechercher Emplacement', 'code_dev' => 'search_emplacement', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],

            // Actions Modèles (Menu 41)
            ['id' => 141, 'Menu' => 41, 'action' => 'Créer Modèle', 'code_dev' => 'add_model', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 142, 'Menu' => 41, 'action' => 'Modifier Modèle', 'code_dev' => 'update_model', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 143, 'Menu' => 41, 'action' => 'Supprimer Modèle', 'code_dev' => 'delete_model', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 144, 'Menu' => 41, 'action' => 'Rechercher Modèle', 'code_dev' => 'search_model', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ----- ACCÈS PAR RÔLE -----
        $newMenus = [40, 41]; // Emplacements et Modèles

        // Rôle 1 (Développeur) - Accès complet
        foreach ($newMenus as $menu) {
            DB::table('action_menu_acces')->insert([
                'Menu' => $menu,
                'Role' => 1,
                'ActionMenu' => 0,
                'statut' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $actions = DB::table('action_menus')->where('Menu', $menu)->pluck('id');
            foreach ($actions as $actionId) {
                DB::table('action_menu_acces')->insert([
                    'Menu' => $menu,
                    'Role' => 1,
                    'ActionMenu' => $actionId,
                    'statut' => '0',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Rôle 2 (Magasinier) - Accès complet aussi
        foreach ($newMenus as $menu) {
            DB::table('action_menu_acces')->insert([
                'Menu' => $menu,
                'Role' => 2,
                'ActionMenu' => 0,
                'statut' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $actions = DB::table('action_menus')->where('Menu', $menu)->pluck('id');
            foreach ($actions as $actionId) {
                DB::table('action_menu_acces')->insert([
                    'Menu' => $menu,
                    'Role' => 2,
                    'ActionMenu' => $actionId,
                    'statut' => '0',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Rôle 3 (Chef de projet) - Lecture seule
        foreach ($newMenus as $menu) {
            DB::table('action_menu_acces')->insert([
                'Menu' => $menu,
                'Role' => 3,
                'ActionMenu' => 0,
                'statut' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Seulement recherche
            $searchAction = DB::table('action_menus')->where('Menu', $menu)->where('action', 'like', 'Rechercher%')->first();
            if ($searchAction) {
                DB::table('action_menu_acces')->insert([
                    'Menu' => $menu,
                    'Role' => 3,
                    'ActionMenu' => $searchAction->id,
                    'statut' => '0',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Rôle 4 (Analyste) - Lecture seule
        foreach ($newMenus as $menu) {
            DB::table('action_menu_acces')->insert([
                'Menu' => $menu,
                'Role' => 4,
                'ActionMenu' => 0,
                'statut' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Seulement recherche
            $searchAction = DB::table('action_menus')->where('Menu', $menu)->where('action', 'like', 'Rechercher%')->first();
            if ($searchAction) {
                DB::table('action_menu_acces')->insert([
                    'Menu' => $menu,
                    'Role' => 4,
                    'ActionMenu' => $searchAction->id,
                    'statut' => '0',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
