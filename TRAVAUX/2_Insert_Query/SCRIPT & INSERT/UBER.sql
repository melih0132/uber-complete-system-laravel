/*==============================================================*/
/* Nom de SGBD :  PostgreSQL 8                                  */
/* Date de création :  01/01/2001                      */
/*==============================================================*/

DROP TABLE IF EXISTS ADRESSE CASCADE;

DROP TABLE IF EXISTS APPARTIENT_2 CASCADE;

DROP TABLE IF EXISTS A_3 CASCADE;

DROP TABLE IF EXISTS A_COMME_CATEGORIE CASCADE;

DROP TABLE IF EXISTS A_COMME_TYPE CASCADE;

DROP TABLE IF EXISTS CARTE_BANCAIRE CASCADE;

DROP TABLE IF EXISTS CATEGORIE_PRESTATION CASCADE;

DROP TABLE IF EXISTS CATEGORIE_PRODUIT CASCADE;

DROP TABLE IF EXISTS CLIENT CASCADE;

DROP TABLE IF EXISTS CODE_POSTAL CASCADE;

DROP TABLE IF EXISTS COMMANDE CASCADE;

DROP TABLE IF EXISTS CONTIENT_2 CASCADE;

DROP TABLE IF EXISTS COURSE CASCADE;

DROP TABLE IF EXISTS COURSIER CASCADE;

DROP TABLE IF EXISTS DEPARTEMENT CASCADE;

DROP TABLE IF EXISTS ENTREPRISE CASCADE;

DROP TABLE IF EXISTS EST_SITUE_A_2 CASCADE;

DROP TABLE IF EXISTS ETABLISSEMENT CASCADE;

DROP TABLE IF EXISTS FACTURE_COURSE CASCADE;

DROP TABLE IF EXISTS PANIER CASCADE;

DROP TABLE IF EXISTS PAYS CASCADE;

DROP TABLE IF EXISTS PLANNING_RESERVATION CASCADE;

DROP TABLE IF EXISTS PRODUIT CASCADE;

DROP TABLE IF EXISTS REGLEMENT_SALAIRE CASCADE;

DROP TABLE IF EXISTS RESERVATION CASCADE;

DROP TABLE IF EXISTS TYPE_PRESTATION CASCADE;

DROP TABLE IF EXISTS VEHICULE CASCADE;

DROP TABLE IF EXISTS VELO CASCADE;

DROP TABLE IF EXISTS VILLE CASCADE;

/*==============================================================*/
/* Table : ADRESSE                                              */
/*==============================================================*/
CREATE TABLE ADRESSE (
   IDADRESSE INT4 NOT NULL,
   IDVILLE INT4 NOT NULL,
   LIBELLEADRESSE VARCHAR(100) NULL,
   CONSTRAINT PK_ADRESSE PRIMARY KEY (IDADRESSE)
);

/*==============================================================*/
/* Table : APPARTIENT_2                                         */
/*==============================================================*/
CREATE TABLE APPARTIENT_2 (
   IDCB INT4 NOT NULL,
   IDCLIENT INT4 NOT NULL,
   CONSTRAINT PK_APPARTIENT_2 PRIMARY KEY (IDCB, IDCLIENT)
);

/*==============================================================*/
/* Table : A_3                                                  */
/*==============================================================*/
CREATE TABLE A_3 (
   IDPRODUIT INT4 NOT NULL,
   IDCATEGORIE INT4 NOT NULL,
   CONSTRAINT PK_A_3 PRIMARY KEY (IDPRODUIT, IDCATEGORIE)
);

/*==============================================================*/
/* Table : A_COMME_TYPE                                         */
/*==============================================================*/
CREATE TABLE A_COMME_TYPE (
   IDVEHICULE INT4 NOT NULL,
   IDPRESTATION INT4 NOT NULL,
   CONSTRAINT PK_A_COMME_TYPE PRIMARY KEY (IDVEHICULE, IDPRESTATION)
);

/*==============================================================*/
/* Table : CARTE_BANCAIRE                                       */
/*==============================================================*/
CREATE TABLE CARTE_BANCAIRE (
   IDCB INT4 NOT NULL,
   NUMEROCB NUMERIC(17, 1) NOT NULL,
   CONSTRAINT UQ_CARTE_NUMEROCB UNIQUE (NUMEROCB),
   DATEEXPIRECB DATE NOT NULL,
   CONSTRAINT CK_CARTE_DATEEXPIRECB CHECK (DATEEXPIRECB >= CURRENT_DATE),
   CRYPTOGRAMME NUMERIC(3, 0) NOT NULL,
   TYPECARTE VARCHAR(30) NOT NULL,
   TYPERESEAUX VARCHAR(30) NOT NULL,
   CONSTRAINT PK_CARTE_BANCAIRE PRIMARY KEY (IDCB)
);

/*==============================================================*/
/* Table : CATEGORIE_PRODUIT                                    */
/*==============================================================*/
CREATE TABLE CATEGORIE_PRODUIT (
   IDCATEGORIE INT4 NOT NULL,
   NOMCATEGORIE VARCHAR(100) NULL,
   CONSTRAINT PK_CATEGORIE_PRODUIT PRIMARY KEY (IDCATEGORIE)
);

/*==============================================================*/
/* Table : CLIENT                                               */
/*==============================================================*/
CREATE TABLE CLIENT (
   IDCLIENT INT4 NOT NULL,
   IDPANIER INT4 NULL,
   IDPLANNING INT4 NULL,
   IDENTREPRISE INT4 NULL,
   IDADRESSE INT4 NOT NULL,
   GENREUSER VARCHAR(20) NOT NULL,
   CONSTRAINT CK_CLIENT_GENRE CHECK(GENREUSER IN ('Monsieur', 'Madame')),
   NOMUSER VARCHAR(50) NOT NULL,
   PRENOMUSER VARCHAR(50) NOT NULL,
   DATENAISSANCE DATE NOT NULL,
   CONSTRAINT CK_DATE_NAISS CHECK ( DATENAISSANCE <= CURRENT_DATE AND DATENAISSANCE <= CURRENT_DATE - INTERVAL '18 years' ),
   TELEPHONE VARCHAR(15) NOT NULL,
   CONSTRAINT CK_CLIENT_TEL CHECK ((TELEPHONE LIKE '06%' OR TELEPHONE LIKE '07%') AND LENGTH(TELEPHONE) = 10),
   EMAILUSER VARCHAR(200) NOT NULL,
   CONSTRAINT UQ_CLIENT UNIQUE (EMAILUSER),
   MOTDEPASSEUSER VARCHAR(200) NOT NULL,
   PHOTOPROFILE VARCHAR(300) NULL,
   SOUHAITERECEVOIRBONPLAN BOOL NULL,
   CONSTRAINT PK_CLIENT PRIMARY KEY (IDCLIENT)
);

/*==============================================================*/
/* Table : CODE_POSTAL                                          */
/*==============================================================*/
CREATE TABLE CODE_POSTAL (
   IDCODEPOSTAL INT4 NOT NULL,
   IDPAYS INT4 NOT NULL,
   CODEPOSTAL CHAR(5) NOT NULL,
   CONSTRAINT UQ_CODEPOSTAL UNIQUE (CODEPOSTAL),
   CONSTRAINT PK_CODE_POSTAL PRIMARY KEY (IDCODEPOSTAL)
);

/*==============================================================*/
/* Table : COMMANDE                                             */
/*==============================================================*/
CREATE TABLE COMMANDE (
   IDCOMMANDE INT4 NOT NULL,
   IDPANIER INT4 NOT NULL,
   IDCOURSIER INT4 NULL,
   IDADRESSE INT4 NOT NULL,
   ADR_IDADRESSE INT4 NOT NULL,
   PRIXCOMMANDE DECIMAL(5, 2) NOT NULL,
   CONSTRAINT CK_COMMANDE_PRIX CHECK (PRIXCOMMANDE >= 0),
   TEMPSCOMMANDE INT4 NOT NULL,
   CONSTRAINT CK_TEMPS_COMMANDE CHECK (TEMPSCOMMANDE >= 0),
   ESTLIVRAISON BOOL NOT NULL,
   STATUTCOMMANDE VARCHAR(20) NOT NULL,
   CONSTRAINT CK_STATUT_COMMANDE CHECK ( STATUTCOMMANDE IN ('En attente', 'En cours', 'Livrée', 'Annulée') ),
   CONSTRAINT PK_COMMANDE PRIMARY KEY (IDCOMMANDE)
);

/*==============================================================*/
/* Table : CONTIENT_2                                           */
/*==============================================================*/
CREATE TABLE CONTIENT_2 (
   IDPANIER INT4 NOT NULL,
   IDPRODUIT INT4 NOT NULL,
   CONSTRAINT PK_CONTIENT_2 PRIMARY KEY (IDPANIER, IDPRODUIT)
);

/*==============================================================*/
/* Table : COURSE                                               */
/*==============================================================*/
CREATE TABLE COURSE (
   IDCOURSE INT4 NOT NULL,
   IDCOURSIER INT4 NULL,
   IDCB INT4 NOT NULL,
   IDADRESSE INT4 NOT NULL,
   IDRESERVATION INT4 NOT NULL,
   ADR_IDADRESSE INT4 NOT NULL,
   IDPRESTATION INT4 NOT NULL,
   PRIXCOURSE NUMERIC(5, 2) NOT NULL,
   CONSTRAINT CK_COURSE_PRIX CHECK (PRIXCOURSE >= 0),
   STATUTCOURSE VARCHAR(20) NOT NULL,
   CONSTRAINT CK_COURSE_STATUT CHECK (STATUTCOURSE IN ('En attente', 'En cours', 'Terminée', 'Annulée')),
   NOTECOURSE NUMERIC(2, 1) NULL,
   CONSTRAINT CK_COURSE_NOTE CHECK (NOTECOURSE >= 0 AND NOTECOURSE <= 5),
   CONSTRAINT CK_COURSE_NOTE_IS_NULL CHECK ( (STATUTCOURSE NOT IN ('En cours', 'En attente', 'Annulée')) OR (STATUTCOURSE IN ('En cours', 'En attente', 'Annulée') AND NOTECOURSE IS NULL) ),
   COMMENTAIRECOURSE VARCHAR(1500) NULL,
   POURBOIRE NUMERIC(5, 2) NULL,
   CONSTRAINT CK_POURBOIRE CHECK (POURBOIRE >= 0 OR POURBOIRE = NULL),
   DISTANCE NUMERIC(5, 2) NULL,
   CONSTRAINT CK_COURSE_DISTANCE CHECK (DISTANCE >= 0),
   TEMPS INT4 NULL,
   CONSTRAINT CK_COURSE_TEMPS CHECK (TEMPS >= 0),
   CONSTRAINT PK_COURSE PRIMARY KEY (IDCOURSE)
);

/*==============================================================*/
/* Table : COURSIER                                             */
/*==============================================================*/
CREATE TABLE COURSIER (
   IDCOURSIER INT4 NOT NULL,
   IDENTREPRISE INT4 NOT NULL,
   IDADRESSE INT4 NOT NULL,
   GENREUSER VARCHAR(20) NOT NULL,
   CONSTRAINT CK_COURSIER_GENRE CHECK(GENREUSER IN ('Monsieur', 'Madame')),
   NOMUSER VARCHAR(50) NOT NULL,
   PRENOMUSER VARCHAR(50) NOT NULL,
   DATENAISSANCE DATE NOT NULL,
   CONSTRAINT CK_COURSIER_DATE CHECK ( DATENAISSANCE <= CURRENT_DATE AND DATENAISSANCE <= CURRENT_DATE - INTERVAL '18 years' ),
   TELEPHONE VARCHAR(15) NOT NULL,
   CONSTRAINT CK_COURSIER_TEL CHECK ((TELEPHONE LIKE '06%' OR TELEPHONE LIKE '07%') AND LENGTH(TELEPHONE) = 10),
   EMAILUSER VARCHAR(200) NOT NULL,
   MOTDEPASSEUSER VARCHAR(200) NOT NULL,
   NUMEROCARTEVTC NUMERIC(13, 1) NOT NULL,
   CONSTRAINT UQ_COURSIER_NUMCARTE UNIQUE (NUMEROCARTEVTC),
   IBAN VARCHAR(30) NOT NULL,
   CONSTRAINT UQ_COURSIER_IBAN UNIQUE (IBAN),
   DATEDEBUTACTIVITE DATE NOT NULL,
   CONSTRAINT CK_COURSIER_DATE_DEBUT CHECK (DATEDEBUTACTIVITE <= CURRENT_DATE),
   NOTEMOYENNE NUMERIC(2, 1) NOT NULL,
   CONSTRAINT CK_COURSIER_NOTE CHECK( NOTEMOYENNE >= 1 AND NOTEMOYENNE <= 5 ),
   CONSTRAINT PK_COURSIER PRIMARY KEY (IDCOURSIER)
);

/*==============================================================*/
/* Table : DEPARTEMENT                                          */
/*==============================================================*/
CREATE TABLE DEPARTEMENT (
   IDDEPARTEMENT INT4 NOT NULL,
   IDPAYS INT4 NOT NULL,
   CODEDEPARTEMENT CHAR(3) NULL,
   LIBELLEDEPARTEMENT VARCHAR(50) NULL,
   CONSTRAINT PK_DEPARTEMENT PRIMARY KEY (IDDEPARTEMENT)
);

/*==============================================================*/
/* Table : ENTREPRISE                                           */
/*==============================================================*/
CREATE TABLE ENTREPRISE (
   IDENTREPRISE INT4 NOT NULL,
   IDCLIENT INT4 NULL,
   IDADRESSE INT4 NOT NULL,
   SIRETENTREPRISE VARCHAR(20) NOT NULL,
   CONSTRAINT CK_SIRET_ENTREPRISE CHECK (SIRETENTREPRISE ~ '^[0-9]{14}$'),
   NOMENTREPRISE VARCHAR(50) NOT NULL,
   TAILLE VARCHAR(30) NOT NULL,
   CONSTRAINT CK_ENTREPRISE_TAILLE CHECK (TAILLE IN ('PME', 'ETI', 'GE')),
   CONSTRAINT PK_ENTREPRISE PRIMARY KEY (IDENTREPRISE)
);

/*==============================================================*/
/* Table : EST_SITUE_A_2                                        */
/*==============================================================*/
CREATE TABLE EST_SITUE_A_2 (
   IDPRODUIT INT4 NOT NULL,
   IDETABLISSEMENT INT4 NOT NULL,
   CONSTRAINT PK_EST_SITUE_A_2 PRIMARY KEY (IDPRODUIT, IDETABLISSEMENT)
);

/*==============================================================*/
/* Table : ETABLISSEMENT                                        */
/*==============================================================*/
CREATE TABLE ETABLISSEMENT (
   IDETABLISSEMENT INT4 NOT NULL,
   TYPEETABLISSEMENT VARCHAR(50) NOT NULL,
   CONSTRAINT CK_TYPEETABLISSEMENT CHECK (TYPEETABLISSEMENT IN ('Restaurant', 'Épicerie')),
   IDADRESSE INT4 NOT NULL,
   NOMETABLISSEMENT VARCHAR(50) NULL,
   IMAGEETABLISSEMENT VARCHAR(200) NULL,
   HORAIRESOUVERTURE TIME NULL,
   HORAIRESFERMETURE TIME NULL,
   LIVRAISON BOOL NULL,
   AEMPORTER BOOL NULL,
   CONSTRAINT PK_ETABLISSEMENT PRIMARY KEY (IDETABLISSEMENT)
);

/*==============================================================*/
/* Table : FACTURE_COURSE                                       */
/*==============================================================*/
CREATE TABLE FACTURE_COURSE (
   IDFACTURE INT4 NOT NULL,
   IDCOURSE INT4 NOT NULL,
   IDPAYS INT4 NOT NULL,
   IDCLIENT INT4 NOT NULL,
   MONTANTREGLEMENT NUMERIC(5, 2) NULL,
   DATEFACTURE DATE NULL,
   CONSTRAINT CK_FACTURE_DATE CHECK (DATEFACTURE <= CURRENT_DATE),
   QUANTITE INT4 NULL,
   CONSTRAINT PK_FACTURE_COURSE PRIMARY KEY (IDFACTURE)
);

/*==============================================================*/
/* Table : PANIER                                               */
/*==============================================================*/
CREATE TABLE PANIER (
   IDPANIER INT4 NOT NULL,
   IDCLIENT INT4 NOT NULL,
   PRIX DECIMAL(5, 2) NULL,
   CONSTRAINT CK_PANIER_PRIX CHECK (PRIX >= 0),
   CONSTRAINT PK_PANIER PRIMARY KEY (IDPANIER)
);

/*==============================================================*/
/* Table : PAYS                                                 */
/*==============================================================*/
CREATE TABLE PAYS (
   IDPAYS INT4 NOT NULL,
   NOMPAYS VARCHAR(50) NULL,
   POURCENTAGETVA NUMERIC(4, 2) NULL,
   CONSTRAINT UQ_NOMPAYS UNIQUE (NOMPAYS),
   CONSTRAINT CK_TVA CHECK ( POURCENTAGETVA >= 0 AND POURCENTAGETVA < 100 ),
   CONSTRAINT PK_PAYS PRIMARY KEY (IDPAYS)
);

/*==============================================================*/
/* Table : PLANNING_RESERVATION                                 */
/*==============================================================*/
CREATE TABLE PLANNING_RESERVATION (
   IDPLANNING INT4 NOT NULL,
   IDCLIENT INT4 NOT NULL,
   CONSTRAINT PK_PLANNING_RESERVATION PRIMARY KEY (IDPLANNING)
);

/*==============================================================*/
/* Table : PRODUIT                                              */
/*==============================================================*/
CREATE TABLE PRODUIT (
   IDPRODUIT INT4 NOT NULL,
   NOMPRODUIT VARCHAR(200) NULL,
   PRIXPRODUIT NUMERIC(5, 2) NULL,
   CONSTRAINT CK_PRODUIT_PRIX CHECK (PRIXPRODUIT > 0),
   IMAGEPRODUIT VARCHAR(300) NULL,
   DESCRIPTION VARCHAR(1500) NULL,
   CONSTRAINT PK_PRODUIT PRIMARY KEY (IDPRODUIT)
);

/*==============================================================*/
/* Table : REGLEMENT_SALAIRE                                    */
/*==============================================================*/
CREATE TABLE REGLEMENT_SALAIRE (
   IDREGLEMENT INT4 NOT NULL,
   IDCOURSIER INT4 NOT NULL,
   MONTANTREGLEMENT NUMERIC(6, 2) NULL,
   CONSTRAINT CK_SALAIRE_MNT CHECK (MONTANTREGLEMENT >= 0),
   CONSTRAINT PK_REGLEMENT_SALAIRE PRIMARY KEY (IDREGLEMENT)
);

/*==============================================================*/
/* Table : RESERVATION                                          */
/*==============================================================*/
CREATE TABLE RESERVATION (
   IDRESERVATION INT4 NOT NULL,
   IDCLIENT INT4 NOT NULL,
   IDPLANNING INT4 NOT NULL,
   IDVELO INT4 NULL,
   DATERESERVATION DATE NULL,
   CONSTRAINT CK_RESERVATION_DATE CHECK (DATERESERVATION <= CURRENT_DATE),
   HEURERESERVATION TIME NULL,
   POURQUI VARCHAR(100) NULL,
   CONSTRAINT PK_RESERVATION PRIMARY KEY (IDRESERVATION)
);

/*==============================================================*/
/* Table : TYPE_PRESTATION                                      */
/*==============================================================*/
CREATE TABLE TYPE_PRESTATION (
   IDPRESTATION INT4 NOT NULL,
   LIBELLEPRESTATION VARCHAR(50) NULL,
   DESCRIPTIONPRESTATION VARCHAR(500) NULL,
   IMAGEPRESTATION VARCHAR(300) NULL,
   CONSTRAINT PK_TYPE_PRESTATION PRIMARY KEY (IDPRESTATION)
);

/*==============================================================*/
/* Table : CATEGORIE_PRESTATION                                 */
/*==============================================================*/
CREATE TABLE CATEGORIE_PRESTATION (
   IDCATEGORIEPRESTATION INT4 NOT NULL,
   LIBELLECATEGORIEPRESTATION VARCHAR(50) NULL,
   DESCRIPTIONCATEGORIEPRESTATION VARCHAR(500) NULL,
   IMAGECATEGORIEPRESTATION VARCHAR(300) NULL,
   CONSTRAINT PK_CATEGORIE_PRESTATION PRIMARY KEY (IDCATEGORIEPRESTATION)
);

/*==============================================================*/
/* Table : A_COMME_CATEGORIE                                    */
/*==============================================================*/
CREATE TABLE A_COMME_CATEGORIE (
   IDCATEGORIEPRESTATION INT4 NOT NULL,
   IDETABLISSEMENT INT4 NOT NULL,
   CONSTRAINT PK_A_COMME_CATEGORIE PRIMARY KEY (IDCATEGORIEPRESTATION, IDETABLISSEMENT)
);

/*==============================================================*/
/* Table : VEHICULE                                             */
/*==============================================================*/
CREATE TABLE VEHICULE (
   IDVEHICULE INT4 NOT NULL,
   IDCOURSIER INT4 NOT NULL,
   IMMATRICULATION CHAR(9) NOT NULL,
   CONSTRAINT UQ_VEHICULE_IMMA UNIQUE (IMMATRICULATION),
   CONSTRAINT CK_VEHICULE_IMMA CHECK (IMMATRICULATION ~ '^[A-Z]{2}-[0-9]{3}-[A-Z]{2}$'),
   MARQUE VARCHAR(50) NULL,
   MODELE VARCHAR(50) NULL,
   CAPACITE INT4 NULL,
   CONSTRAINT CK_VEHICULE_CAPACITE CHECK ( CAPACITE BETWEEN 2 AND 7 ),
   ACCEPTEANIMAUX BOOL NOT NULL,
   ESTELECTRIQUE BOOL NOT NULL,
   ESTCONFORTABLE BOOL NOT NULL,
   ESTRECENT BOOL NOT NULL,
   ESTLUXUEUX BOOL NOT NULL,
   COULEUR VARCHAR(20) NULL,
   CONSTRAINT PK_VEHICULE PRIMARY KEY (IDVEHICULE)
);

/*==============================================================*/
/* Table : VELO                                                 */
/*==============================================================*/
CREATE TABLE VELO (
   IDVELO INT4 NOT NULL,
   IDADRESSE INT4 NOT NULL,
   NUMEROVELO INT4 NOT NULL,
   CONSTRAINT UQ_VELO_NUMERO UNIQUE (NUMEROVELO),
   ESTDISPONIBLE BOOL NOT NULL,
   CONSTRAINT PK_VELO PRIMARY KEY (IDVELO)
);

/*==============================================================*/
/* Table : VILLE                                                */
/*==============================================================*/
CREATE TABLE VILLE (
   IDVILLE INT4 NOT NULL,
   IDPAYS INT4 NOT NULL,
   IDCODEPOSTAL INT4 NOT NULL,
   NOMVILLE VARCHAR(50) NULL,
   CONSTRAINT PK_VILLE PRIMARY KEY (IDVILLE)
);

--------------------------------------------------------------------
INSERT INTO ADRESSE (
   IDADRESSE,
   IDVILLE,
   LIBELLEADRESSE
) VALUES (
   1,
   1,
   '1 Rue de l Épée de Bois'
),
(
   2,
   1,
   '15 Rue du Général Foy'
),
(
   3,
   1,
   '27 Rue Navier'
),
(
   4,
   1,
   '18 Rue Chaligny'
),
(
   5,
   1,
   '51 Rue Censier'
),
(
   6,
   1,
   '1 Place Etienne Pernet'
),
(
   7,
   1,
   '7 Rue du Commerce'
),
(
   8,
   1,
   '90 Rue Saint-Dominique'
),
(
   9,
   1,
   '49 Rue de Babylone'
),
(
   10,
   1,
   '98 Avenue Denfert Rochereau'
),
(
   11,
   2,
   '17 Rue Antoine Lumière'
),
(
   12,
   2,
   '8 Rue Rossan'
),
(
   13,
   2,
   '15 Rue Etienne Dolet'
),
(
   14,
   2,
   '154 Avenue Thiers'
),
(
   15,
   2,
   '1 Rue Burdeau'
),
(
   16,
   2,
   '6 Rue Victor Hugo'
),
(
   17,
   2,
   '27 Rue Pierre Delore Bis'
),
(
   18,
   2,
   '12 Rue Wakatsuki'
),
(
   19,
   2,
   '38 rue de la Charité'
),
(
   20,
   2,
   '59 rue de la Part-Dieu'
),
(
   21,
   3,
   '23 rue Saint-Ferréol'
),
(
   22,
   3,
   '5 boulevard Longchamp'
),
(
   23,
   3,
   '58 Rue Charles Kaddouz'
),
(
   24,
   3,
   '34 rue de la Canebière'
),
(
   25,
   3,
   '7 rue du Panier'
),
(
   26,
   3,
   '61 Rue Aviateur le Brix'
),
(
   27,
   3,
   '2 Avenue du Frêne'
),
(
   28,
   3,
   '390 Chemin du Roucas Blanc'
),
(
   29,
   3,
   '5 Avenue Edmond Oraison'
),
(
   30,
   3,
   '31 Rue Vauvenargues'
),
(
   31,
   4,
   '20 Rue Beyssac'
),
(
   32,
   4,
   '16 Rue Nérigean'
),
(
   33,
   4,
   '8 Place Camille Pelletan'
),
(
   34,
   4,
   '22 Rue de Causserouge'
),
(
   35,
   4,
   '93 Rue Manon Cormier'
),
(
   36,
   4,
   '27 Rue Charles Péguy'
),
(
   37,
   4,
   '9 Rue Alfred Dalancourt'
),
(
   38,
   4,
   '10 Avenue des 3 Cardinaux'
),
(
   39,
   4,
   '13 Rue de la Concorde'
),
(
   40,
   4,
   '6 Rue Bossuet'
),
(
   41,
   5,
   '58 Chemin du Vallon Sabatier'
),
(
   42,
   5,
   '203 Route de Saint-Pierre de Féric'
),
(
   43,
   5,
   '144 Chemin de la Costière'
),
(
   44,
   5,
   '28 Chemin du Haut Magnan'
),
(
   45,
   5,
   '32 Avenue du Dom. du Piol'
),
(
   46,
   5,
   '78 Av. du Bois de Cythère'
),
(
   47,
   5,
   '9 Rue Roger Martin du Gard'
),
(
   48,
   5,
   '5 Avenue Suzanne Lenglen'
),
(
   49,
   5,
   '40 Rue Trachel'
),
(
   50,
   5,
   '7 Chemin de la Colline de Magnan'
),
(
   51,
   6,
   '4 Avenue de la Paix'
),
(
   52,
   6,
   '34 Rue Massenet'
),
(
   53,
   6,
   '8 Rue des Reinettes'
),
(
   54,
   6,
   '36 Rue du Chanoine Poupard'
),
(
   55,
   6,
   '22 Rue Jean Baptiste Olivaux'
),
(
   56,
   6,
   '1 Chemin des Noisetiers'
),
(
   57,
   6,
   '6 Rue de Bellevue Bis'
),
(
   58,
   6,
   '40 Rue Thomas Maisonneuve'
),
(
   59,
   6,
   '2 Avenue du Bonheur'
),
(
   60,
   6,
   '73 Rue de Coulmiers'
),
(
   61,
   7,
   '701 Av. de Toulouse'
),
(
   62,
   7,
   '6 Rue Jean Vachet'
),
(
   63,
   7,
   '380 Avenue du Maréchal Leclerc'
),
(
   64,
   7,
   '15 Rue Sainte-Catherine'
),
(
   65,
   7,
   '125 Rue de Cante Gril'
),
(
   66,
   7,
   '6 Rue de la Felouque'
),
(
   67,
   7,
   '1167 Allée de la Martelle'
),
(
   68,
   7,
   '1482 Rue de St - Priest'
),
(
   69,
   7,
   '208 Avenue des Apothicaires'
),
(
   70,
   7,
   '425 Rue de la Croix de Lavit'
),
(
   71,
   8,
   '67 Rue de Ribeauvillé'
),
(
   72,
   8,
   '20 Route de Mittelhausbergen'
),
(
   73,
   8,
   '31 Rue de Dettwiller'
),
(
   74,
   8,
   '34 Rue Becquerel'
),
(
   75,
   8,
   '69 Avenue Molière'
),
(
   76,
   8,
   '58 Allee des Comtes'
),
(
   77,
   8,
   '17 Route des Romains'
),
(
   78,
   8,
   '1 Rue des Bosquets'
),
(
   79,
   8,
   'Chemin Raltauweg'
),
(
   80,
   8,
   'Rue de la Montagne Verte'
),
(
   81,
   9,
   '52 Rue Chaudronnerie'
),
(
   82,
   9,
   '4 Rue des Francs-Bourgeois'
),
(
   83,
   9,
   '81 Rue Monge'
),
(
   84,
   9,
   '48 Avenue Garibaldi'
),
(
   85,
   9,
   '1 Rue Pontus de Tyard'
),
(
   86,
   9,
   '1 Rue de la Gare'
),
(
   87,
   9,
   '13 Rue Sainte-Claire Déville'
),
(
   88,
   9,
   '19 Rue la Fontaine'
),
(
   89,
   9,
   '9 Rue Racine'
),
(
   90,
   9,
   '28 Rue Gustave Flaubert'
),
(
   91,
   10,
   '9 Rue de Condé'
),
(
   92,
   10,
   '69 Rue Jean de la Fontaine'
),
(
   93,
   10,
   '50 Avenue Fourcault de Pavant'
),
(
   94,
   10,
   '6 Avenue du Général Mangin Bis'
),
(
   95,
   10,
   '28 Rue des Missionnaires Bis'
),
(
   96,
   10,
   '2 Rue de Beauvau'
),
(
   97,
   10,
   '32 Rue Berthier'
),
(
   98,
   10,
   '70 Rue de la Paroisse'
),
(
   99,
   10,
   '2 Place Charost'
),
(
   100,
   10,
   '6 Rue des Tournelles'
),
(
   101,
   1,
   '87 Avenue De Flandre'
),
(
   102,
   1,
   '5 Rue Du Cinema'
),
(
   103,
   3,
   '1 Place Ernest Delibes'
),
(
   104,
   11,
   '80 Rue Carnot'
),
(
   105,
   2,
   '8 Place De La Croix-Rousse'
),
(
   106,
   11,
   '5 Rue De LIndustrie'
),
(
   107,
   4,
   '57 Rue Du Château DEau'
),
(
   108,
   11,
   '5 Rue De L Industrie'
),
(
   109,
   3,
   '11 Avenue de St antoine'
),
(
   110,
   5,
   '39 Avenue Georges Clemenceau'
),
(
   111,
   11,
   '3bis Avenue De Chevêne'
),
(
   112,
   6,
   '3 Rue Léon Maître'
),
(
   113,
   7,
   '5 Boulevard De L Observatoire'
),
(
   114,
   7,
   '7 Rue De La Loge'
),
(
   115,
   9,
   '65 Rue du Bourg'
),
(
   116,
   10,
   '76 Rue de la Paroisse'
),
(
   117,
   10,
   'Rue de Montreuil 7'
),
(
   118,
   11,
   '52 Rue Du Pont'
),
(
   119,
   1,
   '22 Rue De La Sablonnière'
),
(
   120,
   8,
   'Rue Des Chevaliers'
),
(
   121,
   11,
   '20 Rue de la République'
),
(
   122,
   11,
   '9 rue de larc en ciel'
),
(
   123,
   11,
   '6 Avenue du Rhône'
),
(
   124,
   11,
   'Rue de la gare'
),
(
   125,
   11,
   'rue des chasseurs'
),
(
   126,
   11,
   '10 Place de la Concorde'
),
(
   127,
   11,
   'Rue Sommeiller'
),
(
   128,
   11,
   '2 Rue Jacqueline Auriol'
),
(
   129,
   11,
   '16 Rue de Lachat'
),
(
   130,
   11,
   'avenue Montaigne'
),
(
   131,
   11,
   '134 Avenue de Genève'
);

INSERT INTO APPARTIENT_2 (
   IDCB,
   IDCLIENT
) VALUES (
   1,
   1
),
(
   2,
   2
),
(
   3,
   3
),
(
   4,
   4
),
(
   5,
   5
),
(
   6,
   6
),
(
   7,
   7
),
(
   8,
   8
),
(
   9,
   9
),
(
   10,
   10
);

INSERT INTO A_3 (
   IDPRODUIT,
   IDCATEGORIE
) VALUES (
   1,
   7
), -- 'BIG MAC™' -> Plats préparés et Surgelés
(
   2,
   7
), -- 'P TIT WRAP RANCH' -> Plats préparés et Surgelés
(
   3,
   7
), -- 'CHEESEBURGER' -> Plats préparés et Surgelés
(
   4,
   5
), -- 'McFLURRY™ SAVEUR VANILLE DAIM®' -> Pâtisseries, Desserts et Glaces
(
   5,
   7
), -- 'Waffine à composer' -> Plats préparés et Surgelés
(
   6,
   7
), -- 'Menu Complet' -> Plats préparés et Surgelés
(
   7,
   11
), -- 'Tropico Tropical 33cl' -> Boissons (alcoolisées et non alcoolisées)
(
   8,
   5
), -- 'Supplément Chocolat Blanc' -> Pâtisseries, Desserts et Glaces
(
   9,
   7
), -- 'Menu sandwich froid' -> Plats préparés et Surgelés
(
   10,
   2
), -- 'La salade PAUL' -> Fruits, Légumes et Produits frais
(
   11,
   7
), -- 'La part de pizza provençale' -> Plats préparés et Surgelés
(
   12,
   6
), -- 'Le pain nordique 300g' -> Épicerie (salée et sucrée)
(
   13,
   5
), -- 'Empanada carne' -> Pâtisseries, Desserts et Glaces
(
   14,
   5
), -- 'Empanada jamon y queso' -> Pâtisseries, Desserts et Glaces
(
   15,
   11
), -- 'Kombucha mate' -> Boissons (alcoolisées et non alcoolisées)
(
   16,
   11
), -- 'Fuzetea' -> Boissons (alcoolisées et non alcoolisées)
(
   17,
   7
), -- '2 MENUS + 2 EXTRAS' -> Plats préparés et Surgelés
(
   18,
   7
), -- '3 MENUS + 3 EXTRAS' -> Plats préparés et Surgelés
(
   19,
   7
), -- 'KINGBOX 10 King Nuggets® + 10 Chili Cheese' -> Plats préparés et Surgelés
(
   20,
   7
), -- 'Veggie Chicken Louisiane Steakhouse' -> Plats préparés et Surgelés
(
   21,
   7
), -- 'Chicken' -> Plats préparés et Surgelés
(
   22,
   7
), -- 'Rustic' -> Plats préparés et Surgelés
(
   23,
   5
), -- 'Cordon Bleu' -> Pâtisseries, Desserts et Glaces
(
   24,
   7
), -- 'Camembert Bites' -> Plats préparés et Surgelés
(
   25,
   7
), -- 'Menu West Coast' -> Plats préparés et Surgelés
(
   26,
   10
), -- 'Mozzarella Sticks' -> Snacks, Apéritifs et Confiseries
(
   27,
   7
), -- 'Menu KO Burger' -> Plats préparés et Surgelés
(
   28,
   5
), -- 'Mexico' -> Pâtisseries, Desserts et Glaces
(
   29,
   7
), -- 'Frites XL' -> Plats préparés et Surgelés
(
   30,
   7
), -- 'Chicken ' -> Plats préparés et Surgelés
(
   31,
   5
), -- 'Tiramisu Nutella Spéculos' -> Pâtisseries, Desserts et Glaces
(
   32,
   7
), -- 'Farmer' -> Plats préparés et Surgelés
(
   33,
   13
), -- 'Nettoyant vitres' -> Hygiène, Beauté et Entretien
(
   34,
   13
), -- 'Carrefour Essential - Papier toilette confort doux (12)' -> Hygiène, Beauté et Entretien
(
   35,
   4
), -- 'Kiri - Fromage enfant crème à tartiner (8)' -> Produits laitiers et Fromages
(
   36,
   3
), -- 'Carrefour Le Marché - Viande hachée pur bœuf (350g)' -> Viandes, Poissons et Charcuterie
(
   37,
   6
), -- 'Brioche Pasquier - Pains au lait (10)' -> Épicerie (salée et sucrée)
(
   38,
   7
), -- 'Le Cordon Bleu' -> Plats préparés et Surgelés
(
   39,
   7
), -- 'Le Western' -> Plats préparés et Surgelés
(
   40,
   5
), -- 'Wings' -> Pâtisseries, Desserts et Glaces
(
   41,
   10
), -- 'Sauce Harissa' -> Sauces, Condiments et Épices
(
   42,
   7
), -- 'Bao Poulet croustillant' -> Plats préparés et Surgelés
(
   43,
   7
), -- 'Menu Poké & Boisson' -> Plats préparés et Surgelés
(
   44,
   7
), -- 'Bobun boeuf' -> Plats préparés et Surgelés
(
   45,
   7
), -- 'Beignets de crevettes tempura' -> Plats préparés et Surgelés
(
   46,
   7
), -- 'PLAT + BOISSON CLASSIQUE' -> Plats préparés et Surgelés
(
   47,
   7
), -- 'CRUNCHY THAÏ BOX' -> Plats préparés et Surgelés
(
   48,
   7
), -- 'CHICKEN ou BEEF THAI' -> Plats préparés et Surgelés
(
   49,
   7
), -- 'Le Cook Mie extra' -> Plats préparés et Surgelés
(
   50,
   7
), -- 'Le Cook Mie Bistro' -> Plats préparés et Surgelés
(
   51,
   6
), -- '3 Cookies Caramel achetés le 4ème offert' -> Épicerie (salée et sucrée)
(
   52,
   6
), -- 'Cookiz duo de choc' -> Épicerie (salée et sucrée)
(
   53,
   7
), -- 'MENU AUTHENTIQUE THON CRUDITÉS' -> Plats préparés et Surgelés
(
   54,
   7
), -- 'TOASTÉ POULET CURRY' -> Plats préparés et Surgelés
(
   55,
   7
), -- 'Quiche Lorraine' -> Plats préparés et Surgelés
(
   56,
   5
), -- 'FUSETTE CITRON MERINGUÉE' -> Pâtisseries, Desserts et Glaces
(
   57,
   5
), -- 'FROMAGE BLANC 0% FRUITS ET COULIS DE FRUITS' -> Pâtisseries, Desserts et Glaces
(
   58,
   11
), -- 'ALLONGÉ' -> Boissons (alcoolisées et non alcoolisées)
(
   59,
   11
), -- 'Pur jus de pomme Franprix 1l' -> Boissons (alcoolisées et non alcoolisées)
(
   60,
   10
), -- 'Barre chocolatée Kit Kat unité 41.5g' -> Snacks, Apéritifs et Confiseries
(
   61,
   4
), -- 'Fromage Compté aux lait cru Franprix' -> Produits laitiers et Fromages
(
   62,
   7
), -- '2 cuisses de canard du Sud-Ouest confites' -> Plats préparés et Surgelés
(
   63,
   5
), -- '2 moelleux au chocolat' -> Pâtisseries, Desserts et Glaces
(
   64,
   5
), -- 'Pizza chèvre, miel, noix' -> Pâtisseries, Desserts et Glaces
(
   65,
   7
), -- 'Boulettes de viande kefta' -> Plats préparés et Surgelés
(
   66,
   10
), -- 'Bret s - Chips de pommes de terre, fromage (125g)' -> Snacks, Apéritifs et Confiseries
(
   67,
   6
), -- 'Granola Choco Lait' -> Épicerie (salée et sucrée)
(
   68,
   5
), -- 'Ben & Jerry s - Crème glacée, vanille, cookie dough (406g)' -> Pâtisseries, Desserts et Glaces
(
   69,
   7
), -- 'Justin Bridou - Saucisson petits bâton de berger nature (100g)' -> Viandes, Poissons et Charcuterie
(
   70,
   5
), -- 'Donut nutella billes' -> Pâtisseries, Desserts et Glaces
(
   71,
   7
), -- 'Bagel R. Charles' -> Plats préparés et Surgelés
(
   72,
   7
), -- 'Bagel N. Simone' -> Plats préparés et Surgelés
(
   73,
   10
), -- 'Röstis x6' -> Snacks, Apéritifs et Confiseries
(
   74,
   7
), -- 'SUB15 Dinde' -> Plats préparés et Surgelés
(
   75,
   7
), -- 'Wrap Crispy Avocado' -> Plats préparés et Surgelés
(
   76,
   7
), -- 'SUB30 Xtreme Raclette Steakhouse' -> Plats préparés et Surgelés
(
   77,
   7
), -- 'Menu SUB30 Poulet ' -> Plats préparés et Surgelés
(
   78,
   10
), -- 'Jalapenos' -> Snacks, Apéritifs et Confiseries
(
   79,
   7
), -- 'Menu 6 HOT WINGS' -> Plats préparés et Surgelés
(
   80,
   7
),
(
   81,
   13
),
(
   82,
   13
),
(
   83,
   18
),
(
   84,
   13
),
(
   85,
   13
),
(
   86,
   11
),
(
   87,
   13
),
(
   88,
   13
),
(
   89,
   16
),
(
   90,
   16
),
(
   91,
   13
),
(
   92,
   18
),
(
   93,
   18
),
(
   94,
   18
),
(
   95,
   18
),
(
   96,
   13
),
(
   97,
   18
),
(
   98,
   13
),
(
   99,
   18
),
(
   100,
   18
);

INSERT INTO A_COMME_CATEGORIE (
   IDETABLISSEMENT,
   IDCATEGORIEPRESTATION
) VALUES (
   1,
   8
),
(
   1,
   34
),
(
   1,
   7
),
(
   2,
   7
),
(
   3,
   16
),
(
   3,
   21
),
(
   4,
   41
),
(
   4,
   21
),
(
   5,
   8
),
(
   5,
   7
),
(
   6,
   22
),
(
   7,
   8
),
(
   7,
   7
),
(
   8,
   41
),
(
   8,
   21
),
(
   9,
   6
),
(
   9,
   36
),
(
   10,
   7
),
(
   10,
   21
),
(
   10,
   4
),
(
   11,
   41
),
(
   11,
   7
),
(
   12,
   7
),
(
   12,
   11
),
(
   13,
   6
),
(
   13,
   16
),
(
   14,
   6
),
(
   14,
   36
),
(
   15,
   6
),
(
   15,
   36
),
(
   16,
   6
),
(
   16,
   36
),
(
   17,
   16
),
(
   17,
   36
),
(
   18,
   41
),
(
   18,
   40
),
(
   18,
   24
),
(
   19,
   41
),
(
   19,
   7
),
(
   19,
   24
),
(
   20,
   7
),
(
   20,
   21
);

INSERT INTO A_COMME_TYPE (
   IDVEHICULE,
   IDPRESTATION
) VALUES (
   1,
   7
),
(
   2,
   1
),
(
   3,
   4
),
(
   4,
   4
),
(
   5,
   5
),
(
   6,
   6
),
(
   7,
   7
),
(
   8,
   1
),
(
   9,
   2
),
(
   10,
   2
),
(
   11,
   3
),
(
   12,
   2
),
(
   13,
   1
),
(
   14,
   5
),
(
   15,
   4
),
(
   16,
   6
),
(
   17,
   7
),
(
   18,
   1
),
(
   19,
   7
),
(
   20,
   5
),
(
   21,
   3
),
(
   22,
   4
),
(
   23,
   6
),
(
   24,
   2
),
(
   25,
   7
),
(
   26,
   7
),
(
   27,
   2
),
(
   28,
   6
),
(
   29,
   6
),
(
   30,
   1
);

INSERT INTO CARTE_BANCAIRE (
   IDCB,
   NUMEROCB,
   DATEEXPIRECB,
   CRYPTOGRAMME,
   TYPECARTE,
   TYPERESEAUX
) VALUES (
   1,
   1234567890123456,
   '2027-05-31',
   789,
   'Visa',
   'CB'
),
(
   2,
   9876543210987654,
   '2028-11-30',
   234,
   'MasterCard',
   'CB'
),
(
   3,
   1234123412341234,
   '2026-07-15',
   567,
   'American Express',
   'CB'
),
(
   4,
   4321432143214321,
   '2029-09-28',
   890,
   'Discover',
   'CB'
),
(
   5,
   8765876587658765,
   '2025-03-20',
   123,
   'Visa',
   'CB'
),
(
   6,
   5678567856785678,
   '2028-12-31',
   345,
   'MasterCard',
   'CB'
),
(
   7,
   3456345634563456,
   '2027-08-30',
   678,
   'Visa',
   'CB'
),
(
   8,
   2345234523452345,
   '2026-04-15',
   456,
   'American Express',
   'CB'
),
(
   9,
   9876987698769876,
   '2029-02-28',
   789,
   'MasterCard',
   'CB'
),
(
   10,
   6543654365436543,
   '2030-01-31',
   234,
   'Visa',
   'CB'
);

INSERT INTO CATEGORIE_PRODUIT (
   IDCATEGORIE,
   NOMCATEGORIE
) VALUES (
   1,
   'Alimentation générale'
),
(
   2,
   'Fruits, Légumes et Produits frais'
),
(
   3,
   'Viandes, Poissons et Charcuterie'
),
(
   4,
   'Produits laitiers et Fromages'
),
(
   5,
   'Pâtisseries, Desserts et Glaces'
),
(
   6,
   'Épicerie (salée et sucrée)'
),
(
   7,
   'Plats préparés et Surgelés'
),
(
   8,
   'Snacks, Apéritifs et Confiseries'
),
(
   9,
   'Céréales, Pâtes et Conserves'
),
(
   10,
   'Sauces, Condiments et Épices'
),
(
   11,
   'Boissons (alcoolisées et non alcoolisées)'
),
(
   12,
   'Produits bio et Spécifiques (sans gluten, sans lactose)'
),
(
   13,
   'Hygiène, Beauté et Entretien'
),
(
   14,
   'Produits pour bébés et enfants'
),
(
   15,
   'Produits pour animaux'
),
(
   16,
   'Produits locaux et de luxe'
),
(
   17,
   'Produits pour fêtes et occasions spéciales'
),
(
   18,
   'Accessoires et Articles divers (papeterie, cuisine)'
),
(
   19,
   'Produits végétariens et végétaliens'
),
(
   20,
   'Compléments alimentaires et Sport'
);

INSERT INTO CLIENT (
   IDCLIENT,
   IDPANIER,
   IDPLANNING,
   IDENTREPRISE,
   IDADRESSE,
   GENREUSER,
   NOMUSER,
   PRENOMUSER,
   DATENAISSANCE,
   TELEPHONE,
   EMAILUSER,
   MOTDEPASSEUSER,
   PHOTOPROFILE,
   SOUHAITERECEVOIRBONPLAN
) VALUES (
   1,
   1,
   1,
   1,
   1,
   'Monsieur',
   'Dupont',
   'Jean',
   '1990-03-15',
   '0612345678',
   'jean.dupont@example.com',
   'password123',
   'profile1.jpg',
   TRUE
),
(
   2,
   2,
   2,
   2,
   2,
   'Madame',
   'Martin',
   'Claire',
   '1985-07-20',
   '0612345679',
   'claire.martin@example.com',
   'password456',
   'profile2.jpg',
   FALSE
),
(
   3,
   3,
   3,
   3,
   3,
   'Monsieur',
   'Durand',
   'Paul',
   '1988-10-05',
   '0612345680',
   'paul.durand@example.com',
   'password789',
   'profile3.jpg',
   TRUE
),
(
   4,
   4,
   4,
   4,
   4,
   'Madame',
   'Bernard',
   'Sophie',
   '1992-12-10',
   '0612345681',
   'sophie.bernard1@example.com',
   'password101',
   'profile4.jpg',
   FALSE
),
(
   5,
   5,
   5,
   5,
   5,
   'Monsieur',
   'Lemoine',
   'Alexandre',
   '1987-01-25',
   '0612345682',
   'alexandre.lemoine@example.com',
   'password202',
   'profile5.jpg',
   TRUE
),
(
   6,
   6,
   6,
   6,
   6,
   'Madame',
   'Petit',
   'Lucie',
   '1995-03-16',
   '0612345683',
   'lucie.petit@example.com',
   'password303',
   'profile6.jpg',
   TRUE
),
(
   7,
   7,
   7,
   7,
   7,
   'Monsieur',
   'Lemoine',
   'Thomas',
   '1980-08-09',
   '0612345684',
   'thomas.lemoine@example.com',
   'password404',
   'profile7.jpg',
   FALSE
),
(
   8,
   8,
   8,
   8,
   8,
   'Madame',
   'Lemoine',
   'Marie',
   '1998-12-02',
   '0612345685',
   'marie.lemoine@example.com',
   'password505',
   'profile8.jpg',
   TRUE
),
(
   9,
   9,
   9,
   9,
   9,
   'Monsieur',
   'Benoit',
   'Philippe',
   '1992-05-22',
   '0612345686',
   'philippe.benoit@example.com',
   'password606',
   'profile9.jpg',
   FALSE
),
(
   10,
   10,
   10,
   10,
   10,
   'Madame',
   'Lemoine',
   'Sophie',
   '1995-07-11',
   '0612345687',
   'sophie.lemoine@example.com',
   'password707',
   'profile10.jpg',
   TRUE
),
(
   11,
   11,
   11,
   11,
   11,
   'Monsieur',
   'Garcia',
   'Carlos',
   '1993-09-14',
   '0612345688',
   'carlos.garcia@example.com',
   'password808',
   'profile11.jpg',
   TRUE
),
(
   12,
   12,
   12,
   12,
   12,
   'Madame',
   'Lemoine',
   'Anna',
   '1986-11-30',
   '0612345689',
   'anna.lemoine@example.com',
   'password909',
   'profile12.jpg',
   FALSE
),
(
   13,
   13,
   13,
   13,
   13,
   'Monsieur',
   'Garcia',
   'Diego',
   '1984-01-19',
   '0612345690',
   'diego.garcia@example.com',
   'password010',
   'profile13.jpg',
   TRUE
),
(
   14,
   14,
   14,
   14,
   14,
   'Madame',
   'Bernard',
   'Hélène',
   '1999-02-28',
   '0612345691',
   'helene.bernard@example.com',
   'password121',
   'profile14.jpg',
   TRUE
),
(
   15,
   15,
   15,
   15,
   15,
   'Monsieur',
   'Dupont',
   'Pierre',
   '1991-05-13',
   '0612345692',
   'pierre.dupont@example.com',
   'password232',
   'profile15.jpg',
   FALSE
),
(
   16,
   16,
   16,
   16,
   16,
   'Madame',
   'Durand',
   'Julie',
   '1994-03-30',
   '0612345693',
   'julie.durand@example.com',
   'password343',
   'profile16.jpg',
   TRUE
),
(
   17,
   17,
   17,
   17,
   17,
   'Monsieur',
   'Lemoine',
   'Benjamin',
   '1989-06-20',
   '0612345694',
   'benjamin.lemoine@example.com',
   'password454',
   'profile17.jpg',
   FALSE
),
(
   18,
   18,
   18,
   18,
   18,
   'Madame',
   'Lemoine',
   'Claire',
   '1983-09-07',
   '0612345695',
   'claire.lemoine@example.com',
   'password565',
   'profile18.jpg',
   TRUE
),
(
   19,
   19,
   19,
   19,
   19,
   'Monsieur',
   'Lemoine',
   'Julien',
   '1987-12-11',
   '0612345696',
   'julien.leoine@example.com',
   'password676',
   'profile19.jpg',
   FALSE
),
(
   20,
   20,
   20,
   2,
   6,
   'Madame',
   'Bernard',
   'Sophie',
   '1991-01-14',
   '0612345697',
   'sophie.bernard@example.com',
   'password787',
   'profile20.jpg',
   TRUE
),
(
   21,
   21,
   21,
   1,
   1,
   'Monsieur',
   'Gomez',
   'Antoine',
   '1992-08-18',
   '0612345698',
   'antoine.gomez@example.com',
   'password898',
   'profile21.jpg',
   TRUE
),
(
   22,
   22,
   22,
   2,
   2,
   'Madame',
   'Lemoine',
   'Isabelle',
   '1994-11-23',
   '0612345699',
   'isabelle.lemoine@example.com',
   'password009',
   'profile22.jpg',
   FALSE
),
(
   23,
   23,
   23,
   3,
   3,
   'Monsieur',
   'Dupont',
   'Frédéric',
   '1985-04-12',
   '0612345700',
   'frederic.dupont@example.com',
   'password110',
   'profile23.jpg',
   TRUE
),
(
   24,
   24,
   24,
   4,
   4,
   'Madame',
   'Garcia',
   'Laura',
   '1987-06-03',
   '0612345701',
   'laura.garcia@example.com',
   'password221',
   'profile24.jpg',
   FALSE
),
(
   25,
   25,
   5,
   5,
   5,
   'Monsieur',
   'Benoit',
   'Eric',
   '1990-09-27',
   '0612345702',
   'eric.benoit@example.com',
   'password332',
   'profile25.jpg',
   TRUE
),
(
   26,
   26,
   6,
   6,
   6,
   'Madame',
   'Lemoine',
   'Margaux',
   '1993-01-14',
   '0612345703',
   'margaux.lemoine@example.com',
   'password443',
   'profile26.jpg',
   TRUE
),
(
   27,
   27,
   7,
   7,
   7,
   'Monsieur',
   'Dupont',
   'Jacques',
   '1996-05-21',
   '0612345704',
   'jacques.dupont@example.com',
   'password554',
   'profile27.jpg',
   FALSE
),
(
   28,
   28,
   8,
   8,
   8,
   'Madame',
   'Bernard',
   'Marion',
   '1999-02-05',
   '0612345705',
   'marion.bernard@example.com',
   'password665',
   'profile28.jpg',
   TRUE
),
(
   29,
   29,
   9,
   9,
   9,
   'Monsieur',
   'Durand',
   'Victor',
   '1991-11-30',
   '0612345706',
   'victor.durand@example.com',
   'password776',
   'profile29.jpg',
   TRUE
),
(
   30,
   30,
   12,
   1,
   2,
   'Madame',
   'Lemoine',
   'Audrey',
   '1988-07-17',
   '0612345707',
   'audrey.lemoine@example.com',
   'password887',
   'profile30.jpg',
   FALSE
),
(
   31,
   31,
   1,
   1,
   1,
   'Monsieur',
   'Gomez',
   'Maxime',
   '1995-03-19',
   '0612345708',
   'maxime.gomez@example.com',
   'password998',
   'profile31.jpg',
   TRUE
),
(
   32,
   32,
   2,
   2,
   2,
   'Madame',
   'Martin',
   'Sophie',
   '1990-06-28',
   '0612345709',
   'sophie.m0artin@example.com',
   'password009',
   'profile32.jpg',
   TRUE
),
(
   33,
   33,
   3,
   3,
   3,
   'Monsieur',
   'Lemoine',
   'Julien',
   '1992-11-11',
   '0612345710',
   'julien.lemoine@example.com',
   'password110',
   'profile33.jpg',
   FALSE
),
(
   34,
   34,
   4,
   4,
   4,
   'Madame',
   'Petit',
   'Amélie',
   '1989-03-15',
   '0612345711',
   'amelie.petit@example.com',
   'password221',
   'profile34.jpg',
   TRUE
),
(
   35,
   35,
   15,
   5,
   5,
   'Monsieur',
   'Lemoine',
   'Laurent',
   '1997-06-23',
   '0612345712',
   'laurent.lemoine@example.com',
   'password332',
   'profile35.jpg',
   FALSE
),
(
   36,
   36,
   6,
   6,
   6,
   'Madame',
   'Durand',
   'Catherine',
   '1994-10-07',
   '0612345713',
   'catherine.durand@example.com',
   'password443',
   'profile36.jpg',
   TRUE
),
(
   37,
   37,
   7,
   7,
   7,
   'Monsieur',
   'Gomez',
   'Lucas',
   '1986-12-19',
   '0612345714',
   'lucas.gomez@example.com',
   'password554',
   'profile37.jpg',
   TRUE
),
(
   38,
   38,
   8,
   8,
   8,
   'Madame',
   'Benoit',
   'Amandine',
   '1991-04-01',
   '0612345715',
   'amandine.benoit@example.com',
   'password665',
   'profile38.jpg',
   FALSE
),
(
   39,
   39,
   9,
   9,
   9,
   'Monsieur',
   'Garcia',
   'Julien',
   '1993-08-10',
   '0612345716',
   'julien.garcia@example.com',
   'password776',
   'profile39.jpg',
   TRUE
),
(
   40,
   40,
   1,
   2,
   3,
   'Madame',
   'Lemoine',
   'Estelle',
   '1996-09-23',
   '0612345717',
   'estelle.lemoine@example.com',
   'password887',
   'profile40.jpg',
   FALSE
),
(
   41,
   41,
   1,
   1,
   1,
   'Monsieur',
   'Lemoine',
   'Frederic',
   '1994-05-14',
   '0612345718',
   'frederic.lemoine@example.com',
   'password998',
   'profile41.jpg',
   TRUE
),
(
   42,
   42,
   2,
   2,
   2,
   'Madame',
   'Garcia',
   'Céline',
   '1988-01-25',
   '0612345719',
   'celine.garcia@example.com',
   'password009',
   'profile42.jpg',
   FALSE
),
(
   43,
   43,
   3,
   3,
   3,
   'Monsieur',
   'Dupont',
   'Victor',
   '1992-12-03',
   '0612345720',
   'victor.dupont@example.com',
   'password110',
   'profile43.jpg',
   TRUE
),
(
   44,
   44,
   4,
   4,
   4,
   'Madame',
   'Lemoine',
   'Valérie',
   '1993-11-28',
   '0612345721',
   'valerie.lemoine@example.com',
   'password221',
   'profile44.jpg',
   TRUE
),
(
   45,
   45,
   5,
   5,
   5,
   'Monsieur',
   'Benoit',
   'Louis',
   '1987-10-10',
   '0612345722',
   'louis.benoit@example.com',
   'password332',
   'profile45.jpg',
   FALSE
),
(
   46,
   46,
   6,
   6,
   6,
   'Madame',
   'Martin',
   'Sophie',
   '1984-08-21',
   '0612345723',
   'sophie.martin@example.com',
   'password443',
   'profile46.jpg',
   TRUE
),
(
   47,
   47,
   7,
   7,
   7,
   'Monsieur',
   'Lemoine',
   'Pierre',
   '1990-03-01',
   '0612345724',
   'pierre.lemoine@example.com',
   'password554',
   'profile47.jpg',
   TRUE
),
(
   48,
   48,
   8,
   8,
   8,
   'Madame',
   'Bernard',
   'Laure',
   '1991-11-14',
   '0612345725',
   'laure.bernard@example.com',
   'password665',
   'profile48.jpg',
   FALSE
),
(
   49,
   49,
   9,
   9,
   9,
   'Monsieur',
   'Garcia',
   'Rafael',
   '1994-06-30',
   '0612345726',
   'rafael.garcia@example.com',
   'password776',
   'profile49.jpg',
   TRUE
),
(
   50,
   50,
   2,
   3,
   1,
   'Madame',
   'Lemoine',
   'Solène',
   '1993-12-09',
   '0612345727',
   'solene.lemoine@example.com',
   'password887',
   'profile50.jpg',
   TRUE
);

INSERT INTO CODE_POSTAL (
   IDCODEPOSTAL,
   IDPAYS,
   CODEPOSTAL
) VALUES (
   1,
   1,
   '75000'
),
(
   2,
   1,
   '69000'
),
(
   3,
   1,
   '13000'
),
(
   4,
   1,
   '33000'
),
(
   5,
   1,
   '06000'
),
(
   6,
   1,
   '44000'
),
(
   7,
   1,
   '34000'
),
(
   8,
   1,
   '67000'
),
(
   9,
   1,
   '21000'
),
(
   10,
   1,
   '78000'
),
(
   11,
   1,
   '74000'
);

INSERT INTO COMMANDE (
   IDCOMMANDE,
   IDPANIER,
   IDCOURSIER,
   IDADRESSE,
   ADR_IDADRESSE,
   PRIXCOMMANDE,
   TEMPSCOMMANDE,
   ESTLIVRAISON,
   STATUTCOMMANDE
) VALUES (
   1,
   1,
   1,
   17,
   25,
   130.00,
   30,
   TRUE,
   'En cours'
),
(
   2,
   2,
   2,
   5,
   35,
   95.00,
   25,
   TRUE,
   'Livrée'
),
(
   3,
   3,
   NULL,
   85,
   74,
   120.00,
   20,
   FALSE,
   'En attente'
),
(
   4,
   4,
   4,
   28,
   41,
   120.00,
   15,
   TRUE,
   'En cours'
),
(
   5,
   5,
   5,
   2,
   88,
   95.00,
   30,
   TRUE,
   'Annulée'
),
(
   6,
   6,
   NULL,
   69,
   72,
   70.00,
   30,
   FALSE,
   'En attente'
),
(
   7,
   7,
   7,
   76,
   43,
   170.00,
   25,
   TRUE,
   'Livrée'
),
(
   8,
   8,
   8,
   77,
   42,
   40.00,
   25,
   TRUE,
   'En cours'
),
(
   9,
   9,
   9,
   85,
   28,
   85.00,
   50,
   TRUE,
   'Livrée'
),
(
   10,
   10,
   10,
   85,
   56,
   100.00,
   50,
   FALSE,
   'Annulée'
),
(
   11,
   11,
   1,
   9,
   22,
   80.00,
   50,
   TRUE,
   'En cours'
),
(
   12,
   12,
   2,
   56,
   100,
   115.00,
   40,
   TRUE,
   'Livrée'
),
(
   13,
   13,
   NULL,
   99,
   18,
   130.00,
   25,
   FALSE,
   'En attente'
),
(
   14,
   14,
   4,
   65,
   17,
   90.00,
   60,
   TRUE,
   'En cours'
),
(
   15,
   15,
   5,
   92,
   16,
   80.00,
   12,
   TRUE,
   'Annulée'
),
(
   16,
   16,
   NULL,
   25,
   41,
   75.00,
   25,
   FALSE,
   'En attente'
),
(
   17,
   17,
   7,
   7,
   44,
   140.00,
   75,
   TRUE,
   'Livrée'
),
(
   18,
   18,
   8,
   22,
   33,
   45.00,
   14,
   TRUE,
   'En cours'
),
(
   19,
   19,
   9,
   30,
   51,
   120.00,
   10,
   FALSE,
   'Annulée'
),
(
   20,
   20,
   10,
   7,
   44,
   100.00,
   20,
   TRUE,
   'En cours'
);

INSERT INTO CONTIENT_2 (
   IDPANIER,
   IDPRODUIT
) VALUES (
   1,
   1
),
(
   2,
   2
),
(
   2,
   3
),
(
   2,
   10
),
(
   2,
   4
),
(
   3,
   3
),
(
   4,
   4
),
(
   5,
   5
),
(
   6,
   6
),
(
   7,
   7
),
(
   7,
   6
),
(
   7,
   4
),
(
   7,
   13
),
(
   8,
   8
),
(
   9,
   9
),
(
   10,
   10
),
(
   11,
   11
),
(
   12,
   12
),
(
   13,
   13
),
(
   14,
   14
),
(
   15,
   15
),
(
   16,
   16
),
(
   17,
   17
),
(
   18,
   18
),
(
   19,
   19
),
(
   20,
   20
);

INSERT INTO COURSE (
   IDCOURSE,
   IDCOURSIER,
   IDCB,
   IDADRESSE,
   IDRESERVATION,
   ADR_IDADRESSE,
   IDPRESTATION,
   PRIXCOURSE,
   STATUTCOURSE,
   NOTECOURSE,
   COMMENTAIRECOURSE,
   POURBOIRE,
   DISTANCE,
   TEMPS
) VALUES (
   1,
   NULL,
   7,
   32,
   50,
   56,
   7,
   52.43,
   'En attente',
   NULL,
   NULL,
   NULL,
   9.9,
   42
),
(
   2,
   NULL,
   6,
   35,
   51,
   59,
   6,
   59.36,
   'En attente',
   NULL,
   NULL,
   NULL,
   1.5,
   56
),
(
   3,
   3,
   4,
   37,
   52,
   46,
   7,
   19.51,
   'Annulée',
   NULL,
   NULL,
   NULL,
   8.0,
   119
),
(
   4,
   4,
   7,
   70,
   53,
   55,
   3,
   50.1,
   'En cours',
   NULL,
   NULL,
   NULL,
   5.7,
   101
),
(
   5,
   5,
   8,
   78,
   54,
   8,
   1,
   90.94,
   'Terminée',
   3.7,
   'Commentaire 5',
   15.2,
   5.8,
   40
),
(
   6,
   6,
   5,
   18,
   55,
   14,
   7,
   96.49,
   'Terminée',
   1.3,
   'Commentaire 6',
   10.25,
   6.6,
   59
),
(
   7,
   NULL,
   3,
   16,
   56,
   39,
   3,
   21.68,
   'En attente',
   NULL,
   NULL,
   NULL,
   2.8,
   70
),
(
   8,
   8,
   1,
   89,
   57,
   31,
   2,
   46.52,
   'Terminée',
   2.4,
   'Commentaire 8',
   8.6,
   5.5,
   27
),
(
   9,
   9,
   4,
   92,
   58,
   74,
   2,
   18.64,
   'En cours',
   NULL,
   NULL,
   NULL,
   8.5,
   78
),
(
   10,
   10,
   5,
   82,
   60,
   44,
   2,
   96.53,
   'En cours',
   NULL,
   NULL,
   NULL,
   1.2,
   12
),
(
   11,
   11,
   1,
   37,
   61,
   17,
   1,
   98.49,
   'Terminée',
   1.3,
   'Commentaire 11',
   4.17,
   5.1,
   31
),
(
   12,
   12,
   6,
   24,
   62,
   88,
   5,
   97.43,
   'En cours',
   NULL,
   NULL,
   NULL,
   7.7,
   77
),
(
   13,
   13,
   1,
   27,
   63,
   74,
   1,
   18.83,
   'En cours',
   NULL,
   NULL,
   NULL,
   1.7,
   115
),
(
   14,
   14,
   6,
   26,
   64,
   11,
   5,
   67.23,
   'En cours',
   NULL,
   NULL,
   NULL,
   4.6,
   13
),
(
   15,
   15,
   7,
   18,
   65,
   4,
   5,
   31.06,
   'Terminée',
   3.5,
   'Commentaire 15',
   0.39,
   2.6,
   117
),
(
   16,
   16,
   10,
   46,
   66,
   35,
   4,
   96.34,
   'Terminée',
   3.5,
   'Commentaire 16',
   5.67,
   5.4,
   99
),
(
   17,
   17,
   3,
   25,
   67,
   49,
   7,
   33.63,
   'Annulée',
   NULL,
   NULL,
   NULL,
   2.4,
   64
),
(
   18,
   18,
   10,
   96,
   68,
   6,
   7,
   84.81,
   'En cours',
   NULL,
   NULL,
   NULL,
   3.0,
   15
),
(
   19,
   NULL,
   1,
   88,
   69,
   31,
   3,
   11.63,
   'En attente',
   NULL,
   NULL,
   NULL,
   8.7,
   35
),
(
   20,
   20,
   6,
   30,
   70,
   7,
   4,
   65.47,
   'Terminée',
   4.5,
   'Commentaire 20',
   6.1,
   9.1,
   93
),
(
   21,
   NULL,
   5,
   93,
   71,
   72,
   2,
   31.93,
   'En attente',
   NULL,
   NULL,
   NULL,
   5.7,
   68
),
(
   22,
   22,
   5,
   67,
   72,
   96,
   6,
   61.06,
   'En cours',
   NULL,
   NULL,
   NULL,
   8.4,
   24
),
(
   23,
   NULL,
   8,
   5,
   73,
   30,
   3,
   42.47,
   'En attente',
   NULL,
   NULL,
   NULL,
   7.6,
   20
),
(
   24,
   24,
   1,
   7,
   74,
   30,
   5,
   81.93,
   'Terminée',
   1.2,
   'Commentaire 24',
   7.56,
   6.6,
   12
),
(
   25,
   NULL,
   10,
   38,
   75,
   58,
   2,
   47.53,
   'En attente',
   NULL,
   NULL,
   NULL,
   2.2,
   50
),
(
   26,
   26,
   3,
   18,
   76,
   14,
   5,
   23.9,
   'En cours',
   NULL,
   NULL,
   NULL,
   3.4,
   97
),
(
   27,
   27,
   6,
   97,
   77,
   38,
   2,
   87.78,
   'Annulée',
   NULL,
   NULL,
   NULL,
   1.1,
   101
),
(
   28,
   28,
   6,
   62,
   78,
   39,
   7,
   64.99,
   'Annulée',
   NULL,
   NULL,
   NULL,
   6.1,
   11
),
(
   29,
   29,
   4,
   23,
   79,
   42,
   3,
   11.06,
   'En cours',
   NULL,
   NULL,
   NULL,
   5.7,
   35
),
(
   30,
   30,
   2,
   94,
   80,
   27,
   6,
   76.18,
   'Annulée',
   NULL,
   NULL,
   NULL,
   3.9,
   116
),
(
   31,
   NULL,
   2,
   83,
   81,
   13,
   5,
   96.23,
   'En attente',
   NULL,
   NULL,
   NULL,
   8.9,
   11
),
(
   32,
   2,
   1,
   55,
   82,
   42,
   3,
   12.51,
   'Annulée',
   NULL,
   NULL,
   NULL,
   4.9,
   93
),
(
   33,
   3,
   5,
   8,
   83,
   6,
   4,
   93.94,
   'En cours',
   NULL,
   NULL,
   NULL,
   3.7,
   13
),
(
   34,
   NULL,
   3,
   25,
   84,
   89,
   3,
   54.66,
   'En attente',
   NULL,
   NULL,
   NULL,
   8.9,
   90
),
(
   35,
   5,
   10,
   31,
   85,
   76,
   1,
   16.46,
   'En cours',
   NULL,
   NULL,
   NULL,
   6.0,
   38
),
(
   36,
   NULL,
   10,
   79,
   86,
   32,
   6,
   25.25,
   'En attente',
   NULL,
   NULL,
   NULL,
   9.2,
   55
),
(
   37,
   NULL,
   4,
   100,
   87,
   56,
   4,
   41.65,
   'En attente',
   NULL,
   NULL,
   NULL,
   7.7,
   48
),
(
   38,
   8,
   9,
   49,
   88,
   44,
   6,
   96.12,
   'En cours',
   NULL,
   NULL,
   NULL,
   9.2,
   38
),
(
   39,
   9,
   6,
   93,
   89,
   55,
   2,
   25.02,
   'Terminée',
   2.3,
   'Commentaire 39',
   4.51,
   2.3,
   118
),
(
   40,
   10,
   9,
   24,
   90,
   84,
   3,
   84.02,
   'Terminée',
   3.3,
   'Commentaire 40',
   8.64,
   9.6,
   38
),
(
   41,
   11,
   4,
   86,
   91,
   63,
   7,
   47.46,
   'En cours',
   NULL,
   NULL,
   NULL,
   8.4,
   11
),
(
   42,
   NULL,
   5,
   54,
   92,
   30,
   7,
   31.21,
   'En attente',
   NULL,
   NULL,
   NULL,
   3.8,
   104
),
(
   43,
   13,
   6,
   72,
   93,
   56,
   2,
   81.99,
   'Annulée',
   NULL,
   NULL,
   NULL,
   2.6,
   88
),
(
   44,
   14,
   3,
   37,
   94,
   75,
   5,
   28.33,
   'En cours',
   NULL,
   NULL,
   NULL,
   7.8,
   52
),
(
   45,
   15,
   5,
   25,
   95,
   83,
   5,
   14.08,
   'Annulée',
   NULL,
   NULL,
   NULL,
   6.5,
   74
),
(
   46,
   16,
   2,
   10,
   96,
   9,
   2,
   13.3,
   'Annulée',
   NULL,
   NULL,
   NULL,
   2.3,
   94
),
(
   47,
   17,
   2,
   82,
   97,
   80,
   5,
   77.08,
   'Terminée',
   4.8,
   'Commentaire 47',
   16.98,
   3.1,
   31
),
(
   48,
   18,
   6,
   44,
   98,
   28,
   2,
   99.36,
   'Terminée',
   1.8,
   'Commentaire 48',
   12.86,
   4.4,
   57
),
(
   49,
   19,
   5,
   63,
   99,
   39,
   4,
   13.28,
   'En cours',
   NULL,
   NULL,
   NULL,
   2.8,
   71
),
(
   50,
   NULL,
   3,
   64,
   100,
   20,
   6,
   30.07,
   'En attente',
   NULL,
   NULL,
   NULL,
   6.8,
   20
);

INSERT INTO COURSIER (
   IDCOURSIER,
   IDENTREPRISE,
   IDADRESSE,
   GENREUSER,
   NOMUSER,
   PRENOMUSER,
   DATENAISSANCE,
   TELEPHONE,
   EMAILUSER,
   MOTDEPASSEUSER,
   NUMEROCARTEVTC,
   IBAN,
   DATEDEBUTACTIVITE,
   NOTEMOYENNE
) VALUES (
   1,
   1,
   1,
   'Monsieur',
   'Martin',
   'Pierre',
   '1985-06-12',
   '0612345678',
   'pierre.martin@example.com',
   'password123',
   '11123456',
   'FR7612345678901234567890123',
   '2020-01-01',
   4.5
),
(
   2,
   2,
   2,
   'Monsieur',
   'Dupont',
   'Paul',
   '1990-09-05',
   '0623456789',
   'paul.dupont@example.com',
   'password456',
   '111654321',
   'FR7623456789012345678901234',
   '2021-03-15',
   4.3
),
(
   3,
   3,
   3,
   'Monsieur',
   'Lemoine',
   'Luc',
   '1992-11-22',
   '0634567890',
   'luc.lemoine@example.com',
   'password789',
   '111789012',
   'FR7634567890123456789012345',
   '2021-07-10',
   4.7
),
(
   4,
   4,
   4,
   'Monsieur',
   'Lopez',
   'Marc',
   '1988-02-17',
   '0645678901',
   'marc.lopez@example.com',
   'password101',
   '111987654',
   'FR7645678901234567890123456',
   '2020-09-20',
   4.0
),
(
   5,
   5,
   5,
   'Monsieur',
   'Thomson',
   'David',
   '1987-03-30',
   '0656789012',
   'david.thomson@example.com',
   'password202',
   '111543210',
   'FR7656789012345678901234567',
   '2019-08-05',
   4.2
),
(
   6,
   6,
   6,
   'Monsieur',
   'Richard',
   'Julien',
   '1983-12-14',
   '0667890123',
   'julien.richard@example.com',
   'password303',
   '111102938',
   'FR7667890123456789012345678',
   '2022-01-25',
   4.6
),
(
   7,
   7,
   7,
   'Monsieur',
   'Bernard',
   'Claude',
   '1991-05-20',
   '0678901234',
   'claude.bernard@example.com',
   'password404',
   '111192837',
   'FR7678901234567890123456789',
   '2021-12-10',
   4.4
),
(
   8,
   8,
   8,
   'Monsieur',
   'Petit',
   'François',
   '1993-07-09',
   '0689012345',
   'francois.petit@example.com',
   'password505',
   '111837261',
   'FR7689012345678901234567890',
   '2021-11-05',
   4.1
),
(
   9,
   9,
   9,
   'Monsieur',
   'Girard',
   'Eric',
   '1984-01-29',
   '0690123456',
   'eric.girard@example.com',
   'password606',
   '111264738',
   'FR7690123456789012345678901',
   '2020-04-18',
   4.8
),
(
   10,
   10,
   10,
   'Monsieur',
   'Faure',
   'Pierre',
   '1986-10-21',
   '0701234567',
   'pierre.faure@example.com',
   'password707',
   '111564728',
   'FR7701234567890123456789012',
   '2021-09-12',
   4.3
),
(
   11,
   11,
   11,
   'Monsieur',
   'Benoit',
   'Antoine',
   '1995-01-12',
   '0712345678',
   'antoine.benoit@example.com',
   'password808',
   '112233445',
   'FR7712345678901234567890123',
   '2020-06-02',
   4.6
),
(
   12,
   12,
   12,
   'Monsieur',
   'Mercier',
   'Xavier',
   '1989-03-18',
   '0723456789',
   'xavier.mercier@example.com',
   'password909',
   '112233446',
   'FR7723456789012345678901234',
   '2020-11-23',
   4.2
),
(
   13,
   13,
   13,
   'Monsieur',
   'Lemoine',
   'Henri',
   '1994-07-25',
   '0734567890',
   'henri.lemoine@example.com',
   'password010',
   '112233447',
   'FR7734567890123456789012345',
   '2021-01-30',
   4.7
),
(
   14,
   14,
   14,
   'Monsieur',
   'Robert',
   'Maxime',
   '1981-09-03',
   '0745678901',
   'maxime.robert@example.com',
   'password111',
   '112233448',
   'FR7745678901234567890123456',
   '2022-05-16',
   4.0
),
(
   15,
   15,
   15,
   'Monsieur',
   'Giraud',
   'Samuel',
   '1986-04-14',
   '0756789012',
   'samuel.giraud@example.com',
   'password222',
   '112233449',
   'FR7756789012345678901234567',
   '2021-07-05',
   4.8
),
(
   16,
   16,
   16,
   'Monsieur',
   'Marchand',
   'Thierry',
   '1988-12-28',
   '0767890123',
   'thierry.marchand@example.com',
   'password333',
   '112233450',
   'FR7767890123456789012345678',
   '2021-10-17',
   4.1
),
(
   17,
   17,
   17,
   'Monsieur',
   'Duval',
   'Olivier',
   '1992-08-22',
   '0778901234',
   'olivier.duval@example.com',
   'password444',
   '112233451',
   'FR7778901234567890123456789',
   '2022-02-05',
   4.3
),
(
   18,
   18,
   18,
   'Monsieur',
   'Perrot',
   'Michel',
   '1987-06-10',
   '0789012345',
   'michel.perrot@example.com',
   'password555',
   '112233452',
   'FR7789012345678901234567890',
   '2021-09-30',
   4.4
),
(
   19,
   19,
   19,
   'Monsieur',
   'Martin',
   'Jacques',
   '1990-10-15',
   '0790123456',
   'jacques.martin@example.com',
   'password666',
   '112233453',
   'FR7790123456789012345678901',
   '2021-11-20',
   4.5
),
(
   20,
   20,
   20,
   'Monsieur',
   'Leroy',
   'Pierre',
   '1982-12-08',
   '0701234567',
   'pierre.leroy@example.com',
   'password777',
   '112233454',
   'FR7801234567890123456789012',
   '2021-05-22',
   4.6
),
(
   21,
   1,
   1,
   'Monsieur',
   'Fournier',
   'Julien',
   '1989-11-03',
   '0712345678',
   'julien.fournier@example.com',
   'password888',
   '112233455',
   'FR7812345678901234567890123',
   '2020-01-17',
   4.2
),
(
   22,
   2,
   2,
   'Monsieur',
   'Hebert',
   'Alain',
   '1985-04-06',
   '0623456789',
   'alain.hebert@example.com',
   'password999',
   '112233456',
   'FR7823456789012345678901234',
   '2021-12-15',
   4.3
),
(
   23,
   3,
   3,
   'Monsieur',
   'Lemoine',
   'Vincent',
   '1991-05-12',
   '0734567890',
   'vincent.lemoine@example.com',
   'password000',
   '112233457',
   'FR7834567890123456789012345',
   '2022-03-01',
   4.7
),
(
   24,
   4,
   4,
   'Monsieur',
   'Robert',
   'Louis',
   '1986-10-14',
   '0745678901',
   'louis.robert@example.com',
   'password111',
   '112233458',
   'FR7845678901234567890123456',
   '2022-08-04',
   4.5
),
(
   25,
   5,
   5,
   'Monsieur',
   'Perrin',
   'Claude',
   '1988-01-22',
   '0656789012',
   'claude.perrin@example.com',
   'password222',
   '112233459',
   'FR7856789012345678901234567',
   '2021-11-10',
   4.4
),
(
   26,
   6,
   6,
   'Monsieur',
   'Leclerc',
   'Gérard',
   '1993-05-30',
   '0767890123',
   'gerard.leclerc@example.com',
   'password333',
   '112233460',
   'FR7867890123456789012345678',
   '2022-01-15',
   4.1
),
(
   27,
   7,
   7,
   'Monsieur',
   'Hamon',
   'Antoine',
   '1990-02-18',
   '0678901234',
   'antoine.hamon@example.com',
   'password444',
   '112233461',
   'FR7878901234567890123456789',
   '2021-09-07',
   4.6
),
(
   28,
   8,
   8,
   'Monsieur',
   'Faure',
   'François',
   '1982-11-29',
   '0789012345',
   'francois.faure@example.com',
   'password555',
   '112233462',
   'FR7889012345678901234567890',
   '2022-02-25',
   4.2
),
(
   29,
   9,
   9,
   'Monsieur',
   'Vidal',
   'Bruno',
   '1994-04-21',
   '0790123456',
   'bruno.vidal@example.com',
   'password666',
   '112233463',
   'FR7890123456789012345678901',
   '2021-10-13',
   4.7
),
(
   30,
   10,
   10,
   'Monsieur',
   'Gauthier',
   'Denis',
   '1987-02-09',
   '0701234567',
   'denis.gauthier@example.com',
   'password777',
   '112233464',
   'FR7901234567890123456789012',
   '2021-08-30',
   4.3
);

INSERT INTO ENTREPRISE (
   IDENTREPRISE,
   IDCLIENT,
   IDADRESSE,
   SIRETENTREPRISE,
   NOMENTREPRISE,
   TAILLE
) VALUES (
   1,
   1,
   1,
   '12345678901234',
   'Entreprise A',
   'PME'
),
(
   2,
   2,
   2,
   '23456789012345',
   'Entreprise B',
   'ETI'
),
(
   3,
   3,
   3,
   '34567890123456',
   'Entreprise C',
   'GE'
),
(
   4,
   4,
   4,
   '45678901234567',
   'Entreprise D',
   'PME'
),
(
   5,
   5,
   5,
   '56789012345678',
   'Entreprise E',
   'ETI'
),
(
   6,
   6,
   6,
   '67890123456789',
   'Entreprise F',
   'GE'
),
(
   7,
   7,
   7,
   '78901234567890',
   'Entreprise G',
   'PME'
),
(
   8,
   8,
   8,
   '89012345678901',
   'Entreprise H',
   'ETI'
),
(
   9,
   9,
   9,
   '90123456789012',
   'Entreprise I',
   'GE'
),
(
   10,
   10,
   10,
   '01234567890123',
   'Entreprise J',
   'PME'
),
(
   11,
   11,
   11,
   '12345678901234',
   'Entreprise K',
   'ETI'
),
(
   12,
   12,
   12,
   '23456789012345',
   'Entreprise L',
   'GE'
),
(
   13,
   13,
   13,
   '34567890123456',
   'Entreprise M',
   'PME'
),
(
   14,
   14,
   14,
   '45678901234567',
   'Entreprise N',
   'ETI'
),
(
   15,
   15,
   15,
   '56789012345678',
   'Entreprise O',
   'GE'
),
(
   16,
   16,
   16,
   '67890123456789',
   'Entreprise P',
   'PME'
),
(
   17,
   17,
   17,
   '78901234567890',
   'Entreprise Q',
   'ETI'
),
(
   18,
   18,
   18,
   '89012345678901',
   'Entreprise R',
   'GE'
),
(
   19,
   19,
   19,
   '90123456789012',
   'Entreprise S',
   'PME'
),
(
   20,
   20,
   20,
   '01234567890123',
   'Entreprise T',
   'ETI'
);

INSERT INTO EST_SITUE_A_2 (
   IDPRODUIT,
   IDETABLISSEMENT
) VALUES (
   1,
   1
),
(
   2,
   1
),
(
   3,
   1
),
(
   4,
   1
),
(
   5,
   2
),
(
   6,
   2
),
(
   7,
   6
),
(
   8,
   8
),
(
   9,
   3
),
(
   10,
   3
),
(
   11,
   3
),
(
   12,
   3
),
(
   13,
   4
),
(
   14,
   4
),
(
   15,
   11
),
(
   16,
   15
),
(
   17,
   5
),
(
   18,
   5
),
(
   19,
   5
),
(
   20,
   5
),
(
   21,
   6
),
(
   22,
   6
),
(
   23,
   6
),
(
   24,
   6
),
(
   25,
   7
),
(
   26,
   7
),
(
   27,
   7
),
(
   28,
   7
),
(
   29,
   7
),
(
   30,
   6
),
(
   31,
   20
),
(
   32,
   6
),
(
   33,
   9
),
(
   34,
   9
),
(
   35,
   15
),
(
   36,
   9
),
(
   37,
   17
),
(
   38,
   10
),
(
   39,
   10
),
(
   40,
   10
),
(
   41,
   10
),
(
   42,
   18
),
(
   43,
   11
),
(
   44,
   12
),
(
   45,
   11
),
(
   46,
   11
),
(
   47,
   12
),
(
   48,
   12
),
(
   49,
   13
),
(
   50,
   13
),
(
   51,
   14
),
(
   52,
   14
),
(
   53,
   14
),
(
   54,
   14
),
(
   55,
   14
),
(
   56,
   14
),
(
   57,
   13
),
(
   58,
   14
),
(
   59,
   9
),
(
   60,
   9
),
(
   61,
   15
),
(
   62,
   16
),
(
   63,
   16
),
(
   64,
   15
),
(
   65,
   17
),
(
   66,
   17
),
(
   67,
   17
),
(
   68,
   16
),
(
   69,
   15
),
(
   70,
   4
),
(
   71,
   18
),
(
   72,
   18
),
(
   73,
   20
),
(
   74,
   20
),
(
   75,
   20
),
(
   76,
   20
),
(
   77,
   20
),
(
   78,
   20
),
(
   79,
   19
),
(
   80,
   19
),
(
   81,
   9
),
(
   82,
   9
),
(
   83,
   9
),
(
   84,
   9
),
(
   85,
   9
),
(
   86,
   9
),
(
   87,
   9
),
(
   88,
   9
),
(
   89,
   9
),
(
   90,
   9
),
(
   91,
   9
),
(
   92,
   9
),
(
   93,
   17
),
(
   94,
   9
),
(
   95,
   17
),
(
   96,
   9
),
(
   97,
   17
),
(
   98,
   9
),
(
   99,
   17
),
(
   100,
   9
),
(
   1,
   21
),
(
   2,
   21
),
(
   3,
   21
),
(
   4,
   21
);

INSERT INTO DEPARTEMENT (
   IDDEPARTEMENT,
   IDPAYS,
   CODEDEPARTEMENT,
   LIBELLEDEPARTEMENT
) VALUES (
   1,
   1,
   '75',
   'Paris'
),
(
   2,
   1,
   '13',
   'Bouches-du-Rhône'
),
(
   3,
   1,
   '69',
   'Rhône'
),
(
   4,
   1,
   '33',
   'Gironde'
),
(
   5,
   1,
   '06',
   'Alpes-Maritimes'
),
(
   6,
   1,
   '44',
   'Loire-Atlantique'
),
(
   7,
   1,
   '59',
   'Nord'
),
(
   8,
   1,
   '34',
   'Hérault'
),
(
   9,
   1,
   '31',
   'Haute-Garonne'
),
(
   10,
   1,
   '85',
   'Vendée'
),
(
   11,
   1,
   '62',
   'Pas-de-Calais'
),
(
   12,
   1,
   '76',
   'Seine-Maritime'
),
(
   13,
   1,
   '94',
   'Val-de-Marne'
),
(
   14,
   1,
   '75',
   'Paris'
),
(
   15,
   1,
   '77',
   'Seine-et-Marne'
),
(
   16,
   1,
   '91',
   'Essonne'
),
(
   17,
   1,
   '93',
   'Seine-Saint-Denis'
),
(
   18,
   1,
   '92',
   'Hauts-de-Seine'
),
(
   19,
   1,
   '95',
   'Val-d Oise'
),
(
   20,
   1,
   '60',
   'Oise'
);

INSERT INTO ETABLISSEMENT (
   IDETABLISSEMENT,
   TYPEETABLISSEMENT,
   IDADRESSE,
   NOMETABLISSEMENT,
   IMAGEETABLISSEMENT,
   HORAIRESOUVERTURE,
   HORAIRESFERMETURE,
   LIVRAISON,
   AEMPORTER
) VALUES (
   1,
   'Restaurant',
   101,
   'McDonald s Paris',
   'https://tb-static.uber.com/prod/image-proc/processed_images/2fbf5a0b7a62e385368c58d3cda5420b/30be7d11a3ed6f6183354d1933fbb6c7.jpeg',
   '09:00:00',
   '18:00:00',
   TRUE,
   FALSE
),
(
   2,
   'Restaurant',
   102,
   'Waffle Factory',
   'https://tb-static.uber.com/prod/image-proc/processed_images/66ba7e9963cfdbe3126a79bd39dcf405/fb86662148be855d931b37d6c1e5fcbe.jpeg',
   '10:00:00',
   '20:00:00',
   FALSE,
   TRUE
),
(
   3,
   'Épicerie',
   103,
   'PAUL',
   'https://tb-static.uber.com/prod/image-proc/processed_images/923496b75901cda79df0e9f8676a63ef/c73ecc27d2a9eaa735b1ee95304ba588.jpeg',
   '07:00:00',
   '15:00:00',
   TRUE,
   TRUE
),
(
   4,
   'Restaurant',
   104,
   'El Chaltén',
   'https://tb-static.uber.com/prod/image-proc/processed_images/39c402fdde3d6556e82959a6a1875629/783282f6131ef2258e5bcd87c46aa87e.jpeg',
   '11:00:00',
   '23:00:00',
   FALSE,
   FALSE
),
(
   5,
   'Restaurant',
   105,
   'Burger King',
   'https://tb-static.uber.com/prod/image-proc/processed_images/7ddafa6aaf2de203eb347fecc6104779/30be7d11a3ed6f6183354d1933fbb6c7.jpeg',
   '09:30:00',
   '19:30:00',
   TRUE,
   FALSE
),
(
   6,
   'Restaurant',
   106,
   'Street Pasta',
   'https://tb-static.uber.com/prod/image-proc/processed_images/73557525500196231ccc1aa93616ed6f/3ac2b39ad528f8c8c5dc77c59abb683d.jpeg',
   '07:00:00',
   '21:00:00',
   FALSE,
   TRUE
),
(
   7,
   'Restaurant',
   107,
   'Black And White Burger',
   'https://tb-static.uber.com/prod/image-proc/processed_images/73b96ac84870d9f13a58811443662dc0/c9252e6c6cd289c588c3381bc77b1dfc.jpeg',
   '10:00:00',
   '22:00:00',
   TRUE,
   FALSE
),
(
   8,
   'Restaurant',
   108,
   'Ben s Food',
   'https://tb-static.uber.com/prod/image-proc/processed_images/2587cc6c5b933f5d9bc5064249bbe575/30be7d11a3ed6f6183354d1933fbb6c7.jpeg',
   '09:00:00',
   '20:00:00',
   TRUE,
   TRUE
),
(
   9,
   'Épicerie',
   109,
   'Carrefour',
   'https://tb-static.uber.com/prod/image-proc/processed_images/2a4e8791c1196c02939b70b106c4c1ae/e00617ce8176680d1c4c1a6fb65963e2.png',
   '08:00:00',
   '20:00:00',
   FALSE,
   FALSE
),
(
   10,
   'Restaurant',
   110,
   'Fat Kebab',
   'https://tb-static.uber.com/prod/image-proc/processed_images/c570cbbeec2420d1ccdeb75ac2d58231/c9252e6c6cd289c588c3381bc77b1dfc.jpeg',
   '11:00:00',
   '23:00:00',
   TRUE,
   FALSE
),
(
   11,
   'Restaurant',
   111,
   'Island Bowls',
   'https://tb-static.uber.com/prod/image-proc/processed_images/57efbcd5e197184d730502035e58bd84/fb86662148be855d931b37d6c1e5fcbe.jpeg',
   '10:00:00',
   '23:00:00',
   FALSE,
   TRUE
),
(
   12,
   'Restaurant',
   112,
   'Pitaya',
   'https://tb-static.uber.com/prod/image-proc/processed_images/40ecc6916fbbd0cd7287694a97bd1d90/3ac2b39ad528f8c8c5dc77c59abb683d.jpeg',
   '09:00:00',
   '18:00:00',
   TRUE,
   FALSE
),
(
   13,
   'Épicerie',
   113,
   'La Mie Câline',
   'https://tb-static.uber.com/prod/image-proc/processed_images/6f02c35928e2bd90b78a8091edb5976f/fb86662148be855d931b37d6c1e5fcbe.jpeg',
   '08:30:00',
   '22:30:00',
   TRUE,
   TRUE
),
(
   14,
   'Épicerie',
   114,
   'Brioche Dorée',
   'https://tb-static.uber.com/prod/image-proc/processed_images/23b865a8185355fdedf2ab992ec28c72/c9252e6c6cd289c588c3381bc77b1dfc.jpeg',
   '06:30:00',
   '15:00:00',
   FALSE,
   FALSE
),
(
   15,
   'Épicerie',
   115,
   'Franprix',
   'https://tb-static.uber.com/prod/image-proc/processed_images/95e959904e811d23b4162516842d57f5/e00617ce8176680d1c4c1a6fb65963e2.png',
   '11:00:00',
   '23:00:00',
   TRUE,
   FALSE
),
(
   16,
   'Épicerie',
   116,
   'Picard',
   'https://tb-static.uber.com/prod/image-proc/processed_images/a5b4df5effe99c1e6459b51137f07938/029e6f4e0c81c14572126109dfe867f3.png',
   '08:00:00',
   '21:00:00',
   TRUE,
   TRUE
),
(
   17,
   'Épicerie',
   117,
   'Vival',
   'https://tb-static.uber.com/prod/image-proc/processed_images/7148418810e489efe27be264ba3694ec/a70f5c9df440d10213e93244e9eb7cad.jpeg',
   '08:00:00',
   '20:00:00',
   FALSE,
   FALSE
),
(
   18,
   'Restaurant',
   118,
   'Instant Rétro',
   'https://tb-static.uber.com/prod/image-proc/processed_images/4be8ea88c2b77663ce2d387b2a7924aa/30be7d11a3ed6f6183354d1933fbb6c7.jpeg',
   '10:00:00',
   '22:00:00',
   TRUE,
   FALSE
),
(
   19,
   'Restaurant',
   119,
   'Chicken HOT',
   'https://tb-static.uber.com/prod/image-proc/processed_images/dcdd6f59af11b38bfaba9961f80fa0ec/cc592037c936600295e9961933037e19.jpeg',
   '09:00:00',
   '18:00:00',
   FALSE,
   TRUE
),
(
   20,
   'Restaurant',
   120,
   'Subway',
   'https://tb-static.uber.com/prod/image-proc/processed_images/6fc5ab9e3adaa71256b0ef5d6619159f/3ac2b39ad528f8c8c5dc77c59abb683d.jpeg',
   '11:00:00',
   '23:00:00',
   TRUE,
   TRUE
),
(
   21,
   'Restaurant',
   131,
   'McDonald s Annecy',
   'https://tb-static.uber.com/prod/image-proc/processed_images/2fbf5a0b7a62e385368c58d3cda5420b/30be7d11a3ed6f6183354d1933fbb6c7.jpeg',
   '09:00:00',
   '18:00:00',
   TRUE,
   FALSE
);

INSERT INTO FACTURE_COURSE (
   IDFACTURE,
   IDCOURSE,
   IDPAYS,
   IDCLIENT,
   MONTANTREGLEMENT,
   DATEFACTURE,
   QUANTITE
) VALUES (
   1,
   1,
   1,
   1,
   50.75,
   '2024-11-21',
   1
),
(
   2,
   2,
   1,
   2,
   35.00,
   '2024-11-20',
   1
),
(
   3,
   3,
   1,
   3,
   40.50,
   '2024-11-19',
   2
),
(
   4,
   4,
   1,
   4,
   25.30,
   '2024-11-18',
   1
),
(
   5,
   5,
   1,
   5,
   45.00,
   '2024-11-17',
   1
),
(
   6,
   6,
   1,
   6,
   20.00,
   '2024-11-16',
   1
),
(
   7,
   7,
   1,
   7,
   60.25,
   '2024-11-15',
   1
),
(
   8,
   8,
   1,
   8,
   33.40,
   '2024-11-14',
   1
),
(
   9,
   9,
   1,
   9,
   27.80,
   '2024-11-13',
   1
),
(
   10,
   10,
   1,
   10,
   55.60,
   '2024-11-12',
   2
),
(
   11,
   11,
   1,
   11,
   15.50,
   '2024-11-11',
   1
),
(
   12,
   12,
   1,
   12,
   28.10,
   '2024-11-10',
   1
),
(
   13,
   13,
   1,
   13,
   22.90,
   '2024-11-09',
   1
),
(
   14,
   14,
   1,
   14,
   19.40,
   '2024-11-08',
   1
),
(
   15,
   15,
   1,
   15,
   30.00,
   '2024-11-07',
   1
),
(
   16,
   16,
   1,
   16,
   43.30,
   '2024-11-06',
   1
),
(
   17,
   17,
   1,
   17,
   25.00,
   '2024-11-05',
   1
),
(
   18,
   18,
   1,
   18,
   48.75,
   '2024-11-04',
   2
),
(
   19,
   19,
   1,
   19,
   38.10,
   '2024-11-03',
   1
),
(
   20,
   20,
   1,
   20,
   52.90,
   '2024-11-02',
   1
);

INSERT INTO PANIER (
   IDPANIER,
   IDCLIENT,
   PRIX
) VALUES (
   1,
   1,
   120.50
),
(
   2,
   2,
   85.30
),
(
   3,
   3,
   110.75
),
(
   4,
   4,
   95.00
),
(
   5,
   5,
   85.90
),
(
   6,
   6,
   55.20
),
(
   7,
   7,
   160.40
),
(
   8,
   8,
   28.25
),
(
   9,
   9,
   68.60
),
(
   10,
   10,
   90.80
),
(
   11,
   11,
   72.10
),
(
   12,
   12,
   110.00
),
(
   13,
   13,
   125.90
),
(
   14,
   14,
   72.50
),
(
   15,
   15,
   74.60
),
(
   16,
   16,
   70.00
),
(
   17,
   17,
   90.30
),
(
   18,
   18,
   37.70
),
(
   19,
   19,
   105.80
),
(
   20,
   20,
   87.20
),
(
   21,
   21,
   112.60
),
(
   22,
   22,
   93.80
),
(
   23,
   23,
   105.50
),
(
   24,
   24,
   88.90
),
(
   25,
   25,
   140.30
),
(
   26,
   26,
   125.00
),
(
   27,
   27,
   110.40
),
(
   28,
   28,
   98.60
),
(
   29,
   29,
   130.90
),
(
   30,
   30,
   140.75
),
(
   31,
   31,
   120.20
),
(
   32,
   32,
   85.50
),
(
   33,
   33,
   100.80
),
(
   34,
   34,
   115.40
),
(
   35,
   35,
   98.30
),
(
   36,
   36,
   105.00
),
(
   37,
   37,
   125.20
),
(
   38,
   38,
   110.90
),
(
   39,
   39,
   95.50
),
(
   40,
   40,
   138.60
),
(
   41,
   41,
   116.70
),
(
   42,
   42,
   124.30
),
(
   43,
   43,
   102.40
),
(
   44,
   44,
   130.00
),
(
   45,
   45,
   90.20
),
(
   46,
   46,
   118.90
),
(
   47,
   47,
   85.60
),
(
   48,
   48,
   125.50
),
(
   49,
   49,
   110.10
),
(
   50,
   50,
   98.80
);

INSERT INTO PAYS (
   IDPAYS,
   NOMPAYS,
   POURCENTAGETVA
) VALUES (
   1,
   'France',
   20.0
),
(
   2,
   'Allemagne',
   19.0
),
(
   3,
   'Espagne',
   21.0
),
(
   4,
   'Italie',
   22.0
),
(
   5,
   'Belgique',
   21.0
),
(
   6,
   'Luxembourg',
   17.0
),
(
   7,
   'Suisse',
   7.7
),
(
   8,
   'Portugal',
   23.0
),
(
   9,
   'Pays-Bas',
   21.0
),
(
   10,
   'Autriche',
   20.0
),
(
   11,
   'Suède',
   25.0
),
(
   12,
   'Danemark',
   25.0
),
(
   13,
   'Finlande',
   24.0
),
(
   14,
   'Irlande',
   23.0
),
(
   15,
   'Grèce',
   24.0
),
(
   16,
   'Pologne',
   23.0
),
(
   17,
   'Hongrie',
   27.0
),
(
   18,
   'République Tchèque',
   21.0
),
(
   19,
   'Slovénie',
   22.0
),
(
   20,
   'Roumanie',
   19.0
);

INSERT INTO PLANNING_RESERVATION (
   IDPLANNING,
   IDCLIENT
) VALUES (
   1,
   1
),
(
   2,
   2
),
(
   3,
   3
),
(
   4,
   4
),
(
   5,
   5
),
(
   6,
   6
),
(
   7,
   7
),
(
   8,
   8
),
(
   9,
   9
),
(
   10,
   10
),
(
   11,
   11
),
(
   12,
   12
),
(
   13,
   13
),
(
   14,
   14
),
(
   15,
   15
),
(
   16,
   16
),
(
   17,
   17
),
(
   18,
   18
),
(
   19,
   19
),
(
   20,
   20
),
(
   21,
   21
),
(
   22,
   22
),
(
   23,
   23
),
(
   24,
   24
),
(
   25,
   25
),
(
   26,
   26
),
(
   27,
   27
),
(
   28,
   28
),
(
   29,
   29
),
(
   30,
   30
),
(
   31,
   31
),
(
   32,
   32
),
(
   33,
   33
),
(
   34,
   34
),
(
   35,
   35
),
(
   36,
   36
),
(
   37,
   37
),
(
   38,
   38
),
(
   39,
   39
),
(
   40,
   40
),
(
   41,
   41
),
(
   42,
   42
),
(
   43,
   43
),
(
   44,
   44
),
(
   45,
   45
),
(
   46,
   46
),
(
   47,
   47
),
(
   48,
   48
),
(
   49,
   49
),
(
   50,
   50
),
(
   51,
   1
),
(
   52,
   2
),
(
   53,
   3
),
(
   54,
   4
),
(
   55,
   5
),
(
   56,
   6
),
(
   57,
   7
),
(
   58,
   8
),
(
   59,
   9
),
(
   60,
   10
),
(
   61,
   11
),
(
   62,
   12
),
(
   63,
   13
),
(
   64,
   14
),
(
   65,
   15
),
(
   66,
   16
),
(
   67,
   17
),
(
   68,
   18
),
(
   69,
   19
),
(
   70,
   20
),
(
   71,
   21
),
(
   72,
   22
),
(
   73,
   23
),
(
   74,
   24
),
(
   75,
   25
),
(
   76,
   26
),
(
   77,
   27
),
(
   78,
   28
),
(
   79,
   29
),
(
   80,
   30
),
(
   81,
   31
),
(
   82,
   32
),
(
   83,
   33
),
(
   84,
   34
),
(
   85,
   35
),
(
   86,
   36
),
(
   87,
   37
),
(
   88,
   38
),
(
   89,
   39
),
(
   90,
   40
),
(
   91,
   41
),
(
   92,
   42
),
(
   93,
   43
),
(
   94,
   44
),
(
   95,
   45
),
(
   96,
   46
),
(
   97,
   47
),
(
   98,
   48
),
(
   99,
   49
),
(
   100,
   50
);

INSERT INTO PRODUIT (
   IDPRODUIT,
   NOMPRODUIT,
   PRIXPRODUIT,
   IMAGEPRODUIT,
   DESCRIPTION
) VALUES (
   1,
   'BIG MAC™',
   14.50,
   'https://tb-static.uber.com/prod/image-proc/processed_images/187f0969e27fd45fb5e70a302aa6ccd6/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
   'Pizza classique avec tomate, mozzarella et basilic frais'
),
(
   2,
   'P TIT WRAP RANCH',
   3.80,
   'https://tb-static.uber.com/prod/image-proc/processed_images/211f0fb68762b32b5baf490fef00bba3/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
   'Pâtes avec une sauce crémeuse au lard et parmesan'
),
(
   3,
   'CHEESEBURGER',
   3.95,
   'https://tb-static.uber.com/prod/image-proc/processed_images/4c2baf71a483ac6bf90fe57309159566/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
   'Burger avec du fromage cheddar fondu, laitue et tomate'
),
(
   4,
   'McFLURRY™ SAVEUR VANILLE DAIM®',
   5.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/d00bfd22b007f99cc299f5e0acb8284f/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
   'Salade verte avec du poulet grillé, croutons et sauce César'
),
(
   5,
   'Waffine à composer',
   5.60,
   'https://tb-static.uber.com/prod/image-proc/processed_images/60b5defd7ce81e7e4594406b1bbeb533/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
   'Spaghetti accompagnés d une sauce à la viande épicée'
),
(
   6,
   'Menu Complet',
   15.50,
   'https://tb-static.uber.com/prod/image-proc/processed_images/fe706ef5f42b6630ce697439389633b5/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
   'Dessert italien à base de café, mascarpone et cacao'
),
(
   7,
   'Tropico Tropical 33cl',
   3.80,
   'https://tb-static.uber.com/prod/image-proc/processed_images/80421cc360d5895fc62ddda20a908c1e/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
   'Gâteau fondant au chocolat avec un cœur coulant'
),
(
   8,
   'Supplément Chocolat Blanc',
   1.10,
   'https://tb-static.uber.com/prod/image-proc/processed_images/5eaff7fba1662e5abe050186d80ee358/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
   'Café latte avec du lait mousseux et une touche de sucre'
),
(
   9,
   'Menu sandwich froid',
   10.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/a66f63bc26d4a10caaf715ad81c3245c/5954bcb006b10dbfd0bc160f6370faf3.jpeg',
   'Assortiment de rouleaux de sushi avec poisson frais et légumes'
),
(
   10,
   'La salade PAUL',
   8.80,
   'https://tb-static.uber.com/prod/image-proc/processed_images/1ab11b6a052383b311a05cce8d3b1090/a19bb09692310dfd41e49a96c424b3a6.jpeg',
   'Poulet rôti avec des herbes et légumes de saison'
),
(
   11,
   'La part de pizza provençale',
   6.00,
   'https://tb-static.uber.com/prod/image-proc/processed_images/0497c9f6af91be7a7efa0eeb8a7f0160/a19bb09692310dfd41e49a96c424b3a6.jpeg',
   'Tacos avec viande, légumes et sauce épicée'
),
(
   12,
   'Le pain nordique 300g',
   4.50,
   'https://tb-static.uber.com/prod/image-proc/processed_images/f9daf45bba1c9b5edaabac3a895251aa/a19bb09692310dfd41e49a96c424b3a6.jpeg',
   'Ravioli farcis aux champignons et sauce crémeuse'
),
(
   13,
   'Empanada carne',
   3.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/7f5ea07b946ef58e7b8c0ba245d3c195/7f4ae9ca0446cbc23e71d8d395a98428.jpeg',
   'Crêpes garnies de Nutella et de bananes fraîches'
),
(
   14,
   'Empanada jamon y queso',
   3.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/b67188ae5e5fdd83dc84edc88799f3aa/7f4ae9ca0446cbc23e71d8d395a98428.jpeg',
   'Crème dessert à la vanille servie avec un coulis de fruits rouges'
),
(
   15,
   'Kombucha mate',
   6.00,
   'https://tb-static.uber.com/prod/image-proc/processed_images/1821d8c777baa704713db996835c53ac/7f4ae9ca0446cbc23e71d8d395a98428.jpeg',
   'Tartare de saumon frais, avocat et citron'
),
(
   16,
   'Fuzetea',
   3.00,
   'https://tb-static.uber.com/prod/image-proc/processed_images/65aeec1897aadd2f4aad333c123d21ca/7f4ae9ca0446cbc23e71d8d395a98428.jpeg',
   'Pâtisserie légère et beurrée, parfaite pour le petit-déjeuner'
),
(
   17,
   '2 MENUS + 2 EXTRAS',
   24.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/b442c5f045ac1003dfa0955d57a3f5f8/5954bcb006b10dbfd0bc160f6370faf3.jpeg',
   'Baguette française croustillante, idéale pour accompagner vos repas'
),
(
   18,
   '3 MENUS + 3 EXTRAS',
   31.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/7984c960f6c32300353e68c2b64c8afb/5954bcb006b10dbfd0bc160f6370faf3.jpeg',
   'Boules de pois chiches épicées, servies avec du pain pita'
),
(
   19,
   'KINGBOX 10 King Nuggets® + 10 Chili Cheese',
   11.70,
   'https://tb-static.uber.com/prod/image-proc/processed_images/0c5a593f78e02d00f3edd7a43921cdd4/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
   'Plat espagnol à base de riz, fruits de mer et légumes'
),
(
   20,
   'Veggie Chicken Louisiane Steakhouse',
   11.10,
   'https://tb-static.uber.com/prod/image-proc/processed_images/a80c346a4df799f7011edf6080b9ab4f/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
   'Burger avec du fromage bleu, oignons caramélisés et sauce maison'
),
(
   21,
   'Chicken',
   11.10,
   'https://tb-static.uber.com/prod/image-proc/processed_images/89da09264832798a80dd0edea34585b5/a19bb09692310dfd41e49a96c424b3a6.jpeg',
   'Pizza avec du pepperoni, tomate et mozzarella'
),
(
   22,
   'Rustic',
   12.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/02a2ae4683161602e5aaaabd38c75cca/a19bb09692310dfd41e49a96c424b3a6.jpeg',
   'Lasagne avec sauce bolognese, viande et béchamel'
),
(
   23,
   'Cordon Bleu',
   15.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/dbc21188817723938eb6b73a82313a6d/a19bb09692310dfd41e49a96c424b3a6.jpeg',
   'Boisson chaude à base de chocolat fondu et lait crémeux'
),
(
   24,
   'Camembert Bites',
   5.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/47f7c16188a930e67364e79de8e0c0e5/a19bb09692310dfd41e49a96c424b3a6.jpeg',
   'Soupe froide à base de tomates, poivrons et concombres'
),
(
   25,
   'Menu West Coast',
   16.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/dab35dc563cbcd50e95518268ca151f2/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
   'Dessert léger et aérien au chocolat noir'
),
(
   26,
   'Mozzarella Sticks',
   3.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/3b6d8a07677b2afc6712b2bc665c6e7f/a19bb09692310dfd41e49a96c424b3a6.jpeg',
   'Steak grillé accompagné de frites croustillantes'
),
(
   27,
   'Menu KO Burger',
   15.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/aa8aa7de050c2d0c6f123e6be9fa6161/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
   'Risotto crémeux avec des champignons frais'
),
(
   28,
   'Mexico',
   14.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/ca94f98a5e134e355245065a275c2340/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
   'Délicat biscuit meringué fourré de ganache parfumée'
),
(
   29,
   'Frites XL',
   5.00,
   'https://tb-static.uber.com/prod/image-proc/processed_images/f5f24d75d579ddbf2e54e93fd8d35247/a19bb09692310dfd41e49a96c424b3a6.jpeg',
   'Rouleaux de maki garnis de saumon frais et légumes'
),
(
   30,
   'Chicken ',
   12.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/89da09264832798a80dd0edea34585b5/a19bb09692310dfd41e49a96c424b3a6.jpeg',
   'Soupe savoureuse à base d’oignons caramélisés et gratinée de fromage'
),
(
   31,
   'Tiramisu Nutella Spéculos',
   4.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/3aaafb7b062923a04ef0ba83ee7e2fd4/a19bb09692310dfd41e49a96c424b3a6.jpeg',
   'Mélange de légumes sautés au wok avec sauce soja'
),
(
   32,
   'Farmer',
   12.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/2a371e731b10487a219a717773edeee2/a19bb09692310dfd41e49a96c424b3a6.jpeg',
   'Burger végétarien avec galette de légumes et sauce mayo maison'
),
(
   33,
   'Nettoyant vitres',
   2.21,
   'https://tb-static.uber.com/prod/image-proc/processed_images/b6afbbfc3c5c6447a0d6e437567159d3/957777de4e8d7439bef56daddbfae227.jpeg',
   'Salade composée de thon, œufs, tomates et olives'
),
(
   34,
   'Carrefour Essential - Papier toilette confort doux (12)',
   4.00,
   'https://tb-static.uber.com/prod/image-proc/processed_images/54290cec7e96e73a88ecf97d5a32c4a4/0e5313be7a8831b8ed60f8dab3c2df10.jpeg',
   'Crêpes flambées avec une sauce à l’orange et au Grand Marnier'
),
(
   35,
   'Kiri - Fromage enfant crème à tartiner (8)',
   2.77,
   'https://tb-static.uber.com/prod/image-proc/processed_images/90b7a5e4812f845a9d8f131052dda3b7/0e5313be7a8831b8ed60f8dab3c2df10.jpeg',
   'Tarte aux pommes caramélisées, servie chaude'
),
(
   36,
   'Carrefour Le Marché - Viande hachée pur bœuf (350g)',
   6.63,
   'https://tb-static.uber.com/prod/image-proc/processed_images/53f823882f73d9ec73513c4930a13ae0/957777de4e8d7439bef56daddbfae227.jpeg',
   'Poulet dans une sauce au curry doux et noix de cajou'
),
(
   37,
   'Brioche Pasquier - Pains au lait (10)',
   2.41,
   'https://tb-static.uber.com/prod/image-proc/processed_images/193aeef2bac1aa55636feb9bad10584d/957777de4e8d7439bef56daddbfae227.jpeg',
   'Pizza avec jambon, champignons, artichauts et olives'
),
(
   38,
   'Le Cordon Bleu',
   7.63,
   'https://tb-static.uber.com/prod/image-proc/processed_images/5b0b83c1202bf16f533886399945ac73/a19bb09692310dfd41e49a96c424b3a6.jpeg',
   'Paella avec des légumes de saison et riz parfumé'
),
(
   39,
   'Le Western',
   11.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/b14503a31169e0a453d4c65fbebcdb80/a19bb09692310dfd41e49a96c424b3a6.jpeg',
   'Tartare de thon frais accompagné de légumes et d’avocat'
),
(
   40,
   'Wings',
   5.90,
   'https://tb-static.uber.com/prod/image-proc/processed_images/b0ce32b34888a8d04607bb2a42e6fefb/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
   'Beignets sucrés frits, servis avec du chocolat chaud'
),
(
   41,
   'Sauce Harissa',
   0.50,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvYzM0YWJhYjc5OWEzMmZhNWU1ZWJiZDhkNmFkZjJiNTYvNTE0M2YxZTIxOGM2N2MyMGZlNWE0Y2QzM2Q5MGIwN2IuanBlZw==',
   'Poulet cuit dans une sauce crémeuse au curry et lait de coco'
),
(
   42,
   'Bao Poulet croustillant',
   8.90,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvNTA3Y2I2YTJjN2I0OGNhNTcyYmJkYzU0YWZlODE3OTkvYTE5YmIwOTY5MjMxMGRmZDQxZTQ5YTk2YzQyNGIzYTYuanBlZw==',
   'Riz basmati parfumé cuit avec des épices et des légumes'
),
(
   43,
   'Menu Poké & Boisson',
   17.50,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvYjI1NmI0ODdmYzYwYmFkYjFkOTg3MmYyZjdmNDRlNjgvN2Y0YWU5Y2EwNDQ2Y2JjMjNlNzFkOGQzOTVhOTg0MjguanBlZw==',
   'Pizza avec jambon, ananas, tomate et mozzarella'
),
(
   44,
   'Bobun boeuf',
   13.50,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvY2Q3NTc3MjAyYmY0YTBlN2ViYzRhMWEzODUxMGQ4YTIvYTE5YmIwOTY5MjMxMGRmZDQxZTQ5YTk2YzQyNGIzYTYuanBlZw==',
   'Gâteau crémeux au fromage avec une base biscuitée'
),
(
   45,
   'Beignets de crevettes tempura',
   7.60,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvOGYxYjU3Mjg5ODczZTBhZDhjZjQ4NWE1NTBlZWMwN2MvYTE5YmIwOTY5MjMxMGRmZDQxZTQ5YTk2YzQyNGIzYTYuanBlZw==',
   'Moules cuites dans un bouillon de vin blanc, ail et persil'
),
(
   46,
   'PLAT + BOISSON CLASSIQUE',
   16.90,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvYTFmNjUzYzJhOTIxYmUxZTYyOTZkZDY3MTY2ODE3MzAvYTE5YmIwOTY5MjMxMGRmZDQxZTQ5YTk2YzQyNGIzYTYuanBlZw==',
   'Mélange de légumes épicés cuits dans une sauce au curry'
),
(
   47,
   'CRUNCHY THAÏ BOX',
   13.50,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvMmYzY2RhZjIwZWRlMTdjMjdiZTUxNjMxMjg4ZmQ4MmQvYTE5YmIwOTY5MjMxMGRmZDQxZTQ5YTk2YzQyNGIzYTYuanBlZw==',
   'Soupe de ramen avec du porc, œuf et légumes'
),
(
   48,
   'CHICKEN ou BEEF THAI',
   15.50,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvMDY2NTVjOTkxZDgzNzQxYzU1ZmE0YzAzZWJlM2FmNGIvNTE0M2YxZTIxOGM2N2MyMGZlNWE0Y2QzM2Q5MGIwN2IuanBlZw==',
   'Viande en pâte feuilletée servie avec une salade verte'
),
(
   49,
   'Le Cook Mie extra',
   13.80,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvNTJiZmZkZTRjZjkwYWQ2N2MyZDFhZTFiM2Y3YjA3NmUvYTE5YmIwOTY5MjMxMGRmZDQxZTQ5YTk2YzQyNGIzYTYuanBlZw==',
   'Boeuf braisé dans une sauce au vin rouge avec des légumes'
),
(
   50,
   'Le Cook Mie Bistro',
   12.30,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvYjJmZDBiMTFlOWI3ZDkzYjQxOGU2OGU1N2RlNTI3MmEvYTE5YmIwOTY5MjMxMGRmZDQxZTQ5YTk2YzQyNGIzYTYuanBlZw==',
   'Gâteau moelleux aux fruits frais de saison'
),
(
   51,
   '3 Cookies Caramel achetés le 4ème offert',
   6.75,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvZjA5ZTNlOGE0M2Q0Y2FkZDMxZDBjYTg5ZmM1Zjk1OTYvYTE5YmIwOTY5MjMxMGRmZDQxZTQ5YTk2YzQyNGIzYTYuanBlZw==',
   'Tartare de bœuf frais, accompagné de frites et sauce à part'
),
(
   52,
   'Cookiz duo de choc',
   2.25,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvYTQ0OGYyZjE5MGJlMWUwZWQwMTZjYzBhNmUzN2Y3ZWUvZjBkMTc2MmI5MWZkODIzYTFhYTliZDBkYWI1YzY0OGQuanBlZw==',
   'Crevettes sautées à l’ail et au persil, servies avec du pain grillé'
),
(
   53,
   'MENU AUTHENTIQUE THON CRUDITÉS',
   11.10,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvZGQ1MjZiZGUzOGJkZjVlMDgzNDQyNGUyMjViMTBjNWUvNTE0M2YxZTIxOGM2N2MyMGZlNWE0Y2QzM2Q5MGIwN2IuanBlZw==',
   'Omelette italienne aux légumes et herbes fraîches'
),
(
   54,
   'TOASTÉ POULET CURRY',
   7.20,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvOGFhMjUxZDM0NGZmZDMwMTFkZTA3NThjZmUzMWIyODUvNTE0M2YxZTIxOGM2N2MyMGZlNWE0Y2QzM2Q5MGIwN2IuanBlZw==',
   'Assortiment de makis avec poisson cru et légumes'
),
(
   55,
   'Quiche Lorraine',
   5.90,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvNjcxYzQ3YjAyMmY2OWMwODNhZjc1OWYxM2ViMzJjZTcvNTE0M2YxZTIxOGM2N2MyMGZlNWE0Y2QzM2Q5MGIwN2IuanBlZw==',
   'Quiche avec lardons, crème fraîche et fromage'
),
(
   56,
   'FUSETTE CITRON MERINGUÉE',
   4.20,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvNGE3Y2ZhZWI4MGU2MDI2ZjNjNTliY2NiMzRmOTc3N2YvNTE0M2YxZTIxOGM2N2MyMGZlNWE0Y2QzM2Q5MGIwN2IuanBlZw==',
   'Pizza repliée avec mozzarella, tomate et jambon'
),
(
   57,
   'FROMAGE BLANC 0% FRUITS ET COULIS DE FRUITS',
   3.70,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvYjRhN2M4YmQ3YzE4NTRiZDczZDdjYjE4MWQ3MDcyNzQvNTE0M2YxZTIxOGM2N2MyMGZlNWE0Y2QzM2Q5MGIwN2IuanBlZw==',
   'Dessert crémeux à base de riz au lait et cannelle'
),
(
   58,
   'ALLONGÉ',
   2.10,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvM2QxMGQxNjRhMjk5Mzc2MTdhMThiYTMxZTNmNTNlMmEvNTE0M2YxZTIxOGM2N2MyMGZlNWE0Y2QzM2Q5MGIwN2IuanBlZw==',
   'Mélange frais de fruits de saison'
),
(
   59,
   'Pur jus de pomme Franprix 1l',
   2.05,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvMjllYjZiNDEzNDAyMjI2NTliZDI3ODk1MmJjMmVlOTkvYTE5YmIwOTY5MjMxMGRmZDQxZTQ5YTk2YzQyNGIzYTYuanBlZw==',
   'Pizza garnie de saumon fumé, crème fraîche et aneth'
),
(
   60,
   'Barre chocolatée Kit Kat unité 41.5g',
   1.30,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvZTVlZWQwMGRkZjQxNjEyYmQ4NzQwOWZjNjljYzA0ZmEvYTE5YmIwOTY5MjMxMGRmZDQxZTQ5YTk2YzQyNGIzYTYuanBlZw==',
   'Soupe de poisson avec des légumes et du pain grillé'
),
(
   61,
   'Fromage Compté aux lait cru Franprix',
   5.02,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvZjRjYmZhMjAwN2UxMWQzMjNiN2I2NGQyMGMwZWI0NWQvYTE5YmIwOTY5MjMxMGRmZDQxZTQ5YTk2YzQyNGIzYTYuanBlZw==',
   'Pizza avec légumes grillés, tomate, mozzarella et basilic'
),
(
   62,
   '2 cuisses de canard du Sud-Ouest confites',
   10.81,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvNWNkNjk3NTllOWEzMjExMjdjYTliY2RhOTBiZmU3OTkvOTU3Nzc3ZGU0ZThkNzQzOWJlZjU2ZGFkZGJmYWUyMjcuanBlZw==',
   'Côtelettes d’agneau grillées avec une sauce au romarin'
),
(
   63,
   '2 moelleux au chocolat',
   4.19,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvZTcwZTQyMTdjYjU3N2MwYjg1OTFjOTBmNTdhMWU3NGMvOTU3Nzc3ZGU0ZThkNzQzOWJlZjU2ZGFkZGJmYWUyMjcuanBlZw==',
   'Gnocchis accompagnés d’une sauce crémeuse au parmesan'
),
(
   64,
   'Pizza chèvre, miel, noix',
   4.83,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvNWM4NTlhY2ViM2ZmOTY2ZTAyZmM4Y2E2ZTJhMWJmZjcvOTU3Nzc3ZGU0ZThkNzQzOWJlZjU2ZGFkZGJmYWUyMjcuanBlZw==',
   'Choux remplis de crème pâtissière et enrobés de chocolat'
),
(
   65,
   'Boulettes de viande kefta',
   4.49,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvMTJiNjMxOTAzOTdjNzQxODAzNzVhNjc5Y2Y3NzVkOGEvOTU3Nzc3ZGU0ZThkNzQzOWJlZjU2ZGFkZGJmYWUyMjcuanBlZw==',
   'Tartelette avec une crème au citron acidulée et croûte sablée'
),
(
   66,
   'Bret s - Chips de pommes de terre, fromage (125g)',
   4.10,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvMzUxNTk0MDA2NmU0MTIwMDBlNmU3OGE3ZTQ2NzQwMzQvMGU1MzEzYmU3YTg4MzFiOGVkNjBmOGRhYjNjMmRmMTAuanBlZw==',
   'Pizza garnie de mozzarella, gorgonzola, chèvre et parmesan'
),
(
   67,
   'Granola Choco Lait',
   2.67,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvYjdmMDYxZTQ2N2UzOTZmYzk1YTIxZDI0OWY3NzM4OTIvOTU3Nzc3ZGU0ZThkNzQzOWJlZjU2ZGFkZGJmYWUyMjcuanBlZw==',
   'Salade de quinoa avec légumes frais et vinaigrette au citron'
),
(
   68,
   'Ben & Jerry s - Crème glacée, vanille, cookie dough (406g)',
   10.77,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvYTg3MzZkMTFkZDIxYTMyNTI0MzM0ZGQ1M2EwMGQ0YjYvMGU1MzEzYmU3YTg4MzFiOGVkNjBmOGRhYjNjMmRmMTAuanBlZw==',
   'Ragoût végétarien avec des légumes mijotés et épicés'
),
(
   69,
   'Justin Bridou - Saucisson petits bâton de berger nature (100g)',
   6.44,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvOTUxZjRlNDczN2I2NGYzMDYwZGJiMmExN2U4OGI1Y2IvMGU1MzEzYmU3YTg4MzFiOGVkNjBmOGRhYjNjMmRmMTAuanBlZw==',
   'Burger avec sauce barbecue, bacon, et oignons grillés'
),
(
   70,
   'Donut nutella billes',
   4.55,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvMGNkMjlhZThhNGJhMzhjN2M4YWQ3YWE2OWYzNDQyOWIvNDIxOGNhMWQwOTE3NDIxODM2NDE2MmNkMGIxYThjYzEuanBlZw==',
   'Moules cuites dans une sauce au vin blanc, servies avec frites'
),
(
   71,
   'Bagel R. Charles',
   13.00,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvMTk5Mzc2YzRlYTZmOWIwMmQ4ZWNkZDJiNzQ4NDQ2NDcvNDIxOGNhMWQwOTE3NDIxODM2NDE2MmNkMGIxYThjYzEuanBlZw==',
   'Poulet frit croustillant, servi avec une sauce épicée'
),
(
   72,
   'Bagel N. Simone',
   13.00,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvYjBjYmI2ODFiY2Q3NzcyYjE0NWRjZjkzOTJmZTJhN2QvNDIxOGNhMWQwOTE3NDIxODM2NDE2MmNkMGIxYThjYzEuanBlZw==',
   'Pizza avec poulet rôti, champignons et mozzarella'
),
(
   73,
   'Röstis x6',
   6.00,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvNWIyMWNlM2RhYTQ2YWU5ZDg2MDhmMjk3YTNjZWI5YmIvNTE0M2YxZTIxOGM2N2MyMGZlNWE0Y2QzM2Q5MGIwN2IuanBlZw==',
   'Boisson à base de yaourt et mangue'
),
(
   74,
   'SUB15 Dinde',
   9.00,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvODg0NzhiNWJlYTZlNjQ2NDA2ODk1YWVlOTRjMzU5NWQvNTE0M2YxZTIxOGM2N2MyMGZlNWE0Y2QzM2Q5MGIwN2IuanBlZw==',
   'Tacos avec viande de porc marinée, ananas et oignons'
),
(
   75,
   'Wrap Crispy Avocado',
   10.00,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvMDQ5MTliYjUyMzM4YjE4NzEwNjRkMDBhYmM4Mzg5YjkvNTE0M2YxZTIxOGM2N2MyMGZlNWE0Y2QzM2Q5MGIwN2IuanBlZw==',
   'Tarte avec poires fraîches et crème d’amandes'
),
(
   76,
   'SUB30 Xtreme Raclette Steakhouse',
   15.00,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvM2M0MWU1ZDU0ZTRjMTk1NGYxNzVhMjJhNzE0Y2NkNzQvNTE0M2YxZTIxOGM2N2MyMGZlNWE0Y2QzM2Q5MGIwN2IuanBlZw==',
   'Plat épicé avec viande hachée, haricots rouges et épices'
),
(
   77,
   'Menu SUB30 Poulet ',
   16.50,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvOTQ2ZWQxMmI0NzViNzRmODIxYmRhZmJjNzlkNmU3MTIvNTE0M2YxZTIxOGM2N2MyMGZlNWE0Y2QzM2Q5MGIwN2IuanBlZw==',
   'Salade avec feta, olives, tomates et concombre'
),
(
   78,
   'Jalapenos',
   5.70,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvZDJmZTE3OWIwYmY2YWYwOGY0OTg3NGUxMzQ1YTlkZTcvNThmNjkxZGE5ZWFlZjg2YjBiNTFmOWIyYzQ4M2ZlNjMuanBlZw==',
   'Tartine de pain grillé avec tapenade d’olive'
),
(
   79,
   'Menu 6 HOT WINGS',
   14.20,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvMTIwOWFjZDQzN2VkMmE4NjcyM2Q5ZWZkYjYyNDBhNGIvNThmNjkxZGE5ZWFlZjg2YjBiNTFmOWIyYzQ4M2ZlNjMuanBlZw==',
   'Foie gras accompagné de pain d’épices et confiture'
),
(
   80,
   'Salade César',
   10.50,
   'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvM2Q1NzlhN2QxYTBhZGVjZTZhNWRhYzZiNDI2ZmUwNTQvNThmNjkxZGE5ZWFlZjg2YjBiNTFmOWIyYzQ4M2ZlNjMuanBlZw==',
   'Pizza avec crème, lardons, fromage et œuf poché'
),
(
   81,
   'Shampooing L’Oréal 250ml',
   4.99,
   'https://example.com/shampooing.jpg',
   'Shampooing nourrissant pour cheveux secs'
),
(
   82,
   'Gel Douche Dove 500ml',
   3.50,
   'https://example.com/gel-douche.jpg',
   'Gel douche hydratant pour une peau douce'
),
(
   83,
   'Papier Aluminium 30m',
   2.99,
   'https://example.com/papier-aluminium.jpg',
   'Rouleau de papier aluminium pour usage domestique'
),
(
   84,
   'Brosse à dents Oral-B',
   2.75,
   'https://example.com/brosse-a-dents.jpg',
   'Brosse à dents avec brins souples pour un nettoyage en profondeur'
),
(
   85,
   'Savon Liquide Dettol 250ml',
   3.20,
   'https://example.com/savon-liquide.jpg',
   'Savon liquide antibactérien pour les mains'
),
(
   86,
   'Pack de 6 bouteilles d’eau Evian 1.5L',
   6.00,
   'https://example.com/eau-evian.jpg',
   'Eau minérale naturelle en pack pratique'
),
(
   87,
   'Lessive Ariel 3kg',
   12.99,
   'https://example.com/lessive-ariel.jpg',
   'Lessive en poudre pour un linge impeccable'
),
(
   88,
   'Boîte de mouchoirs en papier',
   1.80,
   'https://example.com/mouchoirs.jpg',
   'Mouchoirs en papier doux et résistants'
),
(
   89,
   'Batteries AA Duracell (pack de 4)',
   6.50,
   'https://example.com/batteries.jpg',
   'Piles longue durée pour appareils électroniques'
),
(
   90,
   'Ampoule LED 10W Philips',
   5.99,
   'https://example.com/ampoule-led.jpg',
   'Ampoule LED écoénergétique, équivalente à 60W'
),
(
   91,
   'Crème hydratante Nivea 200ml',
   7.50,
   'https://example.com/creme-nivea.jpg',
   'Crème nourrissante pour une peau douce et hydratée'
),
(
   92,
   'Ruban adhésif Scotch 50m',
   1.99,
   'https://example.com/ruban-adhesif.jpg',
   'Ruban adhésif transparent pour usage quotidien'
),
(
   93,
   'Ciseaux Fiskars',
   8.00,
   'https://example.com/ciseaux.jpg',
   'Ciseaux robustes et ergonomiques pour travaux de coupe'
),
(
   94,
   'Cahier Oxford 200 pages',
   3.25,
   'https://example.com/cahier-oxford.jpg',
   'Cahier grand format avec couverture rigide'
),
(
   95,
   'Pack de 3 éponges Scotch-Brite',
   2.49,
   'https://example.com/eponges.jpg',
   'Éponges résistantes pour nettoyage en profondeur'
),
(
   96,
   'Spray nettoyant multisurface Ajax 750ml',
   3.99,
   'https://example.com/spray-nettoyant.jpg',
   'Spray nettoyant pour toutes les surfaces'
),
(
   97,
   'Sac poubelle 50L (10 unités)',
   2.75,
   'https://example.com/sac-poubelle.jpg',
   'Sacs poubelle résistants avec liens intégrés'
),
(
   98,
   'Coton-tiges Johnson & Johnson (200 unités)',
   1.90,
   'https://example.com/coton-tiges.jpg',
   'Coton-tiges doux et pratiques pour une hygiène quotidienne'
),
(
   99,
   'Thermomètre digital Omron',
   12.90,
   'https://example.com/thermometre.jpg',
   'Thermomètre précis pour mesure rapide de la température'
),
(
   100,
   'Pack de feuilles A4 500 pages',
   4.99,
   'https://example.com/feuilles-a4.jpg',
   'Papier pour imprimante et usage bureautique'
);

INSERT INTO REGLEMENT_SALAIRE (
   IDREGLEMENT,
   IDCOURSIER,
   MONTANTREGLEMENT
) VALUES (
   1,
   1,
   1500.00
),
(
   2,
   2,
   1700.00
),
(
   3,
   3,
   1600.00
),
(
   4,
   4,
   1550.00
),
(
   5,
   5,
   1450.00
),
(
   6,
   6,
   1800.00
),
(
   7,
   7,
   1750.00
),
(
   8,
   8,
   1500.00
),
(
   9,
   9,
   1650.00
),
(
   10,
   10,
   1600.00
);

INSERT INTO RESERVATION (
   IDRESERVATION,
   IDCLIENT,
   IDPLANNING,
   IDVELO,
   DATERESERVATION,
   HEURERESERVATION,
   POURQUI
) VALUES (
   1,
   50,
   1,
   1,
   '2024-11-14',
   '13:00:00',
   'moi'
),
(
   2,
   9,
   2,
   2,
   '2024-11-24',
   '09:30:00',
   'mon ami'
),
(
   3,
   27,
   3,
   3,
   '2024-11-27',
   '13:30:00',
   'mon ami'
),
(
   4,
   5,
   4,
   4,
   '2024-11-18',
   '15:45:00',
   'mon ami'
),
(
   5,
   1,
   5,
   5,
   '2024-11-18',
   '17:00:00',
   'mon ami'
),
(
   6,
   2,
   6,
   6,
   '2024-11-22',
   '15:15:00',
   'moi'
),
(
   7,
   3,
   7,
   7,
   '2024-11-22',
   '20:30:00',
   'moi'
),
(
   8,
   38,
   8,
   8,
   '2024-11-26',
   '20:15:00',
   'moi'
),
(
   9,
   28,
   9,
   9,
   '2024-11-24',
   '13:45:00',
   'moi'
),
(
   10,
   34,
   10,
   10,
   '2024-11-18',
   '16:30:00',
   'mon ami'
),
(
   11,
   12,
   11,
   1,
   '2024-11-17',
   '08:00:00',
   'moi'
),
(
   12,
   1,
   12,
   2,
   '2024-11-21',
   '08:15:00',
   'mon ami'
),
(
   13,
   45,
   13,
   3,
   '2024-11-26',
   '19:00:00',
   'mon ami'
),
(
   14,
   16,
   14,
   4,
   '2024-11-19',
   '17:15:00',
   'mon ami'
),
(
   15,
   39,
   15,
   5,
   '2024-11-27',
   '16:45:00',
   'moi'
),
(
   16,
   3,
   16,
   6,
   '2024-11-17',
   '17:00:00',
   'mon ami'
),
(
   17,
   23,
   17,
   7,
   '2024-11-23',
   '10:30:00',
   'mon ami'
),
(
   18,
   2,
   18,
   8,
   '2024-11-15',
   '10:45:00',
   'mon ami'
),
(
   19,
   37,
   19,
   9,
   '2024-11-11',
   '17:30:00',
   'moi'
),
(
   20,
   47,
   20,
   10,
   '2024-11-25',
   '16:30:00',
   'moi'
),
(
   21,
   6,
   21,
   1,
   '2024-11-28',
   '14:30:00',
   'moi'
),
(
   22,
   45,
   22,
   2,
   '2024-11-20',
   '16:00:00',
   'moi'
),
(
   23,
   19,
   23,
   3,
   '2024-11-12',
   '11:45:00',
   'mon ami'
),
(
   24,
   11,
   24,
   4,
   '2024-11-27',
   '10:45:00',
   'moi'
),
(
   25,
   20,
   25,
   5,
   '2024-11-28',
   '15:30:00',
   'mon ami'
),
(
   26,
   12,
   26,
   6,
   '2024-11-28',
   '17:00:00',
   'moi'
),
(
   27,
   34,
   27,
   7,
   '2024-11-26',
   '13:30:00',
   'moi'
),
(
   28,
   21,
   28,
   8,
   '2024-11-21',
   '19:00:00',
   'moi'
),
(
   29,
   18,
   29,
   9,
   '2024-11-25',
   '10:00:00',
   'mon ami'
),
(
   30,
   28,
   30,
   10,
   '2024-11-23',
   '16:45:00',
   'mon ami'
),
(
   31,
   28,
   31,
   1,
   '2024-11-19',
   '17:00:00',
   'moi'
),
(
   32,
   7,
   32,
   2,
   '2024-11-28',
   '16:45:00',
   'moi'
),
(
   33,
   33,
   33,
   3,
   '2024-11-11',
   '12:45:00',
   'moi'
),
(
   34,
   25,
   34,
   4,
   '2024-11-22',
   '10:45:00',
   'mon ami'
),
(
   35,
   18,
   35,
   5,
   '2024-11-28',
   '09:00:00',
   'moi'
),
(
   36,
   10,
   36,
   6,
   '2024-11-22',
   '12:15:00',
   'mon ami'
),
(
   37,
   37,
   37,
   7,
   '2024-11-14',
   '13:00:00',
   'mon ami'
),
(
   38,
   34,
   38,
   8,
   '2024-11-14',
   '09:45:00',
   'mon ami'
),
(
   39,
   39,
   39,
   9,
   '2024-11-11',
   '16:30:00',
   'moi'
),
(
   40,
   15,
   40,
   1,
   '2024-11-22',
   '12:00:00',
   'moi'
),
(
   41,
   26,
   41,
   2,
   '2024-11-13',
   '14:30:00',
   'mon ami'
),
(
   42,
   41,
   42,
   3,
   '2024-11-17',
   '08:45:00',
   'moi'
),
(
   43,
   33,
   43,
   4,
   '2024-11-28',
   '08:00:00',
   'moi'
),
(
   44,
   48,
   44,
   5,
   '2024-11-15',
   '17:45:00',
   'mon ami'
),
(
   45,
   18,
   45,
   6,
   '2024-11-28',
   '13:15:00',
   'mon ami'
),
(
   46,
   38,
   46,
   7,
   '2024-11-18',
   '13:30:00',
   'moi'
),
(
   47,
   4,
   47,
   8,
   '2024-11-18',
   '10:00:00',
   'moi'
),
(
   48,
   49,
   48,
   9,
   '2024-11-27',
   '17:30:00',
   'moi'
),
(
   49,
   38,
   49,
   10,
   '2024-11-25',
   '19:00:00',
   'mon ami'
),
(
   50,
   33,
   50,
   1,
   '2024-11-21',
   '15:30:00',
   'moi'
),
(
   51,
   25,
   24,
   NULL,
   '2024-11-21',
   '09:45:00',
   'moi'
),
(
   52,
   31,
   4,
   NULL,
   '2024-11-18',
   '10:00:00',
   'moi'
),
(
   53,
   11,
   5,
   NULL,
   '2024-11-14',
   '12:45:00',
   'mon ami'
),
(
   54,
   44,
   47,
   NULL,
   '2024-11-14',
   '09:45:00',
   'mon ami'
),
(
   55,
   35,
   9,
   NULL,
   '2024-11-28',
   '19:15:00',
   'moi'
),
(
   56,
   29,
   10,
   NULL,
   '2024-11-28',
   '20:45:00',
   'moi'
),
(
   57,
   12,
   31,
   NULL,
   '2024-11-11',
   '10:15:00',
   'moi'
),
(
   58,
   33,
   1,
   NULL,
   '2024-11-16',
   '13:30:00',
   'moi'
),
(
   59,
   43,
   49,
   NULL,
   '2024-11-28',
   '20:45:00',
   'mon ami'
),
(
   60,
   17,
   6,
   NULL,
   '2024-11-16',
   '17:30:00',
   'moi'
),
(
   61,
   19,
   40,
   NULL,
   '2024-11-26',
   '08:45:00',
   'mon ami'
),
(
   62,
   27,
   29,
   NULL,
   '2024-11-21',
   '18:00:00',
   'moi'
),
(
   63,
   41,
   7,
   NULL,
   '2024-11-13',
   '19:00:00',
   'mon ami'
),
(
   64,
   33,
   24,
   NULL,
   '2024-11-28',
   '16:30:00',
   'moi'
),
(
   65,
   33,
   1,
   NULL,
   '2024-11-16',
   '10:00:00',
   'mon ami'
),
(
   66,
   33,
   2,
   NULL,
   '2024-11-28',
   '15:30:00',
   'mon ami'
),
(
   67,
   45,
   20,
   NULL,
   '2024-11-18',
   '13:00:00',
   'moi'
),
(
   68,
   6,
   7,
   NULL,
   '2024-11-15',
   '09:15:00',
   'mon ami'
),
(
   69,
   35,
   10,
   NULL,
   '2024-11-23',
   '09:15:00',
   'mon ami'
),
(
   70,
   48,
   15,
   NULL,
   '2024-11-11',
   '16:45:00',
   'mon ami'
),
(
   71,
   15,
   2,
   NULL,
   '2024-11-20',
   '10:15:00',
   'moi'
),
(
   72,
   10,
   11,
   NULL,
   '2024-11-28',
   '16:00:00',
   'moi'
),
(
   73,
   5,
   5,
   NULL,
   '2024-11-25',
   '13:00:00',
   'mon ami'
),
(
   74,
   18,
   2,
   NULL,
   '2024-11-28',
   '10:00:00',
   'moi'
),
(
   75,
   24,
   15,
   NULL,
   '2024-11-11',
   '18:00:00',
   'mon ami'
),
(
   76,
   23,
   44,
   NULL,
   '2024-11-25',
   '09:00:00',
   'moi'
),
(
   77,
   33,
   9,
   NULL,
   '2024-11-19',
   '17:15:00',
   'moi'
),
(
   78,
   15,
   46,
   NULL,
   '2024-11-15',
   '18:30:00',
   'moi'
),
(
   79,
   4,
   34,
   NULL,
   '2024-11-28',
   '08:45:00',
   'mon ami'
),
(
   80,
   37,
   50,
   NULL,
   '2024-11-20',
   '15:30:00',
   'moi'
),
(
   81,
   48,
   46,
   NULL,
   '2024-11-26',
   '09:15:00',
   'moi'
),
(
   82,
   2,
   24,
   NULL,
   '2024-11-28',
   '15:00:00',
   'mon ami'
),
(
   83,
   25,
   16,
   NULL,
   '2024-11-24',
   '17:45:00',
   'moi'
),
(
   84,
   8,
   26,
   NULL,
   '2024-11-11',
   '16:30:00',
   'mon ami'
),
(
   85,
   31,
   33,
   NULL,
   '2024-11-20',
   '18:45:00',
   'mon ami'
),
(
   86,
   42,
   15,
   NULL,
   '2024-11-17',
   '12:00:00',
   'mon ami'
),
(
   87,
   3,
   43,
   NULL,
   '2024-11-28',
   '10:45:00',
   'mon ami'
),
(
   88,
   21,
   48,
   NULL,
   '2024-11-18',
   '10:00:00',
   'mon ami'
),
(
   89,
   48,
   13,
   NULL,
   '2024-11-13',
   '11:15:00',
   'moi'
),
(
   90,
   9,
   8,
   NULL,
   '2024-11-22',
   '15:30:00',
   'mon ami'
),
(
   91,
   16,
   9,
   NULL,
   '2024-11-28',
   '17:45:00',
   'mon ami'
),
(
   92,
   46,
   32,
   NULL,
   '2024-11-24',
   '10:45:00',
   'mon ami'
),
(
   93,
   24,
   33,
   NULL,
   '2024-11-21',
   '19:45:00',
   'moi'
),
(
   94,
   18,
   20,
   NULL,
   '2024-11-21',
   '08:15:00',
   'mon ami'
),
(
   95,
   16,
   19,
   NULL,
   '2024-11-13',
   '20:00:00',
   'mon ami'
),
(
   96,
   30,
   22,
   NULL,
   '2024-11-20',
   '13:45:00',
   'moi'
),
(
   97,
   32,
   27,
   NULL,
   '2024-11-28',
   '08:45:00',
   'moi'
),
(
   98,
   49,
   50,
   NULL,
   '2024-11-26',
   '17:45:00',
   'moi'
),
(
   99,
   35,
   34,
   NULL,
   '2024-11-27',
   '14:30:00',
   'mon ami'
),
(
   100,
   43,
   5,
   NULL,
   '2024-11-28',
   '09:00:00',
   'mon ami'
);

INSERT INTO CATEGORIE_PRESTATION (
   IDCATEGORIEPRESTATION,
   LIBELLECATEGORIEPRESTATION,
   DESCRIPTIONCATEGORIEPRESTATION,
   IMAGECATEGORIEPRESTATION
) VALUES (
   1,
   'Courses',
   'Livraison de courses et produits',
   'courses.jpg'
),
(
   2,
   'Halal',
   'Restaurants et plats halal',
   'halal.jpg'
),
(
   3,
   'Pizzas',
   'Livraison de pizzas',
   'pizzas.jpg'
),
(
   4,
   'Sushis',
   'Restaurants et livraison de sushis',
   'sushis.jpg'
),
(
   5,
   'Alcool',
   'Livraison de boissons alcoolisées',
   'alcool.jpg'
),
(
   6,
   'Épicerie',
   'Produits d épicerie',
   'epicerie.jpg'
),
(
   7,
   'Fast food',
   'Restauration rapide',
   'fastfood.jpg'
),
(
   8,
   'Burgers',
   'Restaurants de burgers',
   'burgers.jpg'
),
(
   9,
   'Asiatique',
   'Cuisine asiatique',
   'asiatique.jpg'
),
(
   10,
   'Cuisine saine',
   'Options de repas santé',
   'cuisinesaine.jpg'
),
(
   11,
   'Thaïlandaise',
   'Cuisine thaïlandaise',
   'thailandaise.jpg'
),
(
   12,
   'Coréenne',
   'Cuisine coréenne',
   'coreenne.jpg'
),
(
   13,
   'Indienne',
   'Cuisine indienne',
   'indienne.jpg'
),
(
   14,
   'Thé aux perles',
   'Bubble tea',
   'teauxperles.jpg'
),
(
   15,
   'Ailes de poulet',
   'Spécialités de poulet',
   'ailespoule.jpg'
),
(
   16,
   'Boulangerie',
   'Produits de boulangerie',
   'boulangerie.jpg'
),
(
   17,
   'Casher',
   'Restaurants et plats casher',
   'casher.jpg'
),
(
   18,
   'Chinoise',
   'Cuisine chinoise',
   'chinoise.jpg'
),
(
   19,
   'Poke (poisson cru)',
   'Bols de poisson cru',
   'poke.jpg'
),
(
   20,
   'Mexicaine',
   'Cuisine mexicaine',
   'mexicaine.jpg'
),
(
   21,
   'Sandwich',
   'Sandwichs et paninis',
   'sandwich.jpg'
),
(
   22,
   'Italienne',
   'Cuisine italienne',
   'italienne.jpg'
),
(
   23,
   'Barbecue',
   'Restaurants de barbecue',
   'barbecue.jpg'
),
(
   24,
   'Spécialité',
   'Plats et cuisines spécialisés',
   'specialite.jpg'
),
(
   25,
   'Petit-déjeuner',
   'Options de petit-déjeuner',
   'petitdejeuner.jpg'
),
(
   26,
   'Vegan',
   'Options végétaliennes',
   'vegan.jpg'
),
(
   27,
   'Caribéenne',
   'Cuisine caribéenne',
   'caribeenne.jpg'
),
(
   28,
   'Parapharmacie',
   'Produits paramédicaux',
   'parapharmacie.jpg'
),
(
   29,
   'Fleurs',
   'Livraison de fleurs',
   'fleurs.jpg'
),
(
   30,
   'Japonaise',
   'Cuisine japonaise',
   'japonaise.jpg'
),
(
   31,
   'Vietnamienne',
   'Cuisine vietnamienne',
   'vietnamienne.jpg'
),
(
   32,
   'Fruits de mer',
   'Restaurants de fruits de mer',
   'fruitsmer.jpg'
),
(
   33,
   'Soupes',
   'Restaurants de soupes',
   'soupes.jpg'
),
(
   34,
   'Américaine',
   'Cuisine américaine',
   'americaine.jpg'
),
(
   35,
   'Café',
   'Cafés et boissons chaudes',
   'cafe.jpg'
),
(
   36,
   'Hygiène',
   'Produits d hygiène',
   'hygiene.jpg'
),
(
   37,
   'Afro américaine',
   'Cuisine afro-américaine',
   'afroamericaine.jpg'
),
(
   38,
   'Boutique',
   'Produits de boutique',
   'boutique.jpg'
),
(
   39,
   'Glaces',
   'Crèmes glacées et desserts glacés',
   'glaces.jpg'
),
(
   40,
   'Grecque',
   'Cuisine grecque',
   'grecque.jpg'
),
(
   41,
   'Street food',
   'Cuisine de rue',
   'streetfood.jpg'
),
(
   42,
   'Repas détente',
   'Restaurants décontractés',
   'repasdetente.jpg'
),
(
   43,
   'Smoothies',
   'Boissons smoothies',
   'smoothies.jpg'
),
(
   44,
   'Articles pour animaux',
   'Produits et accessoires pour animaux',
   'animaux.jpg'
),
(
   45,
   'Hawaïenne',
   'Cuisine hawaïenne',
   'hawaienne.jpg'
),
(
   46,
   'Taïwanaise',
   'Cuisine taïwanaise',
   'taiwanaise.jpg'
);

INSERT INTO TYPE_PRESTATION (
   IDPRESTATION,
   LIBELLEPRESTATION,
   DESCRIPTIONPRESTATION,
   IMAGEPRESTATION
) VALUES (
   1,
   'UberX',
   'Un voyage standard',
   'UberX.jpg'
),
(
   2,
   'UberXL',
   'Un voyage standard mais plus grand',
   'UberXL.jpg'
),
(
   3,
   'UberVan',
   'Chauffeurs professionnels proposant des vans',
   'UberVan.jpg'
),
(
   4,
   'Comfort',
   'Pour un voyage dans des véhicules plus récents',
   'Comfort.jpg'
),
(
   5,
   'Green',
   'Pour un voyage respectueux de l’environnement',
   'Green.jpg'
),
(
   6,
   'UberPet',
   'Pour un voyage qui accepte les animaux',
   'UberPet.jpg'
),
(
   7,
   'Berline',
   'Pour flex',
   'Berline.jpg'
);

INSERT INTO VEHICULE (
   IDVEHICULE,
   IDCOURSIER,
   IMMATRICULATION,
   MARQUE,
   MODELE,
   CAPACITE,
   ACCEPTEANIMAUX,
   ESTELECTRIQUE,
   ESTCONFORTABLE,
   ESTRECENT,
   ESTLUXUEUX,
   COULEUR
) VALUES (
   1,
   1,
   'AB-123-CD',
   'Tesla',
   'Model 3',
   4,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   'Noir'
),
(
   2,
   2,
   'XY-456-ZT',
   'Renault',
   'Zoe',
   4,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   FALSE,
   'Blanc'
),
(
   3,
   3,
   'LM-789-OP',
   'BMW',
   'Serie 3',
   5,
   FALSE,
   FALSE,
   TRUE,
   TRUE,
   TRUE,
   'Gris'
),
(
   4,
   4,
   'GH-123-IJ',
   'Mercedes',
   'Classe A',
   4,
   FALSE,
   FALSE,
   TRUE,
   TRUE,
   TRUE,
   'Bleu'
),
(
   5,
   5,
   'KL-456-MN',
   'Audi',
   'A4',
   5,
   FALSE,
   FALSE,
   TRUE,
   TRUE,
   TRUE,
   'Rouge'
),
(
   6,
   6,
   'QR-789-UV',
   'Volkswagen',
   'Golf',
   5,
   TRUE,
   FALSE,
   TRUE,
   TRUE,
   FALSE,
   'Vert'
),
(
   7,
   7,
   'ST-012-WX',
   'Peugeot',
   '208',
   4,
   TRUE,
   FALSE,
   TRUE,
   FALSE,
   FALSE,
   'Jaune'
),
(
   8,
   8,
   'AB-234-CD',
   'Ford',
   'Focus',
   5,
   FALSE,
   FALSE,
   TRUE,
   TRUE,
   FALSE,
   'Noir'
),
(
   9,
   9,
   'CD-567-EF',
   'Toyota',
   'Corolla',
   5,
   FALSE,
   FALSE,
   TRUE,
   FALSE,
   FALSE,
   'Blanc'
),
(
   10,
   10,
   'EF-890-GH',
   'Nissan',
   'Leaf',
   5,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   FALSE,
   'Bleu'
),
(
   11,
   1,
   'JK-234-LM',
   'Tesla',
   'Model S',
   5,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   'Argent'
),
(
   12,
   2,
   'LM-345-NP',
   'Renault',
   'Captur',
   5,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   FALSE,
   'Rouge'
),
(
   13,
   3,
   'OP-456-QW',
   'BMW',
   'X5',
   7,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   'Noir'
),
(
   14,
   4,
   'RT-567-YU',
   'Mercedes',
   'GLE',
   5,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   'Blanc'
),
(
   15,
   5,
   'MN-678-UV',
   'Audi',
   'Q7',
   7,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   'Gris'
),
(
   16,
   6,
   'UV-789-XY',
   'Volkswagen',
   'Passat',
   5,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   FALSE,
   'Bleu'
),
(
   17,
   7,
   'WX-890-YZ',
   'Peugeot',
   '3008',
   5,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   'Vert'
),
(
   18,
   8,
   'YZ-901-ZX',
   'Ford',
   'Fiesta',
   4,
   TRUE,
   FALSE,
   TRUE,
   TRUE,
   FALSE,
   'Jaune'
),
(
   19,
   9,
   'ZA-012-BV',
   'Toyota',
   'Yaris',
   5,
   TRUE,
   TRUE,
   TRUE,
   FALSE,
   FALSE,
   'Blanc'
),
(
   20,
   10,
   'BC-123-FG',
   'Nissan',
   'Micra',
   4,
   TRUE,
   FALSE,
   TRUE,
   FALSE,
   TRUE,
   'Rouge'
),
(
   21,
   1,
   'CD-234-WV',
   'Tesla',
   'Model X',
   7,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   'Noir'
),
(
   22,
   2,
   'EF-345-BV',
   'Renault',
   'Twingo',
   4,
   FALSE,
   TRUE,
   TRUE,
   FALSE,
   FALSE,
   'Rose'
),
(
   23,
   3,
   'GH-456-JN',
   'BMW',
   'X3',
   5,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   'Blanc'
),
(
   24,
   4,
   'HI-567-KP',
   'Mercedes',
   'C-Class',
   5,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   'Bleu'
),
(
   25,
   5,
   'IJ-678-QW',
   'Audi',
   'A3',
   5,
   TRUE,
   TRUE,
   TRUE,
   FALSE,
   TRUE,
   'Noir'
),
(
   26,
   6,
   'KL-789-XC',
   'Volkswagen',
   'Arteon',
   5,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   'Gris'
),
(
   27,
   7,
   'LM-890-BV',
   'Peugeot',
   '508',
   5,
   TRUE,
   TRUE,
   TRUE,
   FALSE,
   FALSE,
   'Blanc'
),
(
   28,
   8,
   'MN-012-CX',
   'Ford',
   'Kuga',
   5,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   FALSE,
   'Rouge'
),
(
   29,
   9,
   'OP-123-FV',
   'Toyota',
   'Auris',
   5,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   FALSE,
   'Bleu'
),
(
   30,
   10,
   'PQ-234-YZ',
   'Nissan',
   'Juke',
   5,
   TRUE,
   FALSE,
   TRUE,
   TRUE,
   TRUE,
   'Orange'
),
(
   31,
   1,
   'QR-345-PX',
   'Tesla',
   'Roadster',
   2,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   'Blanc'
),
(
   32,
   2,
   'ST-456-BC',
   'Renault',
   'Espace',
   7,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   'Gris'
),
(
   33,
   3,
   'TU-567-NZ',
   'BMW',
   'M4',
   2,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   'Noir'
),
(
   34,
   4,
   'VW-678-OP',
   'Mercedes',
   'EQB',
   5,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   'Argent'
),
(
   35,
   5,
   'XY-789-PR',
   'Audi',
   'Q5',
   5,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   FALSE,
   'Bleu'
),
(
   36,
   6,
   'YZ-890-QW',
   'Volkswagen',
   'ID.4',
   5,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   'Vert'
),
(
   37,
   7,
   'ZA-123-KP',
   'Peugeot',
   'Rifter',
   5,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   FALSE,
   'Jaune'
),
(
   38,
   8,
   'BC-234-VY',
   'Ford',
   'Maverick',
   5,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   'Rouge'
),
(
   39,
   9,
   'DE-345-BV',
   'Toyota',
   'Highlander',
   7,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   FALSE,
   'Blanc'
),
(
   40,
   10,
   'EF-456-CV',
   'Nissan',
   'Rogue',
   5,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   TRUE,
   'Noir'
);

INSERT INTO VELO (
   IDVELO,
   IDADRESSE,
   NUMEROVELO,
   ESTDISPONIBLE
) VALUES (
   1,
   1,
   '12345',
   TRUE
),
(
   2,
   4,
   '12346',
   FALSE
),
(
   3,
   18,
   '12347',
   TRUE
),
(
   4,
   84,
   '12348',
   TRUE
),
(
   5,
   46,
   '12349',
   FALSE
),
(
   6,
   18,
   '12350',
   TRUE
),
(
   7,
   69,
   '12351',
   TRUE
),
(
   8,
   33,
   '12352',
   FALSE
),
(
   9,
   71,
   '12353',
   TRUE
),
(
   10,
   99,
   '12354',
   TRUE
);

INSERT INTO VILLE (
   IDVILLE,
   IDPAYS,
   IDCODEPOSTAL,
   NOMVILLE
) VALUES (
   1,
   1,
   1,
   'Paris'
),
(
   2,
   1,
   2,
   'Lyon'
),
(
   3,
   1,
   3,
   'Marseille'
),
(
   4,
   1,
   4,
   'Bordeaux'
),
(
   5,
   1,
   5,
   'Nice'
),
(
   6,
   1,
   6,
   'Nantes'
),
(
   7,
   1,
   7,
   'Montpellier'
),
(
   8,
   1,
   8,
   'Strasbourg'
),
(
   9,
   1,
   9,
   'Dijon'
),
(
   10,
   1,
   10,
   'Versailles'
),
(
   11,
   1,
   11,
   'Annecy'
);

---------------------------------------------------------------
ALTER TABLE ADRESSE
   ADD CONSTRAINT FK_ADRESSE_EST_DANS_VILLE FOREIGN KEY (
      IDVILLE
   )
      REFERENCES VILLE (
         IDVILLE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE APPARTIENT_2
   ADD CONSTRAINT FK_APPARTIENT2_CARTE_BANCAIRE FOREIGN KEY (
      IDCB
   )
      REFERENCES CARTE_BANCAIRE (
         IDCB
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE APPARTIENT_2
   ADD CONSTRAINT FK_APPARTIENT2_CLIENT FOREIGN KEY (
      IDCLIENT
   )
      REFERENCES CLIENT (
         IDCLIENT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE A_3
   ADD CONSTRAINT FK_A_3_PRODUIT FOREIGN KEY (
      IDPRODUIT
   )
      REFERENCES PRODUIT (
         IDPRODUIT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE A_3
   ADD CONSTRAINT FK_A_3_CATEGORIE_PRODUIT FOREIGN KEY (
      IDCATEGORIE
   )
      REFERENCES CATEGORIE_PRODUIT (
         IDCATEGORIE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE A_COMME_TYPE
   ADD CONSTRAINT FK_A_COMME_TYPE_VEHICULE FOREIGN KEY (
      IDVEHICULE
   )
      REFERENCES VEHICULE (
         IDVEHICULE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE A_COMME_TYPE
   ADD CONSTRAINT FK_A_COMME_TYPE_PRESTATION FOREIGN KEY (
      IDPRESTATION
   )
      REFERENCES TYPE_PRESTATION (
         IDPRESTATION
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE CLIENT
   ADD CONSTRAINT FK_CLIENT_PLANNING FOREIGN KEY (
      IDPLANNING
   )
      REFERENCES PLANNING_RESERVATION (
         IDPLANNING
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE CLIENT
   ADD CONSTRAINT FK_CLIENT_PANIER FOREIGN KEY (
      IDPANIER
   )
      REFERENCES PANIER (
         IDPANIER
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE CLIENT
   ADD CONSTRAINT FK_CLIENT_ENTREPRISE FOREIGN KEY (
      IDENTREPRISE
   )
      REFERENCES ENTREPRISE (
         IDENTREPRISE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE CLIENT
   ADD CONSTRAINT FK_CLIENT_ADRESSE FOREIGN KEY (
      IDADRESSE
   )
      REFERENCES ADRESSE (
         IDADRESSE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE CODE_POSTAL
   ADD CONSTRAINT FK_CODE_POSTAL_PAYS FOREIGN KEY (
      IDPAYS
   )
      REFERENCES PAYS (
         IDPAYS
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COMMANDE
   ADD CONSTRAINT FK_COMMANDE_COURSIER FOREIGN KEY (
      IDCOURSIER
   )
      REFERENCES COURSIER (
         IDCOURSIER
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COMMANDE
   ADD CONSTRAINT FK_COMMANDE_PANIER FOREIGN KEY (
      IDPANIER
   )
      REFERENCES PANIER (
         IDPANIER
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE CONTIENT_2
   ADD CONSTRAINT FK_CONTIENT2_PANIER FOREIGN KEY (
      IDPANIER
   )
      REFERENCES PANIER (
         IDPANIER
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE CONTIENT_2
   ADD CONSTRAINT FK_CONTIENT2_PRODUIT FOREIGN KEY (
      IDPRODUIT
   )
      REFERENCES PRODUIT (
         IDPRODUIT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COURSE
   ADD CONSTRAINT FK_COURSE_PRESTATION FOREIGN KEY (
      IDPRESTATION
   )
      REFERENCES TYPE_PRESTATION (
         IDPRESTATION
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COURSE
   ADD CONSTRAINT FK_COURSE_ADRESSE_START FOREIGN KEY (
      ADR_IDADRESSE
   )
      REFERENCES ADRESSE (
         IDADRESSE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COURSE
   ADD CONSTRAINT FK_COURSE_RESERVATION FOREIGN KEY (
      IDRESERVATION
   )
      REFERENCES RESERVATION (
         IDRESERVATION
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COURSE
   ADD CONSTRAINT FK_COURSE_ADRESSE_END FOREIGN KEY (
      IDADRESSE
   )
      REFERENCES ADRESSE (
         IDADRESSE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COURSE
   ADD CONSTRAINT FK_COURSE_CARTE_BANCAIRE FOREIGN KEY (
      IDCB
   )
      REFERENCES CARTE_BANCAIRE (
         IDCB
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COURSE
   ADD CONSTRAINT FK_COURSE_PAR_COURSIER FOREIGN KEY (
      IDCOURSIER
   )
      REFERENCES COURSIER (
         IDCOURSIER
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COURSIER
   ADD CONSTRAINT FK_COURSIER_ENTREPRISE FOREIGN KEY (
      IDENTREPRISE
   )
      REFERENCES ENTREPRISE (
         IDENTREPRISE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COURSIER
   ADD CONSTRAINT FK_COURSIER_ADRESSE FOREIGN KEY (
      IDADRESSE
   )
      REFERENCES ADRESSE (
         IDADRESSE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE DEPARTEMENT
   ADD CONSTRAINT FK_DEPARTEMENT_PAYS FOREIGN KEY (
      IDPAYS
   )
      REFERENCES PAYS (
         IDPAYS
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE ENTREPRISE
   ADD CONSTRAINT FK_ENTREPRISE_CLIENT FOREIGN KEY (
      IDCLIENT
   )
      REFERENCES CLIENT (
         IDCLIENT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE ENTREPRISE
   ADD CONSTRAINT FK_ENTREPRISE_ADRESSE FOREIGN KEY (
      IDADRESSE
   )
      REFERENCES ADRESSE (
         IDADRESSE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE EST_SITUE_A_2
   ADD CONSTRAINT FK_EST_SITUE2_PRODUIT FOREIGN KEY (
      IDPRODUIT
   )
      REFERENCES PRODUIT (
         IDPRODUIT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE EST_SITUE_A_2
   ADD CONSTRAINT FK_EST_SITUE2_ETABLISSEMENT FOREIGN KEY (
      IDETABLISSEMENT
   )
      REFERENCES ETABLISSEMENT (
         IDETABLISSEMENT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE A_COMME_CATEGORIE
   ADD CONSTRAINT FK_A_COMME_CATEGORIE_PRESTATIONC FOREIGN KEY (
      IDCATEGORIEPRESTATION
   )
      REFERENCES CATEGORIE_PRESTATION (
         IDCATEGORIEPRESTATION
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE A_COMME_CATEGORIE
   ADD CONSTRAINT FK_COMME_CATEGORIE_ETABLISSEMENT FOREIGN KEY (
      IDETABLISSEMENT
   )
      REFERENCES ETABLISSEMENT (
         IDETABLISSEMENT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE ETABLISSEMENT
   ADD CONSTRAINT FK_ETABLISSEMENT_ADRESSE FOREIGN KEY (
      IDADRESSE
   )
      REFERENCES ADRESSE (
         IDADRESSE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE FACTURE_COURSE
   ADD CONSTRAINT FK_FACTURE_COURSE FOREIGN KEY (
      IDCOURSE
   )
      REFERENCES COURSE (
         IDCOURSE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE FACTURE_COURSE
   ADD CONSTRAINT FK_FACTURE_CLIENT FOREIGN KEY (
      IDCLIENT
   )
      REFERENCES CLIENT (
         IDCLIENT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE FACTURE_COURSE
   ADD CONSTRAINT FK_FACTURE_PAYS FOREIGN KEY (
      IDPAYS
   )
      REFERENCES PAYS (
         IDPAYS
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE PANIER
   ADD CONSTRAINT FK_PANIER_CLIENT FOREIGN KEY (
      IDCLIENT
   )
      REFERENCES CLIENT (
         IDCLIENT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE PLANNING_RESERVATION
   ADD CONSTRAINT FK_PLANNING_CLIENT FOREIGN KEY (
      IDCLIENT
   )
      REFERENCES CLIENT (
         IDCLIENT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE REGLEMENT_SALAIRE
   ADD CONSTRAINT FK_REGLEMENT_COURSIER FOREIGN KEY (
      IDCOURSIER
   )
      REFERENCES COURSIER (
         IDCOURSIER
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE RESERVATION
   ADD CONSTRAINT FK_RESERVATION_PLANNING FOREIGN KEY (
      IDPLANNING
   )
      REFERENCES PLANNING_RESERVATION (
         IDPLANNING
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE RESERVATION
   ADD CONSTRAINT FK_RESERVATION_VELO FOREIGN KEY (
      IDVELO
   )
      REFERENCES VELO (
         IDVELO
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE RESERVATION
   ADD CONSTRAINT FK_RESERVATION_CLIENT FOREIGN KEY (
      IDCLIENT
   )
      REFERENCES CLIENT (
         IDCLIENT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE VEHICULE
   ADD CONSTRAINT FK_VEHICULE_COURSIER FOREIGN KEY (
      IDCOURSIER
   )
      REFERENCES COURSIER (
         IDCOURSIER
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE VELO
   ADD CONSTRAINT FK_VELO_ADRESSE FOREIGN KEY (
      IDADRESSE
   )
      REFERENCES ADRESSE (
         IDADRESSE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE VILLE
   ADD CONSTRAINT FK_VILLE_CODE_POSTAL FOREIGN KEY (
      IDCODEPOSTAL
   )
      REFERENCES CODE_POSTAL (
         IDCODEPOSTAL
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE VILLE
   ADD CONSTRAINT FK_VILLE_PAYS FOREIGN KEY (
      IDPAYS
   )
      REFERENCES PAYS (
         IDPAYS
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;