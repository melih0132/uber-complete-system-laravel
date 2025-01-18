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

        $botman->hears('.*bonjour.*|.*salut.*|.*hola.*|.*hello.*|.*wesh.*|.*coucou.*|.*bonsoir.*', function (Botman $bot) {
            $currentHour = (int) date('H');
            if ($currentHour > 6 && $currentHour < 12) {
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
                $bot->reply('Buenas noches 🌜');
                $bot->typesAndWaits(1);
                $bot->reply("Comment puis-je vous aider aujourd'hui ?");
            }
        });

        $botman->hears('.*(services.*proposez|services disponibles|vous faites quoi|tes services|quels services).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Nous proposons trois services :<br>
                        1. Uber (voiture ou chauffeur).<br>
                        2. Uber Vélo (location ou livraison à vélo).<br>
                        3. Uber Eats (livraison de repas).<br>
                        En quoi puis-je vous aider ?");
        });

        $botman->hears('.*guide.*|besoin.*aide.*|.*perdu.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Vous trouverez de l'aide dans le guide en cliquant sur 'Aide' dans la barre de menu en haut toutes les étapes vous seront détaillées.");
        });

        $botman->hears('.*inscription.*|création.*compte.*|créer.*compte.*|.*inscrire.*|.*pas de compte.*|.*pas inscrit.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour créer votre compte Uber, cliquez sur 'Inscription' dans la barre de menu en haut à droite.");
        });

        $botman->hears('.*devenir.*chauffeur.*|.*offre.*emploi.*uber.*|.*recrute.*chauffeur.*|.*livreur.*|.*postuler.*uber.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour devenir chauffeur ou livreur Uber :<br>
                         1. Rendez-vous sur la page 'Devenir Chauffeur/Livreur' accessible depuis le pied de page ou la rubrique 'Aide'.<br>
                         2. Remplissez le formulaire de candidature avec vos informations personnelles et vos justificatifs (permis de conduire, assurance, etc.).<br>
                         3. Nous étudierons votre dossier et vous contacterons si vous répondez aux critères requis.");
        });

        $botman->hears('.*connexion.*|.*déconnexion.*|.*connecter.*|.*se déconnecte.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour vous connectez à votre compte Uber, cliquez sur 'Connexion' dans la barre de menu en haut à droite.<br>
                        Pour vous déconnectez de votre compte Uber, lorsque vous êtes connecté cliquez sur 'Se déconnecter' dans la barre de menu en haut à droite.");
        });

        $botman->hears('.*modifier.*telephone.*|.*changer.*telephone.*|.*modifier.*email.*|.*changer.*email.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour modifier votre téléphone ou votre email :<br>
                         1. Connectez-vous à votre compte Uber.<br>
                         2. Allez dans la section 'Mon Compte' ou 'Paramètres'.<br>
                         3. Vous pourrez mettre à jour votre numéro de téléphone ou votre adresse email et sauvegarder les changements.");
        });

        $botman->hears('.*code.*promo.*|.*réduction.*|.*promotions.*|.*code.*réduc.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour utiliser un code promotionnel :<br>
                         1. Connectez-vous à votre compte et sélectionnez votre prochaine commande ou course.<br>
                         2. Au moment du paiement, cliquez sur 'Ajouter un code promo'.<br>
                         3. Entrez votre code et validez. Le montant sera ajusté si le code est valide.");
        });

        $botman->hears('.*mot de passe.*oubli.*|.*reinitialiser.*mdp.*|.*password.*|.*changer.*mot de passe.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour réinitialiser ou changer votre mot de passe :<br>
                         1. Cliquez sur 'Connexion' puis sur 'Mot de passe oublié'.<br>
                         2. Vous recevrez un lien de réinitialisation par email.<br>
                         3. Suivez les instructions pour définir un nouveau mot de passe.");
        });

        $botman->hears('.*supprimer.*compte.*|.*fermer.*compte.*|.*suppression.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Je suis désolé de vous voir partir. Voici comment supprimer votre compte Uber Eats :<br>
                         1. Connectez vous à votre compte client et allez dans la section 'Confidentialité et données'.<br>
                         2. Pour supprimer votre compte, cliquez sur 'Supprimer le compte'.<br>
                         Remarque : Une fois le compte supprimé, vous perdrez l'accès à vos données et commandes associées.");
        });

        $botman->hears('.*problème.*chargement.*|chargement.*page.*|.*charge.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Je suis désolé d'entendre que vous rencontrez des problèmes de chargement de pages. Voici quelques suggestions :<br>
                         1. Assurez-vous d'avoir une connexion Internet stable.<br>
                         2. Essayez de rafraîchir la page.<br>
                         3. Vérifiez si d'autres navigateurs ou appareils fonctionnent correctement.<br>
                         4. Patientez, la connexion peut revenir après un court instant.");
        });

        $botman->hears('.*problème.*géolocalisation.*|.*localisation.*|.*gps.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("En cas de problème de géolocalisation :<br>
                         1. Vérifiez que votre GPS est activé sur votre téléphone et que vous avez autorisé l'application à y accéder.<br>
                         2. Essayez de relancer l'application ou de rafraîchir la page si vous êtes sur un site web.<br>
                         3. Si le problème persiste, contactez le support client.");
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
                         1. Connectez-vous à votre compte client et allez dans la section 'Lieux favoris'.<br>
                         2. Vous verrez ici tous les favoris que vous avez créés.<br>
                         3. Vous pouvez en ajouter en cliquant sur le '+' ou en supprimer en cliquant sur la corbeille.<br>
                         Remarque : Pour modifier un favori, supprimez-le et recréez-le, c'est rapide.");
        });

        $botman->hears('.*faire.*course.*|.*effectue.*course.*|.*réserve.*course.*|.*faire.*réservation.*|.*effectuer.*réservation.*|.*facture.*|.*faire.*uber.*|.*effectue.*uber.*|.*réserve.*uber.*|.*prestation.*', function (BotMan $bot) {
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

        $botman->hears('.*faire.*commande.*|.*effectuer.*commande.*|.*ajouter.*produit.*|.*ajouter.*panier.*', function (BotMan $bot) {
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

        $botman->hears('.*ma commande.*|.*voir.*commande.*|.*mes.*commande.*|.*dernière.*commande.*|.*compte.*|.*ma course.*|.*voir.*course.*|.*mes.*course.*|.*dernière.*course.*|.*livraison.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Si vous voulez voir vos courses ou vos commandes passées ou en cours, voici comment faire :<br>
                         1. Connectez-vous à votre compte client.<br>
                         2. Pour voir les courses cliquez sur 'Mon Compte' dans la barre de menu Uber en haut à droite, puis dans la partie 'Planning des courses' vous verrez ici toutes vos courses passées et en cours.<br>
                         3. Pour voir les commandes cliquez sur 'Mes commandes' dans la barre de menu Uber Eats en haut à gauche.");
        });

        $botman->hears('.*délai.*livraison.*|.*quand.*arrive.*commande.*|.*commande.*trop longue.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply(
                "Les délais de livraison sur Uber Eats varient selon l'établissement et l'adresse de livraison.<br>
                         1. Vous pouvez suivre en temps réel le statut de votre commande dans votre compte.<br>
                         2. Si le délai dépasse le temps estimé, vous pouvez contacter le support ou le restaurant pour vérifier l'avancement."
            );
        });

        $botman->hears('.*retour.*produit.*|.*remboursement.*commande.*|.*litige.*commande.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply(
                "Pour demander un retour ou un remboursement :<br>
                         1. Rendez-vous dans 'Mes commandes' et sélectionnez la commande concernée.<br>
                         2. Cliquez sur 'Signaler un problème' ou 'Demander un remboursement'.<br>
                         3. Décrivez la situation (produit manquant, défectueux, etc.).<br>
                         4. Notre équipe de support examinera votre demande et vous contactera."
            );
        });

        $botman->hears('.*mon compte.*|.*compte.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Si vous voulez voir votre compte et tous les éléments de celui-ci, voici comment faire :<br>
                         1. Connectez-vous à votre compte client.<br>
                         2. Puis cliquez sur 'Mon Compte dans la barre de menu en haut à droite.
                         3. Vous pourrez ainsi voir les informations du compte, les lieux favoris, le planning des courses mais aussi gérer vos cartes bancaires, changer motre mot de passe...");
        });

        $botman->hears('.*vélo.*|.*bicyclette.*|.*velo.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Si vous voulez réserver un vélo avec Uber Velo, voici comment faire :<br>
                         1. Connectez-vous à votre compte client.<br>
                         2. Dans la barre de menu en haut, cliquez sur 'Uber Velo'.<br>
                         3. Sur la page d'accueil, saisissez les informations de votre réservation (lieu, date, heure, durée) puis cliquez sur 'Voir les vélos disponibles'.<br>
                         4. Choisissez ensuite un vélo parmi ceux disponibles en cliquant sur 'Réserver'.<br>
                         5. Vous pourrez enfin régler votre réservation.");
        });

        $botman->hears('.*prix.*|.*tarif.*|.*combien.*coute.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply(
                "Les tarifs dépendent de plusieurs facteurs : distance, heure de la journée, demande...<br>
                 1. Avant de commander, vous pouvez voir une estimation du prix dans l'application ou sur le site.<br>
                 2. Les majorations (tarifs plus élevés) peuvent s'appliquer en période de forte demande."
            );
        });

        $botman->hears('.*livrer.*quel.*pays.*|.*pays.*disponible.*|.*dans.*quel.*pays.*uber.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply(
                "Uber est disponible dans de nombreux pays et villes à travers le monde. Pour vérifier la disponibilité :<br>
                 1. Consultez la page officielle 'Uber dans le monde' (souvent en bas du site).<br>
                 2. Entrez votre ville pour voir si Uber y est proposé.<br>
                 3. Vous pouvez également télécharger l'application et vérifier directement si la zone est couverte."
            );
        });

        $botman->hears('.*plaint.*|.*réclamation.*|.*litige.*|.*problème.*grave.*|.*contester.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply(
                "Pour effectuer une réclamation ou déclarer un litige :<br>
                 1. Allez dans votre historique de courses ou de commandes et sélectionnez l'élément concerné.<br>
                 2. Cliquez sur 'Signaler un problème' ou 'Contester cette transaction'.<br>
                 3. Décrivez la situation en détail, et un agent du service client vous répondra rapidement."
            );
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
                         Ensuite, vous accéderez à la page vous permettant de noter la course et de donner un pourboire si vous le souhaitez.");
        });

        // bah tu l'avais fait finalement chef, pk t'a dis que tu l'avais pas fait, wallah pardon
        $botman->hears('.*temps.*attente.*|.*chauffeur.*retard.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Le temps d'attente peut varier selon les conditions de circulation et l'emplacement du chauffeur.
                         Vous pouvez suivre la position du chauffeur en temps réel dans l'application Uber. Si l'attente est excessive, vous pouvez annuler et essayer de réserver à nouveau.");
        });

        $botman->hears('.*modifier.*panier.*|.*supprimer.*panier.*|.*enlever.*panier.*|.*modifier.*quantité.*|.*changer.*quantité.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Une fois connecté, pour gérer votre panier :<br>
                         1. Rendez-vous dans le panier en cliquant sur l'icône de panier dans la barre de menu Uber Eats en haut à droite.<br>
                         2. Vous verrez ici tous les produits ajoutés au panier.<br>
                         3. Si vous souhaitez supprimer tout le panier, cliquez sur 'Vider le panier'.<br>
                         4. Pour retirer un seul produit, cliquez sur la corbeille à côté du produit.<br>
                         5. Pour changer la quantité, utilisez les flèches sur la ligne du produit. Vous pouvez commander jusqu'à 99 fois le même produit.");
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
                         3. Pour supprimer une carte, sélectionnez-la et appuyez sur l'icône corbeille.");
        });

        $botman->hears('.*choix.*date.*|.*choix.*heure.*|.*choix.*horaire.*|.*choisir.*heure.*|.*choisir.*date.*|.*choisir.*horaire.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour choisir la date et l'heure de votre course :<br>
                         Lorsque vous êtes sur la page d'accueil, cliquez sur la date du jour et sélectionnez la bonne date,
                         puis cliquez sur 'Maintenant' et sélectionnez l'horaire souhaité.");
        });

        $botman->hears('.*ajout.*établissement.*|.*ajout.*restaurant.*|.*restaurateur.*|.*responsable enseigne.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Vous êtes restaurateur / responsable d'enseigne et vous souhaitez ajouter votre établissement sur Uber Eats ?<br>
                         1. Inscrivez-vous en cliquant sur 'Inscription' dans la barre de menu en haut à droite.<br>
                         2. Vous arriverez sur la page d'interface inscription, sélectionnez celle en bas à droite, puis choisissez restaurateur ou responsable d'enseigne.<br>
                         3. Renseignez toutes les informations nécessaires à l'inscription, puis créez votre compte.<br>
                         4. Enfin connectez vous à l'aide des informations que vous avez saisi précedemment, vous pourrez alors ajouter votre établissement, vos produits, vos menus...");
        });

        $botman->hears('.*contacter.*support.*|.*service client.*|.*assistance.*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour contacter notre support client, vous pouvez :<br>
                         1. Utiliser le formulaire de contact disponible en bas de chaque page.<br>
                         2. Nous appeler directement au numéro suivant : 01 23 45 67 89.<br>
                         3. Consulter la FAQ dans la section 'Aide' pour résoudre vous-même les problèmes les plus courants.");
        });

        // le boss qui se fait plaiz ici
        $botman->hears('.*melih.*', function (BotMan $bot) {
            $bot->typesAndWaits(3);
            $bot->reply("el cooké");
        });

        $botman->hears('.*nathan.*', function (BotMan $bot) {
            $bot->typesAndWaits(3);
            $bot->reply("visca Barca y visca cataloña 🔴🔵");
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

        // redirection quand imcrompréhension
        $botman->fallback(function (BotMan $bot) {
            $bot->typesAndWaits(1);
            $bot->reply("Désolé, je ne comprends pas votre demande. Pouvez-vous reformuler ou préciser votre problème ?");
            $bot->typesAndWaits(1);
            $bot->reply("Si vous avez besoin d'aide cliquez sur 'Aide' dans la barre de menu.");
        });

        $botman->listen();
    }
}
