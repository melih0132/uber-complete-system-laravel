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


        $botman->hears('.*bonjour.*|.*salut.*|.*hola.*|.*hello.*|.*wesh.*|.*coucou.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Bonjour ! <br>Comment puis-je vous aider aujourd'hui ?");
        });
        $botman->hears('.*guide.*|besoin.*aide.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Vous trouverez de l'aide dans le guide en cliquant sur 'Aide' dans la barre de menu en haut toutes les étapes vous seront détaillées.");
        });

        $botman->hears('.*problème.*chargement.*|chargement.*page.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Je suis désolé d'entendre que vous rencontrez des problèmes de chargement de pages. Voici quelques suggestions :<br>
                         1. Assurez-vous d'avoir une connexion Internet stable.<br>
                         2. Essayez de rafraîchir la page.<br>
                         3. Vérifiez si d'autres navigateurs ou appareils fonctionnent correctement.");
        });
        $botman->hears('.*problème.*paiement.|.*paiement.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Si vous avez des problèmes de navigation ou si vous ne trouvez pas la section des promotions, essayez ceci :<br>
                     1. Vérifiez le menu de navigation principal.<br>
                     2. Utilisez la barre de recherche pour trouver des promotions spécifiques.<br>
                     3. Essayez de vider le cache de votre navigateur.");
        });
        $botman->hears('.*historique.*trajet.*|.*mes courses.*|.*historique.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Vous pouvez consulter l'historique de vos trajets directement dans le site Uber.<br>
                         1. Connectez-vous à votre compte client et allez dans la section 'Planning des courses'.<br>
                         2. Vous y trouverez la liste complète de vos courses passées, avec les détails de chaque course.");
        });
        $botman->hears('.*faire.*course.*|.*effectuer.*course.*|.*réserver.*course.*', function (BotMan $bot) {
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
        $botman->hears('.*faire.*commande.*|.*effectuer.*commande.*|.*commander.*', function (BotMan $bot) {
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

        $botman->hears('.*horaires.*restaurant.*|.*ouvert.*quand.*|.*horaires.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour connaître les horaires d'un restaurant :<br>
                         1. Rendez vous dans la rubrique Uber Eats dans la barre de menu en-haut.<br>
                         2. Ensuite rentrez la ville dans laquelle se trouve votre restaurant.<br>
                         3. Vous verrez tous les établissements proposés par Uber Eats, cliquez sur le restaurant pour voir tous les détails de celui-ci.<br>
                         Remarque : Si le restaurant est fermé, vous ne le verrez pas tant qu'il n'a pas ouvert.");
        });
        $botman->hears('.*ajouter.*carte.*|.*supprimer.*carte.*|ajout.*carte.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour gérer vos cartes bancaires dans Uber Eats :<br>
                         - Connectez-vous à votre compte client et allez dans la section 'Carte Bancaire'.<br>
                         - Pour enregistrer une nouvelle carte, cliquez sur 'Ajouter une carte bancaire'.<br>
                         - Pour supprimer une carte, sélectionnez-la et appuyez sur l'icône corbeille.");
        });

        $botman->hears('.*supprimer.*compte.*|.*fermer.*compte.*|.*suppression.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Je suis désolé de vous voir partir. Voici comment supprimer votre compte Uber Eats :<br>
                         1. Connectez vous à votre compte client et allez dans la section 'Confidentialité et données'.<br>
                         2. Pour supprimer votre compte, cliquez sur 'Supprimer le compte'.<br>
                         Remarque : Une fois le compte supprimé, vous perdrez l'accès à vos données et commandes associées.");
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
            $bot->reply("finis favoris vite");
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
