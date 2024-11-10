

// namespace App\Http\Controllers;
// use App\Http\Requests\ValidationRequest;

// use App\Services\PointService;
// use Illuminate\Http\JsonResponse;

// class PointController extends Controller
// {
//     protected $pointService;

//     public function __construct(PointService $pointService)
//     {
//         $this->pointService = $pointService;
//     }

    // Afficher le classement global
    // public function rankings(): JsonResponse
    // {
    //     $rankings = $this->pointService->getRankings();

    //     return response()->json($rankings);
    // }

    // Afficher les points d'une équipe donnée
//     public function teamPoints($joueurId): JsonResponse
//     {
//         $points = $this->pointService->getTeamPoints($joueurId);

//         return response()->json([
//             'joueur_id' => $joueurId,
//             'total_points' => $points,
//         ]);
//     }
// }



<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\joueur;
use App\Models\equipes;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StorejoueurRequest;
use App\Http\Requests\UpdatejoueurRequest;
use App\Mail\joueurCreated;
use App\Mail\equipeCreated;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class joueurController extends Controller
{



    /**
     *Voir la liste des joueurs 
     */

    public function index()
    {
        // Récupérer les equipes avec leurs joueurs
        $equipes = equipes::with('joueurs')->get();

        // Structurer la réponse JSON pour regrouper les joueurs par equipe
        $data = $equipes->map(function ($equipe) {
            return [
                'equipe' => [
                    'nom' => $equipe->nom,
                    'prenom' => $equipe->prenom,
                    'email' => $equipe->user->email,
                    'telephone' => $equipe->telephone,
                    'adresse' => $equipe->adresse
                ],
                'joueurs' => $equipe->joueurs->map(function ($joueur) {
                    return [
                        'nom' => $joueur->nom,
                        'prenom' => $joueur->prenom,
                        'matricule' => $joueur->matricule,
                        'date_naissance' => $joueur->date_naissance,
                        'genre' => $joueur->genre,
                        'telephone' => $joueur->telephone,
                    ];
                })
            ];
        });

        return response()->json([
            'message' => 'Liste des joueurs regroupés par equipe',
            'données' => $data,
            'status' => 200
        ]);
    }


    /**
     * Methode pour ajouter des joueurs
     * 
     */
    public function store(StorejoueurRequest $request)
    {
        // Rechercher un equipe existant via son numéro de téléphone
        $equipe = equipes::where('telephone', $request->equipe_telephone)->first();
    
        // Générer un mot de passe aléatoire de 10 caractères
        $password = Str::random(10);
    
        // Variable pour vérifier si le equipe a été créé
        $equipeCree = false;
    
        // Si le equipe n'existe pas, on le crée
        if (!$equipe) {
            // Créer un nouvel utilisateur pour le equipe
            $userequipe = User::create([
                'email' => $request->equipe_email,
                'password' => bcrypt($password), // Mot de passe par défaut
            ]);
    
            // Assigner le rôle "equipe" à l'utilisateur
            $userequipe->assignRole('equipe');
    
            // Créer le equipe
            $equipe = equipes::create([
                'nom' => $request->equipe_nom,
                'prenom' => $request->equipe_prenom,
                'telephone' => $request->equipe_telephone,
                'adresse' => $request->equipe_adresse,
                'photo' => $request->hasFile('equipe_photo') ? $request->equipe_photo->store('photos') : null,
                'user_id' => $userequipe->id,
            ]);
    
            // Marquer le equipe comme nouvellement créé
            $equipeCree = true;
        }
    
        // Envoyer un email au equipe uniquement si le equipe a été nouvellement créé
        if ($equipeCree) {
            Mail::to($request->equipe_email)->send(new equipeCreated($equipe, $password));
        }
    
        // Si l'email de l'joueur est fourni, l'utiliser. Sinon, générer un email fictif
        $emailjoueur = $request->email ?? strtolower($request->nom . '.' . $request->prenom . '@joueur.local');
    
        // Générer un mot de passe aléatoire pour l'joueur
        $password = Str::random(10);
    
        // Créer un nouvel utilisateur pour l'joueur
        $userjoueur = User::create([
            'email' => $emailjoueur,
            'password' => bcrypt($password),
        ]);
    
        // Assigner le rôle "joueur" à l'utilisateur
        $userjoueur->assignRole('joueur');
    
        // Générer une matricule unique pour l'joueur
        $prenom = strtoupper(substr($request->prenom, 0, 2));
        $matricule = 'E' . $prenom . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
    
        // S'assurer que la matricule est unique pour l'joueur
        while (joueur::where('matricule', $matricule)->exists()) {
            $matricule = 'E' . $prenom . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        }
    
        // Ajouter l'joueur à la base de données
        $joueur = joueur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'matricule' => $matricule,
            'photo' => $request->hasFile('photo') ? $request->photo->store('photos') : null,
            'date_naissance' => $request->date_naissance,
            'genre' => $request->genre,
            'user_id' => $userjoueur->id,
            'equipe_id' => $equipe->id,
        ]);
    
        // Envoyer un email à l'joueur
        Mail::to($emailjoueur)->send(new joueurCreated($joueur, $password));
    
        // Retourner la réponse JSON
        return response()->json([
            'message' => 'joueur créé avec succès',
            'données' => $joueur,
            'status' => 201
        ]);
    }
    

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Charger l'joueur avec son equipe
        $joueur = joueur::with('equipe')->find($id);

        // Vérifier si l'joueur existe
        if (!$joueur) {
            return response()->json([
                'message' => 'joueur non trouvé',
                'status' => 404
            ]);
        }

        // Structurer la réponse avec les détails de l'joueur et du equipe
        $response = [
            'message' => 'Détails de l\'joueur',
            'données' => [
                'joueur' => [
                    'id' => $joueur->id,
                    'nom' => $joueur->nom,
                    'prenom' => $joueur->prenom,
                    'matricule' => $joueur->matricule,
                    'date_naissance' => $joueur->date_naissance,
                    'genre' => $joueur->genre,
                    'photo' => $joueur->photo,
                ],
                'equipe' => [
                    'nom_equipe' => $joueur->equipe->nom,
                    'prenom_equipe' => $joueur->equipe->prenom,
                    'telephone_equipe' => $joueur->equipe->telephone,
                    'adresse_equipe' => $joueur->equipe->adresse,
                    'email_equipe' => $joueur->equipe->user->email,
                ]
            ],
            'status' => 200
        ];

        return response()->json($response);
    }

    /**
     * Récupérer la liste de tous les joueurs avec leurs equipes.
     */
    public function joueurs()
    {
        // Charger tous les joueurs avec leurs equipes
        $joueurs = joueur::with('equipe')->get();

        // Structurer la réponse avec les détails de chaque joueur et de son equipe
        $response = [
            'message' => 'Liste des joueurs',
            'données' => $joueurs->map(function ($joueur) {
                return [
                    'id' => $joueur->id,
                    'nom' => $joueur->nom,
                    'prenom' => $joueur->prenom,
                    'matricule' => $joueur->matricule,
                    'date_naissance' => $joueur->date_naissance,
                    'genre' => $joueur->genre,
                    'telephone' => $joueur->telephone,
                    'photo' => $joueur->photo,
                    'email' => $joueur->user->email,
                    // 'equipe' => [
                    //     'nom_equipe' => $joueur->equipe->nom,
                    //     'prenom_equipe' => $joueur->equipe->prenom,
                    //     'telephone_equipe' => $joueur->equipe->telephone,
                    //     'adresse_equipe' => $joueur->equipe->adresse,
                    //     'email_equipe' => $joueur->equipe->user->email,
                    // ]
                ];
            }),
            'status' => 200
        ];

        return response()->json($response);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatejoueurRequest $request, $id)
    {
        // Rechercher l'joueur par son ID
        $joueur = joueur::findOrFail($id);

        // Mettre à jour les informations de l'joueur
        $joueur->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'photo' => $request->hasFile('photo') ? $request->photo->store('photos') : $joueur->photo, // Conserver l'ancienne photo si aucune nouvelle n'est fournie
            'date_naissance' => $request->date_naissance,
            'genre' => $request->genre,
        ]);

        // Mettre à jour les informations du equipe
        // $equipe = equipes::findOrFail($joueur->equipe_id); // On récupère le equipe associé à l'joueur
        // $equipe->update([
        //     'nom' => $request->equipe_nom,
        //     'prenom' => $request->equipe_prenom,
        //     'adresse' => $request->equipe_adresse,
        //     'telephone' => $request->equipe_telephone,
        //     'photo' => $request->hasFile('equipe_photo') ? $request->equipe_photo->store('photos') : $equipe->photo, // Conserver l'ancienne photo si aucune nouvelle n'est fournie
        // ]);

        // Retourner la réponse JSON avec les données de l'joueur et du equipe mis à jour
        return response()->json([
            'message' => 'joueur et equipe mis à jour avec succès',
            'données' => [
                'joueur' => $joueur,
                // 'equipe' => $equipe
            ],
            'status' => 200
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Récupérer l'joueur
        $joueur = joueur::findOrFail($id);

        // Supprimer l'utilisateur associé
        if ($joueur->user_id) {
            $user = User::find($joueur->user_id);
            if ($user) {
                $user->delete();
            }
        }

        // Supprimer l'joueur
        $joueur->delete();

        return response()->json([
            'message' => 'joueur et utilisateur supprimés avec succès',
            'status' => 200
        ]);
    }

   
public function modifierjoueur(Request $request)
{
    // Récupérer l'utilisateur actuellement connecté
    $userAuthId = Auth::id(); // ou Auth::user()->id

    // Récupérer l'joueur associé à l'utilisateur connecté
    $joueur = joueur::where('user_id', $userAuthId)->first();

    if (!$joueur) {
        return response()->json([
            'message' => 'joueur non trouvé pour cet utilisateur',
            'status' => 404
        ]);
    }

    // Récupérer l'utilisateur associé à cet joueur
    $user = User::find($userAuthId);

    if (!$user) {
        return response()->json([
            'message' => 'Utilisateur non trouvé',
            'status' => 404
        ]);
    }

    Log::info('Données envoyées pour la mise à jour:', $request->all());

    // Mettre à jour les données de l'joueur
    $joueur->nom = $request->input('nom', $joueur->nom);
    $joueur->prenom = $request->input('prenom', $joueur->prenom);
    $joueur->date_naissance = $request->input('date_naissance', $joueur->date_naissance);
    $joueur->genre = $request->input('genre', $joueur->genre);

    // Vérifier si une nouvelle image a été uploadée
    if ($request->hasFile('photo')) {
        // Supprimer l'ancienne photo
        if ($joueur->photo) {
            Storage::disk('public')->delete($joueur->photo);
        }

        // Stocker la nouvelle photo
        $photoPath = $request->file('photo')->store('photos', 'public');
        $joueur->photo = $photoPath; // Stocke le chemin relatif de l'image
    }

    // Mettre à jour les données de l'utilisateur
    if ($request->has('email')) {
        $user->email = $request->input('email');
    }

    if ($request->has('password')) {
        $user->password = bcrypt($request->input('password'));
    }

    // Sauvegarder les modifications
    $joueur->save();
    $user->save();

    return response()->json([
        'message' => 'joueur et utilisateur modifiés avec succès',
        'données' => $joueur,
        'status' => 200
    ]);
}
}