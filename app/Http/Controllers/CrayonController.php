<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Crayon;
use Illuminate\Support\Facades\DB;

class CrayonController extends Controller
{
    function base64url_decode($data)
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }

    function decodeJWT(string $token, string $secret, string $expectedIss)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return null;

        list($b64Header, $b64Payload, $b64Signature) = $parts;
        $header = json_decode($this->base64url_decode($b64Header), true);
        $payload = json_decode($this->base64url_decode($b64Payload), true);
        $signature = $this->base64url_decode($b64Signature);

        //Vérifier la signature
        $segments[] = $b64Header;
        $segments[] = $b64Payload;
        $signerInput = implode('.', $segments);
        $correctSignature = hash_hmac('sha256', $signerInput, $secret, true);
        if (!$correctSignature == $signature) return null;

        if (!is_array($header) || ($header['alg'] ?? '') !== 'HS256') return null;
        if (!is_array($payload)) return null;


        // Vérifications standard
        if (isset($payload['exp']) && time() >= $payload['exp']) return null;
        if (($payload['iss'] ?? '') !== $expectedIss) return null;

        return $payload;
    }


    // Afficher la liste des crayons
    public function index()
    {
        $crayons = Crayon::all();
        return view('crayons.index', compact('crayons'));
    }

    public function donothing(\App\Services\RSAService $rsa)
    {
        $rsa->doUselessRSAWork();
    }

    // Afficher le formulaire de création de crayon
    public function create()
    {
        return view('crayons.create');
    }

    // Enregistrer un nouveau crayon dans la base de données
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'quantite' => 'required|integer|min:0',
        ]);

        Crayon::create([
            'nom' => $request->input('nom'),
            'quantite' => $request->input('quantite'),
        ]);

        return redirect('/crayons')->with('success', 'Crayon ajouté avec succès');
    }

    // Afficher le formulaire de modification de crayon
    public function edit($id)
    {
        try {
            session_start();
        } catch (\Exception) {
        }
        //CORRECTIONS DES AUTORISATIONS
        if (isset($_COOKIE['auth_token'])) {
            $crayon = Crayon::findOrFail($id);
            return view('crayons.edit', compact('crayon'));

        } else {
            return redirect('/');
        }
    }

    // Mettre à jour les informations du crayon dans la base de données
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required',
            'quantite' => 'required|integer|min:0',
        ]);

        $crayon = Crayon::findOrFail($id);
        $crayon->update([
            'nom' => $request->input('nom'),
            'quantite' => $request->input('quantite'),
        ]);

        return redirect('/crayons')->with('success', 'Crayon mis à jour avec succès');
    }

    // Supprimer un crayon de la base de données
    public function destroy($id)
    {
        try {
            session_start();
        } catch (\Exception) {
        }
        //CORRECTIONS DES AUTORISATIONS
        if (isset($_COOKIE['auth_token'])) {
            $crayon = Crayon::findOrFail($id);
            $crayon->delete();
            return redirect('/crayons')->with('success', 'Crayon supprimé avec succès');

        } else {
            return redirect('/');
        }
    }

    public function search(Request $request)
    {
        $crayons = DB::table('crayons')
            //CORRECTION POUR L'INJECTION SQL
            ->where('nom', 'like', '%' . $request->texte . '%')
            ->get();
        return view('crayons.index', compact('crayons'));
    }
}
