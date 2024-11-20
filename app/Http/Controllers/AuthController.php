<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    // public function login(Request $request)
    // {
    //     // Validation des données
    //     $validator = validator($request->all(), [
    //         'email' => ['required', 'email', 'string'],
    //         'password' => ['required', 'string'],
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     $credentials = $request->only(['email', 'password']);

    //     // Tentative de connexion avec les informations d'identification
    //     if (!$token = auth()->guard('api')->attempt($credentials)) {
    //         return response()->json([
    //             'message' => 'Identifiants de connexion invalides',
    //         ], 401);
    //     }
    //     // Obtenez les rôles de l'utilisateur
    //     $user = auth()->guard('api')->user();

    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'user' => auth()->guard('api')->user(),
    //         'expires_in' => auth()->guard('api')->factory()->getTTL() * 60, // Expiration du token en secondes
    //     ]);
    // }

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
        // Pour le débogage
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return response()->json([
                'message' => 'Mot de passe incorrect.',
            ], 401);
        }
        return response()->json([
            'message' => 'Utilisateur non trouvé.',
        ], 401);
    }

    // Obtenez les rôles de l'utilisateur
    $user = auth()->guard('api')->user();

    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'user' => $user,
        'expires_in' => auth()->guard('api')->factory()->getTTL() * 60,
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
}
