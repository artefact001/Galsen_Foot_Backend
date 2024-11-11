
// namespace App\Http\Controllers;

use App\Http\Requests\EquipeRequest;  // Importer EquipeRequest pour la validation
use App\Models\Equipe;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Services\EquipeService;
use Illuminate\Http\JsonResponse;

// class EquipeController extends Controller
// {
//     private $equipeService;

//     public function __construct(EquipeService $equipeService)
//     {
//         $this->equipeService = $equipeService;
//     }

    /**
     * Afficher la liste de toutes les équipes.
     *
     * @return JsonResponse
     */
    // public function index(): JsonResponse
    // {
    //     $equipes = $this->equipeService->recupererToutesLesEquipes();
    //     return response()->json($equipes);
    // }

    /**
     * Créer une nouvelle équipe.
     *
     * @param EquipeRequest $request  // Utilisation de EquipeRequest
     * @return JsonResponse
     */
    public function store(Request $request)
    {
     
      
 
        try {
            // Handle profile picture upload
            $photo_profile = null;
            if ($request->hasFile('photo_profile')) {
                $photo_profile = $request->file('photo_profile')->store('profiles', 'public');
            }
 
 
            // Create the user with the password
            $password = $request->nom . Str::random(8); // Example: "prenomXYZ"
            $password = $request->password;
            $user = User::create([
                'nom' => $request->nom,
                'email' => $request->email,
                'password' => Hash::make($password), // Encrypt the password
                'role'  => 'equipe',
                'photo_profile' => $photo_profile, // Store the photo path if available
            ]);
 
                 if ($user) {
                     Equipe::create([
                     'nom' => $request->nom_equipe,
                     'logo' => $request->logo,
                     'date_creer' => $request->date_creer,
                     'user_id' => $user->id,
                     'zone_id' => $request->zone_id
                     ]);
                     }
 
 
 
            // Assign role and promotion if necessary
         //    $role = Role::firstOrCreate(['name' => 'admin']);
         //    $user->assignRole($role);
 
 
 
            // Send email notification
         //    $user->notify(new ZoneInscriptionNotification($user, $password));
 
 
            return response()->json([
                'success' => true,
                'message' => 'Equipe inscrit avec succès et notification envoyée par email',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher une équipe spécifique.
     *
     * @param int $id
     * @return JsonResponse
     */
    // public function show(int $id): JsonResponse
    // {
    //     $equipe = $this->equipeService->recupererEquipeParId($id);
    //     return response()->json($equipe);
    // }

    /**
     * Mettre à jour une équipe existante.
     *
     * @param EquipeRequest $request  // Utilisation de EquipeRequest
     * @param int $id
     * @return JsonResponse
     */
    // public function update(EquipeRequest $request, int $id): JsonResponse
    // {
        // Utiliser validated() pour récupérer les données validées
        // $data = $request->validated();

        // Récupérer l'équipe et la mettre à jour
    //     $equipe = $this->equipeService->recupererEquipeParId($id);
    //     $equipe = $this->equipeService->mettreAJourEquipe($equipe, $data);

    //     return response()->json($equipe);
    // }

    /**
     * Supprimer une équipe.
     *
     * @param int $id
     * @return JsonResponse
     */
//     public function destroy(int $id): JsonResponse
//     {
//         $equipe = $this->equipeService->recupererEquipeParId($id);
//         $this->equipeService->supprimerEquipe($equipe);

//         return response()->json(['message' => 'Équipe supprimée avec succès.'], 204);
//     }
// }







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