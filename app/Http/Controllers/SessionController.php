<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{

    // Afficher le formulaire de création de crayon
    public function login(Request $request)
    {
        if(DB::table('users')
            ->where('email', '=', $request->input('email'))
            ->where('password', '=', $request->input('password'))
            ->first() != null){
            try {
                session_start();
            }
            catch (\Exception){}
            $_SESSION['login'] = 'true';
            return redirect('/');
        }
        else{
            return view('login');
        }
    }

    // Enregistrer un nouveau crayon dans la base de données
    public function register(Request $request)
    {
       User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);

        return redirect('/login');
    }

    // Afficher le formulaire de modification de crayon
    public function logout()
    {
        try {
            session_start();
        }
        catch (\Exception){}
        $_SESSION['login'] = 'false';
        return redirect('/');
    }
}
