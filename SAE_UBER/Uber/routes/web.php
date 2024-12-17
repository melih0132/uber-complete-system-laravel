<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoursierController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\AccueilUberEatController;
use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FactureController;

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

Route::get('/', function () {
    return view('accueil');
});



Route::get('/UberEats', [EtablissementController::class, 'accueilubereats'])->name('etablissement.accueilubereats');
Route::get('/addRestaurant', [EtablissementController::class, 'add'])->name('etablissement.add');
Route::post('/addRestaurant', [EtablissementController::class, 'store'])->name('etablissement.store');
Route::get('/etablissement', [EtablissementController::class, 'index'])->name('etablissement.index');
Route::get('/etablissement/{idetablissement}', [EtablissementController::class, 'detail'])->name('etablissement.detail');

Route::get('/UberVelo', function () {
    return view('uber-velo');
});




Route::post('/course/detail', [CourseController::class, 'detailCourse'])->name('course.detail');
Route::post('/course', [CourseController::class, 'index'])->name('course.index');
Route::post('/course/valide', [CourseController::class, 'CourseAdd'])->name('course.valide');
Route::post('/course/cancel', [CourseController::class, 'endCourse'])->name('course.cancel');
Route::post('/course/rate-and-invoice', [CourseController::class, 'rateAndInvoice'])->name('course.rateAndInvoice');



Route::post('/{idreservation}/facture', [FactureController::class, 'index'])->name('facture');






Route::get('/coursier', [CoursierController::class, 'index'])->name('coursier.index');
Route::post('/coursier/accept/{idreservation}', [CoursierController::class, 'setBDAccept'])->name('coursier.accept');
Route::post('/coursier/cancel/{idreservation}', [CoursierController::class, 'setBDCancel'])->name('coursier.cancel');


/* Route::post('/api/courses/decline', [CourseController::class, 'declineCourse']);
Route::get('/api/courses/declined', [CourseController::class, 'exportDeclinedCourses']); */


// Route::get('/accueil/UberEat', [AccueilUberEatController::class, 'index']);

Route::get('/commande', [CommandeController::class, 'index']);


Route::get('/map', function () {
    return view('map');
});




Route::post('/note-pourboire', [PanierController::class, 'index'])->name('notePourboire.index');


Route::post('/panier/ajouter', [PanierController::class, 'ajouterAuPanier'])->name('panier.ajouter');
Route::get('/panier', [PanierController::class, 'index'])->name('panier.index');
Route::put('/panier/mettre-a-jour/{idproduit}', [PanierController::class, 'mettreAJour'])->name('panier.mettreAJour');
Route::delete('/panier/supprimer/{idProduit}', [PanierController::class, 'supprimerDuPanier'])->name('panier.supprimer');
Route::post('/panier/vider', [PanierController::class, 'viderPanier'])->name('panier.vider');

// Route::get('/Legal', function () {
//     return view('cookie-politique');
// });

// // Routes d'authentification
// Route::prefix('auth')->group(function () {
//     // Login
//     Route::view('/login', 'auth.login')->name('login.form');
//     Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');
//     Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

//     // Inscription
//     Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
//     Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
// });

// // Route pour le compte utilisateur
// Route::middleware('auth')->group(function () {
//     Route::get('/mon-compte', [LoginController::class, 'monCompte'])->name('mon-compte');
// });






Route::get('clients/create', [ClientController::class, 'create']);
Route::post('clients', [ClientController::class, 'store']);

Route::post('/send-distance', [CourseController::class, 'receiveDistance']);
