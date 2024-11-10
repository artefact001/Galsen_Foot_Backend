

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
        //             'nom' => $request->nom_zone,
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
use App\Models\zone;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use App\Mail\zoneCreated;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StorezoneRequest;
use App\Http\Requests\UpdatezoneRequest;
use Illuminate\Support\Str;


class zoneController extends Controller
{

    //nombre total de zone
    public function totalzones()
    {

        // Compter le nombre total de prof 
        $totalzone = zone::count();

        // Structurer la réponse en JSON
        return response()->json([
            'message' => 'Total d\'equipe pour l\'année en cours.',
            'total' => $totalzone,
            'status' => 200
        ]);
    }
    /**
     * Afficher la liste des zones
     */
    public function index()
    {
        // Récupérer tous les zones avec leurs emails (s'ils sont dans une relation avec 'users' par exemple)
        $zones = zone::with('user')->get(); // Suppose que la relation 'user' existe dans le modèle zone

        // Transformer les données pour inclure les emails
        $resultat = $zones->map(function ($zone) {
            return [
                'id' => $zone->id,
                'nom' => $zone->nom,
                'prenom' => $zone->prenom,
                'telephone' => $zone->telephone,
                'matricule' => $zone->matricule,
                'email' => $zone->user->email,
                'user_id' => $zone->user->id,
            ];
        });

        return response()->json([
            'message' => 'Liste des zones',
            'données' => $resultat,
            'status' => 200
        ]);
    }




    /**
     * Methode pour ajouter un zone
     */
    public function store(StorezoneRequest $request)
    {

        // Générer un mot de passe aléatoire de 10 caractères
        $password = Str::random(8);

        // Créer un nouvel utilisateur
        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($password),
        ]);
        // Assigner le rôle "zone" à l'utilisateur (en supposant que le rôle existe dans la base de données)
        $user->assignRole('zone');

   

        // Ajouter le zone à la table des zones
        $zone = zone::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'matricule' => $matricule,
            'telephone' => $request->telephone,
            'photo' => $request->hasFile('photo') ? $request->photo->store('photos') : null,
            'user_id' => $user->id, // ID de l'utilisateur créé
        ]);
        // Envoyer un email au zone
        Mail::to($request->email)->send(new zoneCreated($zone, $password));

        return response()->json([
            'message' => 'zone créé avec succès',
            'données' => $zone,
            'status' => 201
        ]);
    }


    /**
     *voir les details d'un zone
     */
    public function show(zone $zone)
    {
        return response()->json([
            'message' => 'Détails du zone',
            'données' => $zone,
            'status' => 200
        ]);
    }


    /**
     * ;ethode pour supprimer un zone
     */

    public function update(Request $request, $id)
    {
        // Récupérer le zone par ID
        $zone = zone::find($id);

        if (!$zone) {
            return response()->json([
                'message' => 'zone non trouvé',
                'status' => 404
            ]);
        }

        // Récupérer l'utilisateur associé au zone
        $user = User::find($zone->user_id);

        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé',
                'status' => 404
            ]);
        }

        Log::info('Données envoyées pour la mise à jour:', $request->all());

        // Mettre à jour les données du zone
        $zone->nom = $request->input('nom', $zone->nom);
        $zone->prenom = $request->input('prenom', $zone->prenom);
        $zone->telephone = $request->input('telephone', $zone->telephone);

        // Vérifier si une nouvelle image a été uploadée
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo
            if ($zone->photo) {
                Storage::disk('public')->delete($zone->photo);
            }

            // Stocker la nouvelle photo
            $photoPath = $request->file('photo')->store('photos', 'public');

            $zone->photo = $photoPath; // Stocke le chemin relatif de l'image
        }
        // Mettre à jour les données de l'utilisateur
        if ($request->has('email')) {
            $user->email = $request->input('email');
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // Sauvegarder les modifications
        $zone->save();
        $user->save();

        return response()->json([
            'message' => 'zone et utilisateur modifiés avec succès',
            'données' => $zone,
            'status' => 200
        ]);
    }


    /**
     * Supprimer un zone
     */
    public function destroy(zone $zone)
    {
        // Supprimer l'utilisateur associé
        if ($zone->user_id) {
            $user = User::find($zone->user_id);
            if ($user) {
                $user->delete();
            }
        }

        // Supprimer le zone
        $zone->delete();

        return response()->json([
            'message' => 'zone et utilisateur supprimés avec succès',
            'status' => 200
        ]);
    }
}