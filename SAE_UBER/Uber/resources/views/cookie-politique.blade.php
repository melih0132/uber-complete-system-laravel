@extends('layouts.app')

@section('title', 'Legal | Uber')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app.blade.css') }}">

@endsection

@section('content')
    <section id="privacy-policy" class="my-5">
        <h1 class="text-center">Politique de protection des données personnelles</h1>

        <div class="container">
            <h3 class="mt-5">Introduction</h3>
            <p>Dans le cadre de ses activités, la société Uber, dont le siège social est situé à San Francisco en
                Californie, est amenée à collecter et à traiter des informations dont certaines sont qualifiées des «
                données personnelles ». Uber attache une grande importance au respect de la vie privée, et n’utilise que des
                données de manière responsable et confidentielle et dans une finalité précise.</p>

            <h3>Données personnelles</h3>
            <p>Sur le site web Uber, il y a plusieurs types de données personnelles susceptibles d’être recueillies :</p>
            <ul>
                <li>Les données fournies par les utilisateurs ou chauffeurs par des formulaires d’inscription ou de contact.
                    Les informations fournies sont le prénom, le nom, l’adresse e-mail, le numéro de téléphone, et des
                    informations concernant les chauffeurs du type permis de conduire, assurance, et d’autres informations
                    professionnelles nécessaires pour valider leur profil et leur éligibilité à conduire pour Uber.</li>
                <li>Les données fournies lors d’une configuration des profils comme le moyen de paiement, les adresses
                    fournies pour le départ/arrivée d’une course en favoris.</li>
                <li>Les données collectées lors des demandes d’aide grâce aux formulaires de contact sur le site ou par
                    email comme le motif de la demande, les détails du problème concerné et toute autre information
                    nécessaire pour résoudre le problème.</li>
            </ul>

            <h3>Les données transmises directement</h3>
            <p>Ces données sont celles que vous nous transmettez directement, via un formulaire de contact ou bien par
                contact direct par email. Sont obligatoires dans le formulaire de contact les champs « prénom », « nom », «
                entreprise ou organisation » et « email ».</p>

            <h3>Les données collectées automatiquement</h3>
            <p>Lors de vos visites, une fois votre consentement donné, nous pouvons recueillir des informations de type «
                web Analytics » relatives à votre navigation, la durée de votre consultation, votre adresse IP, votre type
                et version de navigateur. La technologie utilisée est le cookie.</p>
            <p><strong>Utilisation des données</strong></p>
            <p>Les données que vous nous transmettez directement sont utilisées dans le but de vous recontacter et/ou dans
                le cadre de la demande que vous nous faites. Les données « web Analytics » sont collectées de forme anonyme
                (en enregistrant des adresses IP anonymes) par Google Analytics, et nous permettent de mesurer l'audience de
                notre site web, les consultations et les éventuelles erreurs afin d’améliorer constamment l’expérience des
                utilisateurs. Ces données sont utilisées par Uber ainsi que par le responsable du traitement des données, et
                ne seront jamais cédées à un tiers ni utilisées à d’autres fins que ceux détaillés ci-dessus.</p>

            <h3>Base légale</h3>
            <p>Les données personnelles collectées par Uber ne sont collectées qu’après consentement obligatoire de
                l’utilisateur. Ce consentement est recueilli de manière valable (boutons et cases à cocher), libre, clair et
                sans équivoque.</p>

            <h3>Durée de conservation</h3>
            <p>Des données des utilisateurs seront sauvegardées pour une durée maximale de 3 ans en cas d'inactivité. Des
                données des chauffeurs peuvent être conservées pour une durée de 5 ans après la fin de leurs activités selon
                les obligations légales. Des données anonymisées peuvent être conservées indéfiniment pour effectuer des
                analyses statistiques ou des mesures de performance de la plateforme.</p>

            <h3>Cookies</h3>
            <p>Voici la liste des cookies utilisés et leurs objectifs :</p>
            <ul>
                <li><strong>Cookies Google Analytics (liste exhaustive) :</strong> Web analytics</li>
                <li><strong>Cookies de consentement :</strong> Permet de garder en mémoire le fait que vous acceptez les
                    cookies afin de ne plus vous importuner lors de votre prochaine visite.</li>
                <li><strong>Cookies strictement nécessaires :</strong> Permet le fonctionnement du site, comme la gestion
                    des comptes utilisateurs, la connexion ou la validation des transactions.</li>
                <li><strong>Cookies personnalisés :</strong> Permet d’enregistrer les préférences des utilisateurs comme les
                    langues, choix de région, mode de navigation pour simplifier les prochaines expériences des utilisateurs
                    lors de la prochaine utilisation du site.</li>
                <li><strong>Cookies publicitaires :</strong> Permet de personnaliser les annonces publicitaires en fonction
                    des historiques de recherche, des trajets effectués, ou de la localisation. Ces cookies sont activés
                    uniquement si vous nous avez donné votre consentement.</li>
            </ul>

            <h3>DPO (Délégué à la Protection des Données)</h3>
            <p><strong>Définition d’un DPO :</strong></p>
            <p>Personne désignée au sein d'une organisation pour veiller à la conformité au Règlement Général sur la
                Protection des Données (RGPD) et à la protection des données personnelles. Le DPO doit s'assurer que
                l'entreprise respecte les règles relatives à la collecte, au traitement et à la sécurisation des données
                personnelles des utilisateurs, employés et autres parties prenantes.</p>
            <p><strong>Comme explicité dans l’article 37 de la CNIL :</strong></p>
            <blockquote>
                <p>Article 37 : « Le responsable du traitement et le sous-traitant désignent en tout état de cause un
                    délégué à la protection des données lorsque :</p>
                <ul>
                    <li>a) le traitement est effectué par une autorité publique ou un organisme public, à l'exception des
                        juridictions agissant dans l'exercice de leur fonction juridictionnelle ;</li>
                    <li>b) les activités de base du responsable du traitement ou du sous-traitant consistent en des
                        opérations de traitement qui du fait de leur nature, de leur portée et/ou de leurs finalités,
                        exigent un suivi régulier et systématique à grande échelle des personnes concernées ; où</li>
                    <li>c) les activités de base du responsable du traitement ou du sous-traitant consistent en un
                        traitement à grande échelle de catégories particulières de données visées à l’article 9 ou de
                        données à caractère personnel relatives à des condamnations pénales et à des infractions visées à
                        l’article 10. »</li>
                </ul>
            </blockquote>
            <p>Un DPO est nécessaire car les activités principales d’Uber consistent à suivre régulièrement des personnes à
                grande échelle par l’utilisation de la géolocalisation. Certaines données traitées présentent des risques
                élevés pour les droits et libertés des personnes, ce qui nécessite un suivi juridique et organisationnel.
            </p>

            <h3>Contact délégué à la protection des données</h3>
            <p><strong>Feyza Tinastepe</strong></p>
            <p><strong>Téléphone :</strong> 06 47 29 12 07</p>
            <p><strong>Email :</strong> <a href="mailto:feyza.tinastepe@etu.univ-smb.fr">feyza.tinastepe@etu.univ-smb.fr</a>
            </p>

            <h3>Analyse d’Impact relative à la Protection des Données (AIPD)</h3>
            <p><strong>Définition d’une AIPD :</strong></p>
            <p>Processus qui permet d’identifier les risques associés au traitement des données personnelles et mettre en
                place des solutions pour garantir la protection des droits et libertés des personnes concernées.</p>
            <p><strong>Comme dit dans l’article 35 de la CNIL :</strong></p>
            <blockquote>
                <p>Article 35, paragraphe 1 : « Lorsqu’un type de traitement, en particulier par le recours à de nouvelles
                    technologies, et compte tenu de la nature, de la portée, du contexte et des finalités du traitement, est
                    susceptible d'engendrer un risque élevé pour les droits et libertés des personnes physiques, le
                    responsable du traitement effectue, avant le traitement, une analyse de l'impact des opérations de
                    traitement envisagées sur la protection des données à caractère personnel. »</p>
            </blockquote>
            <p>Une AIPD est nécessaire lorsque les traitements envisagés représentent des dangers pour la vie privée des
                individus. Elle a pour objectif d’évaluer ces risques de manière approfondie et de proposer des mesures
                adaptées garantissant une meilleure protection des droits et libertés des personnes concernées.</p>

            <p><strong>Les critères pour l’obligation d’une AIPD pour Uber sont :</strong></p>
            <ul>
                <li><strong>Le profilage :</strong>
                    <ul>
                        <li>Attribution des courses aux chauffeurs en fonction des critères du client</li>
                        <li>Evaluation des chauffeurs par les utilisateurs avec le système de notation</li>
                        <li>Tarif des courses en fonction des lieux du client / Historique des trajets</li>
                    </ul>
                </li>
                <li><strong>Les courses :</strong>
                    <ul>
                        <li>Géolocalisation des clients/chauffeurs lors d’une course ou d’une commande</li>
                        <li>Mode de paiement</li>
                    </ul>
                </li>
            </ul>
        </div>
    </section>

@endsection
