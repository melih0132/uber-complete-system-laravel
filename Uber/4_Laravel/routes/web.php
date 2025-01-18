<?php

use App\Http\Controllers\TranslationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

// use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\ServiceCourseController;
use App\Http\Controllers\CoursierController;
use App\Http\Controllers\LivreurController;

use App\Http\Controllers\CourseController;

use App\Http\Controllers\ResponsableEnseigneController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\PanierController;

use App\Http\Controllers\VeloController;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SecurityController;

use App\Http\Controllers\CarteBancaireController;

use App\Http\Controllers\EntretienController;
use App\Http\Controllers\LogistiqueController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\FacturationController;

use App\Http\Controllers\JuridiqueController;

use App\Http\Controllers\BotManController;

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
})->name('accueil');


// * DEMANDE DE COURSE
// 1 - demande de course - affichage des présations
Route::post('/drive', [CourseController::class, 'index'])->name('course.index');

// 2 - visu détails de la réservation
Route::match(['get', 'post'], '/drive/details', [CourseController::class, 'showDetails'])->name('course.details');

// 3 - début course
Route::post('/drive/search-driver', [CourseController::class, 'searchDriver'])->name('course.searchDriver');
Route::get('/drive/search-driver', [CourseController::class, 'createCourse'])->name('course.createCourse');
Route::post('/drive/validate', [CourseController::class, 'validateCourse'])->name('course.validate');
Route::post('/drive/cancel', [CourseController::class, 'cancelCourse'])->name('course.cancel');

// 4 - fin course - avec facture
Route::post('/drive/add-tip-rate', [CourseController::class, 'addTipAndRate'])->name('course.addTipRate');
Route::post('/courses/{id}/update', [CourseController::class, 'updateCourse'])->name('courses.update');
Route::post('/invoice/reservation/{idreservation}', [FacturationController::class, 'generateInvoiceCourse'])->name('invoice.view');

Route::get('/favorites-suggestions', [CourseController::class, 'getFavorites'])->name('favorites.suggestions');











// * POV COURSIER
// Entretien RH
Route::get('/coursier/entretien', [CoursierController::class, 'entretien'])->name('coursier.entretien');
Route::post('/coursier/entretien/valider/{entretien}', [CoursierController::class, 'validerEntretien'])->name('coursier.entretien.valider');
Route::post('/coursier/entretien/annuler/{entretien}', [CoursierController::class, 'annulerEntretien'])->name('coursier.entretien.annuler');
Route::get('/coursier/entretien/planifie', [CoursierController::class, 'planifie'])->name('coursier.entretien.planifie');

// Entretien Logistque
Route::get('/conducteurs/demandes/{id}', [LogistiqueController::class, 'afficherDemandesParCoursier'])->name('conducteurs.demandes');
Route::post('/vehicules/{id}/complete-modification', [LogistiqueController::class, 'markModificationAsCompleted'])->name('vehicules.completeModification');

// Iban Coursier
Route::get('/coursier/iban', [CoursierController::class, 'afficherIban'])->name('coursier.iban');
Route::post('/coursier/ajouter/iban', [CoursierController::class, 'saisirIban'])->name('coursier.ajouter.iban');

// Coursier consulte les demandes de courses 'pas immédiate'
Route::get('/coursier/courses', [CoursierController::class, 'index'])->name('coursier.courses.index');

Route::post('/coursier/courses/accept/{idreservation}', [CoursierController::class, 'acceptTask'])->name('coursier.courses.accept');
Route::post('/coursier/courses/cancel/{idreservation}', [CoursierController::class, 'cancelTask'])->name('coursier.courses.cancel');
Route::post('/coursier/courses/finish/{idreservation}', [CoursierController::class, 'finishTask'])->name('coursier.courses.finish');









// ! Uber Eats
Route::get('/UberEats', [EtablissementController::class, 'accueilubereats'])->name('etablissement.accueilubereats');
Route::get('/UberEats/etablissements', [EtablissementController::class, 'index'])->name('etablissement.index');

Route::get('/UberEats/etablissements/filtrer', [EtablissementController::class, 'filtrageEtablissements'])->name('etablissement.filtrage');
Route::get('/UberEats/etablissements/details/{idetablissement}', [EtablissementController::class, 'detail'])->name('etablissement.detail');

// Gestion du panier
Route::get('/panier', [PanierController::class, 'index'])->name('panier.index');
Route::post('/panier/ajouter', [PanierController::class, 'ajouterAuPanier'])->name('panier.ajouter');
Route::put('/panier/mettre-a-jour/{idproduit}', [PanierController::class, 'mettreAJour'])->name('panier.mettreAJour');
Route::delete('/panier/supprimer/{idProduit}', [PanierController::class, 'supprimerDuPanier'])->name('panier.supprimer');
Route::post('/panier/vider', [PanierController::class, 'viderPanier'])->name('panier.vider');

// Commander -> nécessite la connexion
Route::get('/panier/commander/choix-livraison', [CommandeController::class, 'choisirModeLivraison'])->name('commande.choixLivraison');
Route::post('/panier/commander/choix-livraison', [CommandeController::class, 'choisirModeLivraisonStore'])->name('commande.choixLivraisonStore');

Route::get('/panier/commander/choix-carte', [CommandeController::class, 'choisirCarteBancaire'])->name('commande.choisirCarteBancaire');
Route::post('/panier/commander/enregistrer-commande', [CommandeController::class, 'enregistrerCommande'])->name('commande.enregistrer');

// Route::match(['get', 'post'], '/panier/commander/paiement', [CommandeController::class, 'paiementCarte'])->name('commande.paiementCarte');

Route::get('/commande/confirmation/{id}', [CommandeController::class, 'confirmation'])->name('commande.confirmation');

Route::get('/mes-commandes', [CommandeController::class, 'mesCommandes'])->name('commande.mesCommandes');
Route::post('/commandes/{id}/informer-refus', [CommandeController::class, 'informerRefus'])->name('commande.informerRefus');





// * POV LIVREUR
Route::get('/livreur/livraisons', [LivreurController::class, 'index'])->name('coursier.livraisons.index');

Route::get('/livreur/livraisons-affectees', [LivreurController::class, 'livraisonsEnCours'])->name('livreur.livraisons.encours');
Route::put('/livreur/livraisons/{idcommande}/livree', [LivreurController::class, 'marquerLivree'])->name('livreur.livraisons.marquerLivree');

Route::post('/coursier/livraisons/accept/{idreservation}', [LivreurController::class, 'acceptTaskLivreur'])->name('coursier.livraisons.accept');
Route::post('/coursier/livraisons/cancel/{idreservation}', [LivreurController::class, 'cancelTaskLivreur'])->name('coursier.livraisons.cancel');
Route::post('/coursier/livraisons/finish/{idreservation}', [LivreurController::class, 'finishTaskLivreur'])->name('coursier.livraisons.finish');




// ! Uber Velo
Route::get('/UberVelo', [VeloController::class, 'accueilVelo'])->name('velo.show');

Route::post('/UberVelo', [VeloController::class, 'index'])->name('velo.index');

Route::get('/UberVelo/velos/details-velo/{id}', [VeloController::class, 'showDetailsVelo'])->name('velo.details');
Route::get('/UberVelo/velos/details-reservation/{id}', [VeloController::class, 'showReservationDetails'])->name('velo.reservation');

Route::post('/UberVelo/velos/reserver/{id}', [VeloController::class, 'validateReservation'])->name('velo.reserver');
Route::get('/UberVelo/paiement', [VeloController::class, 'paiementReservation'])->name('velo.confirmation');

Route::match(['get', 'post'], '/UberVelo/reservation-effectuer', [VeloController::class, 'finaliserPaiement'])->name('velo.fin-reservation');

/* Route::post('/UberVelo/velos/confirmation/{id}', [VeloController::class, 'confirmation'])->name('velo.confirmation'); */



// * POV RESTAURATEUR
// Ajout d'un établissement
Route::get('/UberEats/etablissements/ajouter', [ResponsableEnseigneController::class, 'add'])->name('etablissement.create');
Route::post('/UberEats/etablissements/ajouter', [ResponsableEnseigneController::class, 'store'])->name('etablissement.store');

// Gestion de la bannière d'un établissement
Route::get('/UberEats/etablissements/{id}/banniere/ajouter', [ResponsableEnseigneController::class, 'addBanner'])->name('etablissement.banner.create');
Route::post('/UberEats/etablissements/banniere/enregistrer', [ResponsableEnseigneController::class, 'storeBanner'])->name('etablissement.banner.store');

// Ajout de produits
Route::get('/UberEats/produits/create', [ResponsableEnseigneController::class, 'createProduit'])->name('manager.produits.create');
Route::post('/UberEats/produits/store', [ResponsableEnseigneController::class, 'storeProduit'])->name('manager.produits.store');

Route::get('produits', [ResponsableEnseigneController::class, 'indexProduits'])->name('manager.produits.index');


// * POV RESPONSABLE
// Gestion des commandes
Route::get('/UberEats/etablissements/commandes', [ResponsableEnseigneController::class, 'commandes'])->name('responsable.commandes');
Route::get('/UberEats/etablissements/{id}/commandes/prochaine-heure', [ResponsableEnseigneController::class, 'commandesProchaineHeure'])->name('responsable.ordernextHour');

Route::get('/commandes/search-livreurs', [ResponsableEnseigneController::class, 'searchLivreurs'])->name('responsable.search-livreur');
Route::post('/commandes/{idcommande}/assigner-livreur', [ResponsableEnseigneController::class, 'assignerLivreur'])->name('responsable.assignerlivreur');






// ! Login
Route::get('/interface-connexion', function () {
    return view('interfaces.interface-connexion');
})->name('interface-connexion');

Route::get('/login', function () {
    return view('auth/login');
})->name('login');
Route::get('/login-driver', function () {
    return view('auth/login-driver');
})->name('login-driver');
Route::get('/login-manager', function () {
    return view('auth/login-manager');
})->name('login-manager');
Route::get('/login-service', function () {
    return view('auth/login-service');
})->name('login-service');

Route::post('/login', [LoginController::class, 'auth'])->name('auth');

Route::get('/myaccount', [LoginController::class, 'showAccount'])->name('myaccount');

Route::post('/myaccount/favorites/add', [LoginController::class, 'addFavoriteAddress'])->name('account.favorites.add');
Route::delete('/myaccount/favorites/{id}', [LoginController::class, 'deleteFavoriteAddress'])->name('account.favorites.delete');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// pp
Route::post('/update-profile-image', [LoginController::class, 'updateProfileImage'])->name('update.profile.image');

// MFA
Route::post('/activate-mfa', [SecurityController::class, 'activateMFA'])->name('activateMFA');

Route::post('/send-otp', [SecurityController::class, 'sendOtp'])->name('sendOtp');

Route::get('/otp', function () {
    return view('auth.otp');
})->name('otp');

Route::post('/validate-otp', [SecurityController::class, 'verifyOtp'])->name('verifyOtp');
Route::post('/resend-otp', [SecurityController::class, 'resendOtp'])->name('resendOtp');

// Reset Password
Route::get('/reset-password', [SecurityController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [SecurityController::class, 'resetPassword'])->name('password.update');

// mdp oublié
Route::get('/forget-password', [SecurityController::class, 'forgetPassword'])->name('password.forget');



// ! Register
// client
Route::get('/interface-inscription', function () {
    return view('interfaces.interface-inscription');
});

Route::post('/register/passenger/form', [RegisterController::class, 'register'])->name('register')->middleware('throttle:5,1');
Route::post('/register/eats/form', [RegisterController::class, 'register'])->name('register')->middleware('throttle:5,1');

Route::get('/register/passenger', [RegisterController::class, 'showPassengerRegistrationForm'])->name('register.passenger');
Route::get('/register/eats', [RegisterController::class, 'showEatsRegistrationForm'])->name('register.eats');

// coursier
Route::get('/interface-inscription-coursier', function () {
    return view('interfaces.interface-inscription-coursier');
})->name('login-coursier');

Route::post('/register/driver/form', [RegisterController::class, 'register'])->name('register')->middleware('throttle:5,1');
Route::post('/register/deliverer/form', [RegisterController::class, 'register'])->name('register')->middleware('throttle:5,1');

Route::get('/register/coursier/{role}', [RegisterController::class, 'registerCoursier'])->name('register.coursier');
Route::get('/register/driver', [RegisterController::class, 'showDriverRegistrationForm'])->name('register.driver');
Route::get('/register/deliverer', [RegisterController::class, 'showDelivererRegistrationForm'])->name('register.deliverer');

// manager
Route::get('/interface-inscription-manager', function () {
    return view('interfaces.interface-inscription-manager');
});

Route::post('/register/brandmanager/form', [RegisterController::class, 'register'])->name('register')->middleware('throttle:5,1');
Route::post('/register/restaurateur/form', [RegisterController::class, 'register'])->name('register')->middleware('throttle:5,1');

Route::get('/register/manager/{role}', [RegisterController::class, 'registerManager'])->name('register.manager');
Route::get('/register/brandmanager', [RegisterController::class, 'showBrandManagerRegistrationForm'])->name('register.brandmanager');
Route::get('/register/restaurateur', [RegisterController::class, 'showRestaurateurRegistrationForm'])->name('register.restaurateur');



// ! Delete
Route::delete('/client/delete', [JuridiqueController::class, 'demandeSuppression'])->name('user.delete');







// Carte Bancaire
Route::get('/carte-bancaire', [CarteBancaireController::class, 'index'])->name('carte-bancaire.index');
Route::get('/carte-bancaire/create', [CarteBancaireController::class, 'create'])->name('carte-bancaire.create');
Route::post('/carte-bancaire', [CarteBancaireController::class, 'store'])->name('carte-bancaire.store');
Route::delete('/carte-bancaire/{id}', [CarteBancaireController::class, 'destroy'])->name('carte-bancaire.destroy');










// ! SERVICE UBER
// RH
Route::get('/entretiens/rechercher', [EntretienController::class, 'rechercher'])->name('entretiens.rechercher');

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


// Service Adminisratif
Route::get('/admin', [AdministrationController::class, 'index'])->name('admin.index');

Route::get('/admin/search', [AdministrationController::class, 'searchCoursiers'])->name('admin.search-coursiers');

Route::post('/admin/demander-iban/{idcoursier}', [AdministrationController::class, 'demanderIban'])->name('admin.demander-iban');
Route::delete('/admin/validation/supprimer/{idcoursier}', [AdministrationController::class, 'supprimerCoursier'])->name('admin.validation.supprimer');


// Service Facturation
Route::get('/facturation/search-coursiers', [FacturationController::class, 'searchCoursiers'])->name('facturation.search-coursiers');

Route::get('/facturation', [FacturationController::class, 'index'])->name('facturation.index');

Route::post('/facturation/filter', [FacturationController::class, 'filterTrips'])->name('facturation.filter');
Route::post('/facturation/generate', [FacturationController::class, 'generateInvoice'])->name('facturation.generate');


// Service Juridique
Route::get('/juridique/anonymisation', [JuridiqueController::class, 'showAnonymisationForm'])->name('juridique.anonymisation');
Route::post('/juridique/anonymisation', [JuridiqueController::class, 'anonymise'])->name('juridique.anonymisation.submit');

Route::get('/privacy', function () {
    return view('juridique.privacy');
})->name('privacy');

Route::get('/cgu', function () {
    return view('juridique.cgu');
})->name('cgu');


// Service Commande
Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');

Route::post('/commandes/{id}/refuser', [CommandeController::class, 'enregistrerRefus'])->name('commande.refuser');
Route::post('/commandes/{id}/rembourser', [CommandeController::class, 'rembourserCommande'])->name('commande.rembourser');
Route::post('/commandes/{id}/mettre-a-jour-statut', [CommandeController::class, 'mettreAJourStatut'])->name('commande.mettreAJourStatut');


// Service Course
Route::get('/directeur/course', [ServiceCourseController::class, 'index'])->name('serviceCourse.index');
Route::get('/directeur/course/analyse', [ServiceCourseController::class, 'analyse'])->name('serviceCourse.analyse');
Route::get('/directeur/course/analyse/statistiquesMensuelles', [ServiceCourseController::class, 'statistiquesMensuelles'])->name('serviceCourse.statistiquesCourses');
Route::get('/directeur/course/analyse/statistiquesMontants', [ServiceCourseController::class, 'statistiquesMontants'])->name('serviceCourse.statistiquesMontants');
Route::get('/directeur/course/analyse/statistiquesPrestations', [ServiceCourseController::class, 'statistiquesPrestations'])->name(name: 'serviceCourse.statistiquesPrestations');
Route::get('/directeur/course/analyse/statistiquesGeo', [ServiceCourseController::class, 'statistiquesGeo'])->name('serviceCourse.statistiquesGeo');







// ! GUIDE
Route::get('Uber/guide', function () {
    return view('guide.aide-uber');
});

Route::get('/UberEats/guide', function () {
    return view('guide.aide-uber-eat');
});

Route::post('/translate', [TranslationController::class, 'translate']);





// ! CHATBOT
Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);
