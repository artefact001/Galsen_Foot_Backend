
// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use App\Models\User;
// use Illuminate\Support\Facades\Hash;
// use Tymon\JWTAuth\Facades\JWTAuth;
// use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validation des données
        $validator = validator($request->all(), [
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only(['email', 'password']);

        // Tentative de connexion avec les informations d'identification
        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Identifiants de connexion invalides',
            ], 401);
        }
        // Obtenez les rôles de l'utilisateur
        $user = auth()->guard('api')->user();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->guard('api')->user(),
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60, // Expiration du token en secondes
        ]);
    }

    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:8',
    //         'role_id' => 'required|exists:roles,id',
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     $user->roles()->attach($request->role_id);

    //     $token = JWTAuth::fromUser($user);

    //     return response()->json(['token' => $token], 201);
    // }
// }





<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    //Methode pour gerer la connexion

        public function login(Request $request)
        {
            // Validation des données
            $validator = validator(
                $request->all(),
                [
                    'email' => 'required|email|string',
                    'password' => 'required|string|min:8',
                ]
            );
            // Si les données ne sont pas valides, renvoyer les erreurs
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
            // Si les données sont valides, authentifier l'utilisateur
            $credentials = $request->only('email', 'password');
            $token = auth()->attempt($credentials);

                    // Récupérer l'utilisateur authentifié
            $user = auth()->user();

            // Récupérer les rôles de l'utilisateur
            $roles = $user->getRoleNames(); // Méthode Spatie pour obtenir les noms de rôles

            // Si les informations de connexion ne sont pas correctes, renvoyer une erreur 401  
            if (!$token) {
                return response()->json(['message' => 'Information de connexion incorrectes'], 401);
            }
            // Renvoyer le token d'authentification
            return response()->json([
                "access_token" => $token,
                "token_type" => "bearer",
                "user" => auth()->user(),
                "roles" => $roles,
                "expires_in" => env("JwT_TTL") * 60  . 'seconds'
            ]);
        }

    //methode pour la déconnexion

        public function logout()
        {
            // Supprimer le jeton d'authentification
            auth()->logout();
            // Renvoyer une réponse avec un message de succès
            return response()->json(['message' => 'Déconnexion réussie']);
        }

        //methode pour rafraichir
        public function refresh()
        {
            // Renouveler le jeton d'authentification
            $token = auth()->refresh();
            // Renvoyer le nouveau jeton d'authentification
            return response()->json([
                "access_token" => $token,
                "token_type" => "bearer",
                "user" => auth()->user(),
                "expires_in" => env("JWT_TTL") * 240  .'seconds'
            ]);
        }
    
    //methode pour récuperer les informations du user
    public function profile() {
        // Récupérer l'utilisateur authentifié
        $user = auth()->user();
    
        // Récupérer les rôles de l'utilisateur
        $roles = $user->getRoleNames(); // Méthode Spatie pour obtenir les noms de rôles
    
        // Variable pour stocker le prénom
        $prenom = null;
    
        // Vérification des rôles et récupération du prénom dans la table correspondante
        if ($roles->contains('zone')) {
            // Si l'utilisateur est un zone
            $zone = $user->zone; // Relation avec la table 'zones'
            $prenom = $zone ? $zone->prenom : null;
        }
        // elseif ($roles->contains('parent')) {
            // Si l'utilisateur est un parent
            // $parent = $user->parent; // Relation avec la table 'parents'
            // $prenom = $parent ? $parent->prenom : null;
        // }
        elseif ($roles->contains('equipe')) {
            // Si l'utilisateur est un élève
            $equipe = $user->equipe; // Relation avec la table 'equipes'
            $prenom = $equipe ? $equipe->prenom : null;
        }
    
        // Renvoyer les informations du user avec ses rôles et le prénom
        return response()->json([
            "user" => $user,
            "roles" => $roles,
            "prenom" => $prenom
        ]);
    }
    
}