<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

// Controller imports
use App\Http\Controllers\{
    AuthController,
    UserController,
    RoleController,
    PermissionController,
    CompetitionController,
    EquipeController,
    MatcheController,
    JoueurController,
    TirageController,
    ReclamationController,
    NotificationController,
    // ResultatController,
    ClassementController,
    PointController,
    CalendrierController,
    DashboardController,
    StatistiqueController,
    ArticleController,
    CommentaireController,
    CategorieController,
    ZoneController
};

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});

/*
|--------------------------------------------------------------------------
| User Management Routes
|--------------------------------------------------------------------------
*/
// Route::group(['middleware' => 'auth:api'], function () {
    // Users
    Route::apiResource('users', UserController::class);
    
    // Roles and Permissions
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
// });

/*
|--------------------------------------------------------------------------
| Competition Management Routes
|--------------------------------------------------------------------------
*/
// Route::group(['middleware' => 'auth:api'], function () {
    // Competitions
    Route::apiResource('competitions', CompetitionController::class);
    Route::apiResource('zones', ZoneController::class);
    
    // Matches
    Route::apiResource('matches', MatcheController::class);
    
    // // Results
    // Route::group(['prefix' => 'resultats'], function () {
    //     Route::post('/', [ResultatController::class, 'store']);
    //     Route::get('/matche/{matcheId}', [ResultatController::class, 'show']);
    //     Route::post('/matche/{matcheId}/winner', [ResultatController::class, 'determineWinner']);
    // });
// });

/*
|--------------------------------------------------------------------------
| Team and Player Management Routes
|--------------------------------------------------------------------------
*/
// Route::group(['middleware' => 'auth:api'], function () {
    // Teams
    Route::apiResource('equipes', EquipeController::class);
    
    // Players
    Route::apiResource('joueurs', JoueurController::class);
    
    // Draw Management
    Route::apiResource('tirages', TirageController::class);
    Route::post('tirages/lancer', [TirageController::class, 'lancerTirage']);
// });

/*
|--------------------------------------------------------------------------
| Rankings and Statistics Routes
|--------------------------------------------------------------------------
*/
// Route::group(['middleware' => 'auth:api'], function () {
    // Rankings
    Route::get('rankings', [PointController::class, 'rankings']);
    Route::get('teams/{equipeId}/points', [PointController::class, 'teamPoints']);
    Route::get('classement', [ClassementController::class, 'getGlobalRankings']);
    Route::get('classement/equipe/{equipeId}', [ClassementController::class, 'getTeamRank']);
    
    // Statistics
    Route::group(['prefix' => 'statistiques'], function () {
        Route::apiResource('/', StatistiqueController::class)->except('show');
        Route::get('/joueur/{joueurId}', [StatistiqueController::class, 'showByJoueur']);
    });
// });

/*
|--------------------------------------------------------------------------
| Content Management Routes
|--------------------------------------------------------------------------
*/
// Route::group(['middleware' => 'auth:api'], function () {
    // Articles and Comments
    Route::apiResource('articles', ArticleController::class);
    Route::post('articles/{articleId}/commentaires', [CommentaireController::class, 'store']);
    Route::delete('commentaires/{id}', [CommentaireController::class, 'destroy']);
    
    // Categories
    Route::apiResource('categories', CategorieController::class);
// });

/*
|--------------------------------------------------------------------------
| Communication Routes
|--------------------------------------------------------------------------
*/
// Route::group(['middleware' => 'auth:api'], function () {
    // Notifications
    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::put('{id}/read', [NotificationController::class, 'markAsRead']);
        Route::put('read-all', [NotificationController::class, 'markAllAsRead']);
    });
    
    // Reclamations
    Route::apiResource('reclamations', ReclamationController::class);
// });

/*
|--------------------------------------------------------------------------
| Calendar and Dashboard Routes
|--------------------------------------------------------------------------
*/
// Route::group(['middleware' => 'auth:api'], function () {
    // Calendar
    Route::apiResource('calendriers', CalendrierController::class)->except(['show']);
    Route::get('calendriers/matche/{matcheId}', [CalendrierController::class, 'getByMatche']);
    
    // Dashboard
    // Route::get('dashboard', [DashboardController::class, 'index']);
    // Route::get('dashboard/stats', [DashboardController::class, 'getStats']);
// });

/*
|--------------------------------------------------------------------------
| Test Routes
|--------------------------------------------------------------------------
*/
Route::get('/test-email', function () {
    Mail::raw('C\'est le mail pour tester l\'ajout.', function ($message) {
        $message->to('cheikhsane656@example.com')
                ->subject('Test Email');
    });
    return response()->json(['message' => 'Email envoyé avec succès']);
});

// Sanctum authentication check
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');