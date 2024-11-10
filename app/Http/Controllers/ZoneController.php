

// namespace App\Http\Controllers;

// use App\Models\Zone;
// use Illuminate\Http\Request;
// use Illuminate\Support\Str;
// use App\Models\User;
// use Spatie\Permission\Models\Role; // Utilise le modèle de Spatie
// use App\Notifications\ZoneInscriptionNotification;
// use Illuminate\Support\Facades\Hash;
// use App\Http\Requests\ValidationRequest;

// class ZoneController extends Controller
// {
// public function inscrireZone(Request $request)
//    {
       // Validation des données
    //    $validator = validator($request->all(), [
    //        'nom' => ['required', 'string', 'max:255'],
    //        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //    'photo_profile' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
    //    ]);


    //    if ($validator->fails()) {
    //        return response()->json([
    //            'success' => false,
    //            'errors' => $validator->errors(),
    //        ], 422);
    //    }


    //    try {
           // Handle profile picture upload
        //    $photo_profile = null;
        //    if ($request->hasFile('photo_profile')) {
        //        $photo_profile = $request->file('photo_profile')->store('profiles', 'public');
        //    }


           // Create the user with the password
        //    $password = $request->nom . Str::random(4); // Example: "prenomXYZ"
        //    $user = User::create([
        //        'nom' => $request->nom,
        //        'email' => $request->email,
        //        'password' => Hash::make($password), // Encrypt the password
        //        'role'  => 'admin',
        //        'photo_profile' => $photo_profile, // Store the photo path if available
        //    ]);

        //         if ($user) {
        //             Zone::create([
        //             'nom' => $request->nom_equipe,
        //             'localite' => $request->localite,
        //             'user_id' => $request->$user->id,
        //             ]);
        //             }



           // Assign role and promotion if necessary
        //    $role = Role::firstOrCreate(['name' => 'admin']);
        //    $user->assignRole($role);



           // Send email notification
//            $user->notify(new ZoneInscriptionNotification($user, $password));


//            return response()->json([
//                'success' => true,
//                'message' => 'Zone inscrit avec succès et notification envoyée par email',
//                'user' => $user
//            ], 201);
//        } catch (\Exception $e) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Une erreur est survenue : ' . $e->getMessage()
//            ], 500);
//        }
//    }
// }



<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Professeur;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use App\Mail\ProfesseurCreated;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreProfesseurRequest;
use App\Http\Requests\UpdateProfesseurRequest;
use Illuminate\Support\Str;


class ProfesseurController extends Controller
{

    //nombre total de professeur
    public function totalProfesseurs()
    {

        // Compter le nombre total de prof 
        $totalProsseur = Professeur::count();

        // Structurer la réponse en JSON
        return response()->json([
            'message' => 'Total d\'élèves pour l\'année en cours.',
            'total' => $totalProsseur,
            'status' => 200
        ]);
    }
    /**
     * Afficher la liste des professeurs
     */
    public function index()
    {
        // Récupérer tous les professeurs avec leurs emails (s'ils sont dans une relation avec 'users' par exemple)
        $professeurs = Professeur::with('user')->get(); // Suppose que la relation 'user' existe dans le modèle Professeur

        // Transformer les données pour inclure les emails
        $resultat = $professeurs->map(function ($professeur) {
            return [
                'id' => $professeur->id,
                'nom' => $professeur->nom,
                'prenom' => $professeur->prenom,
                'telephone' => $professeur->telephone,
                'matricule' => $professeur->matricule,
                'email' => $professeur->user->email,
                'user_id' => $professeur->user->id,
            ];
        });

        return response()->json([
            'message' => 'Liste des professeurs',
            'données' => $resultat,
            'status' => 200
        ]);
    }




    /**
     * Methode pour ajouter un professeur
     */
    public function store(StoreProfesseurRequest $request)
    {

        // Générer un mot de passe aléatoire de 10 caractères
        $password = Str::random(10);

        // Créer un nouvel utilisateur
        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($password),
        ]);
        // Assigner le rôle "professeur" à l'utilisateur (en supposant que le rôle existe dans la base de données)
        $user->assignRole('professeur');

        // Générer une matricule unique
        $prenom = strtoupper(substr($request->prenom, 0, 2)); // Prendre les deux premières lettres du prénom en majuscules
        $matricule = 'P' . $prenom . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT); // Générer trois chiffres aléatoires

        // S'assurer que la matricule est unique
        while (Professeur::where('matricule', $matricule)->exists()) {
            // Régénérer une nouvelle matricule si elle existe déjà
            $matricule = 'P' . $prenom . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        }

        // Ajouter le professeur à la table des professeurs
        $professeur = Professeur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'matricule' => $matricule,
            'telephone' => $request->telephone,
            'photo' => $request->hasFile('photo') ? $request->photo->store('photos') : null,
            'user_id' => $user->id, // ID de l'utilisateur créé
        ]);
        // Envoyer un email au professeur
        Mail::to($request->email)->send(new ProfesseurCreated($professeur, $password));

        return response()->json([
            'message' => 'Professeur créé avec succès',
            'données' => $professeur,
            'status' => 201
        ]);
    }


    /**
     *voir les details d'un professeur
     */
    public function show(Professeur $professeur)
    {
        return response()->json([
            'message' => 'Détails du professeur',
            'données' => $professeur,
            'status' => 200
        ]);
    }


    /**
     * ;ethode pour supprimer un professeur
     */

    public function update(Request $request, $id)
    {
        // Récupérer le professeur par ID
        $professeur = Professeur::find($id);

        if (!$professeur) {
            return response()->json([
                'message' => 'Professeur non trouvé',
                'status' => 404
            ]);
        }

        // Récupérer l'utilisateur associé au professeur
        $user = User::find($professeur->user_id);

        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé',
                'status' => 404
            ]);
        }

        Log::info('Données envoyées pour la mise à jour:', $request->all());

        // Mettre à jour les données du professeur
        $professeur->nom = $request->input('nom', $professeur->nom);
        $professeur->prenom = $request->input('prenom', $professeur->prenom);
        $professeur->telephone = $request->input('telephone', $professeur->telephone);

        // Vérifier si une nouvelle image a été uploadée
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo
            if ($professeur->photo) {
                Storage::disk('public')->delete($professeur->photo);
            }

            // Stocker la nouvelle photo
            $photoPath = $request->file('photo')->store('photos', 'public');

            $professeur->photo = $photoPath; // Stocke le chemin relatif de l'image
        }
        // Mettre à jour les données de l'utilisateur
        if ($request->has('email')) {
            $user->email = $request->input('email');
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // Sauvegarder les modifications
        $professeur->save();
        $user->save();

        return response()->json([
            'message' => 'Professeur et utilisateur modifiés avec succès',
            'données' => $professeur,
            'status' => 200
        ]);
    }


    /**
     * Supprimer un professeur
     */
    public function destroy(Professeur $professeur)
    {
        // Supprimer l'utilisateur associé
        if ($professeur->user_id) {
            $user = User::find($professeur->user_id);
            if ($user) {
                $user->delete();
            }
        }

        // Supprimer le professeur
        $professeur->delete();

        return response()->json([
            'message' => 'Professeur et utilisateur supprimés avec succès',
            'status' => 200
        ]);
    }
}