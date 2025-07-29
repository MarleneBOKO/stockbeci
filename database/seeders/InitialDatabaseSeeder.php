<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitialDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['idRole' => 1, 'libelle' => 'Développeur', 'code' => 'dev', 'user_action' => 1, 'statut' => '0', 'created_at' => '2021-09-02 17:11:54', 'updated_at' => '2021-09-29 15:41:02'],
        ]);

        DB::table('users')->insert([
            [
                'idUser' => 1, 'nom' => 'DJIDAGBAGBA', 'prenom' => 'S T Emmanuel', 'sexe' => 'M',
                'tel' => '61310573', 'mail' => 'emmanueldjidagbagba@gmail.com', 'adresse' => 'Cotonou',
                'login' => 'kanths', 'password' => 'com5d9aeeeb1929fcf1b19f6a11a75f5a083a577274dste',
                'Role' => 1, 'Societe' => 1, 'other' => null, 'image' => null, 'signature' => null,
                'auth' => 'direct', 'user_action' => 1, 'action_save' => 's', 'statut' => '0',
                'created_at' => '2021-11-08 08:28:13', 'updated_at' => '2021-12-10 08:50:10'
            ]
        ]);

        DB::table('menus')->insert([
            ['idMenu' => 1, 'libelleMenu' => 'Administration', 'titre_page' => 'Administration', 'route' => '#', 'Topmenu_id' => 0, 'user_action' => 1, 'num_ordre' => 0, 'iconee' => 'menu-icon mdi mdi-settings-box', 'statut' => '0', 'created_at' => '2021-09-27 11:30:12', 'updated_at' => '2022-02-03 14:39:32'],
            ['idMenu' => 2, 'libelleMenu' => 'Paramétrages', 'titre_page' => 'Paramétrages', 'route' => '#', 'Topmenu_id' => 0, 'user_action' => 1, 'num_ordre' => 1, 'iconee' => 'menu-icon mdi mdi-settings', 'statut' => '0', 'created_at' => '2021-09-27 18:00:51', 'updated_at' => '2021-11-02 08:34:45'],
            ['idMenu' => 7, 'libelleMenu' => 'Utilisateurs', 'titre_page' => 'Liste des utilisateurs', 'route' => 'listU', 'Topmenu_id' => 1, 'user_action' => 1, 'num_ordre' => 0, 'iconee' => null, 'statut' => '0', 'created_at' => '2021-09-30 08:13:34', 'updated_at' => '2021-11-02 08:42:31'],
            ['idMenu' => 8, 'libelleMenu' => 'Rôles', 'titre_page' => 'Liste des rôles', 'route' => 'listR', 'Topmenu_id' => 1, 'user_action' => 1, 'num_ordre' => 1, 'iconee' => null, 'statut' => '0', 'created_at' => '2021-09-30 08:14:01', 'updated_at' => '2022-02-04 13:14:06'],
            ['idMenu' => 11, 'libelleMenu' => 'Menu', 'titre_page' => 'Liste des Menus', 'route' => 'listM', 'Topmenu_id' => 1, 'user_action' => 1, 'num_ordre' => 3, 'iconee' => null, 'statut' => '0', 'created_at' => '2021-09-30 08:15:21', 'updated_at' => '2021-11-02 08:47:20'],
            ['idMenu' => 20, 'libelleMenu' => 'Société', 'titre_page' => 'Société', 'route' => 'listSoc', 'Topmenu_id' => 1, 'user_action' => 1, 'num_ordre' => 4, 'iconee' => null, 'statut' => '0', 'created_at' => '2021-11-02 08:51:15', 'updated_at' => '2021-11-02 08:51:15'],
            ['idMenu' => 30, 'libelleMenu' => 'Traces Système', 'titre_page' => 'Liste des Traces du système', 'route' => 'listTrace', 'Topmenu_id' => 0, 'user_action' => 1, 'num_ordre' => 7, 'iconee' => 'menu-icon mdi mdi-book-open', 'statut' => '0', 'created_at' => '2021-11-05 11:36:36', 'updated_at' => '2021-11-05 11:36:36'],
        ]);

        DB::table('societes')->insert([
            ['id' => 1, 'libelleSociete' => 'BECI BTP', 'avis' => 'RISQUE ACCEPTE', 'email' => '', 'contact' => '21 xx 65 23', 'adresse' => 'Cotonou', 'signature' => 'logo.png', 'duplicata' => 'duplicata.jpg', 'logo' => 'logo.png', 'piedpage' => 'Société 66 ', 'tauxAIB' => 3, 'tauxNonAIB' => 5, 'carec' => 0, 'periode' => '07-2024', 'primemin' => 1000, 'created_at' => '2024-08-05 00:34:14', 'updated_at' => null],
        ]);

        DB::table('action_menus')->insert([
            [
                'id' => 6,
                'Menu' => 7,
                'action' => 'Ajouter un utilisateur',
                'code_dev' => 'add_user',
                'statut' => null,
                'created_at' => '2021-11-02 10:37:07',
                'updated_at' => '2021-11-02 10:37:07',
            ],
            [
                'id' => 7,
                'Menu' => 7,
                'action' => 'Modifier un utilisateur',
                'code_dev' => 'update_user',
                'statut' => null,
                'created_at' => '2021-11-02 12:19:37',
                'updated_at' => '2021-11-02 12:19:37',
            ],
            [
                'id' => 8,
                'Menu' => 7,
                'action' => 'Supprimer un utilisateur',
                'code_dev' => 'delete_user',
                'statut' => null,
                'created_at' => '2021-11-02 12:20:34',
                'updated_at' => '2021-11-02 12:20:34',
            ],
            [
                'id' => 9,
                'Menu' => 7,
                'action' => "Réinitialiser mot de passe de l'utilisateur",
                'code_dev' => 'reset_user',
                'statut' => null,
                'created_at' => '2021-11-02 12:22:31',
                'updated_at' => '2021-11-02 12:22:31',
            ],
            [
                'id' => 10,
                'Menu' => 7,
                'action' => "Statut de l'utilisateur",
                'code_dev' => 'status_user',
                'statut' => null,
                'created_at' => '2021-11-02 12:27:07',
                'updated_at' => '2021-11-02 12:27:07',
            ],
            [
                'id' => 11,
                'Menu' => 8,
                'action' => "Ajouter un Rôle",
                'code_dev' => 'add_role',
                'statut' => null,
                'created_at' => '2021-11-02 15:36:31',
                'updated_at' => '2021-11-02 15:36:31',
            ],
            [
                'id' => 12,
                'Menu' => 8,
                'action' => "Modifier un Rôle",
                'code_dev' => 'update_role',
                'statut' => null,
                'created_at' => '2021-11-02 15:37:16',
                'updated_at' => '2021-11-02 15:37:16',
            ],
            [
                'id' => 13,
                'Menu' => 8,
                'action' => "Supprimer un Rôle",
                'code_dev' => 'delete_role',
                'statut' => null,
                'created_at' => '2021-11-02 15:37:38',
                'updated_at' => '2021-11-02 15:37:38',
            ],
            [
                'id' => 14,
                'Menu' => 8,
                'action' => "Attribuer menu",
                'code_dev' => 'menu_role',
                'statut' => null,
                'created_at' => '2021-11-02 15:38:15',
                'updated_at' => '2021-11-02 15:38:15',
            ],
            [
                'id' => 19,
                'Menu' => 11,
                'action' => "Ajouter Menu",
                'code_dev' => 'add_menu',
                'statut' => null,
                'created_at' => '2021-11-03 06:34:41',
                'updated_at' => '2021-11-03 06:34:41',
            ],
            [
                'id' => 20,
                'Menu' => 11,
                'action' => "Modifier Menu",
                'code_dev' => 'update_menu',
                'statut' => null,
                'created_at' => '2021-11-03 06:35:24',
                'updated_at' => '2021-11-03 06:35:24',
            ],
            [
                'id' => 21,
                'Menu' => 11,
                'action' => "Supprimer Menu",
                'code_dev' => 'delete_menu',
                'statut' => null,
                'created_at' => '2021-11-03 06:35:54',
                'updated_at' => '2021-11-03 06:35:54',
            ],
            [
                'id' => 22,
                'Menu' => 11,
                'action' => "Gérer les actions Menu",
                'code_dev' => 'manage_menu',
                'statut' => null,
                'created_at' => '2021-11-03 06:38:01',
                'updated_at' => '2021-11-03 06:38:01',
            ],
            [
                'id' => 23,
                'Menu' => 20,
                'action' => "Modifier Société",
                'code_dev' => 'update_soc',
                'statut' => null,
                'created_at' => '2021-11-03 06:43:28',
                'updated_at' => '2021-11-03 06:43:28',
            ],
        ]);

        DB::table('action_menu_acces')->insert([
            ['id' => 2, 'Menu' => '1', 'Role' => '1', 'ActionMenu' => '0', 'statut' => '0', 'created_at' => '2021-11-01 21:08:36', 'updated_at' => '2021-11-01 21:08:36'],
            ['id' => 3, 'Menu' => '2', 'Role' => '1', 'ActionMenu' => '0', 'statut' => '0', 'created_at' => '2021-11-01 21:08:36', 'updated_at' => '2021-11-01 21:08:36'],
            ['id' => 9, 'Menu' => '7', 'Role' => '1', 'ActionMenu' => '0', 'statut' => '0', 'created_at' => '2021-11-01 21:54:01', 'updated_at' => '2021-11-01 21:54:01'],
            ['id' => 10, 'Menu' => '8', 'Role' => '1', 'ActionMenu' => '0', 'statut' => '0', 'created_at' => '2021-11-01 21:54:02', 'updated_at' => '2021-11-01 21:54:02'],
            ['id' => 15, 'Menu' => '11', 'Role' => '1', 'ActionMenu' => '0', 'statut' => '0', 'created_at' => '2021-11-01 22:04:02', 'updated_at' => '2021-11-01 22:04:02'],
            ['id' => 25, 'Menu' => '20', 'Role' => '1', 'ActionMenu' => '0', 'statut' => '0', 'created_at' => '2021-11-02 10:22:03', 'updated_at' => '2021-11-02 10:22:03'],
            ['id' => 33, 'Menu' => '7', 'Role' => '1', 'ActionMenu' => '6', 'statut' => '0', 'created_at' => '2021-11-02 12:28:27', 'updated_at' => '2021-11-02 12:28:27'],
            ['id' => 34, 'Menu' => '7', 'Role' => '1', 'ActionMenu' => '7', 'statut' => '0', 'created_at' => '2021-11-02 12:28:27', 'updated_at' => '2021-11-02 12:28:27'],
            ['id' => 35, 'Menu' => '7', 'Role' => '1', 'ActionMenu' => '8', 'statut' => '0', 'created_at' => '2021-11-02 12:28:27', 'updated_at' => '2021-11-02 12:28:27'],
            ['id' => 36, 'Menu' => '7', 'Role' => '1', 'ActionMenu' => '9', 'statut' => '0', 'created_at' => '2021-11-02 12:28:27', 'updated_at' => '2021-11-02 12:28:27'],
            ['id' => 37, 'Menu' => '7', 'Role' => '1', 'ActionMenu' => '10', 'statut' => '0', 'created_at' => '2021-11-02 12:28:27', 'updated_at' => '2021-11-02 12:28:27'],
            ['id' => 38, 'Menu' => '8', 'Role' => '1', 'ActionMenu' => '11', 'statut' => '0', 'created_at' => '2021-11-02 15:39:13', 'updated_at' => '2021-11-02 15:39:13'],
            ['id' => 39, 'Menu' => '8', 'Role' => '1', 'ActionMenu' => '12', 'statut' => '0', 'created_at' => '2021-11-02 15:39:13', 'updated_at' => '2021-11-02 15:39:13'],
            ['id' => 40, 'Menu' => '8', 'Role' => '1', 'ActionMenu' => '13', 'statut' => '0', 'created_at' => '2021-11-02 15:39:13', 'updated_at' => '2021-11-02 15:39:13'],
            ['id' => 41, 'Menu' => '8', 'Role' => '1', 'ActionMenu' => '14', 'statut' => '0', 'created_at' => '2021-11-02 15:39:13', 'updated_at' => '2021-11-02 15:39:13'],
            ['id' => 50, 'Menu' => '11', 'Role' => '1', 'ActionMenu' => '19', 'statut' => '0', 'created_at' => '2021-11-03 11:46:40', 'updated_at' => '2021-11-03 11:46:40'],
            ['id' => 51, 'Menu' => '11', 'Role' => '1', 'ActionMenu' => '20', 'statut' => '0', 'created_at' => '2021-11-03 11:46:40', 'updated_at' => '2021-11-03 11:46:40'],
            ['id' => 52, 'Menu' => '11', 'Role' => '1', 'ActionMenu' => '21', 'statut' => '0', 'created_at' => '2021-11-03 11:46:40', 'updated_at' => '2021-11-03 11:46:40'],
            ['id' => 53, 'Menu' => '11', 'Role' => '1', 'ActionMenu' => '22', 'statut' => '0', 'created_at' => '2021-11-03 11:46:40', 'updated_at' => '2021-11-03 11:46:40'],
            ['id' => 89, 'Menu' => '20', 'Role' => '1', 'ActionMenu' => '23', 'statut' => '0', 'created_at' => '2021-11-03 14:39:09', 'updated_at' => '2021-11-03 14:39:09'],
            ['id' => 105, 'Menu' => '30', 'Role' => '1', 'ActionMenu' => '0', 'statut' => '0', 'created_at' => '2021-11-05 10:45:54', 'updated_at' => '2021-11-05 10:45:54'],
        ]);

    }
}
