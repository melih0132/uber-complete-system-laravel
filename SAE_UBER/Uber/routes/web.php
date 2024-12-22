<?php

use App\Http\Controllers\TranslationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoursierController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\CarteBancaireController;
use App\Models\Categorie_prestation;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ! UBER
Route::get('/', function () {
    return view('accueil');
});

// Gestion des courses
Route::post('/course', [CourseController::class, 'index'])->name('course.index');

// 1 - Avant et pendant la course
Route::post('/course/details', [CourseController::class, 'detailCourse'])->name('course.details');
Route::post('/course/validate', [CourseController::class, 'courseAdd'])->name('course.validate');
Route::post('/course/cancel', [CourseController::class, 'endCourse'])->name('course.cancel');

// 2 - Après la course
Route::post('/course/add-tip-rate', [CourseController::class, 'addTipAndRate'])->name('course.addTipRate');
Route::post('/invoice/reservation/{idreservation}', [FactureController::class, 'index'])->name('invoice.view');


// * POV COURSIER
Route::get('/coursier', [CoursierController::class, 'index'])->name('coursier.index');
Route::post('/coursier/accept/{idreservation}', [CoursierController::class, 'setBDAccept'])->name('coursier.accept');
Route::post('/coursier/cancel/{idreservation}', [CoursierController::class, 'setBDCancel'])->name('coursier.cancel');









// ! Uber Eats
Route::get('/UberEats', [EtablissementController::class, 'accueilubereats'])->name('etablissement.accueilubereats');
Route::get('/addRestaurant', [EtablissementController::class, 'add'])->name('etablissement.add');
Route::post('/addRestaurant', [EtablissementController::class, 'store'])->name('etablissement.store');

Route::get('/etablissement', [EtablissementController::class, 'index'])->name('etablissement.index');
Route::get('/etablissement/{idetablissement}', [EtablissementController::class, 'detail'])->name('etablissement.detail');

Route::get('/commande', [CommandeController::class, 'index']);

Route::post('/panier/ajouter', [PanierController::class, 'ajouterAuPanier'])->name('panier.ajouter');
Route::get('/panier', [PanierController::class, 'index'])->name('panier.index');
Route::put('/panier/mettre-a-jour/{idproduit}', [PanierController::class, 'mettreAJour'])->name('panier.mettreAJour');
Route::delete('/panier/supprimer/{idProduit}', [PanierController::class, 'supprimerDuPanier'])->name('panier.supprimer');
Route::post('/panier/vider', [PanierController::class, 'viderPanier'])->name('panier.vider');

Route::get('/carte-bancaire', [CarteBancaireController::class, 'create'])->name('carte_bancaire.create');
Route::post('/carte-bancaire', [CarteBancaireController::class, 'store'])->name('carte_bancaire.store');

Route::get('/coursier-courses', [CoursierController::class, 'showTrips'])->name('courier.courses');
Route::post('/coursier-courses', [CoursierController::class, 'filterTrips'])->name('courier.courses.filter');



// ! Uber Velo
Route::get('/UberVelo', function () {
    return view('uber-velo');
});










// ! Login
Route::get('/interface-connexion', function () {
    return view('interface-connexion');
});

Route::get('/login', function () {
    return view('auth/login');
})->name('login');

Route::post('/login', [LoginController::class, 'auth'])->name('auth');
Route::get('/mon-compte', [LoginController::class, 'monCompte'])->name('mon-compte');
Route::post('/update-profile-image', [LoginController::class, 'updateProfileImage'])->name('update.profile.image');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ! Register
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');






// ! DROIT
Route::get('/Legal', function () {
    return view('cookie-politique');
});






// ! GUIDE
Route::get('Uber/guide', function () {
    return view('aide-uber');
});

Route::get('/UberEats/guide', function () {
    return view('aide-uber-eat');
});

Route::post('/translate', [TranslationController::class, 'translate']);



// * ? * //
// Route::post('/api/courses/decline', [CourseController::class, 'declineCourse']);
// Route::get('/api/courses/declined', [CourseController::class, 'exportDeclinedCourses']);

// Route::get('/accueil/UberEat', [AccueilUberEatController::class, 'index']);

Route::get('/map', function () {
    return view('map');
});

Route::post('/send-distance', [CourseController::class, 'receiveDistance']);

Route::get('clients/create', [ClientController::class, 'create']);
Route::post('clients', [ClientController::class, 'store']);
