<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\equipe;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use App\Mail\equipeCreated;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreequipeRequest;
use App\Http\Requests\UpdateequipeRequest;
use Illuminate\Support\Str;


class equipeController extends Controller
{

    //nombre total de equipe
    public function totalequipes()
    {

        // Compter le nombre total de prof 
        $totalEquipe = equipe::count();

        // Structurer la réponse en JSON
        return response()->json([
            'message' => 'Total d\'élèves pour l\'année en cours.',
            'total' => $totalEquipe,
            'status' => 200
        ]);
    }
    /**
     * Afficher la liste des equipes
     */
    public function index()
    {
        // Récupérer tous les equipes avec leurs emails (s'ils sont dans une relation avec 'users' par exemple)
        $equipes = equipe::with('user')->get(); // Suppose que la relation 'user' existe dans le modèle equipe

        // Transformer les données pour inclure les emails
        $resultat = $equipes->map(function ($equipe) {
            return [
                'id' => $equipe->id,
                'nom' => $equipe->nom,
                'prenom' => $equipe->prenom,
                'telephone' => $equipe->telephone,
                'matricule' => $equipe->matricule,
                'email' => $equipe->user->email,
                'user_id' => $equipe->user->id,
            ];
        });

        return response()->json([
            'message' => 'Liste des equipes',
            'données' => $resultat,
            'status' => 200
        ]);
    }




    /**
     * Methode pour ajouter un equipe
     */
    public function store(StoreequipeRequest $request)
    {

        // Générer un mot de passe aléatoire de 10 caractères
        $password = Str::random(8);

        // Créer un nouvel utilisateur
        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($password),
        ]);
        // Assigner le rôle "equipe" à l'utilisateur (en supposant que le rôle existe dans la base de données)
        $user->assignRole('equipe');

   

        // Ajouter le equipe à la table des equipes
        $equipe = equipe::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'matricule' => $matricule,
            'telephone' => $request->telephone,
            'photo' => $request->hasFile('photo') ? $request->photo->store('photos') : null,
            'user_id' => $user->id, // ID de l'utilisateur créé
        ]);
        // Envoyer un email au equipe
        Mail::to($request->email)->send(new equipeCreated($equipe, $password));

        return response()->json([
            'message' => 'equipe créé avec succès',
            'données' => $equipe,
            'status' => 201
        ]);
    }


    /**
     *voir les details d'un equipe
     */
    public function show(equipe $equipe)
    {
        return response()->json([
            'message' => 'Détails du equipe',
            'données' => $equipe,
            'status' => 200
        ]);
    }


    /**
     * ;ethode pour supprimer un equipe
     */

    public function update(Request $request, $id)
    {
        // Récupérer le equipe par ID
        $equipe = equipe::find($id);

        if (!$equipe) {
            return response()->json([
                'message' => 'equipe non trouvé',
                'status' => 404
            ]);
        }

        // Récupérer l'utilisateur associé au equipe
        $user = User::find($equipe->user_id);

        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé',
                'status' => 404
            ]);
        }

        Log::info('Données envoyées pour la mise à jour:', $request->all());

        // Mettre à jour les données du equipe
        $equipe->nom = $request->input('nom', $equipe->nom);
        $equipe->prenom = $request->input('prenom', $equipe->prenom);
        $equipe->telephone = $request->input('telephone', $equipe->telephone);

        // Vérifier si une nouvelle image a été uploadée
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo
            if ($equipe->photo) {
                Storage::disk('public')->delete($equipe->photo);
            }

            // Stocker la nouvelle photo
            $photoPath = $request->file('photo')->store('photos', 'public');

            $equipe->photo = $photoPath; // Stocke le chemin relatif de l'image
        }
        // Mettre à jour les données de l'utilisateur
        if ($request->has('email')) {
            $user->email = $request->input('email');
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // Sauvegarder les modifications
        $equipe->save();
        $user->save();

        return response()->json([
            'message' => 'equipe et utilisateur modifiés avec succès',
            'données' => $equipe,
            'status' => 200
        ]);
    }


    /**
     * Supprimer un equipe
     */
    public function destroy(equipe $equipe)
    {
        // Supprimer l'utilisateur associé
        if ($equipe->user_id) {
            $user = User::find($equipe->user_id);
            if ($user) {
                $user->delete();
            }
        }

        // Supprimer le equipe
        $equipe->delete();

        return response()->json([
            'message' => 'equipe et utilisateur supprimés avec succès',
            'status' => 200
        ]);
    }
}