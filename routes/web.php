<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GestionnaireController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TraceController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SocieteController;
use App\Http\Controllers\ActifController;
use App\Http\Controllers\ComposantController;
use App\Http\Controllers\ConsommableController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\EmplacementController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\FabricantController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\EtatController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\AccessoireController;
use App\Http\Controllers\KitController;



/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/
Route::get('/cach', function () {
    Artisan::call('config:cache');
});

Route::get('/maintenance', function () {
    return view('vendor.error.501');
});

Route::get('/login', [LoginController::class, 'login'])->name('log');
Route::get('/modifier-mot-de-passe', [LoginController::class, 'passmodif'])->name('pas');
Route::post('/login', [LoginController::class, 'authenticate']);

Route::fallback(function () {
    return view('vendor.error.404');
});

/*
|--------------------------------------------------------------------------
| Routes protégées par middleware AuthECOM
|--------------------------------------------------------------------------
*/
Route::middleware([App\Http\Middleware\AuthECOM::class])->group(function () {

    // Dashboard
    Route::get('/dashboard', [GestionnaireController::class, 'dash'])->name('home');

    /*
    |--------------------------------------------------------------------------
    | Utilisateurs
    |--------------------------------------------------------------------------
    */
    Route::get('/listusers', [GestionnaireController::class, 'listusers'])->name('listU');
    Route::get('/deconnecte', [LoginController::class, 'logout'])->name('offU');
    Route::get('/delete-users-{id}', [GestionnaireController::class, 'deleteuser'])->name('DelU');
    Route::post('/modif-users', [GestionnaireController::class, 'modifyuser'])->name('ModifU');
    Route::get('/modif-users-{id}', [GestionnaireController::class, 'getmodifyuser'])->name('GetModifU');
    Route::post('/add-users', [GestionnaireController::class, 'adduser'])->name('AddU');
    Route::get('/reinitialiser-users-{id}', [GestionnaireController::class, 'reinitialiseruser'])->name('ReiniU');
    Route::get('/desactivé-users-{id}', [GestionnaireController::class, 'desactiveuser'])->name('ActiverU');
    Route::get('/activé-users-{id}', [GestionnaireController::class, 'activeuser'])->name('DesactiverU');
    Route::get('/profil', [ProfilController::class, 'getprofil'])->name('GPU');
    Route::post('/profil', [ProfilController::class, 'setprofil'])->name('SPU');

    /*
    |--------------------------------------------------------------------------
    | Rôles
    |--------------------------------------------------------------------------
    */
    Route::get('/listrole', [RoleController::class, 'listrole'])->name('listR');
    Route::get('/delete-roles-{id}', [RoleController::class, 'deleterole'])->name('DelR');
    Route::post('/modif-roles', [RoleController::class, 'modifrole'])->name('ModifR');
    Route::get('/modif-roles-{id}', [RoleController::class, 'getmodifrole'])->name('GetModifR');
    Route::get('/menu-roles-{id}', [RoleController::class, 'getmenurole'])->name('GetMenuAttr');
    Route::post('/menu-roles', [RoleController::class, 'setmenurole'])->name('MenuAttr');
    Route::post('/add-roles', [RoleController::class, 'addrole'])->name('AddR');

    /*
    |--------------------------------------------------------------------------
    | Traces
    |--------------------------------------------------------------------------
    */
    Route::get('/listtrace', [TraceController::class, 'getlist'])->name('listTrace');

    /*
    |--------------------------------------------------------------------------
    | Menus
    |--------------------------------------------------------------------------
    */
    Route::get('/listmenu', [MenuController::class, 'getmenu'])->name('listM');
    Route::post('/menu', [MenuController::class, 'setmenu'])->name('AddMenu');
    Route::get('/delete-menu-{id}', [MenuController::class, 'delmenu'])->name('DelMenu');
    Route::post('/modif-menu', [MenuController::class, 'setmodifmenu'])->name('ModifMenu');
    Route::post('/actions', [MenuController::class, 'setactionmenu'])->name('ActionMenu');
    Route::get('/modif-menu-{id}', [MenuController::class, 'getmodifmenu'])->name('GetModifMenu');

    /*
    |--------------------------------------------------------------------------
    | Sociétés
    |--------------------------------------------------------------------------
    */
    Route::get('/listsoc', [SocieteController::class, 'getsoc'])->name('listSoc');
    Route::post('/soc', [SocieteController::class, 'setsoc'])->name('AddSoc');







    Route::prefix('actifs')->group(function () {
        Route::get('/', [ActifController::class, 'index'])->name('actifs');
        Route::post('/', [ActifController::class, 'store'])->name('actifs.store');
        Route::get('/{id}', [ActifController::class, 'show']);
        Route::put('/{id}', [ActifController::class, 'update']);
        Route::delete('/{id}', [ActifController::class, 'destroy']);

        // Fonctionnalités spécifiques
        Route::post('/{id}/affecter-projet', [ActifController::class, 'affecterProjet'])->name('actifs.affecterProjet');
        Route::put('/{idActif}/changer-statut/{statut}', [ActifController::class, 'changerStatut']);
        Route::get('/search', [ActifController::class, 'search'])->name('actifs.search');

    });

    Route::prefix('accessoires')->group(function () {
        Route::get('/', [AccessoireController::class, 'index'])->name('accessoires'); // pour afficher
        Route::post('/', [AccessoireController::class, 'store'])->name('accessoires.store'); // pour ajouter
        Route::get('/{id}', [AccessoireController::class, 'show'])->name('accessoires.show');
        Route::put('/{id}', [AccessoireController::class, 'update'])->name('accessoires.update');
        Route::delete('/{id}', [AccessoireController::class, 'destroy'])->name('accessoires.destroy');
        Route::get('/accessoires/search', [AccessoireController::class, 'search'])->name('accessoires.search');

        // Associer un accessoire à un actif
        Route::put('/{idAccessoire}/associer-actif/{idActif}', [AccessoireController::class, 'associerActif'])->name('accessoires.associer');
    });

    Route::prefix('projets')->group(function () {
        Route::get('/', [ProjetController::class, 'index'])->name('projets.index');
        Route::get('/create', [ProjetController::class, 'create'])->name('projets.create');
        Route::post('/', [ProjetController::class, 'store'])->name('projets.store');
        Route::get('/{projet}/edit', [ProjetController::class, 'edit'])->name('projets.edit');
        Route::put('/{projet}', [ProjetController::class, 'update'])->name('projets.update');
        Route::delete('/{projet}', [ProjetController::class, 'destroy'])->name('projets.destroy');

        Route::put('/{projet}/toggle', [ProjetController::class, 'toggleActif'])->name('projets.toggle');

        // ✅ Route pour afficher le formulaire d’attribution (si modal ou page)
        Route::get('{projet}/assign', [ProjetController::class, 'showAssignForm'])->name('projets.assign.form');

        // ✅ Route pour enregistrer l’attribution générale
        Route::post('{projet}/assign', [ProjetController::class, 'assignItems'])->name('projets.assign');
        Route::post('/projets/{projet}/assign', [ProjetController::class, 'assignItem'])->name('projets.assignItem');

        // ✅ 🆕 Route spécifique pour assignation d’un ACTIF (modal)
        Route::post('/{projet}/assign-actif', [ProjetController::class, 'assignActif'])->name('projets.assignActif');
    });


    Route::prefix('categories')->group(function () {
        Route::get('/', [CategorieController::class, 'index'])->name('categories.index'); // Liste
        Route::get('/create', [CategorieController::class, 'create'])->name('categories.create'); // Formulaire création
        Route::post('/', [CategorieController::class, 'store'])->name('categories.store'); // Enregistrer
        Route::get('/{categorie}/edit', [CategorieController::class, 'edit'])->name('categories.edit'); // Formulaire édition
        Route::put('/{categorie}', [CategorieController::class, 'update'])->name('categories.update'); // Mettre à jour
        Route::delete('/{categorie}', [CategorieController::class, 'destroy'])->name('categories.destroy'); // Supprimer

        // Activation / Désactivation

        // Recherche
        Route::get('/search', [CategorieController::class, 'search'])->name('categories.search');
    });



Route::prefix('kits')->group(function () {
    Route::get('/', [KitController::class, 'index'])->name('kits'); // Liste
    Route::get('/create', [KitController::class, 'create'])->name('kits.create'); // Formulaire création
    Route::post('/', [KitController::class, 'store'])->name('kits.store'); // Enregistrer
    Route::get('/{kit}/edit', [KitController::class, 'edit'])->name('kits.edit'); // Formulaire édition
    Route::put('/{kit}', [KitController::class, 'update'])->name('kits.update'); // Mettre à jour
    Route::delete('/{kit}', [KitController::class, 'destroy'])->name('kits.destroy'); // Supprimer
        Route::get('/kits/search', [KitController::class, 'search'])->name('kits.search');
        Route::post('/{kit}/affecter-projet', [KitController::class, 'affecterProjet'])->name('kits.affecterProjet');
    });

    Route::prefix('consommables')->group(function () {
        Route::get('/', [ConsommableController::class, 'index'])->name('consommables.index');
        Route::get('/search', [ConsommableController::class, 'search'])->name('consommables.search');
        Route::post('/', [ConsommableController::class, 'store'])->name('consommables.store');
        Route::put('/{id}', [ConsommableController::class, 'update'])->name('consommables.update');
        Route::delete('/{id}', [ConsommableController::class, 'destroy'])->name('consommables.destroy');
        Route::post('/{id}/entree', [ConsommableController::class, 'entreeStock'])->name('consommables.entree');
        Route::post('/{id}/sortie', [ConsommableController::class, 'sortieStock'])->name('consommables.sortie');

        Route::post('/{id}/affecter-projet', [ConsommableController::class, 'affecterProjet'])->name('consommables.affecterProjet');

    });



Route::prefix('composants')->group(function () {
    Route::get('/', [ComposantController::class, 'index'])->name('composants.index');
    Route::get('/create', [ComposantController::class, 'create'])->name('composants.create');
    Route::post('/', [ComposantController::class, 'store'])->name('composants.store');
    Route::get('/{composant}/edit', [ComposantController::class, 'edit'])->name('composants.edit');
    Route::put('/{composant}', [ComposantController::class, 'update'])->name('composants.update');
    Route::delete('/{composant}', [ComposantController::class, 'destroy'])->name('composants.destroy');

    // Entrée / sortie stock
    Route::post('/{composant}/entree', [ComposantController::class, 'entree'])->name('composants.entree');
    Route::post('/{composant}/sortie', [ComposantController::class, 'sortie'])->name('composants.sortie');
        Route::post('/{composant}/affecter-projet', [ComposantController::class, 'affecterProjet'])->name('composants.affecterProjet');

        // Recherche AJAX
    Route::get('/search', [ComposantController::class, 'search'])->name('composants.search');
});




Route::prefix('fournisseurs')->group(function () {
    Route::get('/', [FournisseurController::class, 'index'])->name('fournisseurs');
    Route::post('/', [FournisseurController::class, 'store'])->name('fournisseurs.store');
    Route::put('/{fournisseur}', [FournisseurController::class, 'update'])->name('fournisseurs.update');
    Route::delete('/{fournisseur}', [FournisseurController::class, 'destroy'])->name('fournisseurs.destroy');
    Route::get('/search', [FournisseurController::class, 'search'])->name('fournisseurs.search');
});

Route::prefix('fabricants')->group(function () {
    Route::get('/', [FabricantController::class, 'index'])->name('fabricants');
    Route::post('/', [FabricantController::class, 'store'])->name('fabricants.store');
    Route::put('/{fabricant}', [FabricantController::class, 'update'])->name('fabricants.update');
    Route::delete('/{fabricant}', [FabricantController::class, 'destroy'])->name('fabricants.destroy');
    Route::get('/search', [FabricantController::class, 'search'])->name('fabricants.search');
});




// Liste de tous les emplacements
Route::get('/emplacements', [EmplacementController::class, 'index'])->name('emplacements.index');

// Créer un nouvel emplacement
Route::post('/emplacements', [EmplacementController::class, 'store'])->name('emplacements.store');

// Voir un emplacement précis (par son ID)
Route::get('/emplacements/{id}', [EmplacementController::class, 'show'])->name('emplacements.show');

// Mettre à jour un emplacement
Route::put('/emplacements/{id}', [EmplacementController::class, 'update'])->name('emplacements.update');
Route::patch('/emplacements/{id}', [EmplacementController::class, 'update']); // optionnel pour PATCH

// Supprimer un emplacement
Route::delete('/emplacements/{id}', [EmplacementController::class, 'destroy'])->name('emplacements.destroy');


// Liste de tous les modèles
Route::get('/models', [ModelController::class, 'index'])->name('models.index');

// Créer un nouveau modèle
Route::post('/models', [ModelController::class, 'store'])->name('models.store');

// Voir un modèle précis (par son ID)
Route::get('/models/{id}', [ModelController::class, 'show'])->name('models.show');

// Mettre à jour un modèle
Route::put('/models/{id}', [ModelController::class, 'update'])->name('models.update');
Route::patch('/models/{id}', [ModelController::class, 'update']); // optionnel pour PATCH

// Supprimer un modèle
Route::delete('/models/{id}', [ModelController::class, 'destroy'])->name('models.destroy');


});
