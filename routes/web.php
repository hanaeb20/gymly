<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GerantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EssaieController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\HistoriqueController;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\Gerant\DashboardController;
use App\Http\Controllers\Gerant\HoraireController as GerantHoraireController;
use App\Http\Controllers\Gerant\EquipementController;
use App\Http\Controllers\Gerant\InscriptionController;
use App\Http\Controllers\Gerant\AbonnementController as GerantAbonnementController;
use App\Http\Controllers\Gerant\StatistiqueController;
use App\Http\Controllers\Gerant\SalleController as GerantSalleController;
use App\Http\Controllers\Gerant\CoachController as GerantCoachController;
use App\Http\Controllers\Gerant\InscriptionController as GerantInscriptionController;
use App\Http\Controllers\Gerant\MaterielController as GerantMaterielController;
use App\Http\Controllers\Gerant\AnnonceController;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Gerant\DeclarationController;

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/essaie', [EssaieController::class, 'index'])->name('essaie.index');
Route::get('/essaie-simple', [EssaieController::class, 'simple'])->name('essaie.simple');
Route::get('/espace-coach', [EssaieController::class, 'index'])->name('essaie');

// Routes publiques
Route::get('/salles', [SalleController::class, 'index'])->name('salles.index');
Route::get('/cours', [CoursController::class, 'index'])->name('cours.index');
Route::get('/coachs', [CoachController::class, 'index'])->name('coachs.index');

// Routes pour les salles (accessibles à tous)
Route::prefix('salles')->name('salles.')->group(function () {
    Route::get('/search', [SalleController::class, 'search'])->name('search');
    Route::get('/{salle}', [SalleController::class, 'show'])->name('show');
});

// Routes pour les cours (accessibles à tous)
Route::prefix('cours')->name('cours.')->group(function () {
    Route::get('/{cours}', [CoursController::class, 'show'])->name('show');
});

// Routes pour les coachs (accessibles à tous)
Route::prefix('coachs')->name('coachs.')->group(function () {
    Route::get('/{coach}', [CoachController::class, 'show'])->name('show');
});

// Routes de contact (accessibles à tous)
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register/choose', [RegisterController::class, 'showProfileChoice'])->name('register.choose');
    Route::get('/register/gerant', function () {
        return view('auth.register-gerant');
    })->name('register.gerant');
    Route::get('/register/coach', function () {
        return view('auth.register-coach');
    })->name('register.coach');
});

// Route de déconnexion
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    // Routes pour les salles
    Route::get('/salles/create', [SalleController::class, 'create'])->name('salles.create');
    Route::post('/salles', [SalleController::class, 'store'])->name('salles.store');
    Route::get('/salles/{salle}/inscription', [InscriptionController::class, 'show'])->name('salles.inscription');
    Route::post('/salles/{salle}/inscription', [InscriptionController::class, 'store'])->name('salles.inscription.store');
    Route::delete('/salles/{salle}/inscription', [SalleController::class, 'annulerInscription'])->name('salles.inscription.destroy');
    Route::post('/salles/{salle}/inscription', [SalleController::class, 'inscription'])->name('salles.inscription');

    // Routes pour les coachs
    Route::middleware('coach')->prefix('coach')->name('coach.')->group(function () {
        Route::get('/dashboard', [CoachController::class, 'dashboard'])->name('dashboard');
    });

    // Routes pour la gestion (accessibles uniquement aux gérants)
    Route::middleware('basic_gerant')->prefix('gestion')->name('gestion.')->group(function () {
        Route::get('/horaires', [GestionController::class, 'horaires'])->name('horaires');
        Route::get('/equipements', [GestionController::class, 'equipements'])->name('equipements');
        Route::get('/abonnements', [GestionController::class, 'abonnements'])->name('abonnements');
    });

    // Routes pour le profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Routes pour les déclarations
    Route::get('/declarations', [App\Http\Controllers\DeclarationController::class, 'index'])->name('declarations.index');
    Route::get('/declarations/create', [App\Http\Controllers\DeclarationController::class, 'create'])->name('declarations.create');
    Route::post('/declarations', [App\Http\Controllers\DeclarationController::class, 'store'])->name('declarations.store');

    // Routes pour les cours
    Route::get('/cours/create', [CoursController::class, 'create'])->name('cours.create');
    Route::post('/cours', [CoursController::class, 'store'])->name('cours.store');
    Route::get('/cours/{cours}/edit', [CoursController::class, 'edit'])->name('cours.edit');
    Route::put('/cours/{cours}', [CoursController::class, 'update'])->name('cours.update');
    Route::delete('/cours/{cours}', [CoursController::class, 'destroy'])->name('cours.destroy');

    // Réservations
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/create', [ReservationController::class, 'create'])->name('create');
        Route::post('/', [ReservationController::class, 'store'])->name('store');
        Route::get('/{reservation}', [ReservationController::class, 'show'])->name('show');
        Route::put('/{reservation}/statut', [ReservationController::class, 'updateStatut'])->name('update.statut');
        Route::delete('/{reservation}', [ReservationController::class, 'destroy'])->name('destroy');
    });

    // Route pour les réservations des clients
    Route::get('/mes-reservations', [CoachController::class, 'reservations'])->name('client.reservations')->middleware('auth');

    // Évaluations
    Route::prefix('evaluations')->name('evaluations.')->group(function () {
        Route::get('/', [EvaluationController::class, 'index'])->name('index');
        Route::get('/coach', [EvaluationController::class, 'create'])->name('create');
        Route::get('/coach/{coach}', [EvaluationController::class, 'create'])->name('create.coach');
        Route::post('/coach/{coach}', [EvaluationController::class, 'store'])->name('store');
        Route::get('/coach/{coach}/evaluations', [EvaluationController::class, 'showCoachEvaluations'])->name('coach.show');
    });

    // Abonnements
    Route::prefix('abonnements')->name('abonnements.')->group(function () {
        Route::get('/', [AbonnementController::class, 'show'])->name('show');
        Route::post('/', [AbonnementController::class, 'subscribe'])->name('subscribe');
    });

    // Planning
    Route::prefix('planning')->name('planning.')->group(function () {
        Route::get('/', [PlanningController::class, 'index'])->name('index');
    });

    // Historique
    Route::prefix('historique')->name('historique.')->group(function () {
        Route::get('/cours', [HistoriqueController::class, 'cours'])->name('cours');
        Route::get('/evaluations', [HistoriqueController::class, 'evaluations'])->name('evaluations');
    });

    // Routes pour les gérants
    Route::middleware(['auth', \App\Http\Middleware\VerifyRole::class.':gerant'])->prefix('gerant')->name('gerant.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Routes pour les déclarations
        Route::get('/declarations', [DeclarationController::class, 'index'])->name('declarations.index');
        Route::get('/declarations/{declaration}', [DeclarationController::class, 'show'])->name('declarations.show');
        Route::put('/declarations/{declaration}', [DeclarationController::class, 'update'])->name('declarations.update');

        // Routes pour la gestion de la salle
        Route::get('/salles', [GerantSalleController::class, 'index'])->name('salles.index');
        Route::get('/salles/create', [GerantSalleController::class, 'create'])->name('salles.create');
        Route::post('/salles', [GerantSalleController::class, 'store'])->name('salles.store');
        Route::get('/salles/{salle}/edit', [GerantSalleController::class, 'edit'])->name('salles.edit');
        Route::put('/salles/{salle}', [GerantSalleController::class, 'update'])->name('salles.update');
        Route::delete('/salles/{salle}', [GerantSalleController::class, 'destroy'])->name('salles.destroy');
        Route::put('/salles/{salle}/annonce', [GerantSalleController::class, 'updateAnnonce'])->name('salles.update-annonce');
        Route::post('/salles/{salle}/photos', [GerantSalleController::class, 'addPhoto'])->name('salles.add-photo');
        Route::delete('/salles/{salle}/photos/{photo}', [GerantSalleController::class, 'deletePhoto'])->name('salles.delete-photo');

        // Routes pour la gestion des cours
        Route::prefix('cours')->name('cours.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Gerant\CoursController::class, 'index'])->name('index');
            Route::get('/{cours}/edit', [\App\Http\Controllers\Gerant\CoursController::class, 'edit'])->name('edit');
            Route::put('/{cours}', [\App\Http\Controllers\Gerant\CoursController::class, 'update'])->name('update');
            Route::delete('/{cours}', [\App\Http\Controllers\Gerant\CoursController::class, 'destroy'])->name('destroy');
        });

        // Routes pour la gestion des annonces
        Route::get('/annonces', [AnnonceController::class, 'index'])->name('annonces.index');
        Route::get('/annonces/create', [AnnonceController::class, 'create'])->name('annonces.create');
        Route::post('/annonces', [AnnonceController::class, 'store'])->name('annonces.store');
        Route::get('/annonces/{annonce}/edit', [AnnonceController::class, 'edit'])->name('annonces.edit');
        Route::put('/annonces/{annonce}', [AnnonceController::class, 'update'])->name('annonces.update');
        Route::delete('/annonces/{annonce}', [AnnonceController::class, 'destroy'])->name('annonces.destroy');

        // Routes pour la gestion des coachs
        Route::resource('coachs', GerantCoachController::class);
        Route::post('coachs/{coach}/regenerate-code', [GerantCoachController::class, 'regenerateCode'])->name('coachs.regenerate-code');

        // Routes pour la gestion des inscriptions
        Route::get('/inscriptions', [GerantInscriptionController::class, 'index'])->name('inscriptions.index');
        Route::get('/inscriptions/{inscription}', [GerantInscriptionController::class, 'show'])->name('inscriptions.show');
        Route::put('/inscriptions/{inscription}/valider', [GerantInscriptionController::class, 'valider'])->name('inscriptions.valider');
        Route::put('/inscriptions/{inscription}/refuser', [GerantInscriptionController::class, 'refuser'])->name('inscriptions.refuser');

        // Routes pour la gestion du matériel
        Route::get('/materiel', [GerantMaterielController::class, 'index'])->name('materiel.index');
        Route::post('/materiel', [GerantMaterielController::class, 'store'])->name('materiel.store');
        Route::put('/materiel/{materiel}', [GerantMaterielController::class, 'update'])->name('materiel.update');
        Route::delete('/materiel/{materiel}', [GerantMaterielController::class, 'destroy'])->name('materiel.destroy');

        // Routes pour la gestion des horaires
        Route::get('/horaires', [GerantHoraireController::class, 'index'])->name('horaires.index');
        Route::post('/horaires', [GerantHoraireController::class, 'store'])->name('horaires.store');
        Route::put('/horaires/{horaire}', [GerantHoraireController::class, 'update'])->name('horaires.update');
        Route::delete('/horaires/{horaire}', [GerantHoraireController::class, 'destroy'])->name('horaires.destroy');

        // Routes pour la gestion des abonnements
        Route::get('/abonnements', [GerantAbonnementController::class, 'index'])->name('abonnements');
    });

    // Routes pour les coachs
    Route::middleware(['auth', \App\Http\Middleware\VerifyRole::class.':coach'])->prefix('coach')->name('coach.')->group(function () {
        Route::get('/dashboard', [CoachController::class, 'dashboard'])->name('dashboard');
    });

    // Routes pour les clients
    Route::middleware(['auth', \App\Http\Middleware\VerifyRole::class.':client'])->prefix('client')->name('client.')->group(function () {
        Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    });
});

// Routes pour la réinitialisation du mot de passe
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.update');

// Routes d'authentification
require __DIR__.'/auth.php';
