@extends('layouts.app')

@section('title', 'Cookie | Uber')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app.blade.css') }}">
@endsection

@section('content')
    <section id="privacy-policy" class="my-5">
        <div class="container">
            <h1 class="text-center">Politique de Protection des Données Personnelles</h1>

            <div class="row mt-5">
                <aside id="toc" class="col-md-3">
                    <h5>Table des Matières</h5>
                    <ul class="flex-column">
                        <li class="nav-item"><a class="nav-link" href="#introduction">Introduction</a></li>
                        <li class="nav-item"><a class="nav-link" href="#donnees-personnelles">Données Personnelles</a></li>
                        <li class="nav-item"><a class="nav-link" href="#cookies">Cookies</a></li>
                        <li class="nav-item"><a class="nav-link" href="#dpo">Délégué à la Protection des Données</a></li>
                        <li class="nav-item"><a class="nav-link" href="#aipd">Analyse d’Impact sur la Protection des
                                Données</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    </ul>
                </aside>

                <div class="col-md-9">
                    <div id="introduction" class="card">
                        <div class="card-header">Introduction</div>
                        <div class="card-body">
                            <p>Dans le cadre de ses activités, la société Uber, dont le siège social est situé à San
                                Francisco, Californie, collecte et traite des informations qualifiées de « données
                                personnelles ».<br> Uber attache une grande importance au respect de la vie privée et
                                n’utilise
                                ces données que de manière responsable.</p>
                        </div>
                    </div>

                    <div id="donnees-personnelles" class="card">
                        <div class="card-header">Données Personnelles</div>
                        <div class="card-body">
                            <p>Sur le site web Uber, plusieurs types de données personnelles sont susceptibles d’être
                                recueillies :</p>
                            <ul>
                                <li>Données fournies par les utilisateurs via des formulaires.</li>
                                <li>Données configurées dans les profils (modes de paiement, adresses).</li>
                                <li>Données collectées lors des demandes d’aide.</li>
                            </ul>
                            <p>Ces données sont utilisées dans le cadre des services Uber.</p>
                        </div>
                    </div>

                    <div id="cookies" class="card">
                        <div class="card-header">Cookies</div>
                        <div class="card-body">
                            <p>Voici la liste des cookies utilisés sur notre site :</p>
                            <ul>
                                <li><strong>Google Analytics :</strong> Analyse de trafic. Durée de conservation : 13 mois.
                                </li>
                                <li><strong>Cookies de consentement :</strong> Enregistrement des préférences utilisateurs.
                                    Durée de conservation : 6 mois.</li>
                                <li><strong>Cookies nécessaires :</strong> Fonctionnement technique du site.</li>
                                <li><strong>Cookies publicitaires :</strong> Personnalisation des annonces (avec
                                    consentement).</li>
                            </ul>
                            <p>Vous pouvez modifier vos préférences ou retirer votre consentement à tout moment via notre <a
                                    href="/cookies/preferences">gestionnaire de cookies</a>.</p>
                        </div>
                    </div>

                    <div id="dpo" class="card">
                        <div class="card-header">Délégué à la Protection des Données (DPO)</div>
                        <div class="card-body">
                            <p>Le DPO garantit que les règles relatives à la collecte et à la sécurité des données
                                personnelles sont respectées.</p>
                        </div>
                    </div>

                    <div id="aipd" class="card">
                        <div class="card-header">Analyse d’Impact sur la Protection des Données (AIPD)</div>
                        <div class="card-body">
                            <p>Une AIPD identifie les risques liés aux traitements de données et propose des solutions
                                adaptées.</p>
                        </div>
                    </div>

                    <div id="contact" class="card">
                        <div class="card-header">Contact DPO</div>
                        <div class="card-body">
                            <p><strong>Nom :</strong> Feyza Tinastepe</p>
                            <p><strong>Téléphone :</strong> +33 6 47 29 12 07</p>
                            <p><strong>Email :</strong> <a
                                    href="mailto:feyza.tinastepe@etu.univ-smb.fr">feyza.tinastepe@etu.univ-smb.fr</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
