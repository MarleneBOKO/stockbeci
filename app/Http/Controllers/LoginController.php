<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User as Users;
use DB;
use Auth;

class LoginController extends Controller
{
    public static function login()
    {
        if (request('f') != null) {
            $error = "Identifiant ou mot de passe incorrect.";
            flash($error)->error();
            return back();
        }

        if (request('d') != null) {
            $user = Users::where('login', request('d'))->first();

            if (!$user) {
                $error = "Identifiant n'existe pas sur la plateforme.";
                flash($error)->error();
                return back();
            }

            if ($user->statut == "1") {
                $error = "Votre compte n'est pas activé. Veuillez contacter l'administrateur.";
                flash($error)->error();
                return back();
            }

            Session::put('utilisateur', $user);

            // Menu principal
            $allmenu_autoriser = DB::table('action_menu_acces')
                ->join('menus', "menus.idMenu", "=", "action_menu_acces.Menu")
                ->select('Menu')
                ->where('ActionMenu', 0)
                ->where('Role', $user->Role)
                ->where('Topmenu_id', 0)
                ->where('action_menu_acces.statut', 0)
                ->orderBy('menus.created_at', 'desc')
                ->get();
            $array = array();
            foreach ($allmenu_autoriser as $all) {
                if (!in_array($all->Menu, $array))
                    array_push($array, $all->Menu);
            }

            // Sous-menu
            $allmenu_sous = DB::table('action_menu_acces')
                ->join('menus', "menus.idMenu", "=", "action_menu_acces.Menu")
                ->select('Menu')
                ->where('Role', $user->Role)
                ->where('Topmenu_id', '<>', 0)
                ->where('action_menu_acces.statut', 0)
                ->orderBy('num_ordre', 'ASC')
                ->get();
            $array_ss = array();
            foreach ($allmenu_sous as $all) {
                if (!in_array($all->Menu, $array_ss))
                    array_push($array_ss, $all->Menu);
            }

            // Actions
            $allaction_autoriser = DB::table('action_menu_acces')
                ->select('ActionMenu', 'Menu')
                ->where('Role', $user->Role)
                ->where('statut', 0)
                ->get();
            $allAction = array();
            foreach ($allaction_autoriser as $value) {
                if ($value->ActionMenu != 0) {
                    $all_act = DB::table('action_menus')->where('id', $value->ActionMenu)->first()->code_dev;
                    array_push($allAction, $all_act);
                }
            }

            Session::put('auto_menu', $array);
            Session::put('auto_ss_menu', $array_ss);
            Session::put('auto_action', $allAction);
            Session::put('DateConnexion', date("Y-m-d"));

            TraceController::setTrace(
                $user->nom . " " . $user->prenom . "! Vous vous êtes bien connecté aujourd'hui " . date("d-m-Y à H:i:s"),
                $user->idUser
            );

            return redirect()->intended('dashboard');
        } else {
            Session::forget(['utilisateur', 'auto_ss_menu', 'montall', 'allreglement', 'qr', 'auto_action', 'DateConnexion']);
            $connect = "block";
            $modif = "none";
            return view('auth.login', compact("connect", "modif"));
        }
    }

    public static function passmodif()
    {
        return view('auth.password');
    }

    public static function authenticate(Request $request)
    {
        if ($request->libelle == "connexion") {
            $request->validate([
                'login' => 'required|string',
                'password' => 'required|string',
            ]);

            $user = Users::where('login', $request->login)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                $error = "Identifiant ou mot de passe incorrect.";
                flash($error)->error();
                return back();
            }

            if ($user->statut == "1") {
                $error = "Votre compte n'est pas activé. Veuillez contacter l'administrateur.";
                flash($error)->error();
                return back();
            }

            Session::put('utilisateur', $user);

            // Menu principal
            $allmenu_autoriser = DB::table('action_menu_acces')
                ->join('menus', "menus.idMenu", "=", "action_menu_acces.Menu")
                ->select('Menu')
                ->where('ActionMenu', 0)
                ->where('Role', $user->Role)
                ->where('Topmenu_id', 0)
                ->where('action_menu_acces.statut', 0)
                ->orderBy('num_ordre', 'ASC')
                ->get();
            $array = array();
            foreach ($allmenu_autoriser as $all)
                array_push($array, $all->Menu);

            // Sous-menu
            $allmenu_sous = DB::table('action_menu_acces')
                ->join('menus', "menus.idMenu", "=", "action_menu_acces.Menu")
                ->select('Menu')
                ->where('Role', $user->Role)
                ->where('Topmenu_id', '<>', 0)
                ->where('action_menu_acces.statut', 0)
                ->orderBy('num_ordre', 'ASC')
                ->get();
            $array_ss = array();
            foreach ($allmenu_sous as $all) {
                if (!in_array($all->Menu, $array_ss))
                    array_push($array_ss, $all->Menu);
            }

            // Actions
            $allaction_autoriser = DB::table('action_menu_acces')
                ->select('ActionMenu', 'Menu')
                ->where('Role', $user->Role)
                ->where('statut', 0)
                ->get();
            $allAction = array();
            foreach ($allaction_autoriser as $value) {
                if ($value->ActionMenu != 0) {
                    $all_act = DB::table('action_menus')->where('id', $value->ActionMenu)->first()->code_dev;
                    array_push($allAction, $all_act);
                }
            }

            Session::put('auto_menu', $array);
            Session::put('auto_ss_menu', $array_ss);
            Session::put('auto_action', $allAction);
            Session::put('DateConnexion', date("Y-m-d"));

            TraceController::setTrace(
                $user->nom . " " . $user->prenom . "! Vous vous êtes bien connecté aujourd'hui " . date("d-m-Y à H:i:s"),
                $user->idUser
            );

            return redirect()->intended('dashboard');
        }

        if ($request->libelle == "modifier") {
            $user = Users::where('login', $request->login)->first();

            if (!$user || !Hash::check($request->ancien_pass, $user->password)) {
                $error = "Identifiant ou ancien mot de passe incorrect!";
                flash($error)->error();
                return back();
            }

            if ($request->new_pass !== $request->confir_pass) {
                $error = "La confirmation du mot de passe est incorrecte!";
                flash($error)->error();
                return back();
            }

            $user->password = Hash::make($request->new_pass);
            $user->save();

            flash("Mot de passe modifié avec succès")->success();
            return back();
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::forget('utilisateur');
        return redirect('login');
    }
}
