@extends('layouts.app')

@section('title', 'Conditions Générales d\'Utilisation | Uber')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app.blade.css') }}">
@endsection

@section('content')
    <section id="cgu" class="my-5">
        <div class="container">
            <h1 class="text-center">Conditions Générales d'Utilisation</h1>

            <div class="row mt-5">
                <aside id="toc" class="col-md-3">
                    <h5>Table des Matières</h5>
                    <ul class="flex-column">
                        <li class="nav-item"><a class="nav-link" href="#presentation">Présentation des Services</a></li>
                        <li class="nav-item"><a class="nav-link" href="#acceptation">Acceptation des CGU</a></li>
                        <li class="nav-item"><a class="nav-link" href="#inscription">Inscription et Compte</a></li>
                        <li class="nav-item"><a class="nav-link" href="#utilisation">Utilisation des Services</a></li>
                        <li class="nav-item"><a class="nav-link" href="#paiements">Paiements et Tarifs</a></li>
                        <li class="nav-item"><a class="nav-link" href="#responsabilites">Responsabilités</a></li>
                        <li class="nav-item"><a class="nav-link" href="#propriete">Propriété Intellectuelle</a></li>
                        <li class="nav-item"><a class="nav-link" href="#donnees">Protection des Données</a></li>
                        <li class="nav-item"><a class="nav-link" href="#litiges">Droit Applicable et Litiges</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    </ul>
                </aside>

                <div class="col-md-9">
                    <div id="presentation" class="card">
                        <div class="card-header">Présentation des Services</div>
                        <div class="card-body">
                            <p>Uber/Uber Eats propose des services de mise en relation pour :</p>
                            <ul>
                                <li>La réservation de courses via des chauffeurs partenaires.</li>
                                <li>La commande et livraison de repas via des livreurs partenaires et des restaurants.</li>
                            </ul>
                        </div>
                    </div>

                    <div id="acceptation" class="card">
                        <div class="card-header">Acceptation des CGU</div>
                        <div class="card-body">
                            <p>En utilisant nos services, vous acceptez de respecter les présentes Conditions Générales
                                d'Utilisation.</p>
                        </div>
                    </div>

                    <div id="inscription" class="card">
                        <div class="card-header">Inscription et Compte</div>
                        <div class="card-body">
                            <ul>
                                <li>Vous devez être âgé d'au moins 18 ans.</li>
                                <li>Vous êtes responsable de l'exactitude des informations fournies lors de l'inscription.
                                </li>
                                <li>En cas de violation des CGU, nous nous réservons le droit de suspendre ou de supprimer
                                    votre compte.</li>
                            </ul>
                        </div>
                    </div>

                    <div id="utilisation" class="card">
                        <div class="card-header">Utilisation des Services</div>
                        <div class="card-body">
                            <p>Les services sont réservés à un usage personnel. Toute utilisation abusive ou illégale est
                                interdite.</p>
                        </div>
                    </div>

                    <div id="paiements" class="card">
                        <div class="card-header">Paiements et Tarifs</div>
                        <div class="card-body">
                            <ul>
                                <li>Les paiements sont effectués via les modes de paiement proposés dans l'application.</li>
                                <li>Les tarifs incluent les frais de service et de livraison.</li>
                            </ul>
                        </div>
                    </div>

                    <div id="responsabilites" class="card">
                        <div class="card-header">Responsabilités</div>
                        <div class="card-body">
                            <p>Uber/Uber Eats agit en tant qu'intermédiaire. Nous ne sommes pas responsables des prestations
                                fournies par les partenaires.</p>
                        </div>
                    </div>

                    <div id="propriete" class="card">
                        <div class="card-header">Propriété Intellectuelle</div>
                        <div class="card-body">
                            <p>Tout contenu présent sur notre plateforme est protégé par les droits de propriété
                                intellectuelle.</p>
                        </div>
                    </div>

                    <div id="donnees" class="card">
                        <div class="card-header">Protection des Données</div>
                        <div class="card-body">
                            <p>La collecte et le traitement de vos données personnelles respectent notre <a
                                    href="{{ route('privacy') }}">Politique de Confidentialité</a>.</p>
                        </div>
                    </div>

                    <div id="litiges" class="card">
                        <div class="card-header">Droit Applicable et Litiges</div>
                        <div class="card-body">
                            <p>Les présentes CGU sont régies par le droit français. En cas de litige, une solution amiable
                                sera recherchée avant tout recours judiciaire.</p>
                        </div>
                    </div>

                    <div id="contact" class="card">
                        <div class="card-header">Contact</div>
                        <div class="card-body">
                            <p>Pour toute question, veuillez nous contacter :</p>
                            <ul>
                                <li>Email : support@uber.com</li>
                                <li>Téléphone : [Numéro de support]</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
