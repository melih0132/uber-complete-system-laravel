/*==============================================================*/
/* Nom de SGBD :  PostgreSQL 8                                  */
/* Date de création :  28/11/2024 15:20:22                      */
/*==============================================================*/
DROP TABLE IF EXISTS ADRESSE CASCADE;

DROP TABLE IF EXISTS APPARTIENT_2 CASCADE;

DROP TABLE IF EXISTS A_3 CASCADE;

DROP TABLE IF EXISTS A_COMME_TYPE CASCADE;

DROP TABLE IF EXISTS CARTE_BANCAIRE CASCADE;

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
   CONSTRAINT CK_CARTE_DATEEXPIRECB CHECK (DATEEXPIRECB > CURRENT_DATE),
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
   NOMCATEGORIE VARCHAR(50) NULL,
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
   IDRESERVATION INT4 NULL,
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
   IDADRESSE INT4 NOT NULL,
   NOMETABLISSEMENT VARCHAR(50) NULL,
   IMAGEETABLISSEMENT VARCHAR(200) NULL,
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
   NOMPRODUIT VARCHAR(50) NULL,
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
   IDCOURSE INT4 NULL,
   IDCOURSIER INT4 NULL,
   IDADRESSE INT4 NOT NULL,
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
   IDRESERVATION INT4 NOT NULL,
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
   '10 Rue de Paris'
),
(
   2,
   2,
   '20 Boulevard Haussmann'
),
(
   3,
   3,
   '15 Avenue de Lyon'
),
(
   4,
   4,
   '5 Place de la République'
),
(
   5,
   5,
   '12 Rue des Lilas'
),
(
   6,
   6,
   '3 Impasse des Églantines'
),
(
   7,
   7,
   '7 Place de la Liberté'
),
(
   8,
   8,
   '19 Avenue des Champs'
),
(
   9,
   9,
   '2 Rue Victor Hugo'
),
(
   10,
   10,
   '8 Boulevard Saint-Michel'
),
(
   11,
   1,
   '11 Place du Capitole'
),
(
   12,
   2,
   '25 Avenue Jean Jaurès'
),
(
   13,
   3,
   '6 Rue des Acacias'
),
(
   14,
   4,
   '18 Rue des Fleurs'
),
(
   15,
   5,
   '22 Boulevard Pasteur'
),
(
   16,
   6,
   '30 Avenue des Pyrénées'
),
(
   17,
   7,
   '45 Rue de la Gare'
),
(
   18,
   8,
   '50 Place du Marché'
),
(
   19,
   9,
   '9 Impasse des Peupliers'
),
(
   20,
   10,
   '14 Avenue des Pins'
),
(
   21,
   1,
   '3 Rue de la Paix'
),
(
   22,
   2,
   '7 Avenue des Ternes'
),
(
   23,
   3,
   '8 Rue des Roses'
),
(
   24,
   4,
   '10 Boulevard Montparnasse'
),
(
   25,
   5,
   '6 Rue de la République'
),
(
   26,
   6,
   '20 Rue des Écoles'
),
(
   27,
   7,
   '25 Boulevard de la Gare'
),
(
   28,
   8,
   '3 Place des Vosges'
),
(
   29,
   9,
   '12 Rue de la Liberté'
),
(
   30,
   10,
   '21 Boulevard de la Mer'
),
(
   31,
   1,
   '14 Rue Montmartre'
),
(
   32,
   2,
   '16 Rue de la Madeleine'
),
(
   33,
   3,
   '2 Rue de la Concorde'
),
(
   34,
   4,
   '4 Place de la Bastille'
),
(
   35,
   5,
   '23 Rue des Champs-Élysées'
),
(
   36,
   6,
   '5 Rue des Carrières'
),
(
   37,
   7,
   '19 Avenue du Général Leclerc'
),
(
   38,
   8,
   '13 Rue Saint-Denis'
),
(
   39,
   9,
   '7 Rue de la Montagne'
),
(
   40,
   10,
   '15 Avenue de la Mer'
),
(
   41,
   1,
   '8 Rue de la Sainte-Croix'
),
(
   42,
   2,
   '18 Rue de l’Opéra'
),
(
   43,
   3,
   '5 Place de la Bourse'
),
(
   44,
   4,
   '22 Rue de la Paix'
),
(
   45,
   5,
   '24 Boulevard de Sébastopol'
),
(
   46,
   6,
   '10 Place des Invalides'
),
(
   47,
   7,
   '27 Rue des Arts'
),
(
   48,
   8,
   '28 Boulevard des Capucines'
),
(
   49,
   9,
   '6 Rue du Faubourg Saint-Antoine'
),
(
   50,
   10,
   '17 Avenue des Alpes'
),
(
   51,
   1,
   '9 Rue de la Place des Vosges'
),
(
   52,
   2,
   '3 Rue de la Cité'
),
(
   53,
   3,
   '16 Rue du Faubourg Saint-Honoré'
),
(
   54,
   4,
   '7 Avenue des Gobelins'
),
(
   55,
   5,
   '11 Rue de la Paix'
),
(
   56,
   6,
   '3 Rue de la Chapelle'
),
(
   57,
   7,
   '10 Rue de l’Indépendance'
),
(
   58,
   8,
   '12 Rue des Moulins'
),
(
   59,
   9,
   '18 Rue du Pont Neuf'
),
(
   60,
   10,
   '11 Rue des Grandes Alpes'
),
(
   61,
   1,
   '2 Place de la République'
),
(
   62,
   2,
   '12 Avenue Montaigne'
),
(
   63,
   3,
   '25 Rue du Faubourg Saint-Antoine'
),
(
   64,
   4,
   '16 Place de la Nation'
),
(
   65,
   5,
   '4 Rue du Vieux-Colombier'
),
(
   66,
   6,
   '17 Rue des Boulevards'
),
(
   67,
   7,
   '14 Rue de l’Arcade'
),
(
   68,
   8,
   '9 Rue des Bouquinistes'
),
(
   69,
   9,
   '10 Rue de Montmartre'
),
(
   70,
   10,
   '23 Rue de Saint-Germain'
),
(
   71,
   1,
   '1 Rue de Rivoli'
),
(
   72,
   2,
   '20 Rue de la Sarrazine'
),
(
   73,
   3,
   '8 Rue de la République'
),
(
   74,
   4,
   '15 Avenue du Parc des Princes'
),
(
   75,
   5,
   '22 Rue de la Villette'
),
(
   76,
   6,
   '13 Rue du Trône'
),
(
   77,
   7,
   '6 Avenue de la Plage'
),
(
   78,
   8,
   '4 Rue de l’Église'
),
(
   79,
   9,
   '7 Place de la Liberté'
),
(
   80,
   10,
   '8 Rue du Palais des Congrès'
),
(
   81,
   1,
   '17 Boulevard Saint-Germain'
),
(
   82,
   2,
   '4 Rue des Francs-Bourgeois'
),
(
   83,
   3,
   '19 Rue de la Ville'
),
(
   84,
   4,
   '6 Boulevard de l’Étoile'
),
(
   85,
   5,
   '5 Rue des Mimosas'
),
(
   86,
   6,
   '1 Rue de la Gare'
),
(
   87,
   7,
   '23 Avenue des Tuileries'
),
(
   88,
   8,
   '11 Rue de la Pomme'
),
(
   89,
   9,
   '14 Rue de Saint-Roch'
),
(
   90,
   10,
   '7 Avenue des Neiges'
),
(
   91,
   1,
   '3 Rue des Lilas'
),
(
   92,
   2,
   '12 Rue de la République'
),
(
   93,
   3,
   '22 Rue des Acacias'
),
(
   94,
   4,
   '18 Boulevard de la Liberté'
),
(
   95,
   5,
   '10 Place de la Concorde'
),
(
   96,
   6,
   '13 Rue des Oliviers'
),
(
   97,
   7,
   '14 Rue du Rocher'
),
(
   98,
   8,
   '20 Rue des Pères'
),
(
   99,
   9,
   '16 Rue du Pont'
),
(
   100,
   10,
   '9 Rue des Pêcheurs'
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
   1
),
(
   12,
   2
),
(
   13,
   3
),
(
   14,
   4
),
(
   15,
   5
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
   8
),
(
   19,
   9
),
(
   20,
   10
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
   'Fruits et Légumes'
),
(
   2,
   'Viandes et Poissons'
),
(
   3,
   'Produits laitiers'
),
(
   4,
   'Pâtisseries et Desserts'
),
(
   5,
   'Boissons'
),
(
   6,
   'Épicerie salée'
),
(
   7,
   'Épicerie sucrée'
),
(
   8,
   'Congelés'
),
(
   9,
   'Céréales et Pâtes'
),
(
   10,
   'Snacks et Apéritifs'
),
(
   11,
   'Sauces et Condiments'
),
(
   12,
   'Produits bio'
),
(
   13,
   'Boulangerie'
),
(
   14,
   'Repas préparés'
),
(
   15,
   'Produits végétariens'
),
(
   16,
   'Fromages'
),
(
   17,
   'Charcuterie'
),
(
   18,
   'Plats cuisinés'
),
(
   19,
   'Boissons alcoolisées'
),
(
   20,
   'Aliments pour bébés'
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
   '75001'
),
(
   2,
   1,
   '75002'
),
(
   3,
   1,
   '75003'
),
(
   4,
   1,
   '75004'
),
(
   5,
   1,
   '75005'
),
(
   6,
   1,
   '75006'
),
(
   7,
   1,
   '75007'
),
(
   8,
   1,
   '75008'
),
(
   9,
   1,
   '75009'
),
(
   10,
   1,
   '75010'
),
(
   11,
   2,
   '33000'
),
(
   12,
   2,
   '33100'
),
(
   13,
   2,
   '33200'
),
(
   14,
   2,
   '33300'
),
(
   15,
   2,
   '33400'
),
(
   16,
   3,
   '69001'
),
(
   17,
   3,
   '69002'
),
(
   18,
   3,
   '69003'
),
(
   19,
   3,
   '69004'
),
(
   20,
   3,
   '69005'
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
   7,
   32,
   2,
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
   6,
   35,
   4,
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
   4,
   37,
   6,
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
   7,
   70,
   8,
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
   8,
   78,
   10,
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
   5,
   18,
   12,
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
   3,
   16,
   14,
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
   1,
   89,
   16,
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
   4,
   92,
   18,
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
   5,
   82,
   20,
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
   1,
   37,
   22,
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
   6,
   24,
   24,
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
   1,
   27,
   26,
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
   6,
   26,
   28,
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
   7,
   18,
   30,
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
   10,
   46,
   32,
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
   3,
   25,
   34,
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
   10,
   96,
   36,
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
   1,
   88,
   38,
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
   6,
   30,
   40,
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
   5,
   93,
   42,
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
   5,
   67,
   44,
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
   8,
   5,
   46,
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
   1,
   7,
   48,
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
   10,
   38,
   50,
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
   3,
   18,
   52,
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
   6,
   97,
   54,
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
   6,
   62,
   56,
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
   4,
   23,
   58,
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
   2,
   94,
   60,
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
   2,
   83,
   62,
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
   1,
   55,
   64,
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
   5,
   8,
   66,
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
   3,
   25,
   68,
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
   10,
   31,
   70,
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
   10,
   79,
   72,
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
   4,
   100,
   74,
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
   9,
   49,
   76,
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
   6,
   93,
   78,
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
   9,
   24,
   80,
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
   4,
   86,
   82,
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
   5,
   54,
   84,
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
   6,
   72,
   86,
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
   3,
   37,
   88,
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
   5,
   25,
   90,
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
   2,
   10,
   92,
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
   2,
   82,
   94,
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
   6,
   44,
   96,
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
   5,
   63,
   98,
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
   IDRESERVATION,
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
   21,
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
   22,
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
   23,
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
   24,
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
   25,
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
   26,
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
   27,
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
   28,
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
   29,
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
   30,
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
   IDADRESSE,
   NOMETABLISSEMENT,
   IMAGEETABLISSEMENT
) VALUES (
   1,
   1,
   'Le Gourmet Parisien',
   'image1.jpg'
),
(
   2,
   2,
   'Le Bistrot Lyonnais',
   'image2.jpg'
),
(
   3,
   3,
   'Chez Mamma',
   'image3.jpg'
),
(
   4,
   4,
   'Le Petit Savoyard',
   'image4.jpg'
),
(
   5,
   5,
   'L’Épicurienne',
   'image5.jpg'
),
(
   6,
   6,
   'La Table du Chef',
   'image6.jpg'
),
(
   8,
   7,
   'La Brasserie de la Gare',
   'image8.jpg'
),
(
   7,
   8,
   'Le Bistro du Marché',
   'image7.jpg'
),
(
   9,
   9,
   'Géant',
   'image9.jpg'
),
(
   10,
   10,
   'Le Palais des Pâtes',
   'image10.jpg'
),
(
   11,
   11,
   'Le Comptoir du Vin',
   'image11.jpg'
),
(
   12,
   12,
   'Le Jardin Gourmand',
   'image12.jpg'
),
(
   13,
   13,
   'L’Oasis de Saveurs',
   'image13.jpg'
),
(
   14,
   14,
   'Les Folies Gourmandes',
   'image14.jpg'
),
(
   15,
   15,
   'Chez Jean-Claude',
   'image15.jpg'
),
(
   16,
   16,
   'La Cuisine de Mamie',
   'image16.jpg'
),
(
   17,
   17,
   'Auchan',
   'image17.jpg'
),
(
   18,
   18,
   'La Grange à Manger',
   'image18.jpg'
),
(
   19,
   19,
   'Le Marché de Provence',
   'image19.jpg'
),
(
   20,
   20,
   'Les Délices de la Mer',
   'image20.jpg'
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
   'Pizza Margherita',
   12.50,
   'pizza_margherita.jpg',
   'Pizza classique avec tomate, mozzarella et basilic frais'
),
(
   2,
   'Pasta Carbonara',
   15.00,
   'pasta_carbonara.jpg',
   'Pâtes avec une sauce crémeuse au lard et parmesan'
),
(
   3,
   'Burger Cheeseburger',
   9.90,
   'cheeseburger.jpg',
   'Burger avec du fromage cheddar fondu, laitue et tomate'
),
(
   4,
   'Salade César',
   10.00,
   'salade_cesar.jpg',
   'Salade verte avec du poulet grillé, croutons et sauce César'
),
(
   5,
   'Spaghetti Bolognese',
   14.50,
   'spaghetti_bolognese.jpg',
   'Spaghetti accompagnés d une sauce à la viande épicée'
),
(
   6,
   'Tiramisu',
   6.00,
   'tiramisu.jpg',
   'Dessert italien à base de café, mascarpone et cacao'
),
(
   7,
   'Gâteau au chocolat',
   5.50,
   'gateau_chocolat.jpg',
   'Gâteau fondant au chocolat avec un cœur coulant'
),
(
   8,
   'Café Latte',
   3.50,
   'cafe_latte.jpg',
   'Café latte avec du lait mousseux et une touche de sucre'
),
(
   9,
   'Sushi Roll',
   12.00,
   'sushi_roll.jpg',
   'Assortiment de rouleaux de sushi avec poisson frais et légumes'
),
(
   10,
   'Poulet rôti',
   18.00,
   'poulet_roti.jpg',
   'Poulet rôti avec des herbes et légumes de saison'
),
(
   11,
   'Tacos',
   8.00,
   'tacos.jpg',
   'Tacos avec viande, légumes et sauce épicée'
),
(
   12,
   'Ravioli aux champignons',
   13.00,
   'ravioli_champignons.jpg',
   'Ravioli farcis aux champignons et sauce crémeuse'
),
(
   13,
   'Crepes Nutella',
   7.50,
   'crepes_nutella.jpg',
   'Crêpes garnies de Nutella et de bananes fraîches'
),
(
   14,
   'Panna Cotta',
   6.80,
   'panna_cotta.jpg',
   'Crème dessert à la vanille servie avec un coulis de fruits rouges'
),
(
   15,
   'Salmon Tartare',
   16.00,
   'salmon_tartare.jpg',
   'Tartare de saumon frais, avocat et citron'
),
(
   16,
   'Croissant',
   2.50,
   'croissant.jpg',
   'Pâtisserie légère et beurrée, parfaite pour le petit-déjeuner'
),
(
   17,
   'Baguette',
   1.20,
   'baguette.jpg',
   'Baguette française croustillante, idéale pour accompagner vos repas'
),
(
   18,
   'Falafel',
   7.00,
   'falafel.jpg',
   'Boules de pois chiches épicées, servies avec du pain pita'
),
(
   19,
   'Paella',
   18.50,
   'paella.jpg',
   'Plat espagnol à base de riz, fruits de mer et légumes'
),
(
   20,
   'Gourmet Burger',
   13.50,
   'gourmet_burger.jpg',
   'Burger avec du fromage bleu, oignons caramélisés et sauce maison'
),
(
   21,
   'Pizza Pepperoni',
   13.50,
   'pizza_pepperoni.jpg',
   'Pizza avec du pepperoni, tomate et mozzarella'
),
(
   22,
   'Lasagne Bolognese',
   16.00,
   'lasagne_bolognese.jpg',
   'Lasagne avec sauce bolognese, viande et béchamel'
),
(
   23,
   'Chocolat chaud',
   4.00,
   'chocolat_chaud.jpg',
   'Boisson chaude à base de chocolat fondu et lait crémeux'
),
(
   24,
   'Gaspacho',
   7.20,
   'gaspacho.jpg',
   'Soupe froide à base de tomates, poivrons et concombres'
),
(
   25,
   'Mousse au chocolat',
   5.80,
   'mousse_chocolat.jpg',
   'Dessert léger et aérien au chocolat noir'
),
(
   26,
   'Steak Frites',
   19.00,
   'steak_frites.jpg',
   'Steak grillé accompagné de frites croustillantes'
),
(
   27,
   'Risotto aux champignons',
   14.00,
   'risotto_champignons.jpg',
   'Risotto crémeux avec des champignons frais'
),
(
   28,
   'Macaron',
   2.00,
   'macaron.jpg',
   'Délicat biscuit meringué fourré de ganache parfumée'
),
(
   29,
   'Maki au saumon',
   12.00,
   'maki_saumon.jpg',
   'Rouleaux de maki garnis de saumon frais et légumes'
),
(
   30,
   'Soupe à l’oignon',
   6.50,
   'soupe_oignon.jpg',
   'Soupe savoureuse à base d’oignons caramélisés et gratinée de fromage'
),
(
   31,
   'Wok de légumes',
   10.50,
   'wok_legumes.jpg',
   'Mélange de légumes sautés au wok avec sauce soja'
),
(
   32,
   'Burger Végétarien',
   11.00,
   'burger_vegetarien.jpg',
   'Burger végétarien avec galette de légumes et sauce mayo maison'
),
(
   33,
   'Salade Niçoise',
   12.50,
   'salade_nicoise.jpg',
   'Salade composée de thon, œufs, tomates et olives'
),
(
   34,
   'Crêpes Suzette',
   8.00,
   'crepes_suzette.jpg',
   'Crêpes flambées avec une sauce à l’orange et au Grand Marnier'
),
(
   35,
   'Tarte Tatin',
   7.20,
   'tarte_tatin.jpg',
   'Tarte aux pommes caramélisées, servie chaude'
),
(
   36,
   'Poulet Korma',
   16.50,
   'poulet_korma.jpg',
   'Poulet dans une sauce au curry doux et noix de cajou'
),
(
   37,
   'Pizza Quatre Saisons',
   14.50,
   'pizza_quatre_saisons.jpg',
   'Pizza avec jambon, champignons, artichauts et olives'
),
(
   38,
   'Paella Végétarienne',
   17.00,
   'paella_vegetarienne.jpg',
   'Paella avec des légumes de saison et riz parfumé'
),
(
   39,
   'Tartare de thon',
   17.50,
   'tartare_thon.jpg',
   'Tartare de thon frais accompagné de légumes et d’avocat'
),
(
   40,
   'Churros',
   5.00,
   'churros.jpg',
   'Beignets sucrés frits, servis avec du chocolat chaud'
),
(
   41,
   'Poulet au curry',
   16.00,
   'poulet_curry.jpg',
   'Poulet cuit dans une sauce crémeuse au curry et lait de coco'
),
(
   42,
   'Riz Pilaf',
   5.50,
   'riz_pilaf.jpg',
   'Riz basmati parfumé cuit avec des épices et des légumes'
),
(
   43,
   'Pizza Hawaïenne',
   13.00,
   'pizza_hawaiienne.jpg',
   'Pizza avec jambon, ananas, tomate et mozzarella'
),
(
   44,
   'Gâteau au fromage',
   6.50,
   'gateau_fromage.jpg',
   'Gâteau crémeux au fromage avec une base biscuitée'
),
(
   45,
   'Moules marinières',
   18.00,
   'moules_mariniere.jpg',
   'Moules cuites dans un bouillon de vin blanc, ail et persil'
),
(
   46,
   'Curry de légumes',
   13.00,
   'curry_legumes.jpg',
   'Mélange de légumes épicés cuits dans une sauce au curry'
),
(
   47,
   'Ramen au porc',
   12.00,
   'ramen_porc.jpg',
   'Soupe de ramen avec du porc, œuf et légumes'
),
(
   48,
   'Pâté en croûte',
   8.50,
   'pate_en_croute.jpg',
   'Viande en pâte feuilletée servie avec une salade verte'
),
(
   49,
   'Boeuf Bourguignon',
   20.00,
   'boeuf_bourguignon.jpg',
   'Boeuf braisé dans une sauce au vin rouge avec des légumes'
),
(
   50,
   'Gâteau aux fruits',
   5.00,
   'gateau_fruits.jpg',
   'Gâteau moelleux aux fruits frais de saison'
),
(
   51,
   'Tartare de bœuf',
   16.50,
   'tartare_boeuf.jpg',
   'Tartare de bœuf frais, accompagné de frites et sauce à part'
),
(
   52,
   'Crevettes à l’ail',
   14.00,
   'crevettes_ail.jpg',
   'Crevettes sautées à l’ail et au persil, servies avec du pain grillé'
),
(
   53,
   'Frittata aux légumes',
   10.00,
   'frittata_legumes.jpg',
   'Omelette italienne aux légumes et herbes fraîches'
),
(
   54,
   'Sushi Maki',
   11.50,
   'sushi_maki.jpg',
   'Assortiment de makis avec poisson cru et légumes'
),
(
   55,
   'Quiche Lorraine',
   9.00,
   'quiche_lorraine.jpg',
   'Quiche avec lardons, crème fraîche et fromage'
),
(
   56,
   'Calzone',
   13.00,
   'calzone.jpg',
   'Pizza repliée avec mozzarella, tomate et jambon'
),
(
   57,
   'Gâteau de riz',
   4.00,
   'gateau_riz.jpg',
   'Dessert crémeux à base de riz au lait et cannelle'
),
(
   58,
   'Salade de fruits',
   5.00,
   'salade_fruits.jpg',
   'Mélange frais de fruits de saison'
),
(
   59,
   'Pizza au saumon',
   15.00,
   'pizza_saumon.jpg',
   'Pizza garnie de saumon fumé, crème fraîche et aneth'
),
(
   60,
   'Soupe de poisson',
   9.50,
   'soupe_poisson.jpg',
   'Soupe de poisson avec des légumes et du pain grillé'
),
(
   61,
   'Pizza végétarienne',
   13.50,
   'pizza_vegetarienne.jpg',
   'Pizza avec légumes grillés, tomate, mozzarella et basilic'
),
(
   62,
   'Côtelettes d’agneau',
   22.00,
   'cotelettes_agneau.jpg',
   'Côtelettes d’agneau grillées avec une sauce au romarin'
),
(
   63,
   'Gnocchis à la crème',
   14.00,
   'gnocchis_creme.jpg',
   'Gnocchis accompagnés d’une sauce crémeuse au parmesan'
),
(
   64,
   'Pâtisserie choux',
   3.50,
   'patisserie_choux.jpg',
   'Choux remplis de crème pâtissière et enrobés de chocolat'
),
(
   65,
   'Tartelette au citron',
   6.00,
   'tartelette_citron.jpg',
   'Tartelette avec une crème au citron acidulée et croûte sablée'
),
(
   66,
   'Pizza 4 fromages',
   14.00,
   'pizza_4fromages.jpg',
   'Pizza garnie de mozzarella, gorgonzola, chèvre et parmesan'
),
(
   67,
   'Salade de quinoa',
   10.00,
   'salade_quinoa.jpg',
   'Salade de quinoa avec légumes frais et vinaigrette au citron'
),
(
   68,
   'Ragoût de légumes',
   13.50,
   'ragout_legumes.jpg',
   'Ragoût végétarien avec des légumes mijotés et épicés'
),
(
   69,
   'Hamburger BBQ',
   13.50,
   'hamburger_barbeque.jpg',
   'Burger avec sauce barbecue, bacon, et oignons grillés'
),
(
   70,
   'Moules frites',
   17.50,
   'moules_frites.jpg',
   'Moules cuites dans une sauce au vin blanc, servies avec frites'
),
(
   71,
   'Fried chicken',
   12.00,
   'fried_chicken.jpg',
   'Poulet frit croustillant, servi avec une sauce épicée'
),
(
   72,
   'Pizza Poulet Champignon',
   14.50,
   'pizza_poulet_champignon.jpg',
   'Pizza avec poulet rôti, champignons et mozzarella'
),
(
   73,
   'Lassi à la mangue',
   4.00,
   'lassi_mangue.jpg',
   'Boisson à base de yaourt et mangue'
),
(
   74,
   'Tacos al Pastor',
   9.00,
   'tacos_al_pastor.jpg',
   'Tacos avec viande de porc marinée, ananas et oignons'
),
(
   75,
   'Tarte aux poires',
   7.50,
   'tarte_poires.jpg',
   'Tarte avec poires fraîches et crème d’amandes'
),
(
   76,
   'Chili con carne',
   15.00,
   'chili_con_carne.jpg',
   'Plat épicé avec viande hachée, haricots rouges et épices'
),
(
   77,
   'Salade grecque',
   11.00,
   'salade_grecque.jpg',
   'Salade avec feta, olives, tomates et concombre'
),
(
   78,
   'Tartine de tapenade',
   5.50,
   'tartine_tapenade.jpg',
   'Tartine de pain grillé avec tapenade d’olive'
),
(
   79,
   'Pâté de foie gras',
   14.00,
   'pate_foie_gras.jpg',
   'Foie gras accompagné de pain d’épices et confiture'
),
(
   80,
   'Pizza Carbonara',
   14.00,
   'pizza_carbonara.jpg',
   'Pizza avec crème, lardons, fromage et œuf poché'
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
   IDCOURSE,
   IDCOURSIER,
   IDADRESSE,
   IDVELO,
   DATERESERVATION,
   HEURERESERVATION,
   POURQUI
) VALUES (
   1,
   50,
   1,
   NULL,
   NULL,
   65,
   6,
   '2024-11-14',
   '13:00:00',
   'moi'
),
(
   2,
   9,
   2,
   NULL,
   NULL,
   68,
   4,
   '2024-11-24',
   '09:30:00',
   'mon ami'
),
(
   3,
   27,
   3,
   NULL,
   NULL,
   95,
   3,
   '2024-11-27',
   '13:30:00',
   'mon ami'
),
(
   4,
   5,
   4,
   NULL,
   NULL,
   52,
   8,
   '2024-11-18',
   '15:45:00',
   'mon ami'
),
(
   5,
   1,
   5,
   NULL,
   NULL,
   96,
   8,
   '2024-11-18',
   '17:00:00',
   'mon ami'
),
(
   6,
   2,
   6,
   NULL,
   NULL,
   14,
   1,
   '2024-11-22',
   '15:15:00',
   'moi'
),
(
   7,
   3,
   7,
   NULL,
   NULL,
   40,
   3,
   '2024-11-22',
   '20:30:00',
   'moi'
),
(
   8,
   38,
   8,
   NULL,
   NULL,
   78,
   5,
   '2024-11-26',
   '20:15:00',
   'moi'
),
(
   9,
   28,
   9,
   NULL,
   NULL,
   79,
   8,
   '2024-11-24',
   '13:45:00',
   'moi'
),
(
   10,
   34,
   10,
   NULL,
   NULL,
   96,
   1,
   '2024-11-18',
   '16:30:00',
   'mon ami'
),
(
   11,
   12,
   11,
   NULL,
   NULL,
   51,
   5,
   '2024-11-17',
   '08:00:00',
   'moi'
),
(
   12,
   1,
   12,
   NULL,
   NULL,
   51,
   6,
   '2024-11-21',
   '08:15:00',
   'mon ami'
),
(
   13,
   45,
   13,
   NULL,
   NULL,
   2,
   9,
   '2024-11-26',
   '19:00:00',
   'mon ami'
),
(
   14,
   16,
   14,
   NULL,
   NULL,
   45,
   10,
   '2024-11-19',
   '17:15:00',
   'mon ami'
),
(
   15,
   39,
   15,
   NULL,
   NULL,
   79,
   9,
   '2024-11-27',
   '16:45:00',
   'moi'
),
(
   16,
   3,
   16,
   NULL,
   NULL,
   61,
   2,
   '2024-11-17',
   '17:00:00',
   'mon ami'
),
(
   17,
   23,
   17,
   NULL,
   NULL,
   92,
   1,
   '2024-11-23',
   '10:30:00',
   'mon ami'
),
(
   18,
   2,
   18,
   NULL,
   NULL,
   87,
   4,
   '2024-11-15',
   '10:45:00',
   'mon ami'
),
(
   19,
   37,
   19,
   NULL,
   NULL,
   66,
   2,
   '2024-11-11',
   '17:30:00',
   'moi'
),
(
   20,
   47,
   20,
   NULL,
   NULL,
   66,
   2,
   '2024-11-25',
   '16:30:00',
   'moi'
),
(
   21,
   6,
   21,
   NULL,
   NULL,
   60,
   5,
   '2024-11-28',
   '14:30:00',
   'moi'
),
(
   22,
   45,
   22,
   NULL,
   NULL,
   69,
   1,
   '2024-11-20',
   '16:00:00',
   'moi'
),
(
   23,
   19,
   23,
   NULL,
   NULL,
   24,
   8,
   '2024-11-12',
   '11:45:00',
   'mon ami'
),
(
   24,
   11,
   24,
   NULL,
   NULL,
   89,
   10,
   '2024-11-27',
   '10:45:00',
   'moi'
),
(
   25,
   20,
   25,
   NULL,
   NULL,
   73,
   1,
   '2024-11-28',
   '15:30:00',
   'mon ami'
),
(
   26,
   12,
   26,
   NULL,
   NULL,
   93,
   10,
   '2024-11-28',
   '17:00:00',
   'moi'
),
(
   27,
   34,
   27,
   NULL,
   NULL,
   20,
   7,
   '2024-11-26',
   '13:30:00',
   'moi'
),
(
   28,
   21,
   28,
   NULL,
   NULL,
   55,
   7,
   '2024-11-21',
   '19:00:00',
   'moi'
),
(
   29,
   18,
   29,
   NULL,
   NULL,
   1,
   9,
   '2024-11-25',
   '10:00:00',
   'mon ami'
),
(
   30,
   28,
   30,
   NULL,
   NULL,
   2,
   7,
   '2024-11-23',
   '16:45:00',
   'mon ami'
),
(
   31,
   28,
   31,
   NULL,
   NULL,
   13,
   4,
   '2024-11-19',
   '17:00:00',
   'moi'
),
(
   32,
   7,
   32,
   NULL,
   NULL,
   3,
   5,
   '2024-11-28',
   '16:45:00',
   'moi'
),
(
   33,
   33,
   33,
   NULL,
   NULL,
   70,
   7,
   '2024-11-11',
   '12:45:00',
   'moi'
),
(
   34,
   25,
   34,
   NULL,
   NULL,
   20,
   3,
   '2024-11-22',
   '10:45:00',
   'mon ami'
),
(
   35,
   18,
   35,
   NULL,
   NULL,
   94,
   3,
   '2024-11-28',
   '09:00:00',
   'moi'
),
(
   36,
   10,
   36,
   NULL,
   NULL,
   27,
   1,
   '2024-11-22',
   '12:15:00',
   'mon ami'
),
(
   37,
   37,
   37,
   NULL,
   NULL,
   4,
   7,
   '2024-11-14',
   '13:00:00',
   'mon ami'
),
(
   38,
   34,
   38,
   NULL,
   NULL,
   44,
   2,
   '2024-11-14',
   '09:45:00',
   'mon ami'
),
(
   39,
   39,
   39,
   NULL,
   NULL,
   62,
   5,
   '2024-11-11',
   '16:30:00',
   'moi'
),
(
   40,
   15,
   40,
   NULL,
   NULL,
   29,
   3,
   '2024-11-22',
   '12:00:00',
   'moi'
),
(
   41,
   26,
   41,
   NULL,
   NULL,
   21,
   10,
   '2024-11-13',
   '14:30:00',
   'mon ami'
),
(
   42,
   41,
   42,
   NULL,
   NULL,
   18,
   9,
   '2024-11-17',
   '08:45:00',
   'moi'
),
(
   43,
   33,
   43,
   NULL,
   NULL,
   30,
   3,
   '2024-11-28',
   '08:00:00',
   'moi'
),
(
   44,
   48,
   44,
   NULL,
   NULL,
   1,
   4,
   '2024-11-15',
   '17:45:00',
   'mon ami'
),
(
   45,
   18,
   45,
   NULL,
   NULL,
   30,
   4,
   '2024-11-28',
   '13:15:00',
   'mon ami'
),
(
   46,
   38,
   46,
   NULL,
   NULL,
   89,
   10,
   '2024-11-18',
   '13:30:00',
   'moi'
),
(
   47,
   4,
   47,
   NULL,
   NULL,
   24,
   3,
   '2024-11-18',
   '10:00:00',
   'moi'
),
(
   48,
   49,
   48,
   NULL,
   NULL,
   63,
   4,
   '2024-11-27',
   '17:30:00',
   'moi'
),
(
   49,
   38,
   49,
   NULL,
   NULL,
   59,
   4,
   '2024-11-25',
   '19:00:00',
   'mon ami'
),
(
   50,
   33,
   50,
   NULL,
   NULL,
   53,
   7,
   '2024-11-21',
   '15:30:00',
   'moi'
),
(
   51,
   25,
   24,
   1,
   NULL,
   12,
   NULL,
   '2024-11-21',
   '09:45:00',
   'moi'
),
(
   52,
   31,
   4,
   2,
   NULL,
   92,
   NULL,
   '2024-11-18',
   '10:00:00',
   'moi'
),
(
   53,
   11,
   5,
   3,
   7,
   51,
   NULL,
   '2024-11-14',
   '12:45:00',
   'mon ami'
),
(
   54,
   44,
   47,
   4,
   1,
   56,
   NULL,
   '2024-11-14',
   '09:45:00',
   'mon ami'
),
(
   55,
   35,
   9,
   5,
   5,
   80,
   NULL,
   '2024-11-28',
   '19:15:00',
   'moi'
),
(
   56,
   29,
   10,
   6,
   6,
   45,
   NULL,
   '2024-11-28',
   '20:45:00',
   'moi'
),
(
   57,
   12,
   31,
   7,
   NULL,
   53,
   NULL,
   '2024-11-11',
   '10:15:00',
   'moi'
),
(
   58,
   33,
   1,
   8,
   10,
   78,
   NULL,
   '2024-11-16',
   '13:30:00',
   'moi'
),
(
   59,
   43,
   49,
   9,
   4,
   40,
   NULL,
   '2024-11-28',
   '20:45:00',
   'mon ami'
),
(
   60,
   17,
   6,
   10,
   8,
   55,
   NULL,
   '2024-11-16',
   '17:30:00',
   'moi'
),
(
   61,
   19,
   40,
   11,
   6,
   39,
   NULL,
   '2024-11-26',
   '08:45:00',
   'mon ami'
),
(
   62,
   27,
   29,
   12,
   1,
   80,
   NULL,
   '2024-11-21',
   '18:00:00',
   'moi'
),
(
   63,
   41,
   7,
   13,
   6,
   1,
   NULL,
   '2024-11-13',
   '19:00:00',
   'mon ami'
),
(
   64,
   33,
   24,
   14,
   3,
   2,
   NULL,
   '2024-11-28',
   '16:30:00',
   'moi'
),
(
   65,
   33,
   1,
   15,
   8,
   57,
   NULL,
   '2024-11-16',
   '10:00:00',
   'mon ami'
),
(
   66,
   33,
   2,
   16,
   4,
   98,
   NULL,
   '2024-11-28',
   '15:30:00',
   'mon ami'
),
(
   67,
   45,
   20,
   17,
   4,
   11,
   NULL,
   '2024-11-18',
   '13:00:00',
   'moi'
),
(
   68,
   6,
   7,
   18,
   4,
   78,
   NULL,
   '2024-11-15',
   '09:15:00',
   'mon ami'
),
(
   69,
   35,
   10,
   19,
   NULL,
   27,
   NULL,
   '2024-11-23',
   '09:15:00',
   'mon ami'
),
(
   70,
   48,
   15,
   20,
   3,
   9,
   NULL,
   '2024-11-11',
   '16:45:00',
   'mon ami'
),
(
   71,
   15,
   2,
   21,
   NULL,
   46,
   NULL,
   '2024-11-20',
   '10:15:00',
   'moi'
),
(
   72,
   10,
   11,
   22,
   9,
   27,
   NULL,
   '2024-11-28',
   '16:00:00',
   'moi'
),
(
   73,
   5,
   5,
   23,
   NULL,
   23,
   NULL,
   '2024-11-25',
   '13:00:00',
   'mon ami'
),
(
   74,
   18,
   2,
   24,
   3,
   1,
   NULL,
   '2024-11-28',
   '10:00:00',
   'moi'
),
(
   75,
   24,
   15,
   25,
   NULL,
   5,
   NULL,
   '2024-11-11',
   '18:00:00',
   'mon ami'
),
(
   76,
   23,
   44,
   26,
   2,
   49,
   NULL,
   '2024-11-25',
   '09:00:00',
   'moi'
),
(
   77,
   33,
   9,
   27,
   1,
   1,
   NULL,
   '2024-11-19',
   '17:15:00',
   'moi'
),
(
   78,
   15,
   46,
   28,
   5,
   68,
   NULL,
   '2024-11-15',
   '18:30:00',
   'moi'
),
(
   79,
   4,
   34,
   29,
   4,
   43,
   NULL,
   '2024-11-28',
   '08:45:00',
   'mon ami'
),
(
   80,
   37,
   50,
   30,
   1,
   63,
   NULL,
   '2024-11-20',
   '15:30:00',
   'moi'
),
(
   81,
   48,
   46,
   31,
   NULL,
   21,
   NULL,
   '2024-11-26',
   '09:15:00',
   'moi'
),
(
   82,
   2,
   24,
   32,
   10,
   91,
   NULL,
   '2024-11-28',
   '15:00:00',
   'mon ami'
),
(
   83,
   25,
   16,
   33,
   4,
   95,
   NULL,
   '2024-11-24',
   '17:45:00',
   'moi'
),
(
   84,
   8,
   26,
   34,
   NULL,
   84,
   NULL,
   '2024-11-11',
   '16:30:00',
   'mon ami'
),
(
   85,
   31,
   33,
   35,
   8,
   61,
   NULL,
   '2024-11-20',
   '18:45:00',
   'mon ami'
),
(
   86,
   42,
   15,
   36,
   NULL,
   9,
   NULL,
   '2024-11-17',
   '12:00:00',
   'mon ami'
),
(
   87,
   3,
   43,
   37,
   NULL,
   11,
   NULL,
   '2024-11-28',
   '10:45:00',
   'mon ami'
),
(
   88,
   21,
   48,
   38,
   5,
   6,
   NULL,
   '2024-11-18',
   '10:00:00',
   'mon ami'
),
(
   89,
   48,
   13,
   39,
   7,
   5,
   NULL,
   '2024-11-13',
   '11:15:00',
   'moi'
),
(
   90,
   9,
   8,
   40,
   8,
   54,
   NULL,
   '2024-11-22',
   '15:30:00',
   'mon ami'
),
(
   91,
   16,
   9,
   41,
   6,
   67,
   NULL,
   '2024-11-28',
   '17:45:00',
   'mon ami'
),
(
   92,
   46,
   32,
   42,
   NULL,
   28,
   NULL,
   '2024-11-24',
   '10:45:00',
   'mon ami'
),
(
   93,
   24,
   33,
   43,
   6,
   94,
   NULL,
   '2024-11-21',
   '19:45:00',
   'moi'
),
(
   94,
   18,
   20,
   44,
   2,
   74,
   NULL,
   '2024-11-21',
   '08:15:00',
   'mon ami'
),
(
   95,
   16,
   19,
   45,
   6,
   70,
   NULL,
   '2024-11-13',
   '20:00:00',
   'mon ami'
),
(
   96,
   30,
   22,
   46,
   4,
   97,
   NULL,
   '2024-11-20',
   '13:45:00',
   'moi'
),
(
   97,
   32,
   27,
   47,
   8,
   77,
   NULL,
   '2024-11-28',
   '08:45:00',
   'moi'
),
(
   98,
   49,
   50,
   48,
   4,
   85,
   NULL,
   '2024-11-26',
   '17:45:00',
   'moi'
),
(
   99,
   35,
   34,
   49,
   5,
   56,
   NULL,
   '2024-11-27',
   '14:30:00',
   'mon ami'
),
(
   100,
   43,
   5,
   50,
   NULL,
   99,
   NULL,
   '2024-11-28',
   '09:00:00',
   'mon ami'
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
   IDRESERVATION,
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
   2,
   '12346',
   FALSE
),
(
   3,
   3,
   '12347',
   TRUE
),
(
   4,
   4,
   '12348',
   TRUE
),
(
   5,
   5,
   '12349',
   FALSE
),
(
   6,
   6,
   '12350',
   TRUE
),
(
   7,
   7,
   '12351',
   TRUE
),
(
   8,
   8,
   '12352',
   FALSE
),
(
   9,
   9,
   '12353',
   TRUE
),
(
   10,
   10,
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
   ADD CONSTRAINT FK_APPARTIE_APPARTIEN_CARTE_BA FOREIGN KEY (
      IDCB
   )
      REFERENCES CARTE_BANCAIRE (
         IDCB
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE APPARTIENT_2
   ADD CONSTRAINT FK_APPARTIE_APPARTIEN_CLIENT FOREIGN KEY (
      IDCLIENT
   )
      REFERENCES CLIENT (
         IDCLIENT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE A_3
   ADD CONSTRAINT FK_A_3_A_3_PRODUIT FOREIGN KEY (
      IDPRODUIT
   )
      REFERENCES PRODUIT (
         IDPRODUIT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE A_3
   ADD CONSTRAINT FK_A_3_A_4_CATEGORI FOREIGN KEY (
      IDCATEGORIE
   )
      REFERENCES CATEGORIE_PRODUIT (
         IDCATEGORIE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE A_COMME_TYPE
   ADD CONSTRAINT FK_A_COMME__A_COMME_T_VEHICULE FOREIGN KEY (
      IDVEHICULE
   )
      REFERENCES VEHICULE (
         IDVEHICULE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE A_COMME_TYPE
   ADD CONSTRAINT FK_A_COMME__A_COMME_T_TYPE_PRE FOREIGN KEY (
      IDPRESTATION
   )
      REFERENCES TYPE_PRESTATION (
         IDPRESTATION
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE CLIENT
   ADD CONSTRAINT FK_CLIENT_A2_PLANNING FOREIGN KEY (
      IDPLANNING
   )
      REFERENCES PLANNING_RESERVATION (
         IDPLANNING
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE CLIENT
   ADD CONSTRAINT FK_CLIENT_APPARTIEN_PANIER FOREIGN KEY (
      IDPANIER
   )
      REFERENCES PANIER (
         IDPANIER
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE CLIENT
   ADD CONSTRAINT FK_CLIENT_FAIT_PART_ENTREPRI FOREIGN KEY (
      IDENTREPRISE
   )
      REFERENCES ENTREPRISE (
         IDENTREPRISE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE CLIENT
   ADD CONSTRAINT FK_CLIENT_HABITE_ADRESSE FOREIGN KEY (
      IDADRESSE
   )
      REFERENCES ADRESSE (
         IDADRESSE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE CODE_POSTAL
   ADD CONSTRAINT FK_CODE_POS_APPARTIEN_PAYS FOREIGN KEY (
      IDPAYS
   )
      REFERENCES PAYS (
         IDPAYS
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COMMANDE
   ADD CONSTRAINT FK_COMMANDE_EST_LIVRE_COURSIER FOREIGN KEY (
      IDCOURSIER
   )
      REFERENCES COURSIER (
         IDCOURSIER
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COMMANDE
   ADD CONSTRAINT FK_COMMANDE_PASSE_COM_PANIER FOREIGN KEY (
      IDPANIER
   )
      REFERENCES PANIER (
         IDPANIER
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE CONTIENT_2
   ADD CONSTRAINT FK_CONTIENT_CONTIENT__PANIER FOREIGN KEY (
      IDPANIER
   )
      REFERENCES PANIER (
         IDPANIER
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE CONTIENT_2
   ADD CONSTRAINT FK_CONTIENT_CONTIENT__PRODUIT FOREIGN KEY (
      IDPRODUIT
   )
      REFERENCES PRODUIT (
         IDPRODUIT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COURSE
   ADD CONSTRAINT FK_COURSE_A_2_TYPE_PRE FOREIGN KEY (
      IDPRESTATION
   )
      REFERENCES TYPE_PRESTATION (
         IDPRESTATION
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COURSE
   ADD CONSTRAINT FK_COURSE_COMMENCE__ADRESSE FOREIGN KEY (
      ADR_IDADRESSE
   )
      REFERENCES ADRESSE (
         IDADRESSE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COURSE
   ADD CONSTRAINT FK_COURSE_EST_POUR_RESERVAT FOREIGN KEY (
      IDRESERVATION
   )
      REFERENCES RESERVATION (
         IDRESERVATION
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COURSE
   ADD CONSTRAINT FK_COURSE_SE_FINIT__ADRESSE FOREIGN KEY (
      IDADRESSE
   )
      REFERENCES ADRESSE (
         IDADRESSE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COURSE
   ADD CONSTRAINT FK_COURSE_UTILISE_CARTE_BA FOREIGN KEY (
      IDCB
   )
      REFERENCES CARTE_BANCAIRE (
         IDCB
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COURSIER
   ADD CONSTRAINT FK_COURSIER_EST_CONDU_RESERVAT FOREIGN KEY (
      IDRESERVATION
   )
      REFERENCES RESERVATION (
         IDRESERVATION
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COURSIER
   ADD CONSTRAINT FK_COURSIER_FAIT_PART_ENTREPRI FOREIGN KEY (
      IDENTREPRISE
   )
      REFERENCES ENTREPRISE (
         IDENTREPRISE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE COURSIER
   ADD CONSTRAINT FK_COURSIER_SE_SITUE__ADRESSE FOREIGN KEY (
      IDADRESSE
   )
      REFERENCES ADRESSE (
         IDADRESSE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE DEPARTEMENT
   ADD CONSTRAINT FK_DEPARTEM_EST_DANS__PAYS FOREIGN KEY (
      IDPAYS
   )
      REFERENCES PAYS (
         IDPAYS
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE ENTREPRISE
   ADD CONSTRAINT FK_ENTREPRI_FAIT_PART_CLIENT FOREIGN KEY (
      IDCLIENT
   )
      REFERENCES CLIENT (
         IDCLIENT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE ENTREPRISE
   ADD CONSTRAINT FK_ENTREPRI_SE_SITUE__ADRESSE FOREIGN KEY (
      IDADRESSE
   )
      REFERENCES ADRESSE (
         IDADRESSE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE EST_SITUE_A_2
   ADD CONSTRAINT FK_EST_SITU_EST_SITUE_PRODUIT FOREIGN KEY (
      IDPRODUIT
   )
      REFERENCES PRODUIT (
         IDPRODUIT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE EST_SITUE_A_2
   ADD CONSTRAINT FK_EST_SITU_EST_SITUE_ETABLISS FOREIGN KEY (
      IDETABLISSEMENT
   )
      REFERENCES ETABLISSEMENT (
         IDETABLISSEMENT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE ETABLISSEMENT
   ADD CONSTRAINT FK_ETABLISS_EST_SITUE_ADRESSE FOREIGN KEY (
      IDADRESSE
   )
      REFERENCES ADRESSE (
         IDADRESSE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE FACTURE_COURSE
   ADD CONSTRAINT FK_FACTURE__APPARTIEN_COURSE FOREIGN KEY (
      IDCOURSE
   )
      REFERENCES COURSE (
         IDCOURSE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE FACTURE_COURSE
   ADD CONSTRAINT FK_FACTURE__RECOIT_FA_CLIENT FOREIGN KEY (
      IDCLIENT
   )
      REFERENCES CLIENT (
         IDCLIENT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE FACTURE_COURSE
   ADD CONSTRAINT FK_FACTURE__RECUPERE__PAYS FOREIGN KEY (
      IDPAYS
   )
      REFERENCES PAYS (
         IDPAYS
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE PANIER
   ADD CONSTRAINT FK_PANIER_APPARTIEN_CLIENT FOREIGN KEY (
      IDCLIENT
   )
      REFERENCES CLIENT (
         IDCLIENT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE PLANNING_RESERVATION
   ADD CONSTRAINT FK_PLANNING_A_CLIENT FOREIGN KEY (
      IDCLIENT
   )
      REFERENCES CLIENT (
         IDCLIENT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE REGLEMENT_SALAIRE
   ADD CONSTRAINT FK_REGLEMEN_RECOIT_RE_COURSIER FOREIGN KEY (
      IDCOURSIER
   )
      REFERENCES COURSIER (
         IDCOURSIER
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE RESERVATION
   ADD CONSTRAINT FK_RESERVAT_EST_CONDU_COURSIER FOREIGN KEY (
      IDCOURSIER
   )
      REFERENCES COURSIER (
         IDCOURSIER
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE RESERVATION
   ADD CONSTRAINT FK_RESERVAT_EST_DANS__PLANNING FOREIGN KEY (
      IDPLANNING
   )
      REFERENCES PLANNING_RESERVATION (
         IDPLANNING
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE RESERVATION
   ADD CONSTRAINT FK_RESERVAT_EST_POUR2_COURSE FOREIGN KEY (
      IDCOURSE
   )
      REFERENCES COURSE (
         IDCOURSE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE RESERVATION
   ADD CONSTRAINT FK_RESERVAT_EST_POUR__VELO FOREIGN KEY (
      IDVELO
   )
      REFERENCES VELO (
         IDVELO
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE RESERVATION
   ADD CONSTRAINT FK_RESERVAT_PEUT_CLIENT FOREIGN KEY (
      IDCLIENT
   )
      REFERENCES CLIENT (
         IDCLIENT
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE RESERVATION
   ADD CONSTRAINT FK_RESERVAT_SE_SITUE_ADRESSE FOREIGN KEY (
      IDADRESSE
   )
      REFERENCES ADRESSE (
         IDADRESSE
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE VEHICULE
   ADD CONSTRAINT FK_VEHICULE_APPARTIEN_COURSIER FOREIGN KEY (
      IDCOURSIER
   )
      REFERENCES COURSIER (
         IDCOURSIER
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE VELO
   ADD CONSTRAINT FK_VELO_EST_POUR__RESERVAT FOREIGN KEY (
      IDRESERVATION
   )
      REFERENCES RESERVATION (
         IDRESERVATION
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE VILLE
   ADD CONSTRAINT FK_VILLE_APPARTIEN_CODE_POS FOREIGN KEY (
      IDCODEPOSTAL
   )
      REFERENCES CODE_POSTAL (
         IDCODEPOSTAL
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE VILLE
   ADD CONSTRAINT FK_VILLE_EST_DANS__PAYS FOREIGN KEY (
      IDPAYS
   )
      REFERENCES PAYS (
         IDPAYS
      ) ON DELETE RESTRICT ON UPDATE RESTRICT;