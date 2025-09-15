<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitialDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ----- ROLES (anciens + nouveaux) -----
        DB::table('roles')->insert([
            // Ancien rôle préservé
            ['idRole' => 1, 'libelle' => 'Développeur', 'code' => 'dev', 'user_action' => 1, 'statut' => '0', 'created_at' => '2021-09-02 17:11:54', 'updated_at' => '2021-09-29 15:41:02'],
            // Nouveaux rôles
            ['idRole' => 2, 'libelle' => 'Magasinier', 'code' => 'mag', 'user_action' => 1, 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['idRole' => 3, 'libelle' => 'Chef de projet', 'code' => 'chef', 'user_action' => 1, 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['idRole' => 4, 'libelle' => 'Analyste', 'code' => 'ana', 'user_action' => 1, 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ----- USERS (ancien kanths + nouveaux) -----
        DB::table('users')->insert([
            // Ancien utilisateur kanths préservé
            [
                'idUser' => 1, 'nom' => 'DJIDAGBAGBA', 'prenom' => 'S T Emmanuel', 'sexe' => 'M',
                'tel' => '61310573', 'mail' => 'emmanueldjidagbagba@gmail.com', 'adresse' => 'Cotonou',
                'login' => 'kanths',
                'password' => bcrypt('password123'),
                'Role' => 1, 'Societe' => 1, 'other' => null, 'image' => null, 'signature' => null,
                'auth' => 'direct', 'user_action' => 1, 'action_save' => 's', 'statut' => '0',
                'created_at' => '2021-11-08 08:28:13', 'updated_at' => '2021-12-10 08:50:10'
            ],
            // Nouveaux utilisateurs
            [
                'idUser' => 2, 'nom' => 'Magasin', 'prenom' => 'User', 'sexe' => 'M',
                'tel' => '62100000', 'mail' => 'magasin@example.com', 'adresse' => 'Cotonou',
                'login' => 'magasinier', 'password' => bcrypt('password123'),
                'Role' => 2, 'Societe' => 1, 'other' => null, 'image' => null, 'signature' => null,
                'auth' => 'direct', 'user_action' => 1, 'action_save' => 's', 'statut' => '0',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'idUser' => 3, 'nom' => 'Chef', 'prenom' => 'Projet', 'sexe' => 'M',
                'tel' => '62200000', 'mail' => 'chef@example.com', 'adresse' => 'Cotonou',
                'login' => 'chefprojet', 'password' => bcrypt('password123'),
                'Role' => 3, 'Societe' => 1, 'other' => null, 'image' => null, 'signature' => null,
                'auth' => 'direct', 'user_action' => 1, 'action_save' => 's', 'statut' => '0',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'idUser' => 4, 'nom' => 'Analyst', 'prenom' => 'User', 'sexe' => 'F',
                'tel' => '62300000', 'mail' => 'analyste@example.com', 'adresse' => 'Cotonou',
                'login' => 'analyste', 'password' => bcrypt('password123'),
                'Role' => 4, 'Societe' => 1, 'other' => null, 'image' => null, 'signature' => null,
                'auth' => 'direct', 'user_action' => 1, 'action_save' => 's', 'statut' => '0',
                'created_at' => now(), 'updated_at' => now()
            ]
        ]);

        // ----- MENUS (anciens IDs préservés + nouveaux) -----
        DB::table('menus')->insert([
            // Anciens menus avec IDs préservés
            ['idMenu' => 1, 'libelleMenu' => 'Administration', 'titre_page' => 'Administration', 'route' => '#', 'Topmenu_id' => 0, 'user_action' => 1, 'num_ordre' => 0, 'iconee' => 'menu-icon mdi mdi-settings-box', 'statut' => '0', 'created_at' => '2021-09-27 11:30:12', 'updated_at' => '2022-02-03 14:39:32'],
            ['idMenu' => 2, 'libelleMenu' => 'Paramétrages', 'titre_page' => 'Paramétrages', 'route' => '#', 'Topmenu_id' => 0, 'user_action' => 1, 'num_ordre' => 1, 'iconee' => 'menu-icon mdi mdi-settings', 'statut' => '0', 'created_at' => '2021-09-27 18:00:51', 'updated_at' => '2021-11-02 08:34:45'],
            ['idMenu' => 7, 'libelleMenu' => 'Utilisateurs', 'titre_page' => 'Liste des utilisateurs', 'route' => 'listU', 'Topmenu_id' => 1, 'user_action' => 1, 'num_ordre' => 0, 'iconee' => null, 'statut' => '0', 'created_at' => '2021-09-30 08:13:34', 'updated_at' => '2021-11-02 08:42:31'],
            ['idMenu' => 8, 'libelleMenu' => 'Rôles', 'titre_page' => 'Liste des rôles', 'route' => 'listR', 'Topmenu_id' => 1, 'user_action' => 1, 'num_ordre' => 1, 'iconee' => null, 'statut' => '0', 'created_at' => '2021-09-30 08:14:01', 'updated_at' => '2022-02-04 13:14:06'],
            ['idMenu' => 11, 'libelleMenu' => 'Menu', 'titre_page' => 'Liste des Menus', 'route' => 'listM', 'Topmenu_id' => 1, 'user_action' => 1, 'num_ordre' => 3, 'iconee' => null, 'statut' => '0', 'created_at' => '2021-09-30 08:15:21', 'updated_at' => '2021-11-02 08:47:20'],
            ['idMenu' => 20, 'libelleMenu' => 'Société', 'titre_page' => 'Société', 'route' => 'listSoc', 'Topmenu_id' => 1, 'user_action' => 1, 'num_ordre' => 4, 'iconee' => null, 'statut' => '0', 'created_at' => '2021-11-02 08:51:15', 'updated_at' => '2021-11-02 08:51:15'],
            ['idMenu' => 30, 'libelleMenu' => 'Traces Système', 'titre_page' => 'Liste des Traces du système', 'route' => 'listTrace', 'Topmenu_id' => 0, 'user_action' => 1, 'num_ordre' => 7, 'iconee' => 'menu-icon mdi mdi-book-open', 'statut' => '0', 'created_at' => '2021-11-05 11:36:36', 'updated_at' => '2021-11-05 11:36:36'],

            // Nouveaux menus avec IDs supérieurs
            ['idMenu' => 31, 'libelleMenu' => 'Actifs', 'titre_page' => 'Gestion Actifs', 'route' => 'actifs', 'Topmenu_id' => 0, 'user_action' => 1, 'num_ordre' => 10, 'iconee' => 'menu-icon mdi mdi-package-variant-closed', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['idMenu' => 32, 'libelleMenu' => 'Consommables', 'titre_page' => 'Gestion Consommables', 'route' => 'consommables.index', 'Topmenu_id' => 0, 'user_action' => 1, 'num_ordre' => 11, 'iconee' => 'menu-icon mdi mdi-cube-outline', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['idMenu' => 33, 'libelleMenu' => 'Composants', 'titre_page' => 'Gestion Composants', 'route' => 'composants.index', 'Topmenu_id' => 0, 'user_action' => 1, 'num_ordre' => 12, 'iconee' => 'menu-icon mdi mdi-cog-box', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['idMenu' => 34, 'libelleMenu' => 'Accessoires', 'titre_page' => 'Gestion Accessoires', 'route' => 'accessoires', 'Topmenu_id' => 0, 'user_action' => 1, 'num_ordre' => 13, 'iconee' => 'menu-icon mdi mdi-puzzle', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['idMenu' => 35, 'libelleMenu' => 'Kits', 'titre_page' => 'Gestion Kits', 'route' => 'kits', 'Topmenu_id' => 0, 'user_action' => 1, 'num_ordre' => 14, 'iconee' => 'menu-icon mdi mdi-package', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ----- SOCIETE (préservée) -----
        DB::table('societes')->insert([
            ['id' => 1, 'libelleSociete' => 'BECI BTP', 'avis' => 'RISQUE ACCEPTE', 'email' => '', 'contact' => '21 xx 65 23', 'adresse' => 'Cotonou', 'signature' => 'logo.png', 'duplicata' => 'duplicata.jpg', 'logo' => 'logo.png', 'piedpage' => 'Société 66 ', 'tauxAIB' => 3, 'tauxNonAIB' => 5, 'carec' => 0, 'periode' => '07-2024', 'primemin' => 1000, 'created_at' => '2024-08-05 00:34:14', 'updated_at' => null],
        ]);

        // ----- ACTION MENUS (anciennes + nouvelles) -----
        DB::table('action_menus')->insert([
            // Anciennes actions (IDs préservés pour kanths)
            ['id' => 6, 'Menu' => 7, 'action' => 'Ajouter un utilisateur', 'code_dev' => 'add_user', 'statut' => null, 'created_at' => '2021-11-02 10:37:07', 'updated_at' => '2021-11-02 10:37:07'],
            ['id' => 7, 'Menu' => 7, 'action' => 'Modifier un utilisateur', 'code_dev' => 'update_user', 'statut' => null, 'created_at' => '2021-11-02 12:19:37', 'updated_at' => '2021-11-02 12:19:37'],
            ['id' => 8, 'Menu' => 7, 'action' => 'Supprimer un utilisateur', 'code_dev' => 'delete_user', 'statut' => null, 'created_at' => '2021-11-02 12:20:34', 'updated_at' => '2021-11-02 12:20:34'],
            ['id' => 9, 'Menu' => 7, 'action' => "Réinitialiser mot de passe de l'utilisateur", 'code_dev' => 'reset_user', 'statut' => null, 'created_at' => '2021-11-02 12:22:31', 'updated_at' => '2021-11-02 12:22:31'],
            ['id' => 10, 'Menu' => 7, 'action' => "Statut de l'utilisateur", 'code_dev' => 'status_user', 'statut' => null, 'created_at' => '2021-11-02 12:27:07', 'updated_at' => '2021-11-02 12:27:07'],
            ['id' => 11, 'Menu' => 8, 'action' => "Ajouter un Rôle", 'code_dev' => 'add_role', 'statut' => null, 'created_at' => '2021-11-02 15:36:31', 'updated_at' => '2021-11-02 15:36:31'],
            ['id' => 12, 'Menu' => 8, 'action' => "Modifier un Rôle", 'code_dev' => 'update_role', 'statut' => null, 'created_at' => '2021-11-02 15:37:16', 'updated_at' => '2021-11-02 15:37:16'],
            ['id' => 13, 'Menu' => 8, 'action' => "Supprimer un Rôle", 'code_dev' => 'delete_role', 'statut' => null, 'created_at' => '2021-11-02 15:37:38', 'updated_at' => '2021-11-02 15:37:38'],
            ['id' => 14, 'Menu' => 8, 'action' => "Attribuer menu", 'code_dev' => 'menu_role', 'statut' => null, 'created_at' => '2021-11-02 15:38:15', 'updated_at' => '2021-11-02 15:38:15'],
            ['id' => 19, 'Menu' => 11, 'action' => "Ajouter Menu", 'code_dev' => 'add_menu', 'statut' => null, 'created_at' => '2021-11-03 06:34:41', 'updated_at' => '2021-11-03 06:34:41'],
            ['id' => 20, 'Menu' => 11, 'action' => "Modifier Menu", 'code_dev' => 'update_menu', 'statut' => null, 'created_at' => '2021-11-03 06:35:24', 'updated_at' => '2021-11-03 06:35:24'],
            ['id' => 21, 'Menu' => 11, 'action' => "Supprimer Menu", 'code_dev' => 'delete_menu', 'statut' => null, 'created_at' => '2021-11-03 06:35:54', 'updated_at' => '2021-11-03 06:35:54'],
            ['id' => 22, 'Menu' => 11, 'action' => "Gérer les actions Menu", 'code_dev' => 'manage_menu', 'statut' => null, 'created_at' => '2021-11-03 06:38:01', 'updated_at' => '2021-11-03 06:38:01'],
            ['id' => 23, 'Menu' => 20, 'action' => "Modifier Société", 'code_dev' => 'update_soc', 'statut' => null, 'created_at' => '2021-11-03 06:43:28', 'updated_at' => '2021-11-03 06:43:28'],

            // Nouvelles actions pour les nouveaux menus (IDs > 100 pour éviter conflicts)
            // Actifs (Menu 31)
            ['id' => 101, 'Menu' => 31, 'action' => 'Ajouter Actif', 'code_dev' => 'add_actif', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 102, 'Menu' => 31, 'action' => 'Modifier Actif', 'code_dev' => 'update_actif', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 103, 'Menu' => 31, 'action' => 'Supprimer Actif', 'code_dev' => 'delete_actif', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 104, 'Menu' => 31, 'action' => 'Affecter Actif à Projet', 'code_dev' => 'assign_actif', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],

            // Consommables (Menu 32)
            ['id' => 105, 'Menu' => 32, 'action' => 'Ajouter Consommable', 'code_dev' => 'add_cons', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 106, 'Menu' => 32, 'action' => 'Modifier Consommable', 'code_dev' => 'update_cons', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 107, 'Menu' => 32, 'action' => 'Supprimer Consommable', 'code_dev' => 'delete_cons', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 108, 'Menu' => 32, 'action' => 'Sortie Consommable pour Projet', 'code_dev' => 'assign_cons', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],

            // Composants (Menu 33)
            ['id' => 109, 'Menu' => 33, 'action' => 'Ajouter Composant', 'code_dev' => 'add_comp', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 110, 'Menu' => 33, 'action' => 'Modifier Composant', 'code_dev' => 'update_comp', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 111, 'Menu' => 33, 'action' => 'Supprimer Composant', 'code_dev' => 'delete_comp', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 112, 'Menu' => 33, 'action' => 'Associer Composant à Kit', 'code_dev' => 'assign_comp', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],

            // Accessoires (Menu 34)
            ['id' => 113, 'Menu' => 34, 'action' => 'Ajouter Accessoire', 'code_dev' => 'add_acc', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 114, 'Menu' => 34, 'action' => 'Modifier Accessoire', 'code_dev' => 'update_acc', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 115, 'Menu' => 34, 'action' => 'Supprimer Accessoire', 'code_dev' => 'delete_acc', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 116, 'Menu' => 34, 'action' => 'Affecter Accessoire à Actif', 'code_dev' => 'assign_acc', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],

            // Kits (Menu 35)
            ['id' => 117, 'Menu' => 35, 'action' => 'Créer Kit', 'code_dev' => 'add_kit', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 118, 'Menu' => 35, 'action' => 'Modifier Kit', 'code_dev' => 'update_kit', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 119, 'Menu' => 35, 'action' => 'Supprimer Kit', 'code_dev' => 'delete_kit', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 120, 'Menu' => 35, 'action' => 'Décomposer Kit', 'code_dev' => 'decompose_kit', 'statut' => '0', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ----- ACTION MENU ACCES (anciens droits kanths préservés + nouveaux droits) -----
        // D'abord, les anciens droits pour kanths (rôle 1) - exactement comme avant
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

        // Maintenant, ajoutons les droits pour les NOUVEAUX menus au développeur (kanths - rôle 1)
        // Il aura accès à tout
        $newMenusForDev = [31, 32, 33, 34, 35]; // Actifs, Consommables, Composants, Accessoires, Kits
        foreach ($newMenusForDev as $menu) {
            // Accès au menu
            DB::table('action_menu_acces')->insert([
                'Menu' => $menu,
                'Role' => 1,
                'ActionMenu' => 0,
                'statut' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Accès à toutes les actions du menu
            $actionsForMenu = DB::table('action_menus')->where('Menu', $menu)->pluck('id');
            foreach ($actionsForMenu as $actionId) {
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

        // ----- DROITS POUR LES NOUVEAUX RÔLES -----

        // MAGASINIER (Rôle 2) - Accès à Actifs, Consommables, Composants, Accessoires, Kits
        $magMenus = [31, 32, 33, 34, 35];
        foreach ($magMenus as $menu) {
            // Accès au menu
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

        // CHEF DE PROJET (Rôle 3) - Accès limité : seulement les actions d'affectation/assignation
        $chefActions = [
            104, // Affecter Actif à Projet
            108, // Sortie Consommable pour Projet
            112, // Associer Composant à Kit
            116, // Affecter Accessoire à Actif
            120  // Décomposer Kit
        ];

        foreach ($chefActions as $actionId) {
            $menu = DB::table('action_menus')->where('id', $actionId)->value('Menu');

            // Accès au menu si pas déjà accordé
            $existingMenuAccess = DB::table('action_menu_acces')
                ->where('Menu', $menu)
                ->where('Role', 3)
                ->where('ActionMenu', 0)
                ->exists();

            if (!$existingMenuAccess) {
                DB::table('action_menu_acces')->insert([
                    'Menu' => $menu,
                    'Role' => 3,
                    'ActionMenu' => 0,
                    'statut' => '0',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Accès à l'action spécifique
            DB::table('action_menu_acces')->insert([
                'Menu' => $menu,
                'Role' => 3,
                'ActionMenu' => $actionId,
                'statut' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // ANALYSTE (Rôle 4) - Accès en lecture seule aux traces + consultation des autres menus
        // Accès aux traces
        DB::table('action_menu_acces')->insert([
            'Menu' => 30,
            'Role' => 4,
            'ActionMenu' => 0,
            'statut' => '0',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Accès consultation aux nouveaux menus (sans actions)
        $analyseMenus = [31, 32, 33, 34, 35];
        foreach ($analyseMenus as $menu) {
            DB::table('action_menu_acces')->insert([
                'Menu' => $menu,
                'Role' => 4,
                'ActionMenu' => 0,
                'statut' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
