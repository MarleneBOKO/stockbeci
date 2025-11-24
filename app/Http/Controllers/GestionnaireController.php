<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User as Users;
use App\Models\Role;
use DB;
use App\Models\Trace;


class GestionnaireController extends Controller
{
    //
    public static function dash(){
       
        // Statistiques Actifs
        $totalActifs = \App\Models\Actif::count();
        $actifsDeployes = \App\Models\Actif::where('statut', 'Déployé')->count();
        $actifsDisponibles = \App\Models\Actif::where('statut', 'Liste')->orWhere('statut', 'Prêt à être déployé')->count();
        $actifsMaintenance = \App\Models\Actif::where('statut', 'En maintenance')->count();

        // Statistiques Projets
        $projetsEnCours = \App\Models\Projet::where('statut', 'en_cours')->count();
        $projetsTermines = \App\Models\Projet::where('statut', 'termine')->count();

        // Alertes Stock (Consommables avec stock faible, ex: < 5)
        // Idéalement, le seuil devrait être défini dans la table consommables ou une config
        $consommablesAlerte = \App\Models\Consommable::where('quantite', '<=', 5)->get();
        $nbAlertes = $consommablesAlerte->count();

        // Derniers mouvements (Traces)
        $dernieresTraces = Trace::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalActifs', 
            'actifsDeployes', 
            'actifsDisponibles', 
            'actifsMaintenance',
            'projetsEnCours', 
            'projetsTermines',
            'consommablesAlerte',
            'nbAlertes',
            'dernieresTraces'
        ));
    }

    public static function listusers(){

        $list = DB::table('users');
        $allRole =  Role::all();

        if(request('rec') == 1){
            if(request('check') != "" && request('check') != null){
                $list = $list->where('nom', 'like', '%'.request('check').'%')
                ->orwhere('prenom', 'like', '%'.request('check').'%')
                ->orwhere('login', 'like', '%'.request('check').'%')->paginate(20);
                return view("personnel.search", compact('list', 'allRole'));
            }else{
                $list = $list->paginate(20);
                return view("personnel.search", compact('list', 'allRole'));
            }
        }

        $list = $list->paginate(20);
        
        return view('personnel.listusers', compact('list', 'allRole'));
    }
    
    public static function deleteuser(Request $request){
        if (!in_array("delete_user", session("auto_action"))) {
            return view("vendor.error.649");
        }else{
            //Users::where('idUser', request('id'))->update(['statut' =>  "sup"]);
            $occurence = json_encode(Users::where('idUser', request('id'))->first());
            Users::where('idUser', request('id'))->delete();
            $info = "L'utilisateur est supprimé avec succès."; flash($info);
            TraceController::setTrace(
            "Vous avez supprimé le compte dont les informations sont les suivants : ".$occurence.".",
            session("utilisateur")->idUser);
            return Back();
        }
    }

    public static function reinitialiseruser(Request $request){
        if (!in_array("reset_user", session("auto_action"))) {
            return view("vendor.error.649");
        }else{
           Users::where('idUser', request('id'))->update(['password' =>  "com".sha1('123')."dste"]);
           $info = "Mot de passe de l'utilisateur est réintialisé avec succès."; flash($info); 
           // Sauvegarde de la trace
            TraceController::setTrace(
            "Vous avez réintialisé le mot de passe du compte de l'utilisateur ". Users::where('idUser', request('id'))->first()->nom." et dont l'identifiant est ".Users::where('idUser', request('id'))->first()->login.".",
            session("utilisateur")->idUser);
           return Back();
       }
    }

    public static function activeuser(Request $request){
        if (!in_array("status_user", session("auto_action"))) {
            return view("vendor.error.649");
        }else{
            Users::where('idUser', request('id'))->update(['statut' =>  "0"]);
            $info = "Le compte de l'utilisateur est activé avec succès."; flash($info); 
            // Sauvegarde de la trace
            TraceController::setTrace(
            "Vous avez activé le compte de l'utilisateur ". Users::where('idUser', request('id'))->first()->nom." et dont l'identifiant est ".Users::where('idUser', request('id'))->first()->login.".",
            session("utilisateur")->idUser);

            return Back();
        }
    }

    public static function desactiveuser(Request $request){
        if (!in_array("status_user", session("auto_action"))) {
            return view("vendor.error.649");
        }else{
           Users::where('idUser', request('id'))->update(['statut' =>  "1"]);
           $info = "Le compte de l'utilisateur est désactivé avec succès."; flash($info); 
           // Sauvegarde de la trace
            TraceController::setTrace(
            "Vous avez désactivé le compte de l'utilisateur ". Users::where('idUser', request('id'))->first()->nom." et dont l'identifiant est ".Users::where('idUser', request('id'))->first()->login.".",
            session("utilisateur")->idUser);

           return Back();
        }
    }

    public static function adduser(Request $request)
    {
        if (!in_array("add_user", session("auto_action"))) {
            return view("vendor.error.649");
        }else{
            if (GestionnaireController::ExisteUser(htmlspecialchars(trim($request->mail)))) {
                flash("L'utilisateur que vous voulez ajouté existe déjà!! ")->error();
                return Back();
            }
            else{
                $add = new Users();
                $add->nom = htmlspecialchars(trim($request->nom));
                $add->prenom =  htmlspecialchars(trim($request->prenom));
                $add->sexe = htmlspecialchars(trim($request->sexe));
                $add->tel = htmlspecialchars(trim($request->tel));
                $add->mail = htmlspecialchars(trim($request->mail));
                $add->adresse = htmlspecialchars(trim($request->adress));
                $add->login = htmlspecialchars(trim($request->login));
                $add->password = "com".sha1("123")."dste";
                $add->Role = htmlspecialchars(trim($request->role));
                $add->Societe = 1;
                $add->other = htmlspecialchars(trim($request->autres));
                $add->user_action = session("utilisateur")->idUser;
                $add->action_save = 's';
                $add->auth = htmlspecialchars(trim($request->auth));
                $add->save();

                // Sauvegarde de la trace
                TraceController::setTrace(
                "Vous avez enregistré l'utilisateur dont le nom est ".$request->prenom." ".$request->nom.".",
                session("utilisateur")->idUser);

                flash("L'utilisateur est enregistré avec succès. ")->success();
                return Back();
            }
        }
    }

    public function getmodifyuser(Request $request)
    {
        if (!in_array("update_user", session("auto_action"))) {
            return view("vendor.error.649");
        }else{
            $allRole =  Role::all();
            $info = Users::where('idUser', request('id'))->first();
            return view('personnel.modifusers', compact('allRole', 'info'));
        }
    }

    public static function modifyuser(Request $request)
    {
        if (!in_array("update_user", session("auto_action"))) {
            return view("vendor.error.649");
        }else{
            $request->validate([
                    'login' => 'required|string', 
                ]);

            Users::where('idUser', request('id'))->update(
                    [
                        'nom' =>  htmlspecialchars(trim($request->nom)),
                        'prenom' =>  htmlspecialchars(trim($request->prenom)),
                        'sexe' =>  htmlspecialchars(trim($request->sexe)),
                        'tel' =>  htmlspecialchars(trim($request->tel)),
                        'mail' =>  htmlspecialchars(trim($request->mail)),
                        'adresse' =>  htmlspecialchars(trim($request->adress)),
                        'login' =>  htmlspecialchars(trim($request->login)),
                        'Role' =>  htmlspecialchars(trim($request->role)),
                        'other' => htmlspecialchars(trim($request->autres)),
                        'user_action' => session("utilisateur")->idUser,
                        'action_save' => 's',
                    ]);
            flash("L'utilisateur est modifié avec succès. ")->success();
            TraceController::setTrace(
            "Vous avez modifié le compte du personnel ".$request->nom." ".$request->prenom." .",
            session("utilisateur")->idUser);

            return redirect('/listusers');
        }
    }

    public static function ExisteUser($libelleemail){
        $user = Users::where('mail', $libelleemail)->first();
        if (isset($user) && $user->idUser != 0) return true; else return false;
    }
}
