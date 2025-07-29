<?php

namespace App\Http\Middleware;
use App\Http\FonctionControllers\VerificationController;
use App\Http\Model\Hierarchie;
use App\Http\Model\Commerciaux;
use App\Http\Model\Compteagent;
use Closure;

class AuthECOM
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        
        if (!isset(session("utilisateur")->idUser)) {
            return redirect('login');
        }elseif(session('DateConnexion') != date('Y-m-d') ){
            return redirect('login');
        }

        return $next($request);
    }
}
