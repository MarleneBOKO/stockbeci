<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdditionalMenusSeeder extends Seeder
{
    public function run(): void
    {
        // ----- MENUS ADDITIONNELS -----
        DB::table('menus')->insert([
            // Projets
            ['idMenu' => 36, 'libelleMenu' => 'Projets', 'titre_page' => 'Gestion des Projets', 'route' => 'projets.index', 'Topmenu_id' => 0, 'user_action' => 1, 'num_ordre' => 15, 'iconee' => 'menu-icon mdi mdi-folder-multiple', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],

            // Catégories
            ['idMenu' => 37, 'libelleMenu' => 'Catégories', 'titre_page' => 'Gestion des Catégories', 'route' => 'categories.index', 'Topmenu_id' => 0, 'user_action' => 1, 'num_ordre' => 16, 'iconee' => 'menu-icon mdi mdi-tag-multiple', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],

            // Fournisseurs
            ['idMenu' => 38, 'libelleMenu' => 'Fournisseurs', 'titre_page' => 'Gestion des Fournisseurs', 'route' => 'fournisseurs', 'Topmenu_id' => 0, 'user_action' => 1, 'num_ordre' => 17, 'iconee' => 'menu-icon mdi mdi-truck-delivery', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],

            // Fabricants
            ['idMenu' => 39, 'libelleMenu' => 'Fabricants', 'titre_page' => 'Gestion des Fabricants', 'route' => 'fabricants', 'Topmenu_id' => 0, 'user_action' => 1, 'num_ordre' => 18, 'iconee' => 'menu-icon mdi mdi-factory', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ----- ACTIONS POUR LES NOUVEAUX MENUS -----
      DB::table('action_menus')->insert([
    // Actions Projets (Menu 36)
    ['id' => 121, 'Menu' => 36, 'action' => 'Créer Projet', 'code_dev' => 'add_projet', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 122, 'Menu' => 36, 'action' => 'Modifier Projet', 'code_dev' => 'update_projet', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 123, 'Menu' => 36, 'action' => 'Supprimer Projet', 'code_dev' => 'delete_projet', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 124, 'Menu' => 36, 'action' => 'Activer/Désactiver Projet', 'code_dev' => 'toggle_projet', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 125, 'Menu' => 36, 'action' => 'Attribuer Kit au Projet', 'code_dev' => 'assign_kit_project', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 126, 'Menu' => 36, 'action' => 'Attribuer Composant au Projet', 'code_dev' => 'assign_composant_project', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 127, 'Menu' => 36, 'action' => 'Attribuer Consommable au Projet', 'code_dev' => 'assign_consommable_project', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 128, 'Menu' => 36, 'action' => 'Attribuer Actif au Projet', 'code_dev' => 'assign_actif_project', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],

    // Actions Catégories (Menu 37)
    ['id' => 129, 'Menu' => 37, 'action' => 'Créer Catégorie', 'code_dev' => 'add_categorie', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 130, 'Menu' => 37, 'action' => 'Modifier Catégorie', 'code_dev' => 'update_categorie', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 131, 'Menu' => 37, 'action' => 'Supprimer Catégorie', 'code_dev' => 'delete_categorie', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 132, 'Menu' => 37, 'action' => 'Rechercher Catégorie', 'code_dev' => 'search_categorie', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],

    // Actions Fournisseurs (Menu 38)
    ['id' => 133, 'Menu' => 38, 'action' => 'Ajouter Fournisseur', 'code_dev' => 'add_fournisseur', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 134, 'Menu' => 38, 'action' => 'Modifier Fournisseur', 'code_dev' => 'update_fournisseur', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 135, 'Menu' => 38, 'action' => 'Supprimer Fournisseur', 'code_dev' => 'delete_fournisseur', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 136, 'Menu' => 38, 'action' => 'Rechercher Fournisseur', 'code_dev' => 'search_fournisseur', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],

    // Actions Fabricants (Menu 39)
    ['id' => 137, 'Menu' => 39, 'action' => 'Ajouter Fabricant', 'code_dev' => 'add_fabricant', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 138, 'Menu' => 39, 'action' => 'Modifier Fabricant', 'code_dev' => 'update_fabricant', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 139, 'Menu' => 39, 'action' => 'Supprimer Fabricant', 'code_dev' => 'delete_fabricant', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 140, 'Menu' => 39, 'action' => 'Rechercher Fabricant', 'code_dev' => 'search_fabricant', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],



            // Actions Catégories (Menu 37)
             ]);

        // ----- DROITS D'ACCÈS POUR LES NOUVEAUX MENUS -----

        // DÉVELOPPEUR (Rôle 1 - kanths) - Accès complet à tout
        $newMenusForDev = [36, 37, 38, 39]; // Projets, Catégories, Fournisseurs, Fabricants

        foreach ($newMenusForDev as $menu) {
            // Vérifier si l'accès au menu existe déjà
            $existingAccess = DB::table('action_menu_acces')
                ->where('Menu', $menu)
                ->where('Role', 1)
                ->where('ActionMenu', 0)
                ->exists();

            if (!$existingAccess) {
                // Accès au menu principal
                DB::table('action_menu_acces')->insert([
                    'Menu' => $menu,
                    'Role' => 1,
                    'ActionMenu' => 0,
                    'statut' => '0',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Accès à toutes les actions du menu
            $actionsForMenu = DB::table('action_menus')->where('Menu', $menu)->pluck('id');
            foreach ($actionsForMenu as $actionId) {
                $existingActionAccess = DB::table('action_menu_acces')
                    ->where('Menu', $menu)
                    ->where('Role', 1)
                    ->where('ActionMenu', $actionId)
                    ->exists();

                if (!$existingActionAccess) {
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
        }

        // MAGASINIER (Rôle 2) - Accès complet aux Projets, Catégories, Fournisseurs, Fabricants
        foreach ($newMenusForDev as $menu) {
            // Accès au menu principal
            DB::table('action_menu_acces')->insert([
                'Menu' => $menu,
                'Role' => 2,
                'ActionMenu' => 0,
                'statut' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Accès à toutes les actions CRUD
            $actionsForMenu = DB::table('action_menus')->where('Menu', $menu)->pluck('id');
            foreach ($actionsForMenu as $actionId) {
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

        // CHEF DE PROJET (Rôle 3) - Accès complet aux Projets, lecture seule pour le reste

        // Accès complet aux Projets
        DB::table('action_menu_acces')->insert([
            'Menu' => 36,
            'Role' => 3,
            'ActionMenu' => 0,
            'statut' => '0',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $projetActions = [121, 122, 123, 124]; // Toutes les actions projets
        foreach ($projetActions as $actionId) {
            DB::table('action_menu_acces')->insert([
                'Menu' => 36,
                'Role' => 3,
                'ActionMenu' => $actionId,
                'statut' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Accès en lecture seule aux Catégories, Fournisseurs, Fabricants
        $readOnlyMenus = [37, 38, 39];
        foreach ($readOnlyMenus as $menu) {
            DB::table('action_menu_acces')->insert([
                'Menu' => $menu,
                'Role' => 3,
                'ActionMenu' => 0,
                'statut' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Seules les actions de recherche/consultation
            if ($menu == 37) { // Catégories
                DB::table('action_menu_acces')->insert([
                    'Menu' => $menu,
                    'Role' => 3,
                    'ActionMenu' => 128, // Rechercher Catégorie
                    'statut' => '0',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } elseif ($menu == 38) { // Fournisseurs
                DB::table('action_menu_acces')->insert([
                    'Menu' => $menu,
                    'Role' => 3,
                    'ActionMenu' => 132, // Rechercher Fournisseur
                    'statut' => '0',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } elseif ($menu == 39) { // Fabricants
                DB::table('action_menu_acces')->insert([
                    'Menu' => $menu,
                    'Role' => 3,
                    'ActionMenu' => 136, // Rechercher Fabricant
                    'statut' => '0',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // ANALYSTE (Rôle 4) - Accès en lecture seule à tous les nouveaux menus
        foreach ($newMenusForDev as $menu) {
            // Accès au menu principal seulement (consultation)
            DB::table('action_menu_acces')->insert([
                'Menu' => $menu,
                'Role' => 4,
                'ActionMenu' => 0,
                'statut' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Accès uniquement aux actions de recherche/consultation
            $searchActions = [];
            switch ($menu) {
                case 37: // Catégories
                    $searchActions = [128]; // Rechercher Catégorie
                    break;
                case 38: // Fournisseurs
                    $searchActions = [132]; // Rechercher Fournisseur
                    break;
                case 39: // Fabricants
                    $searchActions = [136]; // Rechercher Fabricant
                    break;
            }

            foreach ($searchActions as $actionId) {
                DB::table('action_menu_acces')->insert([
                    'Menu' => $menu,
                    'Role' => 4,
                    'ActionMenu' => $actionId,
                    'statut' => '0',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
