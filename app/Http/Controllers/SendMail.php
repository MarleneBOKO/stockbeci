<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\Send;

class SendMail extends Controller
{
    
    public static function sendnotificationfin($destinataire, $mails, $Subject, $data)
    {
        $users = [$destinataire];
        $view = "mail.notificationfin";

        Mail::to($users)->bcc($mails)->queue(new Send($data, $Subject, $view));  
    }
    
    public function test(){
        SendMail::sendnotificationErreurTaux("emmanueldjidagbagba@gmail.com", "TEST", []);
    }
}
