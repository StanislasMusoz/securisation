<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SessionController extends Controller
{
    //CORRECTION: AUTHENTIFICATION AVEC JWT
    function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    function encodeJWT(array $payload, string $secret): string {
        $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        $segments = [];
        $segments[] = $this->base64url_encode(json_encode($header));
        $segments[] = $this->base64url_encode(json_encode($payload));
        $signingInput = implode('.', $segments);
        $signature = hash_hmac('sha256', $signingInput, $secret, true);
        $segments[] = $this->base64url_encode($signature);
        return implode('.', $segments);
    }



    // Afficher le formulaire de création de crayon
    public function login(Request $request)
    {
        //CORRECTION: AUTHENTIFICATION AVEC JWT
        $JWT_SECRET = getenv("JWT_SECRET");
        $user = DB::table('users')->where('email', '=', $request->input('email'))->first();
        if (password_verify($request->input('password'), $user->password)) {

            try {
                $email = htmlentities($user->email);
                $payload = [
                    'iat' => time(),
                    'exp' => time() + 3600,
                    'sub' => $email,
                ];
                $jwt = $this->encodeJWT($payload, $JWT_SECRET);
                setcookie("auth_token",$jwt,[
                    "expires" => time()+3600,
                    "path" => "/",
                    "secure" => false,
                    "httponly" => false,
                    "samesite" => "Lax"
                ]);
                //session_start();
            } catch (\Exception) {
            }
            $_SESSION['login'] = 'true';
            return redirect('/');
        } else {
            return view('login');
        }
    }

    // Enregistrer un nouveau crayon dans la base de données
    public function register(Request $request)
    {
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            //CORRECTION: INFORMATIONS TROP VERBEUX
            'password' => password_hash($request->input('password'), PASSWORD_DEFAULT)
        ]);

        return redirect('/login');
    }

    // Afficher le formulaire de modification de crayon
    public function logout()
    {
        try {
            setcookie("auth_token",null);
            session_start();
        } catch (\Exception) {
        }
        $_SESSION['login'] = 'false';
        return redirect('/');
    }
}
