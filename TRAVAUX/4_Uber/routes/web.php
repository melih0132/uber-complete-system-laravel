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

use App\Http\Controllers\EntretienController;
use App\Http\Controllers\LogistiqueController;
use App\Http\Controllers\FacturationController;

use App\Http\Controllers\ProductController;
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
















// ! Uber Eats
Route::get('/UberEats', [EtablissementController::class, 'accueilubereats'])->name('etablissement.accueilubereats');
Route::get('/UberEats/etablissements', [EtablissementController::class, 'index'])->name('etablissement.index');
Route::get('/UberEats/etablissements/{idetablissement}', [EtablissementController::class, 'detail'])->name('etablissement.detail');

Route::get('/panier', [PanierController::class, 'index'])->name('panier.index');
Route::post('/panier/ajouter', [PanierController::class, 'ajouterAuPanier'])->name('panier.ajouter');
Route::put('/panier/mettre-a-jour/{idproduit}', [PanierController::class, 'mettreAJour'])->name('panier.mettreAJour');
Route::delete('/panier/supprimer/{idProduit}', [PanierController::class, 'supprimerDuPanier'])->name('panier.supprimer');
Route::post('/panier/vider', [PanierController::class, 'viderPanier'])->name('panier.vider');

// Commander -> nécessite la connexion
Route::post('/panier/commander', [PanierController::class, 'passerCommande'])->name('panier.commander');
Route::get('/commande/{idcommande}', [CommandeController::class, 'show'])->name('commande.show');

Route::get('/UberEats/addRestaurant', [EtablissementController::class, 'add'])->name('etablissement.add');
Route::post('/UberEats/addRestaurant', [EtablissementController::class, 'store'])->name('etablissement.store');
// ajout bannière etablissement
Route::get('/UberEats/etablissement/{id}/add-banner', [EtablissementController::class, 'addBanner'])->name('etablissement.addBanner');
Route::post('/UberEats/store-etablissement-banner', [EtablissementController::class, 'storeBanner'])->name('store.etablissement.banner');

//ajout produit pour les nouveaux etablissements
Route::get('/addProduct', [ProductController::class, 'create'])->name('produit.create');
Route::post('/addProduct', [ProductController::class, 'store'])->name('produit.store');






// ! Uber Velo
Route::get('/UberVelo', function () {
    return view('uber-velo');
});










// * POV COURSIER
// Entretien RH
Route::get('/coursier/entretien', [CoursierController::class, 'entretien'])->name('coursier.entretien');
Route::post('/coursier/entretien/valider/{entretien}', [CoursierController::class, 'validerEntretien'])->name('coursier.entretien.valider');
Route::post('/coursier/entretien/annuler/{entretien}', [CoursierController::class, 'annulerEntretien'])->name('coursier.entretien.annuler');
Route::get('/coursier/entretien/planifie', [CoursierController::class, 'planifie'])->name('coursier.entretien.planifie');

// Entretien Logistque
Route::get('/conducteurs/demandes/{id}', [LogistiqueController::class, 'afficherDemandesParCoursier'])->name('conducteurs.demandes');
Route::post('/vehicules/{id}/complete-modification', [LogistiqueController::class, 'markModificationAsCompleted'])->name('vehicules.completeModification');

// Diff Type Coursier
Route::get('/coursier/courses', [CoursierController::class, 'index'])->name('coursier.courses.index');
Route::get('/coursier/livraisons', [CoursierController::class, 'index'])->name('coursier.livraisons.index');

// Coursiers VTC
Route::post('/coursier/courses/accept/{idreservation}', [CoursierController::class, 'acceptTask'])->name('coursier.courses.accept');
Route::post('/coursier/courses/cancel/{idreservation}', [CoursierController::class, 'cancelTask'])->name('coursier.courses.cancel');
Route::post('/coursier/courses/finish/{idreservation}', [CoursierController::class, 'finishTask'])->name('coursier.courses.finish');

// Coursiers Uber Eats
Route::post('/coursier/livraisons/accept/{idreservation}', [CoursierController::class, 'acceptTask'])->name('coursier.livraisons.accept');
Route::post('/coursier/livraisons/cancel/{idreservation}', [CoursierController::class, 'cancelTask'])->name('coursier.livraisons.cancel');
Route::post('/coursier/livraisons/finish/{idreservation}', [CoursierController::class, 'finishTask'])->name('coursier.livraisons.finish');








// ! Login
Route::get('/interface-connexion', function () {
    return view('interface-connexion');
})->name('interface-connexion');

Route::get('/login', function () {
    return view('auth/login');
})->name('login');
Route::get('/login-driver', function () {
    return view('auth/login-driver');
});
Route::get('/login-manager', function () {
    return view('auth/login-manager');
});
Route::get('/login-service', function () {
    return view('auth/login-service');
});

Route::post('/login', [LoginController::class, 'auth'])->name('auth');
Route::get('/mon-compte', [LoginController::class, 'monCompte'])->name('mon-compte');
Route::post('/update-profile-image', [LoginController::class, 'updateProfileImage'])->name('update.profile.image');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Carte Bancaire
Route::get('/carte-bancaire', [CarteBancaireController::class, 'index'])->name('carte-bancaire.index');
Route::get('/carte-bancaire/create', [CarteBancaireController::class, 'create'])->name('carte-bancaire.create');
Route::post('/carte-bancaire', [CarteBancaireController::class, 'store'])->name('carte-bancaire.store');

// Départ Favoris
Route::get('/favoris', function () {
    return view('favoris');
});




// ! Register
Route::get('/interface-inscription', function () {
    return view('interface-inscription');
});

Route::get('/register/driver', [RegisterController::class, 'showDriverRegistrationForm'])->name('register.driver');
Route::get('/register/passenger', [RegisterController::class, 'showPassengerRegistrationForm'])->name('register.passenger');
Route::get('/register/eats', [RegisterController::class, 'showEatsRegistrationForm'])->name('register.eats');
Route::get('/register/manager', [RegisterController::class, 'showManagerRegistrationForm'])->name('register.manager');

Route::post('/register/driver/form', [RegisterController::class, 'register'])->name('register');
Route::post('/register/passenger/form', [RegisterController::class, 'register'])->name('register');
Route::post('/register/eats/form', [RegisterController::class, 'register'])->name('register');
Route::post('/register/manager/form', [RegisterController::class, 'register'])->name('register');









// ! SERVICE UBER
// RH
Route::get('/entretiens/en-attente', [EntretienController::class, 'index'])->name('entretiens.index');
Route::get('/entretiens/plannifies', [EntretienController::class, 'listePlannifies'])->name('entretiens.plannifies');
Route::get('/entretiens/termines', [EntretienController::class, 'listeTermines'])->name('entretiens.termines');

Route::get('/entretiens/planifier/{id?}', [EntretienController::class, 'showPlanifierForm'])->name('entretiens.planifierForm');
Route::post('/entretiens/planifier/{id?}', [EntretienController::class, 'planifier'])->name('entretiens.planifier');

Route::post('/entretiens/resultat/{id}', [EntretienController::class, 'enregistrerResultat'])->name('entretiens.resultat');
Route::delete('/entretiens/supprimer/{id}', [EntretienController::class, 'supprimer'])->name('entretiens.supprimer');

Route::post('/entretiens/{id}/valider', [EntretienController::class, 'validerCoursier'])->name('entretiens.validerCoursier');
Route::post('/entretiens/{id}/refuser', [EntretienController::class, 'refuserCoursier'])->name('entretiens.refuserCoursier');

// Service Logistique
Route::get('/logistique/vehicules/validation', [LogistiqueController::class, 'index'])->name('logistique.vehicules');

Route::get('/logistique/vehicules/select-coursier', [LogistiqueController::class, 'selectCoursier'])->name('logistique.coursiers.select');
Route::get('/logistique/vehicules/create', [LogistiqueController::class, 'showAddVehiculeForm'])->name('logistique.vehicules.create');
Route::post('/logistique/vehicules/store', [LogistiqueController::class, 'storeVehicule'])->name('logistique.vehicules.store');

Route::post('/vehicules/{id}/valider', [LogistiqueController::class, 'valider'])->name('logistique.vehicules.valider');
Route::post('/vehicules/{id}/refuser', [LogistiqueController::class, 'refuser'])->name('logistique.vehicules.refuser');

Route::get('/logistique/vehicules/modifier/{id}', [LogistiqueController::class, 'showModifierForm'])->name('logistique.vehicules.modifierForm');
Route::post('/logistique/vehicules/modifier/{id}', [LogistiqueController::class, 'demanderModification'])->name('logistique.vehicules.modifier');

Route::get('/logistique/modifications', [LogistiqueController::class, 'afficherModifications'])->name('logistique.modifications');
Route::delete('/modifications/{index}', [LogistiqueController::class, 'supprimerModification'])->name('modifications.supprimer');

// Service Facturation
Route::get('/facturation', [FacturationController::class, 'index'])->name('facturation.index');
Route::post('/facturation/filter', [FacturationController::class, 'filterTrips'])->name('facturation.filter');
Route::post('/facturation/generate', [FacturationController::class, 'generateInvoice'])->name('facturation.generate');





// ! DROIT
Route::get('/Cookies', function () {
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

/* Route::get('clients/create', [ClientController::class, 'create']);
Route::post('clients', [ClientController::class, 'store']); */
