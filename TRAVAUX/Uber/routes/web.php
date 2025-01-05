<?php

use App\Http\Controllers\TranslationController;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\ServiceCourseController;
use App\Http\Controllers\CoursierController;
use App\Http\Controllers\CourseController;

use App\Http\Controllers\ResponsableEnseigneController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\PanierController;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

use App\Http\Controllers\CarteBancaireController;

use App\Http\Controllers\EntretienController;
use App\Http\Controllers\LogistiqueController;
use App\Http\Controllers\FacturationController;

use App\Http\Controllers\BotManController;
// use Google\Cloud\Dialogflow\V2\SessionsClient;

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
})->name('accueil');


// * DEMANDE DE COURSE AVANT - MARCHE
// 1 - demande de course - affichage des présations
Route::post('/course', [CourseController::class, 'index'])->name('course.index');

// 2 - visu détails de la réservation
Route::post('/course/details', [CourseController::class, 'showDetails'])->name('course.details');

// 3 - début course
Route::post('/course/validate', [CourseController::class, 'validateCourse'])->name('course.validate');
Route::post('/course/cancel', [CourseController::class, 'cancelCourse'])->name('course.cancel');

// 4 - fin course - avec facture
Route::post('/course/add-tip-rate', [CourseController::class, 'addTipAndRate'])->name('course.addTipRate');
Route::post('/invoice/reservation/{idreservation}', [FacturationController::class, 'generateInvoiceCourse'])->name('invoice.view');






// ============================================================
// * DEMANDE DE COURSE IMMÉDIATE
// ============================================================
// ? 1 - Affichage des prestations disponibles pour une demande immédiate
Route::post('/course/immediate', [CourseController::class, 'index'])->name('course.immediate.index');

// ? 2 - Visualisation des détails de la réservation immédiate
Route::get('/course/immediate/details', [CourseController::class, 'showDetails'])->name('course.immediate.details');

// ? 3 - Acceptation de la réservation immédiate proposée
Route::post('/course/immediate/validate', [CourseController::class, 'validateImmediate'])->name('course.immediate.validate');

// ? 4 - Recherche et notification des coursiers dans le secteur par le service course
Route::post('/course/immediate/search-driver', [ServiceCourseController::class, 'searchDriverForImmediate'])->name('course.immediate.search-driver');
Route::post('/course/immediate/ask-driver', [ServiceCourseController::class, 'askDriverForImmediate'])->name('course.immediate.ask-driver');

// 5 - Coursier visualise toutes les courses proposées par le service course
Route::get('/coursier/courses', [CoursierController::class, 'index'])->name('coursier.courses.index');

// ? 6 - Coursier visualise les demandes spécifiques de courses immédiates
Route::get('/coursier/courses/immediate', [CoursierController::class, 'listImmediateCourses'])->name('coursier.courses.immediate');

// ? 7 - Coursier accepte une demande de course immédiate
Route::post('/coursier/courses/immediate/start', [CoursierController::class, 'startImmediateCourse'])->name('coursier.immediate.start');

// ? 8 - Client visualise la course acceptée par le coursier et peut indiquer son début
Route::get('/course/immediate/detail', [CourseController::class, 'detailImmediate'])->name('course.immediate.detail');
Route::post('/course/immediate/start', [CourseController::class, 'startImmediate'])->name('course.immediate.start');

// ? 9 - Coursier indique le début de la course (détails inclus, tel que statut et informations associées)
Route::get('/coursier/courses/immediate/detail', [CoursierController::class, 'detailImmediateCourse'])->name('coursier.immediate.detail');

// ? 10 - Annulation de la course par le client (validation requise si course déjà démarrée)
Route::post('/course/immediate/cancel', [CourseController::class, 'cancelImmediate'])->name('course.immediate.cancel');

// ? 11 - Coursier termine la course
Route::post('/coursier/courses/immediate/finish', [CoursierController::class, 'finishImmediateCourse'])->name('coursier.immediate.finish');

// ? 12 - Client confirme que la course est terminée
Route::post('/course/immediate/finish', [CourseController::class, 'finishImmediate'])->name('course.immediate.finish');

// ? 13 - Client donne un pourboire et une note au coursier, génère une facture si demandé
Route::post('/course/immediate/add-tip-rate', [CourseController::class, 'addTipAndRate'])->name('course.immediate.addTipRate');
Route::post('/course/immediate/invoice/{idreservation}', [FacturationController::class, 'generateInvoiceCourse'])->name('course.immediate.invoice');

// ============================================================
// * DEMANDE DE COURSE NON IMMÉDIATE
// ============================================================
// ? 1 - Affichage des prestations disponibles pour une réservation non immédiate
Route::post('/course/scheduled', [CourseController::class, 'indexScheduled'])->name('course.scheduled.index');

// ? 2 - Visualisation des détails d'une réservation non immédiate
Route::get('/course/scheduled/details', [CourseController::class, 'showScheduledDetails'])->name('course.scheduled.details');

// ? 3 - Acceptation de la réservation non immédiate proposée
Route::post('/course/scheduled/validate', [CourseController::class, 'validateScheduled'])->name('course.scheduled.validate');

// ? 4 - Annulation de la réservation non immédiate
Route::post('/course/scheduled/cancel', [CourseController::class, 'cancelScheduled'])->name('course.scheduled.cancel');

// ? 5 - Visualisation par le client des informations de réservation programmée
Route::get('/course/scheduled/detail', [CourseController::class, 'detailScheduled'])->name('course.scheduled.detail');

// ? 6 - Recherche et notification des coursiers dans le secteur par le service course
Route::post('/course/scheduled/search-driver', [ServiceCourseController::class, 'searchDriverForScheduled'])->name('course.scheduled.search-driver');
Route::post('/course/scheduled/ask-driver', [ServiceCourseController::class, 'askDriverForScheduled'])->name('course.scheduled.ask-driver');

// 7 - Coursier visualise toutes les courses proposées par le service course
// Route::get('/coursier/courses', [CoursierController::class, 'index'])->name('coursier.courses.index');

// ? 8 - Coursier accepte une demande de course non immédiate
Route::post('/coursier/courses/scheduled/accept', [CoursierController::class, 'acceptScheduledCourse'])->name('coursier.scheduled.accept');

// ? 9 - Coursier indique la prise en charge et la fin d'une réservation non immédiate
Route::post('/coursier/courses/scheduled/start', [CoursierController::class, 'startScheduledCourse'])->name('coursier.scheduled.start');
Route::post('/coursier/courses/scheduled/finish', [CoursierController::class, 'finishScheduledCourse'])->name('coursier.scheduled.finish');

// ? 10 - Client donne un pourboire et une note au coursier pour une course non immédiate
Route::post('/course/scheduled/add-tip-rate', [CourseController::class, 'addTipAndRate'])->name('course.scheduled.addTipRate');
Route::post('/course/scheduled/invoice/{idreservation}', [FacturationController::class, 'generateInvoiceCourse'])->name('course.scheduled.invoice');











// * POV COURSIER
// Entretien RH
Route::get('/coursier/entretien', [CoursierController::class, 'entretien'])->name('coursier.entretien');
Route::post('/coursier/entretien/valider/{entretien}', [CoursierController::class, 'validerEntretien'])->name('coursier.entretien.valider');
Route::post('/coursier/entretien/annuler/{entretien}', [CoursierController::class, 'annulerEntretien'])->name('coursier.entretien.annuler');
Route::get('/coursier/entretien/planifie', [CoursierController::class, 'planifie'])->name('coursier.entretien.planifie');

// Entretien Logistque
Route::get('/conducteurs/demandes/{id}', [LogistiqueController::class, 'afficherDemandesParCoursier'])->name('conducteurs.demandes');
Route::post('/vehicules/{id}/complete-modification', [LogistiqueController::class, 'markModificationAsCompleted'])->name('vehicules.completeModification');

// Coursier consulte les demandes de courses 'pas immédiate'
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
Route::post('/panier/commander/choix-carte', [CommandeController::class, 'paiementCarte'])->name('commande.paiementCarte');

Route::get('/panier/commander/enregistrer-commande', [CommandeController::class, 'enregistrerCommande'])->name('commande.enregistrer');
Route::get('/commande/confirmation/{id}', [CommandeController::class, 'confirmation'])->name('commande.confirmation');



/* Route::get('/panier/livraison', function () {
    return view('livraison');
}); */

// Route::post('/panier/commander', [PanierController::class, 'passerCommande'])->name('panier.commander');
// Route::post('/choix-livraison', [CommandeController::class, 'choixLivraison'])->name('mode.livraison');
// Route::get('/commande/{idcommande}', [CommandeController::class, 'show'])->name('commande.show');







// * POV LIVREUR
Route::get('/coursier/livraisons', [CoursierController::class, 'index'])->name('coursier.livraisons.index');

// Coursiers Uber Eats
Route::post('/coursier/livraisons/accept/{idreservation}', [CoursierController::class, 'acceptTask'])->name('coursier.livraisons.accept');
Route::post('/coursier/livraisons/cancel/{idreservation}', [CoursierController::class, 'cancelTask'])->name('coursier.livraisons.cancel');
Route::post('/coursier/livraisons/finish/{idreservation}', [CoursierController::class, 'finishTask'])->name('coursier.livraisons.finish');












// ! Uber Velo
Route::get('/UberVelo', function () {
    return view('uber-velo');
});















// * POV MANAGER
// Ajout d'un établissement
Route::get('/UberEats/etablissements/ajouter', [ResponsableEnseigneController::class, 'add'])->name('etablissement.create');
Route::post('/UberEats/etablissements/ajouter', [ResponsableEnseigneController::class, 'store'])->name('etablissement.store');

// Gestion de la bannière d'un établissement
Route::get('/UberEats/etablissements/{id}/banniere/ajouter', [ResponsableEnseigneController::class, 'addBanner'])->name('etablissement.banner.create');
Route::post('/UberEats/etablissements/banniere/enregistrer', [ResponsableEnseigneController::class, 'storeBanner'])->name('etablissement.banner.store');

// Gestion des commandes
Route::get('/UberEats/etablissements/{id}/commandes/prochaine-heure', [ResponsableEnseigneController::class, 'commandesProchaineHeure'])->name('manager.ordernextHour');

Route::get('/commandes/search-coursiers', [ResponsableEnseigneController::class, 'searchCoursiers'])->name('manager.search-coursiers');
Route::post('/commandes/{idcommande}/assigner-livreur', [ResponsableEnseigneController::class, 'assignerLivreur'])->name('assignerLivreur');















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

Route::post('/update-profile-image', [LoginController::class, 'updateProfileImage'])->name('update.profile.image');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Carte Bancaire
Route::get('/carte-bancaire', [CarteBancaireController::class, 'index'])->name('carte-bancaire.index');
Route::get('/carte-bancaire/create', [CarteBancaireController::class, 'create'])->name('carte-bancaire.create');
Route::post('/carte-bancaire', [CarteBancaireController::class, 'store'])->name('carte-bancaire.store');

















// ! Register
Route::get('/interface-inscription', function () {
    return view('interfaces.interface-inscription');
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

// Service Facturation
Route::get('/facturation/search-coursiers', [FacturationController::class, 'searchCoursiers'])->name('facturation.search-coursiers');

Route::get('/facturation', [FacturationController::class, 'index'])->name('facturation.index');

Route::post('/facturation/filter', [FacturationController::class, 'filterTrips'])->name('facturation.filter');
Route::post('/facturation/generate', [FacturationController::class, 'generateInvoice'])->name('facturation.generate');

// Service Course - aller voir en haut pour l'instant
Route::get('/service-course/index', [ServiceCourseController::class, 'index'])->name('service-course.index');
Route::get('/service-course/analyse', [ServiceCourseController::class, 'analyse'])->name('service-course.analyse');


















// ! DROIT
Route::get('/Cookies', function () {
    return view('cookie-politique');
});








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
















// * ? * //
// Route::post('/api/courses/decline', [CourseController::class, 'declineCourse']);
// Route::get('/api/courses/declined', [CourseController::class, 'exportDeclinedCourses']);

// Route::get('/accueil/UberEat', [AccueilUberEatController::class, 'index']);

// Route::post('/send-distance', [CourseController::class, 'receiveDistance']);

/* Route::get('clients/create', [ClientController::class, 'create']);
Route::post('clients', [ClientController::class, 'store']); */
