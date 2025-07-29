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

});
