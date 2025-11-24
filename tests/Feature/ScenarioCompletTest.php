<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Projet;
use App\Models\Actif;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;

class ScenarioCompletTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // On peuple la base de données de test
        $this->seed(\Database\Seeders\InitialDatabaseSeeder::class);
    }

    public function test_scenario_complet_attribution()
    {
        echo "\n--- Démarrage du Scénario de Test (SQLite In-Memory) ---\n";

        // 1. Connexion
        echo "1. Tentative de connexion avec 'kanths'...\n";
        $response = $this->post('/login', [
            'libelle' => 'connexion',
            'login' => 'kanths',
            'password' => 'password123',
        ]);

        // Vérification de la redirection (connexion réussie)
        // Vérification de la redirection (connexion réussie)
        $response->assertRedirect('dashboard');
        $response->assertSessionHas('utilisateur'); // Vérifie que la session contient l'utilisateur
        echo "   [OK] Connexion réussie.\n";

        // Récupération de l'utilisateur pour la suite
        $user = User::where('login', 'kanths')->first();
        
        // Préparation des données de session pour simuler l'état connecté
        // Le middleware AuthECOM vérifie 'utilisateur' et 'DateConnexion'
        $sessionData = [
            'utilisateur' => $user,
            'DateConnexion' => date('Y-m-d'),
            'auto_action' => ['add_actif', 'add_cons', 'assign_actif', 'add_projet', 'assign_projet'], // Droits simulés
        ];

        // 2. Création d'un Projet
        echo "2. Création du projet 'Projet Test Auto'...\n";
        $refProjet = 'PROJ-' . time();
        $response = $this->withSession($sessionData)->post(route('projets.store'), [
            'refprojet' => $refProjet,
            'intitule' => 'Projet Test Auto',
            'date_debut' => date('Y-m-d'),
            'statut' => 'en_cours'
        ]);
        
        $response->assertRedirect(route('projets.index'));
        $projet = Projet::where('refprojet', $refProjet)->first();
        $this->assertNotNull($projet, "Le projet n'a pas été créé en base.");
        echo "   [OK] Projet créé (ID: {$projet->id}).\n";

        // 3. Création d'un Actif
        echo "3. Création de l'actif 'Actif Test Auto'...\n";
        $inventaire = 'INV-' . time();
        $response = $this->withSession($sessionData)->post(route('actifs.store'), [
            'nom_actif' => 'Actif Test Auto',
            'inventaire' => $inventaire,
            'model_id' => 1, // On suppose qu'il y a des modèles (seedés)
            'emplacement_id' => 1, // On suppose qu'il y a des emplacements
            'statut' => 'Liste', // Disponible
        ]);

        $response->assertRedirect(route('actifs.index'));
        $actif = Actif::where('inventaire', $inventaire)->first();
        $this->assertNotNull($actif, "L'actif n'a pas été créé en base.");
        echo "   [OK] Actif créé (ID: {$actif->id}) avec statut : {$actif->statut}.\n";

        // 4. Attribution de l'Actif au Projet
        echo "4. Attribution de l'actif au projet...\n";
        $response = $this->withSession($sessionData)->post(route('projets.assignActif', $projet->id), [
            'actif_id' => $actif->id
        ]);

        $response->assertRedirect(route('projets.index'));
        
        // Recharger l'actif pour vérifier les changements
        $actif->refresh();
        
        $this->assertEquals($projet->id, $actif->projet_id, "L'actif n'est pas lié au projet.");
        $this->assertEquals('Déployé', $actif->statut, "Le statut de l'actif n'est pas 'Déployé'.");
        echo "   [OK] Actif attribué et statut mis à jour vers 'Déployé'.\n";

        // Nettoyage (Optionnel, pour ne pas polluer la BDD)
        echo "5. Nettoyage des données de test...\n";
        // Pas besoin de delete manuel avec RefreshDatabase et SQLite memory, tout est effacé à la fin
        echo "   [OK] Données nettoyées automatiquement.\n";

        echo "--- Scénario terminé avec SUCCÈS ---\n";
    }
}
