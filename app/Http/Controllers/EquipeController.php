<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipeRequest;  // Importer EquipeRequest pour la validation
use App\Models\Equipe;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Services\EquipeService;
use Illuminate\Http\JsonResponse;

class EquipeController extends Controller
{
    private $equipeService;

    public function __construct(EquipeService $equipeService)
    {
        $this->equipeService = $equipeService;
    }

    /**
     * Afficher la liste de toutes les équipes.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $equipes = $this->equipeService->recupererToutesLesEquipes();
        return response()->json($equipes);
    }

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
    public function show(int $id): JsonResponse
    {
        $equipe = $this->equipeService->recupererEquipeParId($id);
        return response()->json($equipe);
    }

    /**
     * Mettre à jour une équipe existante.
     *
     * @param EquipeRequest $request  // Utilisation de EquipeRequest
     * @param int $id
     * @return JsonResponse
     */
    public function update(EquipeRequest $request, int $id): JsonResponse
    {
        // Utiliser validated() pour récupérer les données validées
        $data = $request->validated();

        // Récupérer l'équipe et la mettre à jour
        $equipe = $this->equipeService->recupererEquipeParId($id);
        $equipe = $this->equipeService->mettreAJourEquipe($equipe, $data);

        return response()->json($equipe);
    }

    /**
     * Supprimer une équipe.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $equipe = $this->equipeService->recupererEquipeParId($id);
        $this->equipeService->supprimerEquipe($equipe);

        return response()->json(['message' => 'Équipe supprimée avec succès.'], 204);
    }
}
