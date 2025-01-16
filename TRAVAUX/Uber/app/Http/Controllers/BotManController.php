<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Http\Request;

class BotManController extends Controller
{

    public function handle()
    {
        $botman = app('botman');


        $botman->hears('.*bonjour.*|.*salut.*|.*hola.*|.*hello.*|.*wesh.*|.*coucou.*', function(Botman $bot) {
            $currentHour = (int) date('H');
            if ($currentHour < 12) {
                $bot->typesAndWaits(1);
                $bot->reply('Bonjour, bonne matinée 🌞');
                $bot->typesAndWaits(1);
                $bot->reply("Comment puis-je vous aider aujourd'hui ?");
            } elseif ($currentHour < 18) {
                $bot->typesAndWaits(1);
                $bot->reply('Bonjour, bonne après-midi !');
                $bot->typesAndWaits(1);
                $bot->reply("Comment puis-je vous aider aujourd'hui ?");
            } else {
                $bot->typesAndWaits(1);
                $bot->reply('Bonsoir 🌜');
                $bot->typesAndWaits(1);
                $bot->reply("Comment puis-je vous aider aujourd'hui ?");
            }
        });

        $botman->hears('.*va.*', function($bot) {
            $bot->reply('Ça va très bien 😊');
        });

        $botman->hears('.*guide.*|besoin.*aide.*|.*perdu.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Vous trouverez de l'aide dans le guide en cliquant sur 'Aide' dans la barre de menu en haut toutes les étapes vous seront détaillées.");
        });
        $botman->hears('.*inscription.*|création.*compte.*|créer.*compte.*|.*inscrire.*|.*pas de compte.*|.*pas inscrit.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour créer votre compte Uber, cliquez sur 'Inscription' dans la barre de menu en haut à droite.");
        });
        $botman->hears('.*connexion.*|.*déconnexion.*|.*connecter.*|.*se déconnecte.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour vous connectez à votre compte Uber, cliquez sur 'Connexion' dans la barre de menu en haut à droite.<br>
                        Pour vous déconnectez de votre compte Uber, lorsque vous êtes connecté cliquez sur 'Se déconnecter' dans la barre de menu en haut à droite.");
        });
        $botman->hears('.*problème.*chargement.*|chargement.*page.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Je suis désolé d'entendre que vous rencontrez des problèmes de chargement de pages. Voici quelques suggestions :<br>
                         1. Assurez-vous d'avoir une connexion Internet stable.<br>
                         2. Essayez de rafraîchir la page.<br>
                         3. Vérifiez si d'autres navigateurs ou appareils fonctionnent correctement.
                         4. Patientez la connexion va revenir ⏳.");
        });
        $botman->hears('.*problème.*paiement.|.*paiement.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Si vous avez des problèmes pour le paiement consultez le guide en cliquant sur 'Aide' dans la barre de menu en haut.");
        });
        $botman->hears('.*historique.*trajet.*|.*mes courses.*|.*historique.*|.*planning.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Vous pouvez consulter l'historique de vos trajets directement dans le site Uber.<br>
                         1. Connectez-vous à votre compte client et allez dans la section 'Planning des courses'.<br>
                         2. Vous y trouverez la liste complète de vos courses passées, avec les détails de chaque course.");
        });
        $botman->hears('.*modifie.*favori.*|.*change.*favori.*|.*ajoute.*favori.*|.*supprime.*favori.*|.*faire.*favori.*|.*utiliser.*favoris.*|.*ajouter.*favori.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Vous pouvez ajouter, modifier ou supprimer vos favoris directement sur votre compte Uber.<br>
                         1. Connectez-vous à votre compte client et allez dans la section 'lieux favoris'.<br>
                         2. Vous verrez ici tous les favoris que vous avez créés.<br>
                         3. Vous pouvez en ajouter en cliquant sur le '+' ou en supprimer en cliquant sur la corbeille 🗑️.<br>
                         Remarque : Pour modifier un favori, supprimez-le et recréez-le c'est rapide 😉.");
        });
        $botman->hears('.*faire.*course.*|.*effectue.*course.*|.*réserve.*course.*|.*faire.*réservation.*|.*effectuer.*réservation.*|.*facture.*|.*faire.*uber.*|.*effectue.*uber.*|.*réserve.*uber.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Si vous voulez réserver une course, voici comment faire :<br>
                         1. Connectez-vous à votre compte client.<br>
                         2. Sur la page d'accueil, saisissez les informations de votre course (lieu départ, lieu d'arrivée...) puis cliquez sur 'Voir les prestations'.<br>
                         3. Vous verrez alors toutes les prestations, choisissez celle qu'il vous faut puis recherchez.<br>
                         4. Vous arriverez alors sur une page récapitulant toutes les infos saisies précédemment, validez si celles-ci sont correctes.<br>
                         5. Vous serez alors en recherche de coursier et lorsqu'un coursier aura accepté votre course, une nouvelle page apparaitra vous permettant de valider ou d'annuler la course.<br>
                         6. Si vous validez, vous accéderez à la page vous permettant lors de la fin de votre course de la noter et de si vous le souhaitez donner un pourboire.<br>
                         7. Vous pourrez enfin recevoir votre facture ou retourner à l'accueil.
                         Remarque : Vous pouvez aussi vous rendre dans le guide en cliquant sur 'Besoin d'aide' dans la barre de menu en haut, toutes les étapes vous seront détaillées.");
        });
        $botman->hears('.*faire.*commande.*|.*effectuer.*commande.*|.*commander.*|.*ajouter.*produit.*|.*ajouter.*panier.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Si vous voulez commander sur Uber Eats, voici comment faire :<br>
                         1. Connectez-vous à votre compte client.<br>
                         2. Sur la page d'accueil d'Uber Eats, saisissez les informations de votre commande (ville, date, heure) puis cliquez sur 'Rechercher'.<br>
                         3. Vous verrez alors tous les établissements proposés par Uber Eats, cliquez sur le restaurant qui vous intéresse.<br>
                         4. Choisissez ensuite toutes les produits que vous souhaitez commander et ajoutez les au panier.<br>
                         5. Cliquez sur votre panier pour visualiser son contenu, puis sur 'Passer la commande'.<br>
                         6. Vous pourrez enfin choisir votre mode de livraison et renseigner votre adresse si besoin, puis payer afin d'initier la préparation de votre commande et sa livraison.
                         Remarque : Vous pouvez aussi vous rendre dans le guide en cliquant sur 'Aide' dans la barre de menu en haut à droite, toutes les étapes vous seront détaillées.");
        });
        $botman->hears('.*vélo.*|.*bicyclette.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Si vous voulez réserver un vélo avec Uber Velo, voici comment faire :<br>
                         1. Connectez-vous à votre compte client.<br>
                         2. Dans la barre de menu en haut, cliquez sur 'Uber Velo'.<br>
                         3. Sur la page d'accueil, saisissez les informations de votre réservation (lieu, date, heure, durée) puis cliquez sur 'Voir les vélos disponibles'.<br>
                         4. Choisissez ensuite un vélo parmi ceux disponibles en cliquant sur 'Réserver'.<br>
                         5. Vous pourrez enfin régler votre réservation.");
        });
        $botman->hears('.*annulation.*course.*|.*annulé.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Si vous avez annulé une course, voici ce que vous pouvez faire :<br>
                         - Si l'annulation est récente, vous pouvez vérifier les frais d'annulation qui vous seront demandés.<br>
                         - Si vous avez besoin de réserver une nouvelle course, n'hésitez pas à réessayer sur la page d'accueil.");
        });
        $botman->hears('.*ajouter.*note.|.*pourboire.*|.*évaluation.*chauffeur.*|.*évaluer.*|.*noter.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Si vous souhaitez ajouter une note ou une évaluation à un chauffeur, voici comment faire :<br>
                         Après avoir terminé votre course, vous pourrez valider la fin de celle-ci.<br>
                         Ensuite, vous accéderez à la page vous permettant de noter la course et de donner un pourboire si vous le souhaitez 😉");
        });
        $botman->hears('.*temps.*attente.*|.*chauffeur.*retard.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Le temps d'attente peut varier selon les conditions de circulation et l'emplacement du chauffeur.
                         Vous pouvez suivre la position du chauffeur en temps réel dans l'application Uber. Si l'attente est excessive, vous pouvez annuler et essayer de réserver à nouveau.");
        });
        $botman->hears('.*modifier.*panier.*|.*supprimer.*panier.*|.*enlever.*panier.*|.*modifier.*quantité.*|.*changer.*quantité.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Une fois que vous êtes connecté, pour gérer votre panier :<br>
                         1. Rendez vous dans le panier en cliquant sur l'icône 🛒 dans la barre de menu Uber Eats en haut à droite.<br>
                         2. Vous verrez ici tous les produits ajoutés au panier.<br>
                         3. Si vous souhaitez supprimer votre panier cliquez sur 'Vider le panier' vous retirerez tous les produits du panier.<br>
                         4. Si vous souhaitez retirer uniquement un produit cliquez sur la corbeille 🗑️ sur la ligne du produit.<br>
                         5. Si vous voulez changer la quantité pour un produit utiliser les flèches sur la ligne du produit ↕️ (à côté du 1).<br>
                         Remarque : Vous pouvez commander jusqu'à 99 fois le même produits (pour les gourmands 😋).");
        });
        $botman->hears('.*horaires.*restaurant.*|.*ouvert.*quand.*|.*horaires.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour connaître les horaires d'un restaurant :<br>
                         1. Rendez vous dans la rubrique Uber Eats dans la barre de menu en haut.<br>
                         2. Ensuite rentrez la ville dans laquelle se trouve votre restaurant.<br>
                         3. Vous verrez tous les établissements proposés par Uber Eats, cliquez sur le restaurant pour voir tous les détails de celui-ci.<br>
                         Remarque : Si le restaurant est fermé, vous ne le verrez pas tant qu'il n'a pas ouvert.");
        });
        $botman->hears('.*ajout.*carte.*|.*supprimer.*carte.*|.*mode de paiement.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour gérer vos cartes bancaires dans Uber Eats :<br>
                         1. Connectez-vous à votre compte client et allez dans la section 'Carte Bancaire'.<br>
                         2. Pour enregistrer une nouvelle carte, cliquez sur 'Ajouter une carte bancaire'.<br>
                         3. Pour supprimer une carte, sélectionnez-la et appuyez sur l'icône corbeille 🗑️.");
        });
        $botman->hears('.*choix.*date.*|.*choix.*heure.*|.*choix.*horaire.*|.*choisir.*heure.*|.*choisir.*date.*|.*choisir.*horaire.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour choisir la date 📆 et l'heure  ⌚ de votre course :<br>
                        Lorsque vous êtes sur la page d'accueil, cliquez sur la date du jour et sélectionnez la bonne date
                        puis cliquez sur '⏰ Maintenant' et sélectionnez le bon horaire.");
        });
        $botman->hears('.*supprimer.*compte.*|.*fermer.*compte.*|.*suppression.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Je suis désolé de vous voir partir. Voici comment supprimer votre compte Uber Eats :<br>
                         1. Connectez vous à votre compte client et allez dans la section 'Confidentialité et données'.<br>
                         2. Pour supprimer votre compte, cliquez sur 'Supprimer le compte'.<br>
                         Remarque : Une fois le compte supprimé, vous perdrez l'accès à vos données et commandes associées.");
        });
        $botman->hears('.*ajout.*établissement.*|.*ajout.*restaurant.*|.*restaurateur.*|.*responsable enseigne.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Vous êtes restaurateur / responsable d'enseigne et vous souhaitez ajouter votre établissement sur Uber Eats ?<br>
                         1. Inscrivez-vous en cliquant sur 'Inscription' dans la barre de menu en haut à droite.<br>
                         2. Vous arriverez sur la page d'interface inscription, sélectionnez celle en bas à droite, puis choisissez restaurateur ou responsable d'enseigne.<br>
                         3. Renseignez toutes les informations nécessaires à l'inscription, puis créez votre compte.<br>
                         4. Enfin connectez vous à l'aide des informations que vous avez saisi précedemment, vous pourrez alors ajouter votre établissement, vos produits, vos menus...");
        });

        $botman->hears('.*melih.*', function (BotMan $bot) {
            $bot->typesAndWaits(3);
            $bot->reply("melih le chef cuisto 👨🏼‍🍳");
        });

        $botman->hears('.*amir.*', function (BotMan $bot) {
            $bot->typesAndWaits(3);
            $bot->reply("vrai dz");
        });

        $botman->hears('.*feyza.*', function (BotMan $bot) {
            $bot->typesAndWaits(3);
            $bot->reply("DPO ✅");
        });

        $botman->hears('.*nazar.*', function (BotMan $bot) {
            $bot->typesAndWaits(3);
            $bot->reply("ukrainian man");
        });

        $botman->hears('.*Damas.*|.*Luc Damas.*|.*Luc.*|.*M.Damas.*', function (BotMan $bot) {
            $bot->typesAndWaits(3);
            $bot->reply("Bonjour Monsieur Damas bienvenue sur notre site 🧡");
        });

        $botman->fallback(function (BotMan $bot) {
            $bot->typesAndWaits(1);
            $bot->reply("Désolé, je ne comprends pas votre demande. Pouvez-vous reformuler ou préciser votre problème ?");
            $bot->typesAndWaits(1);
            $bot->reply("Vous avez peut-être une faute de frappe 🤔");
        });

        $botman->listen();
    }
}
