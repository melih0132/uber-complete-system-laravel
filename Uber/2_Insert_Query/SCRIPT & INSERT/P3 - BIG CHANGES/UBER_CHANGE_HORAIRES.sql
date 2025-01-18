/*==============================================================*/
/* Nom de SGBD :  PostgreSQL 8                                  */
/* Date de création :  01/01/2001                               */
/*==============================================================*/
DROP TABLE IF EXISTS ADRESSE CASCADE;
DROP TABLE IF EXISTS APPARTIENT_2 CASCADE;
DROP TABLE IF EXISTS A_3 CASCADE;
DROP TABLE IF EXISTS A_COMME_CATEGORIE CASCADE;
DROP TABLE IF EXISTS A_COMME_HORAIRES CASCADE;
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
DROP TABLE IF EXISTS ENTRETIEN CASCADE;
DROP TABLE IF EXISTS EST_SITUE_A_2 CASCADE;
DROP TABLE IF EXISTS ETABLISSEMENT CASCADE;
DROP TABLE IF EXISTS FACTURE_COURSE CASCADE;
DROP TABLE IF EXISTS HORAIRES_ETABLISSEMENT CASCADE;
DROP TABLE IF EXISTS HORAIRES_COURSIER CASCADE;
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
    IDVILLE INT4 NULL,
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
/* Table : A_3                                                  */
/*==============================================================*/
CREATE TABLE A_COMME_HORAIRES (
    IDHORAIRES_ETABLISSEMENT INT4 NOT NULL,
    IDETABLISSEMENT INT4 NOT NULL,
    CONSTRAINT PK_A_COMME_HORAIRES PRIMARY KEY (IDHORAIRES_ETABLISSEMENT, IDETABLISSEMENT)
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
    IDCB SERIAL PRIMARY KEY,
    NUMEROCB NUMERIC(16, 0) NOT NULL UNIQUE,
    DATEEXPIRECB DATE NOT NULL,
    CONSTRAINT CK_CB_DATEEXPIRE CHECK (DATEEXPIRECB >= CURRENT_DATE),
    CRYPTOGRAMME NUMERIC(3, 0) NOT NULL,
    TYPECARTE VARCHAR(30) NOT NULL,
    TYPERESEAUX VARCHAR(30) NOT NULL
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
    IDENTREPRISE INT4 NULL,
    IDADRESSE INT4 NOT NULL,
    GENREUSER VARCHAR(20) NOT NULL,
    CONSTRAINT CK_CLIENT_GENRE CHECK (GENREUSER IN ('Monsieur', 'Madame')),
    NOMUSER VARCHAR(50) NOT NULL,
    PRENOMUSER VARCHAR(50) NOT NULL,
    DATENAISSANCE DATE NOT NULL,
    CONSTRAINT CK_DATE_NAISS CHECK (
        DATENAISSANCE <= CURRENT_DATE
        AND DATENAISSANCE <= CURRENT_DATE - INTERVAL '18 years'
    ),
    TELEPHONE VARCHAR(15) NOT NULL,
    CONSTRAINT CK_CLIENT_TEL CHECK (TELEPHONE ~ '^(06|07)[0-9]{8}$'),
    EMAILUSER VARCHAR(200) NOT NULL,
    CONSTRAINT UQ_CLIENT_MAIL UNIQUE (EMAILUSER),
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
    IDPAYS INT4 NULL,
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
    CONSTRAINT CK_STATUT_COMMANDE CHECK (
        STATUTCOMMANDE IN ('En attente', 'En cours', 'Livrée', 'Annulée')
    ),
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
    DATECOURSE DATE NOT NULL,
    HEURECOURSE TIME NOT NULL,
    PRIXCOURSE NUMERIC(8, 2) NOT NULL,
    CONSTRAINT CK_COURSE_PRIX CHECK (PRIXCOURSE >= 0),
    STATUTCOURSE VARCHAR(20) NOT NULL,
    CONSTRAINT CK_COURSE_STATUT CHECK (
        STATUTCOURSE IN ('En attente', 'En cours', 'Terminée', 'Annulée')
    ),
    NOTECOURSE NUMERIC(2, 1) NULL,
    CONSTRAINT CK_COURSE_NOTE CHECK (
        NOTECOURSE BETWEEN 0 AND 5
        OR NOTECOURSE IS NULL
    ),
    COMMENTAIRECOURSE VARCHAR(1500) NULL,
    POURBOIRE NUMERIC(8, 2) NULL,
    CONSTRAINT CK_COURSE_POURBOIRE CHECK (
        POURBOIRE IS NULL
        OR POURBOIRE >= 0
    ),
    DISTANCE NUMERIC(8, 2) NULL,
    CONSTRAINT CK_COURSE_DISTANCE CHECK (
        DISTANCE IS NULL
        OR DISTANCE >= 0
    ),
    TEMPS INT NULL,
    CONSTRAINT CK_COURSE_TEMPS CHECK (
        TEMPS IS NULL
        OR TEMPS >= 0
    ),
    CONSTRAINT CK_COURSE_NOTE_IS_NULL CHECK (
        STATUTCOURSE <> 'Terminée'
        AND NOTECOURSE IS NULL
        OR STATUTCOURSE = 'Terminée'
    ),
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
    CONSTRAINT CK_COURSIER_GENRE CHECK (GENREUSER IN ('Monsieur', 'Madame')),
    NOMUSER VARCHAR(50) NOT NULL,
    PRENOMUSER VARCHAR(50) NOT NULL,
    DATENAISSANCE DATE NOT NULL,
    CONSTRAINT CK_COURSIER_DATE CHECK (
        DATENAISSANCE <= CURRENT_DATE
        AND DATENAISSANCE <= CURRENT_DATE - INTERVAL '18 years'
    ),
    TELEPHONE VARCHAR(15) NOT NULL,
    CONSTRAINT CK_COURSIER_TEL CHECK (TELEPHONE ~ '^(06|07)[0-9]{8}$'),
    EMAILUSER VARCHAR(200) NOT NULL,
    CONSTRAINT UQ_COURSIER_MAIL UNIQUE (EMAILUSER),
    MOTDEPASSEUSER VARCHAR(200) NOT NULL,
    NUMEROCARTEVTC CHAR(12) NOT NULL,
    CONSTRAINT CK_COURSIER_NUMCARTE_SIZE CHECK (LENGTH(NUMEROCARTEVTC) = 12),
    CONSTRAINT UQ_COURSIER_NUMCARTE UNIQUE (NUMEROCARTEVTC),
    IBAN VARCHAR(30) NULL,
    CONSTRAINT UQ_COURSIER_IBAN UNIQUE (IBAN),
    DATEDEBUTACTIVITE DATE NULL,
    NOTEMOYENNE NUMERIC(2, 1) NULL,
    CONSTRAINT CK_COURSIER_NOTE CHECK (
        NOTEMOYENNE >= 1
        AND NOTEMOYENNE <= 5
        OR NULL
    ),
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
    IDADRESSE INT4 NOT NULL,
    SIRETENTREPRISE VARCHAR(20) NOT NULL,
    CONSTRAINT CK_SIRET_ENTREPRISE CHECK (SIRETENTREPRISE ~ '^[0-9]{14}$'),
    NOMENTREPRISE VARCHAR(50) NOT NULL,
    TAILLE VARCHAR(30) NOT NULL,
    CONSTRAINT CK_ENTREPRISE_TAILLE CHECK (TAILLE IN ('PME', 'ETI', 'GE')),
    CONSTRAINT PK_ENTREPRISE PRIMARY KEY (IDENTREPRISE)
);
/*==============================================================*/
/* Table : ENTRETIEN                                            */
/*==============================================================*/
CREATE TABLE ENTRETIEN (
    IDENTRETIEN INT4 NOT NULL,
    IDCOURSIER INT4 NOT NULL,
    DATEENTRETIEN TIMESTAMP NULL,
    STATUS VARCHAR(20) NOT NULL DEFAULT 'En attente',
    RESULTAT VARCHAR(20) NULL,
    CONSTRAINT CK_STATUS_ENTRETIEN CHECK (
        STATUS IN ('En attente', 'Plannifié', 'Terminée', 'Annulée')
    ),
    CONSTRAINT CK_RESULTAT_ENTRETIEN CHECK (
        RESULTAT IN ('Retenu', 'Rejeté')
        OR RESULTAT IS NULL
    ),
    CONSTRAINT PK_ENTRETIEN PRIMARY KEY (IDENTRETIEN)
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
    DESCRIPTION VARCHAR(1500) NULL,
    IMAGEETABLISSEMENT VARCHAR(200) NULL,
    LIVRAISON BOOL NULL,
    AEMPORTER BOOL NULL,
    CONSTRAINT PK_ETABLISSEMENT PRIMARY KEY (IDETABLISSEMENT)
);
/*==============================================================*/
/* Table : HORAIRES_ETABLISSEMENT                               */
/*==============================================================*/
CREATE TABLE HORAIRES_ETABLISSEMENT (
    IDHORAIRES_ETABLISSEMENT INT4 NOT NULL,
    JOURSEMAINE VARCHAR(9) NOT NULL,
    HORAIRESOUVERTURE TIME WITH TIME ZONE NULL,
    HORAIRESFERMETURE TIME WITH TIME ZONE NULL,
    CONSTRAINT PK_HORAIRES_ETABLISSEMENT PRIMARY KEY (IDHORAIRES_ETABLISSEMENT),
    CONSTRAINT CK_JOURSEMAINE CHECK (
        JOURSEMAINE IN (
            'Lundi',
            'Mardi',
            'Mercredi',
            'Jeudi',
            'Vendredi',
            'Samedi',
            'Dimanche'
        )
    )
);
/*==============================================================*/
/* Table : HORAIRES_COURSIER                                    */
/*==============================================================*/
CREATE TABLE HORAIRES_COURSIER (
    IDHORAIRES_COURSIER INT4 NOT NULL,
    IDCOURSIER INT4 NOT NULL,
    JOURSEMAINE VARCHAR(9) NOT NULL,
    HEUREDEBUT TIME WITH TIME ZONE NULL,
    HEUREFIN TIME WITH TIME ZONE NULL,
    CONSTRAINT PK_HORAIRES_COURSIER PRIMARY KEY (IDHORAIRES_COURSIER),
    CONSTRAINT CK_JOURSEMAINE CHECK (
        JOURSEMAINE IN (
            'Lundi',
            'Mardi',
            'Mercredi',
            'Jeudi',
            'Vendredi',
            'Samedi',
            'Dimanche'
        )
    )
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
    CONSTRAINT CK_TVA CHECK (
        POURCENTAGETVA >= 0
        AND POURCENTAGETVA < 100
    ),
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
    CONSTRAINT CK_VEHICULE_IMMATRICULATION CHECK (IMMATRICULATION ~ '^[A-Z]{2}-[0-9]{3}-[A-Z]{2}$'),
    CONSTRAINT UQ_VEHICULE_IMMATRICULATION UNIQUE (IMMATRICULATION),
    MARQUE VARCHAR(50) NULL,
    MODELE VARCHAR(50) NULL,
    CAPACITE INT4 NULL,
    CONSTRAINT CK_VEHICULE_CAPACITE CHECK (
        CAPACITE BETWEEN 2 AND 7
    ),
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
    IDPAYS INT4 NULL,
    IDCODEPOSTAL INT4 NULL,
    NOMVILLE VARCHAR(50) NULL,
    CONSTRAINT PK_VILLE PRIMARY KEY (IDVILLE)
);
------------------------------------------------------------------------------------------------------------------------------------------------------
ALTER TABLE ADRESSE
ADD CONSTRAINT FK_ADRESSE_EST_DANS_VILLE FOREIGN KEY (IDVILLE) REFERENCES VILLE (IDVILLE) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE APPARTIENT_2
ADD CONSTRAINT FK_APPARTIENT2_CARTE_BANCAIRE FOREIGN KEY (IDCB) REFERENCES CARTE_BANCAIRE (IDCB) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE APPARTIENT_2
ADD CONSTRAINT FK_APPARTIENT2_CLIENT FOREIGN KEY (IDCLIENT) REFERENCES CLIENT (IDCLIENT) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE A_3
ADD CONSTRAINT FK_A_3_PRODUIT FOREIGN KEY (IDPRODUIT) REFERENCES PRODUIT (IDPRODUIT) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE A_3
ADD CONSTRAINT FK_A_3_CATEGORIE FOREIGN KEY (IDCATEGORIE) REFERENCES CATEGORIE_PRODUIT (IDCATEGORIE) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE A_COMME_HORAIRES
ADD CONSTRAINT FK_A_COMME_HORAIRES_HORAIRES FOREIGN KEY (IDHORAIRES_ETABLISSEMENT) REFERENCES HORAIRES_ETABLISSEMENT (IDHORAIRES_ETABLISSEMENT) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE A_COMME_HORAIRES
ADD CONSTRAINT FK_A_COMME_HORAIRES_ETABLISSEMENT FOREIGN KEY (IDETABLISSEMENT) REFERENCES ETABLISSEMENT (IDETABLISSEMENT) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE A_COMME_TYPE
ADD CONSTRAINT FK_A_COMME_TYPE_VEHICULE FOREIGN KEY (IDVEHICULE) REFERENCES VEHICULE (IDVEHICULE) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE A_COMME_TYPE
ADD CONSTRAINT FK_A_COMME_TYPE_PRESTATION FOREIGN KEY (IDPRESTATION) REFERENCES TYPE_PRESTATION (IDPRESTATION) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE CLIENT
ADD CONSTRAINT FK_CLIENT_ENTREPRISE FOREIGN KEY (IDENTREPRISE) REFERENCES ENTREPRISE (IDENTREPRISE) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE CLIENT
ADD CONSTRAINT FK_CLIENT_ADRESSE FOREIGN KEY (IDADRESSE) REFERENCES ADRESSE (IDADRESSE) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE CODE_POSTAL
ADD CONSTRAINT FK_CODE_POSTAL_PAYS FOREIGN KEY (IDPAYS) REFERENCES PAYS (IDPAYS) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE COMMANDE
ADD CONSTRAINT FK_COMMANDE_COURSIER FOREIGN KEY (IDCOURSIER) REFERENCES COURSIER (IDCOURSIER) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE COMMANDE
ADD CONSTRAINT FK_COMMANDE_PANIER FOREIGN KEY (IDPANIER) REFERENCES PANIER (IDPANIER) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE CONTIENT_2
ADD CONSTRAINT FK_CONTIENT2_PANIER FOREIGN KEY (IDPANIER) REFERENCES PANIER (IDPANIER) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE CONTIENT_2
ADD CONSTRAINT FK_CONTIENT2_PRODUIT FOREIGN KEY (IDPRODUIT) REFERENCES PRODUIT (IDPRODUIT) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE COURSE
ADD CONSTRAINT FK_COURSE_PRESTATION FOREIGN KEY (IDPRESTATION) REFERENCES TYPE_PRESTATION (IDPRESTATION) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE COURSE
ADD CONSTRAINT FK_COURSE_ADRESSE_START FOREIGN KEY (ADR_IDADRESSE) REFERENCES ADRESSE (IDADRESSE) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE COURSE
ADD CONSTRAINT FK_COURSE_RESERVATION FOREIGN KEY (IDRESERVATION) REFERENCES RESERVATION (IDRESERVATION) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE COURSE
ADD CONSTRAINT FK_COURSE_ADRESSE_END FOREIGN KEY (IDADRESSE) REFERENCES ADRESSE (IDADRESSE) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE COURSE
ADD CONSTRAINT FK_COURSE_CARTE_BANCAIRE FOREIGN KEY (IDCB) REFERENCES CARTE_BANCAIRE (IDCB) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE COURSE
ADD CONSTRAINT FK_COURSE_PAR_COURSIER FOREIGN KEY (IDCOURSIER) REFERENCES COURSIER (IDCOURSIER) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE COURSIER
ADD CONSTRAINT FK_COURSIER_ENTREPRISE FOREIGN KEY (IDENTREPRISE) REFERENCES ENTREPRISE (IDENTREPRISE) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE COURSIER
ADD CONSTRAINT FK_COURSIER_ADRESSE FOREIGN KEY (IDADRESSE) REFERENCES ADRESSE (IDADRESSE) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE DEPARTEMENT
ADD CONSTRAINT FK_DEPARTEMENT_PAYS FOREIGN KEY (IDPAYS) REFERENCES PAYS (IDPAYS) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE ENTREPRISE
ADD CONSTRAINT FK_ENTREPRISE_ADRESSE FOREIGN KEY (IDADRESSE) REFERENCES ADRESSE (IDADRESSE) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE ENTRETIEN
ADD CONSTRAINT FK_ENTRETIEN_COURSIER FOREIGN KEY (IDCOURSIER) REFERENCES COURSIER (IDCOURSIER) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE EST_SITUE_A_2
ADD CONSTRAINT FK_EST_SITUE2_PRODUIT FOREIGN KEY (IDPRODUIT) REFERENCES PRODUIT (IDPRODUIT) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE EST_SITUE_A_2
ADD CONSTRAINT FK_EST_SITUE2_ETABLISSEMENT FOREIGN KEY (IDETABLISSEMENT) REFERENCES ETABLISSEMENT (IDETABLISSEMENT) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE A_COMME_CATEGORIE
ADD CONSTRAINT FK_A_COMME_CATEGORIE_PRESTATIONC FOREIGN KEY (IDCATEGORIEPRESTATION) REFERENCES CATEGORIE_PRESTATION (IDCATEGORIEPRESTATION) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE A_COMME_CATEGORIE
ADD CONSTRAINT FK_COMME_CATEGORIE_ETABLISSEMENT FOREIGN KEY (IDETABLISSEMENT) REFERENCES ETABLISSEMENT (IDETABLISSEMENT) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE ETABLISSEMENT
ADD CONSTRAINT FK_ETABLISSEMENT_ADRESSE FOREIGN KEY (IDADRESSE) REFERENCES ADRESSE (IDADRESSE) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE HORAIRES_COURSIER
ADD CONSTRAINT FK_COURSIER_HORAIRES FOREIGN KEY (IDCOURSIER) REFERENCES COURSIER (IDCOURSIER) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE FACTURE_COURSE
ADD CONSTRAINT FK_FACTURE_COURSE FOREIGN KEY (IDCOURSE) REFERENCES COURSE (IDCOURSE) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE FACTURE_COURSE
ADD CONSTRAINT FK_FACTURE_CLIENT FOREIGN KEY (IDCLIENT) REFERENCES CLIENT (IDCLIENT) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE FACTURE_COURSE
ADD CONSTRAINT FK_FACTURE_PAYS FOREIGN KEY (IDPAYS) REFERENCES PAYS (IDPAYS) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE PANIER
ADD CONSTRAINT FK_PANIER_CLIENT FOREIGN KEY (IDCLIENT) REFERENCES CLIENT (IDCLIENT) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE PLANNING_RESERVATION
ADD CONSTRAINT FK_PLANNING_CLIENT FOREIGN KEY (IDCLIENT) REFERENCES CLIENT (IDCLIENT) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE REGLEMENT_SALAIRE
ADD CONSTRAINT FK_REGLEMENT_COURSIER FOREIGN KEY (IDCOURSIER) REFERENCES COURSIER (IDCOURSIER) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE RESERVATION
ADD CONSTRAINT FK_RESERVATION_PLANNING FOREIGN KEY (IDPLANNING) REFERENCES PLANNING_RESERVATION (IDPLANNING) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE RESERVATION
ADD CONSTRAINT FK_RESERVATION_VELO FOREIGN KEY (IDVELO) REFERENCES VELO (IDVELO) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE RESERVATION
ADD CONSTRAINT FK_RESERVATION_CLIENT FOREIGN KEY (IDCLIENT) REFERENCES CLIENT (IDCLIENT) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE VEHICULE
ADD CONSTRAINT FK_VEHICULE_COURSIER FOREIGN KEY (IDCOURSIER) REFERENCES COURSIER (IDCOURSIER) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE VELO
ADD CONSTRAINT FK_VELO_ADRESSE FOREIGN KEY (IDADRESSE) REFERENCES ADRESSE (IDADRESSE) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE VILLE
ADD CONSTRAINT FK_VILLE_CODE_POSTAL FOREIGN KEY (IDCODEPOSTAL) REFERENCES CODE_POSTAL (IDCODEPOSTAL) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE VILLE
ADD CONSTRAINT FK_VILLE_PAYS FOREIGN KEY (IDPAYS) REFERENCES PAYS (IDPAYS) ON DELETE RESTRICT ON UPDATE RESTRICT;
INSERT INTO PAYS (IDPAYS, NOMPAYS, POURCENTAGETVA)
VALUES (1, 'France', 20.0),
    (2, 'Allemagne', 19.0),
    (3, 'Espagne', 21.0),
    (4, 'Italie', 22.0),
    (5, 'Belgique', 21.0),
    (6, 'Luxembourg', 17.0),
    (7, 'Suisse', 7.7),
    (8, 'Portugal', 23.0),
    (9, 'Pays-Bas', 21.0),
    (10, 'Autriche', 20.0),
    (11, 'Suède', 25.0),
    (12, 'Danemark', 25.0),
    (13, 'Finlande', 24.0),
    (14, 'Irlande', 23.0),
    (15, 'Grèce', 24.0),
    (16, 'Pologne', 23.0),
    (17, 'Hongrie', 27.0),
    (18, 'République Tchèque', 21.0),
    (19, 'Slovénie', 22.0),
    (20, 'Roumanie', 19.0);
INSERT INTO DEPARTEMENT (
        IDDEPARTEMENT,
        IDPAYS,
        CODEDEPARTEMENT,
        LIBELLEDEPARTEMENT
    )
VALUES (1, 1, '75', 'Paris'),
    (2, 1, '13', 'Bouches-du-Rhône'),
    (3, 1, '69', 'Rhône'),
    (4, 1, '33', 'Gironde'),
    (5, 1, '06', 'Alpes-Maritimes'),
    (6, 1, '44', 'Loire-Atlantique'),
    (7, 1, '59', 'Nord'),
    (8, 1, '34', 'Hérault'),
    (9, 1, '31', 'Haute-Garonne'),
    (10, 1, '85', 'Vendée'),
    (11, 1, '62', 'Pas-de-Calais'),
    (12, 1, '76', 'Seine-Maritime'),
    (13, 1, '94', 'Val-de-Marne'),
    (14, 1, '75', 'Paris'),
    (15, 1, '77', 'Seine-et-Marne'),
    (16, 1, '91', 'Essonne'),
    (17, 1, '93', 'Seine-Saint-Denis'),
    (18, 1, '92', 'Hauts-de-Seine'),
    (19, 1, '95', 'Val-d Oise'),
    (20, 1, '60', 'Oise');
INSERT INTO CODE_POSTAL (IDCODEPOSTAL, IDPAYS, CODEPOSTAL)
VALUES (1, 1, '75000'),
    (2, 1, '69000'),
    (3, 1, '13000'),
    (4, 1, '33000'),
    (5, 1, '06000'),
    (6, 1, '44000'),
    (7, 1, '34000'),
    (8, 1, '67000'),
    (9, 1, '21000'),
    (10, 1, '78000'),
    (11, 1, '74000'),
    (12, 1, '31000'),
    (13, 1, '59000'),
    (14, 1, '35000'),
    (15, 1, '76600'),
    (16, 1, '51100'),
    (17, 1, '42000'),
    (18, 1, '72000'),
    (19, 1, '80000'),
    (20, 1, '29200'),
    (21, 1, '63000'),
    (22, 1, '83000'),
    (23, 1, '49100'),
    (24, 1, '30000'),
    (25, 1, '37000'),
    (26, 1, '14000'),
    (27, 1, '45200'),
    (28, 1, '66000'),
    (29, 1, '73000'),
    (30, 1, '68000'),
    (31, 1, '56000'),
    (32, 1, '13100'),
    (33, 1, '64200'),
    (34, 1, '38000'),
    (35, 1, '45000'),
    (36, 1, '84000'),
    (37, 1, '54000'),
    (38, 1, '56100'),
    (39, 1, '17000'),
    (40, 1, '34200');
INSERT INTO VILLE (
        IDVILLE,
        IDPAYS,
        IDCODEPOSTAL,
        NOMVILLE
    )
VALUES (1, 1, 1, 'Paris'),
    (2, 1, 2, 'Lyon'),
    (3, 1, 3, 'Marseille'),
    (4, 1, 4, 'Bordeaux'),
    (5, 1, 5, 'Nice'),
    (6, 1, 6, 'Nantes'),
    (7, 1, 7, 'Montpellier'),
    (8, 1, 8, 'Strasbourg'),
    (9, 1, 9, 'Dijon'),
    (10, 1, 10, 'Versailles'),
    (11, 1, 11, 'Annecy'),
    (12, 1, 12, 'Toulouse'),
    (13, 1, 13, 'Lille'),
    (14, 1, 14, 'Rennes'),
    (15, 1, 15, 'Le Havre'),
    (16, 1, 16, 'Reims'),
    (17, 1, 17, 'Saint-Étienne'),
    (18, 1, 18, 'Le Mans'),
    (19, 1, 19, 'Amiens'),
    (20, 1, 20, 'Brest'),
    (21, 1, 21, 'Clermont-Ferrand'),
    (22, 1, 22, 'Toulon'),
    (23, 1, 23, 'Angers'),
    (24, 1, 24, 'Nîmes'),
    (25, 1, 25, 'Tours'),
    (26, 1, 26, 'Caen'),
    (27, 1, 27, 'Montargis'),
    (28, 1, 28, 'Perpignan'),
    (29, 1, 29, 'Chambery'),
    (30, 1, 30, 'Colmar'),
    (31, 1, 31, 'Vannes'),
    (32, 1, 32, 'Aix-en-Provence'),
    (33, 1, 33, 'Biarritz'),
    (34, 1, 34, 'Grenoble'),
    (35, 1, 35, 'Orléans'),
    (36, 1, 36, 'Avignon'),
    (37, 1, 37, 'Nancy'),
    (38, 1, 38, 'Lorient'),
    (39, 1, 39, 'La Rochelle'),
    (40, 1, 40, 'Sète');
INSERT INTO ADRESSE (IDADRESSE, IDVILLE, LIBELLEADRESSE)
VALUES (1, 1, '1 Rue de l''Épée de Bois'),
    (2, 1, '15 Rue du Général Foy'),
    (3, 1, '27 Rue Navier'),
    (4, 1, '18 Rue Chaligny'),
    (5, 1, '51 Rue Censier'),
    (6, 1, '1 Place Étienne Pernet'),
    (7, 1, '7 Rue du Commerce'),
    (8, 1, '90 Rue Saint-Dominique'),
    (9, 1, '49 Rue de Babylone'),
    (10, 1, '98 Avenue Denfert Rochereau'),
    (11, 2, '17 Rue Antoine Lumière'),
    (12, 2, '8 Rue Rossan'),
    (13, 2, '15 Rue Etienne Dolet'),
    (14, 2, '154 Avenue Thiers'),
    (15, 2, '1 Rue Burdeau'),
    (16, 2, '6 Rue Victor Hugo'),
    (17, 2, '27 Rue Pierre Delore Bis'),
    (18, 2, '12 Rue Wakatsuki'),
    (19, 2, '38 rue de la Charité'),
    (20, 2, '59 rue de la Part-Dieu'),
    (21, 3, '23 rue Saint-Ferréol'),
    (22, 3, '5 boulevard Longchamp'),
    (23, 3, '58 Rue Charles Kaddouz'),
    (24, 3, '34 rue de la Canebière'),
    (25, 3, '7 rue du Panier'),
    (26, 3, '61 Rue Aviateur le Brix'),
    (27, 3, '2 Avenue du Frêne'),
    (28, 3, '390 Chemin du Roucas Blanc'),
    (29, 3, '5 Avenue Edmond Oraison'),
    (30, 3, '31 Rue Vauvenargues'),
    (31, 4, '20 Rue Beyssac'),
    (32, 4, '16 Rue Nérigean'),
    (33, 4, '8 Place Camille Pelletan'),
    (34, 4, '22 Rue de Causserouge'),
    (35, 4, '93 Rue Manon Cormier'),
    (36, 4, '27 Rue Charles Péguy'),
    (37, 4, '9 Rue Alfred Dalancourt'),
    (38, 4, '10 Avenue des 3 Cardinaux'),
    (39, 4, '13 Rue de la Concorde'),
    (40, 4, '6 Rue Bossuet'),
    (41, 5, '58 Chemin du Vallon Sabatier'),
    (
        42,
        5,
        '203 Route de Saint-Pierre de Féric'
    ),
    (43, 5, '144 Chemin de la Costière'),
    (44, 5, '28 Chemin du Haut Magnan'),
    (45, 5, '32 Avenue du Dom. du Piol'),
    (46, 5, '78 Av. du Bois de Cythère'),
    (47, 5, '9 Rue Roger Martin du Gard'),
    (48, 5, '5 Avenue Suzanne Lenglen'),
    (49, 5, '40 Rue Trachel'),
    (
        50,
        5,
        '7 Chemin de la Colline de Magnan'
    ),
    (51, 6, '4 Avenue de la Paix'),
    (52, 6, '34 Rue Massenet'),
    (53, 6, '8 Rue des Reinettes'),
    (54, 6, '36 Rue du Chanoine Poupard'),
    (55, 6, '22 Rue Jean Baptiste Olivaux'),
    (56, 6, '1 Chemin des Noisetiers'),
    (57, 6, '6 Rue de Bellevue Bis'),
    (58, 6, '40 Rue Thomas Maisonneuve'),
    (59, 6, '2 Avenue du Bonheur'),
    (60, 6, '73 Rue de Coulmiers'),
    (61, 7, '701 Av. de Toulouse'),
    (62, 7, '6 Rue Jean Vachet'),
    (
        63,
        7,
        '380 Avenue du Maréchal Leclerc'
    ),
    (64, 7, '15 Rue Sainte-Catherine'),
    (65, 7, '125 Rue de Cante Gril'),
    (66, 7, '6 Rue de la Felouque'),
    (67, 7, '1167 Allée de la Martelle'),
    (68, 7, '1482 Rue de St - Priest'),
    (69, 7, '208 Avenue des Apothicaires'),
    (70, 7, '425 Rue de la Croix de Lavit'),
    (71, 8, '67 Rue de Ribeauvillé'),
    (72, 8, '20 Route de Mittelhausbergen'),
    (73, 8, '31 Rue de Dettwiller'),
    (74, 8, '34 Rue Becquerel'),
    (75, 8, '69 Avenue Molière'),
    (76, 8, '58 Allee des Comtes'),
    (77, 8, '17 Route des Romains'),
    (78, 8, '1 Rue des Bosquets'),
    (79, 8, 'Chemin Raltauweg'),
    (80, 8, 'Rue de la Montagne Verte'),
    (81, 9, '52 Rue Chaudronnerie'),
    (82, 9, '4 Rue des Francs-Bourgeois'),
    (83, 9, '81 Rue Monge'),
    (84, 9, '48 Avenue Garibaldi'),
    (85, 9, '1 Rue Pontus de Tyard'),
    (86, 9, '1 Rue de la Gare'),
    (87, 9, '13 Rue Sainte-Claire Déville'),
    (88, 9, '19 Rue la Fontaine'),
    (89, 9, '9 Rue Racine'),
    (90, 9, '28 Rue Gustave Flaubert'),
    (91, 10, '9 Rue de Condé'),
    (92, 10, '69 Rue Jean de la Fontaine'),
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
    (96, 10, '2 Rue de Beauvau'),
    (97, 10, '32 Rue Berthier'),
    (98, 10, '70 Rue de la Paroisse'),
    (99, 10, '2 Place Charost'),
    (100, 10, '6 Rue des Tournelles'),
    (101, 1, '87 Avenue De Flandre'),
    (102, 1, '5 Rue Du Cinema'),
    (103, 3, '1 Place Ernest Delibes'),
    (104, 11, '80 Rue Carnot'),
    (105, 2, '8 Place De La Croix-Rousse'),
    (106, 11, '5 Rue De LIndustrie'),
    (107, 4, '57 Rue Du Château DEau'),
    (108, 11, '5 Rue De L''Industrie'),
    (109, 3, '11 Avenue de St.''Antoine'),
    (
        110,
        5,
        '39 Avenue Georges Clemenceau'
    ),
    (111, 11, '3bis Avenue De Chevêne'),
    (112, 6, '3 Rue Léon Maître'),
    (
        113,
        7,
        '5 Boulevard De L''Observatoire'
    ),
    (114, 7, '7 Rue De La Loge'),
    (115, 9, '65 Rue du Bourg'),
    (116, 10, '76 Rue de la Paroisse'),
    (117, 10, '7 Rue de Montreuil'),
    (118, 11, '52 Rue Du Pont'),
    (119, 1, '22 Rue De La Sablonnière'),
    (120, 8, 'Rue Des Chevaliers'),
    (121, 11, '20 Rue de la République'),
    (122, 11, '9 Rue de l''Arc en iel'),
    (123, 11, '6 Avenue du Rhône'),
    (124, 11, 'Rue de la gare'),
    (125, 11, 'rue des chasseurs'),
    (126, 11, '10 Place de la Concorde'),
    (127, 11, 'Rue Sommeiller'),
    (128, 11, '2 Rue Jacqueline Auriol'),
    (129, 11, '16 Rue de Lachat'),
    (130, 11, 'Avenue Montaigne'),
    (131, 12, '10 Boulevard Lascrosses'),
    (132, 12, '74 Avenue Jules Julien'),
    (133, 13, '216 Avenue De Dunkerque'),
    (
        134,
        13,
        '8 Place Louise De Bettignies'
    ),
    (135, 14, 'Place Georges Bernanos'),
    (136, 14, '18 Rue De Bertrand'),
    (137, 15, '22 Rue Casimir Perrier'),
    (138, 15, '65 Avenue René Coty'),
    (139, 16, '7 Rue Du Dr Jacquin'),
    (
        140,
        16,
        '49 Boulevard Du Général Leclerc'
    ),
    (141, 17, '5 Rue Du Grand Moulin'),
    (142, 17, '40 Rue Etienne Mimard'),
    (143, 18, '48 Rue Du Port'),
    (144, 18, '2 Place de la République'),
    (145, 19, '4 Rue de la Cathédrale'),
    (146, 19, '5 Rue des Trois Cailloux'),
    (147, 20, '22 Rue de Siam'),
    (148, 20, '3 Rue Jean Jaurès'),
    (149, 21, '30 Avenue de l’Opéra'),
    (150, 21, '19 Rue de l’Horloge'),
    (151, 22, '24 Rue de la République'),
    (152, 22, '7 Place Puget'),
    (153, 23, '9 Rue d’Alsace'),
    (154, 23, '17 Boulevard Foch'),
    (155, 24, '3 Rue de la Madeleine'),
    (156, 24, '10 Avenue Jean Jaurès'),
    (157, 25, '5 Rue des Halles'),
    (158, 25, '28 Rue de la Préfecture'),
    (159, 26, '20 Place Saint-Sauveur'),
    (
        160,
        26,
        '12 Rue des Fossés Saint-Julien'
    ),
    (161, 27, '25 Rue Jean Jaurès'),
    (162, 27, '7 Rue de la République'),
    (
        163,
        28,
        '13 Boulevard Saint-Assiscle'
    ),
    (164, 28, '20 Rue des Albères'),
    (165, 29, '4 Rue de la République'),
    (
        166,
        29,
        '12 Place du Palais de Justice'
    ),
    (167, 30, '5 Rue des Marchands'),
    (168, 30, '9 Rue de la Poissonnerie'),
    (169, 31, '1 Rue des Halles'),
    (170, 31, '20 Avenue des Sables d’Or'),
    (171, 32, '10 Rue des Cordeliers'),
    (172, 32, '22 Boulevard du Roi René'),
    (173, 33, '5 Rue de la Poste'),
    (
        174,
        33,
        '12 Place Georges Clémenceau'
    ),
    (175, 34, '2 Avenue Rhin Et Danube'),
    (176, 34, '25 Avenue de Verdun'),
    (177, 35, '17 Rue Jeanne d’Arc'),
    (178, 35, '3 Rue de la République'),
    (179, 36, '8 Rue des Teinturiers'),
    (180, 36, '10 Place de l’Horloge'),
    (181, 37, '5 Rue Saint-Jean'),
    (182, 37, '23 Rue de la Commanderie'),
    (183, 38, '11 Rue de la Fraternité'),
    (184, 38, '2 Place de la Mairie'),
    (185, 39, '3 Rue des Dames'),
    (186, 39, '9 Quai Valin'),
    (187, 40, '1 Place des Poissonniers'),
    (188, 40, '14 Quai de la Résistance');
INSERT INTO CATEGORIE_PRODUIT (IDCATEGORIE, NOMCATEGORIE)
VALUES (1, 'Alimentation générale'),
    (2, 'Fruits, Légumes et Produits frais'),
    (3, 'Viandes, Poissons et Charcuterie'),
    (4, 'Produits laitiers et Fromages'),
    (5, 'Pâtisseries, Desserts et Glaces'),
    (6, 'Épicerie (salée et sucrée)'),
    (7, 'Plats préparés et Surgelés'),
    (8, 'Snacks, Apéritifs et Confiseries'),
    (9, 'Céréales, Pâtes et Conserves'),
    (10, 'Sauces, Condiments et Épices'),
    (
        11,
        'Boissons (alcoolisées et non alcoolisées)'
    ),
    (
        12,
        'Produits bio et Spécifiques (sans gluten, sans lactose)'
    ),
    (13, 'Hygiène, Beauté et Entretien'),
    (14, 'Produits pour bébés et enfants'),
    (15, 'Produits pour animaux'),
    (16, 'Produits locaux et de luxe'),
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
    (20, 'Compléments alimentaires et Sport');
INSERT INTO PRODUIT (
        IDPRODUIT,
        NOMPRODUIT,
        PRIXPRODUIT,
        IMAGEPRODUIT,
        DESCRIPTION
    )
VALUES (
        1,
        'BIG MAC™',
        14.50,
        'https://tb-static.uber.com/prod/image-proc/processed_images/187f0969e27fd45fb5e70a302aa6ccd6/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
        'Pizza classique avec tomate, mozzarella et basilic frais'
    ),
    (
        2,
        'P''TIT WRAP RANCH',
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
        'McFLURRY™ SAVEUR VANILLE',
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
        'Tropico Tropical',
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
        'Papier toilette',
        4.00,
        'https://tb-static.uber.com/prod/image-proc/processed_images/54290cec7e96e73a88ecf97d5a32c4a4/0e5313be7a8831b8ed60f8dab3c2df10.jpeg',
        'Crêpes flambées avec une sauce à l’orange et au Grand Marnier'
    ),
    (
        35,
        'Kiri',
        2.77,
        'https://tb-static.uber.com/prod/image-proc/processed_images/90b7a5e4812f845a9d8f131052dda3b7/0e5313be7a8831b8ed60f8dab3c2df10.jpeg',
        'Tarte aux pommes caramélisées, servie chaude'
    ),
    (
        36,
        'Viande hachée pur bœuf',
        6.63,
        'https://tb-static.uber.com/prod/image-proc/processed_images/53f823882f73d9ec73513c4930a13ae0/957777de4e8d7439bef56daddbfae227.jpeg',
        'Poulet dans une sauce au curry doux et noix de cajou'
    ),
    (
        37,
        'Brioche Pasquier',
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
        'Jus de pomme',
        2.05,
        'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvMjllYjZiNDEzNDAyMjI2NTliZDI3ODk1MmJjMmVlOTkvYTE5YmIwOTY5MjMxMGRmZDQxZTQ5YTk2YzQyNGIzYTYuanBlZw==',
        'Pizza garnie de saumon fumé, crème fraîche et aneth'
    ),
    (
        60,
        'Barre chocolatée',
        1.30,
        'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvZTVlZWQwMGRkZjQxNjEyYmQ4NzQwOWZjNjljYzA0ZmEvYTE5YmIwOTY5MjMxMGRmZDQxZTQ5YTk2YzQyNGIzYTYuanBlZw==',
        'Soupe de poisson avec des légumes et du pain grillé'
    ),
    (
        61,
        'Fromage Compté aux lait cru',
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
        'Chips au fromage',
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
        'Ben & Jerry s - Crème glacée, vanille, cookie dough',
        10.77,
        'https://cn-geo1.uber.com/image-proc/resize/eats/format=webp/width=550/height=440/quality=70/srcb64=aHR0cHM6Ly90Yi1zdGF0aWMudWJlci5jb20vcHJvZC9pbWFnZS1wcm9jL3Byb2Nlc3NlZF9pbWFnZXMvYTg3MzZkMTFkZDIxYTMyNTI0MzM0ZGQ1M2EwMGQ0YjYvMGU1MzEzYmU3YTg4MzFiOGVkNjBmOGRhYjNjMmRmMTAuanBlZw==',
        'Ragoût végétarien avec des légumes mijotés et épicés'
    ),
    (
        69,
        'Justin Bridou - Saucisson',
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
        'https://www.e3fgroup.com/wp-content/uploads/elementor/thumbs/shutterstock_1012626229_tenders-qll9yuym43phimgd6wm3ijgpcp9zmca9j682ba6aki.jpg',
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
        'Shampooing',
        4.99,
        'https://cdn.auchan.fr/media/P02000000001INYPRIMARY_2048x2048/B2CD/?format=rw&quality=75&width=1200&height=1200',
        'Shampooing nourrissant pour cheveux secs'
    ),
    (
        82,
        'Gel Douche',
        3.50,
        'https://assets.unileversolutions.com/v1/126516823.png?im=AspectCrop=(985,985);Resize=(985,985)',
        'Gel douche hydratant pour une peau douce'
    ),
    (
        83,
        'Papier Aluminium',
        2.99,
        'https://tm.groupetadlaoui.ma/wp-content/uploads/2021/02/papier-aluminium.png',
        'Rouleau de papier aluminium pour usage domestique'
    ),
    (
        84,
        'Brosse à dents',
        2.75,
        'https://cdn.auchan.fr/media/MEDIASTEP73897051_2048x2048/B2CD/?format=rw&quality=75&width=1200&height=1200',
        'Brosse à dents avec brins souples pour un nettoyage en profondeur'
    ),
    (
        85,
        'Savon Liquide',
        3.20,
        'https://www.laino.fr/wp-content/uploads/2017/02/LAI-602086-3616826020862-EP-768x768.png',
        'Savon liquide antibactérien pour les mains'
    ),
    (
        86,
        'Pack de 6 bouteilles d’eau',
        6.00,
        'https://media.carrefour.fr/medias/3b0923f67fac4c95bef51f0ccd8c59f6/p_540x540/03760021251191_H1N1_s16.jpeg',
        'Eau minérale naturelle en pack pratique'
    ),
    (
        87,
        'Lessive',
        12.99,
        'https://prd-cdn-emea1-joltx.pgsitecore.com/-/jssmedia/growing-families-version1/gf-fr/product/ariel/ttupdates0823/ariel-lessive-liquide-original.ashx?rev=1a1f035100f84299805ea1d8ad65b4cb&extension=webp&w=800&h=0&mw=0&mh=0&iar=0&as=0&sc=0',
        'Lessive en poudre pour un linge impeccable'
    ),
    (
        88,
        'Boîte de mouchoirs en papier',
        1.80,
        'http://www.medical-promo.be/wp-content/uploads/2017/03/Boite-de-mouchoir-150-1024x683.png',
        'Mouchoirs en papier doux et résistants'
    ),
    (
        89,
        'Batteries AA',
        6.50,
        'https://i5.walmartimages.com/seo/Duracell-Coppertop-AA-Alkaline-Batteries-4-Batteries-Pack_7318dcf6-a4c9-4711-8507-ef45acae4059.e319d247f716c6c4262b5ff3d4e282c0.jpeg?odnHeight=640&odnWidth=640&odnBg=FFFFFF',
        'Piles longue durée pour appareils électroniques'
    ),
    (
        90,
        'Ampoule LED',
        5.99,
        'https://i5.walmartimages.com/seo/Philips-LED-50-Watt-PAR20-Spotlight-Light-Bulb-Bright-White-40-Degree-Beam-Angle-Spread-Dimmable-E26-Medium-Base-2-Pack_204a8944-e63a-4759-a86f-b0b6809e1ebc.1ae06131f3dcd90d8ff0bbcd1ed38381.jpeg?odnHeight=640&odnWidth=640&odnBg=FFFFFF',
        'Ampoule LED écoénergétique, équivalente à 60W'
    ),
    (
        91,
        'Crème hydratante',
        7.50,
        'https://adn-cosmetik.fr/wp-content/uploads/2022/09/Creme-hydratante-jeunesse-satin-1.jpg',
        'Crème nourrissante pour une peau douce et hydratée'
    ),
    (
        92,
        'Ruban adhésif',
        1.99,
        'https://dxbyzx5id4chj.cloudfront.net/fit-in/400x400/filters:fill(fff)/pub/media/catalog/product/6/1/61925_1_c364.jpg',
        'Ruban adhésif transparent pour usage quotidien'
    ),
    (
        93,
        'Ciseaux',
        8.00,
        'https://m.media-amazon.com/images/I/71BNm6BWTSL._AC_SY879_.jpg',
        'Ciseaux robustes et ergonomiques pour travaux de coupe'
    ),
    (
        94,
        'Pack de 4 cahiers',
        3.25,
        'https://www.maxxidiscount.com/11190-large_default/lot-de-4-cahiers-oxford-color-life-24-x-32-cm-48p-seyes.webp',
        'Cahier grand format avec couverture rigide'
    ),
    (
        95,
        'Pack de 3 éponges',
        2.49,
        'https://homehardwarerimouski.com/upload/art-84551-1-hh-exclusives-4542298-paquet-de-3-eponges-a-recurer-scrun-image-1-full.jpg',
        'Éponges résistantes pour nettoyage en profondeur'
    ),
    (
        96,
        'Spray nettoyant multisurface',
        3.99,
        'https://cdn.aroma-zone.com/d_default_placeholder.png/c_fill,q_auto,f_auto,w_852,ar_626:441/b_none/v1/cf/0xsz2r7o7t3z/78VgcZCBBxLoe5a2Lb9242/e205c93a50dcf47e7d53329f9915aa07/t374690_spray-nettoyant-multi-surfaces_web.jpg',
        'Spray nettoyant pour toutes les surfaces'
    ),
    (
        97,
        'Sac poubelle 50L',
        2.75,
        'https://m.media-amazon.com/images/I/81+LOyWMS2L._AC_SX300_SY300_.jpg',
        'Sacs poubelle résistants avec liens intégrés'
    ),
    (
        98,
        'Coton-tiges',
        1.90,
        'https://www.landi.ch/ImageOriginal/Img/product/069/641/69641_wattenstaebchen-200-stueck_69641_1.jpg?width=400&height=400&mode=pad&bgcolor=fff',
        'Coton-tiges doux et pratiques pour une hygiène quotidienne'
    ),
    (
        99,
        'Thermomètre digital',
        12.90,
        'https://www.pharmacodel.com/8108-large_default/predictor-thermometre-digital-flex.jpg',
        'Thermomètre précis pour mesure rapide de la température'
    ),
    (
        100,
        'Pack de feuilles A4',
        4.99,
        'https://www.nlf-livraria.com/wp-content/uploads/2020/07/3329680175516-1.jpg',
        'Papier pour imprimante et usage bureautique'
    ),
    (
        101,
        'Box Tenders',
        9.90,
        'https://tb-static.uber.com/prod/image-proc/processed_images/243757bc258a9659902fbfca19473936/a19bb09692310dfd41e49a96c424b3a6.jpeg',
        'une Boîte de tendeurs'
    ),
    (
        102,
        'Pepe Burger',
        11.90,
        'https://tb-static.uber.com/prod/image-proc/processed_images/51d010a796dc2314b2182c29f37a2fad/a19bb09692310dfd41e49a96c424b3a6.jpeg',
        'Le classique Pepe Burger'
    ),
    (
        103,
        'The Smoky One',
        12.90,
        'https://tb-static.uber.com/prod/image-proc/processed_images/106ca280cf158327a26a8ad3ef796432/a19bb09692310dfd41e49a96c424b3a6.jpeg',
        'Le burger épicé de la région du Smoky Mountains'
    ),
    (
        104,
        'Pop''s Ice Tea Pêche',
        2.50,
        'https://tb-static.uber.com/prod/image-proc/processed_images/8808adb8358f576848065e9b06f2a87b/a19bb09692310dfd41e49a96c424b3a6.jpeg',
        'Ice Tea Pêche de Fast Good Cuisine'
    ),
    (
        105,
        'Menu UBER EAT MAX',
        20.50,
        'https://tb-static.uber.com/prod/image-proc/processed_images/00ff38aa9102697f2b5a18aff1a87305/5954bcb006b10dbfd0bc160f6370faf3.jpeg',
        'Menu Max spécialisé pour Uber Eats'
    ),
    (
        106,
        'Soupe Miso',
        3.70,
        'https://tb-static.uber.com/prod/image-proc/processed_images/6418861a3042f4fc1af069f743e05b28/5954bcb006b10dbfd0bc160f6370faf3.jpeg',
        'Soupe de miso'
    ),
    (
        107,
        'COLETTE',
        12.90,
        'https://tb-static.uber.com/prod/image-proc/processed_images/c5d1b2e0dd92bc4bd1321b54dce32e71/a19bb09692310dfd41e49a96c424b3a6.jpeg',
        'Le classique de chez Colette'
    ),
    (
        108,
        'GASTON',
        18.90,
        'https://tb-static.uber.com/prod/image-proc/processed_images/cd22200d2e34e2c05dd9273c570d752d/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
        'Le bourrin de Gaston'
    ),
    (
        109,
        'Cookies aux lait et noisettes',
        4.90,
        'https://tb-static.uber.com/prod/image-proc/processed_images/897feeb5c81114843b41fd2cd8187d1c/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
        'Cookies de Tatie Chocolat lait Noisettes'
    ),
    (
        110,
        'Lot de 6 Oeufs',
        5.30,
        'https://www.bonneterre.fr/wp-content/uploads/2020/02/3022936-GROS-OEUFS-X6-1024x845.jpg',
        '6 gros oeufs tout droit sortie du cul de la poule'
    ),
    (
        111,
        'Sandwich kebab',
        8.90,
        'https://tb-static.uber.com/prod/image-proc/processed_images/639da4362ccb93f0c3ce689806d5e683/a19bb09692310dfd41e49a96c424b3a6.jpeg',
        'Palalala Melissa, yak kendini'
    ),
    (
        112,
        'Sandwich Cordon Bleu',
        6.72,
        'https://tb-static.uber.com/prod/image-proc/processed_images/3906e31e8d2a9534841dbafa53ccdcd2/a19bb09692310dfd41e49a96c424b3a6.jpeg',
        'Kordon ble, fromaj, salat, tomat, onyon.'
    ),
    (
        113,
        'Berliner Kebap',
        7.52,
        'https://tb-static.uber.com/prod/image-proc/processed_images/0c18380c4b280583ba0880b5cb0a7e81/a19bb09692310dfd41e49a96c424b3a6.jpeg',
        'Melih bunu yutar aq'
    ),
    (
        114,
        'Otacos Taille L',
        8.32,
        'https://tb-static.uber.com/prod/image-proc/processed_images/068fbd6de495f8ddb5fc3f109bef900b/5954bcb006b10dbfd0bc160f6370faf3.jpeg',
        'unique mais pas trop'
    ),
    (
        115,
        'O''Bowl M',
        10.50,
        'https://tb-static.uber.com/prod/image-proc/processed_images/3ed06c3b13bff8c175f273c93f6e3095/5143f1e218c67c20fe5a4cd33d90b07b.jpeg',
        'pareil, unique mais pas trop'
    ),
    (
        116,
        'Menu chicken family',
        23.90,
        'https://tb-static.uber.com/prod/image-proc/processed_images/929407058f11ade8363de4782ebef433/a19bb09692310dfd41e49a96c424b3a6.jpeg',
        'Chicken fait maison'
    ),
    (
        117,
        'Menu wings',
        13.50,
        'https://tb-static.uber.com/prod/image-proc/processed_images/9b906e9358717168b7950edfbf33dfba/a19bb09692310dfd41e49a96c424b3a6.jpeg',
        'Poulet frais et marinade faite maison.'
    ),
    (
        118,
        'Menu enfant',
        9.50,
        'https://tb-static.uber.com/prod/image-proc/processed_images/e9cf326a13b7f6dacdc29b0e71b0cf49/a19bb09692310dfd41e49a96c424b3a6.jpeg',
        'nous aussi on peut ouu c''est que pour les enfants'
    );
INSERT INTO CARTE_BANCAIRE (
        IDCB,
        NUMEROCB,
        DATEEXPIRECB,
        CRYPTOGRAMME,
        TYPECARTE,
        TYPERESEAUX
    )
VALUES (
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
INSERT INTO ENTREPRISE (
        IDENTREPRISE,
        IDADRESSE,
        SIRETENTREPRISE,
        NOMENTREPRISE,
        TAILLE
    )
VALUES (
        1,
        1,
        '12345678901234',
        'Entreprise A',
        'PME'
    ),
    (
        2,
        2,
        '23456789012345',
        'Entreprise B',
        'ETI'
    ),
    (
        3,
        3,
        '34567890123456',
        'Entreprise C',
        'GE'
    ),
    (
        4,
        4,
        '45678901234567',
        'Entreprise D',
        'PME'
    ),
    (
        5,
        5,
        '56789012345678',
        'Entreprise E',
        'ETI'
    ),
    (
        6,
        6,
        '67890123456789',
        'Entreprise F',
        'GE'
    ),
    (
        7,
        7,
        '78901234567890',
        'Entreprise G',
        'PME'
    ),
    (
        8,
        8,
        '89012345678901',
        'Entreprise H',
        'ETI'
    ),
    (
        9,
        9,
        '90123456789012',
        'Entreprise I',
        'GE'
    ),
    (
        10,
        10,
        '01234567890123',
        'Entreprise J',
        'PME'
    ),
    (
        11,
        11,
        '12345678901234',
        'Entreprise K',
        'ETI'
    ),
    (
        12,
        12,
        '23456789012345',
        'Entreprise L',
        'GE'
    ),
    (
        13,
        13,
        '34567890123456',
        'Entreprise M',
        'PME'
    ),
    (
        14,
        14,
        '45678901234567',
        'Entreprise N',
        'ETI'
    ),
    (
        15,
        15,
        '56789012345678',
        'Entreprise O',
        'GE'
    ),
    (
        16,
        16,
        '67890123456789',
        'Entreprise P',
        'PME'
    ),
    (
        17,
        17,
        '78901234567890',
        'Entreprise Q',
        'ETI'
    ),
    (
        18,
        18,
        '89012345678901',
        'Entreprise R',
        'GE'
    ),
    (
        19,
        19,
        '90123456789012',
        'Entreprise S',
        'PME'
    ),
    (
        20,
        20,
        '01234567890123',
        'Entreprise T',
        'ETI'
    );
INSERT INTO CLIENT (
        IDCLIENT,
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
    )
VALUES (
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
        '',
        TRUE
    ),
    (
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
        '',
        FALSE
    ),
    (
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
        '',
        TRUE
    ),
    (
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
        '',
        FALSE
    ),
    (
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
        '',
        TRUE
    ),
    (
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
        '',
        TRUE
    ),
    (
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
        '',
        FALSE
    ),
    (
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
        '',
        TRUE
    ),
    (
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
        '',
        FALSE
    ),
    (
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
        '',
        TRUE
    ),
    (
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
        '',
        TRUE
    ),
    (
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
        '',
        FALSE
    ),
    (
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
        '',
        TRUE
    ),
    (
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
        '',
        TRUE
    ),
    (
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
        '',
        FALSE
    ),
    (
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
        '',
        TRUE
    ),
    (
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
        '',
        FALSE
    ),
    (
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
        '',
        TRUE
    ),
    (
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
        '',
        FALSE
    ),
    (
        20,
        20,
        6,
        'Madame',
        'Bernard',
        'Sophie',
        '1991-01-14',
        '0612345697',
        'sophie.bernard@example.com',
        'password787',
        '',
        TRUE
    ),
    (
        21,
        NULL,
        1,
        'Monsieur',
        'Gomez',
        'Antoine',
        '1992-08-18',
        '0612345698',
        'antoine.gomez@example.com',
        'password898',
        '',
        TRUE
    ),
    (
        22,
        NULL,
        2,
        'Madame',
        'Lemoine',
        'Isabelle',
        '1994-11-23',
        '0612345699',
        'isabelle.lemoine@example.com',
        'password009',
        '',
        FALSE
    ),
    (
        23,
        NULL,
        3,
        'Monsieur',
        'Dupont',
        'Frédéric',
        '1985-04-12',
        '0612345700',
        'frederic.dupont@example.com',
        'password110',
        '',
        TRUE
    ),
    (
        24,
        NULL,
        4,
        'Madame',
        'Garcia',
        'Laura',
        '1987-06-03',
        '0612345701',
        'laura.garcia@example.com',
        'password221',
        '',
        FALSE
    ),
    (
        25,
        NULL,
        5,
        'Monsieur',
        'Benoit',
        'Eric',
        '1990-09-27',
        '0612345702',
        'eric.benoit@example.com',
        'password332',
        '',
        TRUE
    ),
    (
        26,
        NULL,
        6,
        'Madame',
        'Lemoine',
        'Margaux',
        '1993-01-14',
        '0612345703',
        'margaux.lemoine@example.com',
        'password443',
        '',
        TRUE
    ),
    (
        27,
        NULL,
        7,
        'Monsieur',
        'Dupont',
        'Jacques',
        '1996-05-21',
        '0612345704',
        'jacques.dupont@example.com',
        'password554',
        '',
        FALSE
    ),
    (
        28,
        NULL,
        8,
        'Madame',
        'Bernard',
        'Marion',
        '1999-02-05',
        '0612345705',
        'marion.bernard@example.com',
        'password665',
        '',
        TRUE
    ),
    (
        29,
        NULL,
        9,
        'Monsieur',
        'Durand',
        'Victor',
        '1991-11-30',
        '0612345706',
        'victor.durand@example.com',
        'password776',
        '',
        TRUE
    ),
    (
        30,
        NULL,
        2,
        'Madame',
        'Lemoine',
        'Audrey',
        '1988-07-17',
        '0612345707',
        'audrey.lemoine@example.com',
        'password887',
        '',
        FALSE
    ),
    (
        31,
        NULL,
        1,
        'Monsieur',
        'Gomez',
        'Maxime',
        '1995-03-19',
        '0612345708',
        'maxime.gomez@example.com',
        'password998',
        '',
        TRUE
    ),
    (
        32,
        NULL,
        2,
        'Madame',
        'Martin',
        'Sophie',
        '1990-06-28',
        '0612345709',
        'sophie.m0artin@example.com',
        'password009',
        '',
        TRUE
    ),
    (
        33,
        NULL,
        3,
        'Monsieur',
        'Lemoine',
        'Julien',
        '1992-11-11',
        '0612345710',
        'julien.lemoine@example.com',
        'password110',
        '',
        FALSE
    ),
    (
        34,
        NULL,
        4,
        'Madame',
        'Petit',
        'Amélie',
        '1989-03-15',
        '0612345711',
        'amelie.petit@example.com',
        'password221',
        '',
        TRUE
    ),
    (
        35,
        NULL,
        5,
        'Monsieur',
        'Lemoine',
        'Laurent',
        '1997-06-23',
        '0612345712',
        'laurent.lemoine@example.com',
        'password332',
        '',
        FALSE
    ),
    (
        36,
        NULL,
        6,
        'Madame',
        'Durand',
        'Catherine',
        '1994-10-07',
        '0612345713',
        'catherine.durand@example.com',
        'password443',
        '',
        TRUE
    ),
    (
        37,
        NULL,
        7,
        'Monsieur',
        'Gomez',
        'Lucas',
        '1986-12-19',
        '0612345714',
        'lucas.gomez@example.com',
        'password554',
        '',
        TRUE
    ),
    (
        38,
        NULL,
        8,
        'Madame',
        'Benoit',
        'Amandine',
        '1991-04-01',
        '0612345715',
        'amandine.benoit@example.com',
        'password665',
        '',
        FALSE
    ),
    (
        39,
        NULL,
        9,
        'Monsieur',
        'Garcia',
        'Julien',
        '1993-08-10',
        '0612345716',
        'julien.garcia@example.com',
        'password776',
        '',
        TRUE
    ),
    (
        40,
        NULL,
        3,
        'Madame',
        'Lemoine',
        'Estelle',
        '1996-09-23',
        '0612345717',
        'estelle.lemoine@example.com',
        'password887',
        '',
        FALSE
    ),
    (
        41,
        NULL,
        1,
        'Monsieur',
        'Lemoine',
        'Frederic',
        '1994-05-14',
        '0612345718',
        'frederic.lemoine@example.com',
        'password998',
        '',
        TRUE
    ),
    (
        42,
        NULL,
        2,
        'Madame',
        'Garcia',
        'Céline',
        '1988-01-25',
        '0612345719',
        'celine.garcia@example.com',
        'password009',
        '',
        FALSE
    ),
    (
        43,
        NULL,
        3,
        'Monsieur',
        'Dupont',
        'Victor',
        '1992-12-03',
        '0612345720',
        'victor.dupont@example.com',
        'password110',
        '',
        TRUE
    ),
    (
        44,
        NULL,
        4,
        'Madame',
        'Lemoine',
        'Valérie',
        '1993-11-28',
        '0612345721',
        'valerie.lemoine@example.com',
        'password221',
        '',
        TRUE
    ),
    (
        45,
        NULL,
        5,
        'Monsieur',
        'Benoit',
        'Louis',
        '1987-10-10',
        '0612345722',
        'louis.benoit@example.com',
        'password332',
        '',
        FALSE
    ),
    (
        46,
        NULL,
        6,
        'Madame',
        'Martin',
        'Sophie',
        '1984-08-21',
        '0612345723',
        'sophie.martin@example.com',
        'password443',
        '',
        TRUE
    ),
    (
        47,
        NULL,
        7,
        'Monsieur',
        'Lemoine',
        'Pierre',
        '1990-03-01',
        '0612345724',
        'pierre.lemoine@example.com',
        'password554',
        '',
        TRUE
    ),
    (
        48,
        NULL,
        8,
        'Madame',
        'Bernard',
        'Laure',
        '1991-11-14',
        '0612345725',
        'laure.bernard@example.com',
        'password665',
        '',
        FALSE
    ),
    (
        49,
        NULL,
        9,
        'Monsieur',
        'Garcia',
        'Rafael',
        '1994-06-30',
        '0612345726',
        'rafael.garcia@example.com',
        'password776',
        '',
        TRUE
    ),
    (
        50,
        NULL,
        1,
        'Madame',
        'Lemoine',
        'Solène',
        '1993-12-09',
        '0612345727',
        'solene.lemoine@example.com',
        'password887',
        '',
        TRUE
    );
INSERT INTO APPARTIENT_2 (IDCB, IDCLIENT)
VALUES (1, 1),
    (2, 2),
    (3, 3),
    (4, 4),
    (5, 5),
    (6, 6),
    (7, 7),
    (8, 8),
    (9, 9),
    (10, 10);
INSERT INTO PANIER (IDPANIER, IDCLIENT, PRIX)
VALUES (1, 1, 120.50),
    (2, 2, 85.30),
    (3, 3, 110.75),
    (4, 4, 95.00),
    (5, 5, 85.90),
    (6, 6, 55.20),
    (7, 7, 160.40),
    (8, 8, 28.25),
    (9, 9, 68.60),
    (10, 10, 90.80),
    (11, 11, 72.10),
    (12, 12, 110.00),
    (13, 13, 125.90),
    (14, 14, 72.50),
    (15, 15, 74.60),
    (16, 16, 70.00),
    (17, 17, 90.30),
    (18, 18, 37.70),
    (19, 19, 105.80),
    (20, 20, 87.20),
    (21, 21, 112.60),
    (22, 22, 93.80),
    (23, 23, 105.50),
    (24, 24, 88.90),
    (25, 25, 140.30),
    (26, 26, 125.00),
    (27, 27, 110.40),
    (28, 28, 98.60),
    (29, 29, 130.90),
    (30, 30, 140.75),
    (31, 31, 120.20),
    (32, 32, 85.50),
    (33, 33, 100.80),
    (34, 34, 115.40),
    (35, 35, 98.30),
    (36, 36, 105.00),
    (37, 37, 125.20),
    (38, 38, 110.90),
    (39, 39, 95.50),
    (40, 40, 138.60),
    (41, 41, 116.70),
    (42, 42, 124.30),
    (43, 43, 102.40),
    (44, 44, 130.00),
    (45, 45, 90.20),
    (46, 46, 118.90),
    (47, 47, 85.60),
    (48, 48, 125.50),
    (49, 49, 110.10),
    (50, 50, 98.80);
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
    )
VALUES (
        1,
        1,
        108,
        'Monsieur',
        'Martin',
        'Pierre',
        '1985-06-12',
        '0612345678',
        'pierre.martin@example.com',
        'password123',
        '123456789012',
        'FR7612345678901234567890123',
        '2020-01-01',
        4.5
    ),
    (
        2,
        2,
        124,
        'Monsieur',
        'Dupont',
        'Paul',
        '1990-09-05',
        '0623456789',
        'paul.dupont@example.com',
        'password456',
        '112312312312',
        'FR7623456789012345678901234',
        '2021-03-15',
        4.3
    ),
    (
        3,
        3,
        121,
        'Monsieur',
        'Lemoine',
        'Luc',
        '1992-11-22',
        '0634567890',
        'luc.lemoine@example.com',
        'password789',
        '147856321456',
        'FR7634567890123456789012345',
        '2021-07-10',
        4.7
    ),
    (
        4,
        4,
        130,
        'Monsieur',
        'Lopez',
        'Marc',
        '1988-02-17',
        '0645678901',
        'marc.lopez@example.com',
        'password101',
        '148512314561',
        'FR7645678901234567890123456',
        '2020-09-20',
        4.0
    ),
    (
        5,
        5,
        127,
        'Monsieur',
        'Thomson',
        'David',
        '1987-03-30',
        '0656789012',
        'david.thomson@example.com',
        'password202',
        '112233445566',
        'FR7656789012345678901234567',
        '2019-08-05',
        4.2
    ),
    (
        6,
        6,
        128,
        'Madame',
        'Tinastepe',
        'Feyza',
        '1983-12-14',
        '0667890123',
        'feyza.tinastepe@example.com',
        'password303',
        '112233445567',
        'FR7667890123456789012345678',
        '2022-01-25',
        4.6
    ),
    (
        7,
        7,
        1,
        'Monsieur',
        'Amaral',
        'Nathan',
        '1991-05-20',
        '0678901234',
        'nathan.amaral@example.com',
        'password404',
        '112233445568',
        'FR7678901234567890123456789',
        '2021-12-10',
        4.4
    ),
    (
        8,
        8,
        10,
        'Monsieur',
        'Petit',
        'François',
        '1993-07-09',
        '0689012345',
        'francois.petit@example.com',
        'password505',
        '112233445569',
        'FR7689012345678901234567890',
        '2021-11-05',
        4.1
    ),
    (
        9,
        9,
        20,
        'Monsieur',
        'Girard',
        'Eric',
        '1984-01-29',
        '0690123456',
        'eric.girard@example.com',
        'password606',
        '112233445570',
        'FR7690123456789012345678901',
        '2020-04-18',
        4.8
    ),
    (
        10,
        10,
        30,
        'Monsieur',
        'Faure',
        'Pierre',
        '1986-10-21',
        '0701234567',
        'pierre.faure@example.com',
        'password707',
        '112233445571',
        'FR7701234567890123456789012',
        '2021-09-12',
        4.3
    ),
    (
        11,
        11,
        40,
        'Monsieur',
        'Cetinkaya',
        'Melih',
        '1995-01-12',
        '0712345678',
        'melih.cetinkaya@example.com',
        'password808',
        '112233445572',
        'FR7712345678901234567890123',
        '2020-06-02',
        4.6
    ),
    (
        12,
        12,
        50,
        'Monsieur',
        'Bekhouche',
        'Amir',
        '1989-03-18',
        '0723456789',
        'amir.bekhouche@example.com',
        'password909',
        '112233445573',
        'FR7723456789012345678901234',
        '2020-11-23',
        4.2
    ),
    (
        13,
        13,
        60,
        'Monsieur',
        'Lemoine',
        'Henri',
        '1994-07-25',
        '0734567890',
        'henri.lemoine@example.com',
        'password010',
        '112233445574',
        'FR7734567890123456789012345',
        '2021-01-30',
        4.7
    ),
    (
        14,
        14,
        70,
        'Monsieur',
        'Robert',
        'Maxime',
        '1981-09-03',
        '0745678901',
        'maxime.robert@example.com',
        'password111',
        '112233445575',
        'FR7745678901234567890123456',
        '2022-05-16',
        4.0
    ),
    (
        15,
        15,
        80,
        'Monsieur',
        'Giraud',
        'Samuel',
        '1986-04-14',
        '0756789012',
        'samuel.giraud@example.com',
        'password222',
        '112233445576',
        'FR7756789012345678901234567',
        '2021-07-05',
        4.8
    ),
    (
        16,
        16,
        90,
        'Monsieur',
        'Marchand',
        'Thierry',
        '1988-12-28',
        '0767890123',
        'thierry.marchand@example.com',
        'password333',
        '112233445577',
        'FR7767890123456789012345678',
        '2021-10-17',
        4.1
    ),
    (
        17,
        17,
        100,
        'Monsieur',
        'Duval',
        'Olivier',
        '1992-08-22',
        '0778901234',
        'olivier.duval@example.com',
        'password444',
        '112233445578',
        'FR7778901234567890123456789',
        '2022-02-05',
        4.3
    ),
    (
        18,
        18,
        110,
        'Monsieur',
        'Perrot',
        'Michel',
        '1987-06-10',
        '0789012345',
        'michel.perrot@example.com',
        'password555',
        '112233445579',
        'FR7789012345678901234567890',
        '2021-09-30',
        4.4
    ),
    (
        19,
        19,
        120,
        'Monsieur',
        'Martin',
        'Jacques',
        '1990-10-15',
        '0790123456',
        'jacques.martin@example.com',
        'password666',
        '112233445580',
        'FR7790123456789012345678901',
        '2021-11-20',
        4.5
    ),
    (
        20,
        20,
        130,
        'Monsieur',
        'Leroy',
        'Pierre',
        '1982-12-08',
        '0701234567',
        'pierre.leroy@example.com',
        'password777',
        '112233445581',
        'FR7801234567890123456789012',
        '2021-05-22',
        4.6
    ),
    (
        21,
        1,
        2,
        'Monsieur',
        'Fournier',
        'Julien',
        '1989-11-03',
        '0712345678',
        'julien.fournier@example.com',
        'password888',
        '112233445582',
        'FR7812345678901234567890123',
        '2020-01-17',
        4.2
    ),
    (
        22,
        2,
        11,
        'Monsieur',
        'Hebert',
        'Alain',
        '1985-04-06',
        '0623456789',
        'alain.hebert@example.com',
        'password999',
        '112233445583',
        'FR7823456789012345678901234',
        '2021-12-15',
        4.3
    ),
    (
        23,
        3,
        21,
        'Monsieur',
        'Lemoine',
        'Vincent',
        '1991-05-12',
        '0734567890',
        'vincent.lemoine@example.com',
        'password000',
        '112233445584',
        'FR7834567890123456789012345',
        '2022-03-01',
        4.7
    ),
    (
        24,
        4,
        41,
        'Monsieur',
        'Robert',
        'Louis',
        '1986-10-14',
        '0745678901',
        'louis.robert@example.com',
        'password111',
        '112233445585',
        'FR7845678901234567890123456',
        '2022-08-04',
        4.5
    ),
    (
        25,
        5,
        51,
        'Monsieur',
        'Perrin',
        'Claude',
        '1988-01-22',
        '0656789012',
        'claude.perrin@example.com',
        'password222',
        '112233445586',
        'FR7856789012345678901234567',
        '2021-11-10',
        4.4
    ),
    (
        26,
        6,
        61,
        'Monsieur',
        'Leclerc',
        'Gérard',
        '1993-05-30',
        '0767890123',
        'gerard.leclerc@example.com',
        'password333',
        '112233445587',
        'FR7867890123456789012345678',
        '2022-01-15',
        4.1
    ),
    (
        27,
        7,
        71,
        'Monsieur',
        'Hamon',
        'Antoine',
        '1990-02-18',
        '0678901234',
        'antoine.hamon@example.com',
        'password444',
        '112233445588',
        'FR7878901234567890123456789',
        '2021-09-07',
        4.6
    ),
    (
        28,
        8,
        81,
        'Monsieur',
        'Faure',
        'François',
        '1982-11-29',
        '0789012345',
        'francois.faure@example.com',
        'password555',
        '112233445589',
        'FR7889012345678901234567890',
        '2022-02-25',
        4.2
    ),
    (
        29,
        9,
        91,
        'Monsieur',
        'Vidal',
        'Bruno',
        '1994-04-21',
        '0790123456',
        'bruno.vidal@example.com',
        'password666',
        '112233445590',
        'FR7890123456789012345678901',
        '2021-10-13',
        4.7
    ),
    (
        30,
        10,
        101,
        'Monsieur',
        'Gauthier',
        'Denis',
        '1987-02-09',
        '0701234567',
        'denis.gauthier@example.com',
        'password777',
        '112233445591',
        'FR7901234567890123456789012',
        '2021-08-30',
        4.3
    );
INSERT INTO ENTRETIEN (
        IDENTRETIEN,
        IDCOURSIER,
        DATEENTRETIEN,
        STATUS,
        RESULTAT
    )
VALUES (1, 1, NULL, 'En attente', NULL),
    (2, 2, '2023-12-02 11:00:00', 'Plannifié', NULL),
    (
        3,
        3,
        '2023-12-03 14:30:00',
        'Terminée',
        'Retenu'
    ),
    (4, 4, '2023-12-04 09:00:00', 'Annulée', NULL),
    (5, 5, NULL, 'En attente', NULL),
    (6, 6, '2023-12-06 15:00:00', 'Plannifié', NULL),
    (
        7,
        7,
        '2023-12-07 16:30:00',
        'Terminée',
        'Rejeté'
    ),
    (8, 8, NULL, 'En attente', NULL),
    (9, 9, NULL, 'En attente', NULL),
    (10, 10, '2023-12-10 11:00:00', 'Plannifié', NULL),
    (
        11,
        11,
        '2023-12-11 14:30:00',
        'Terminée',
        'Retenu'
    ),
    (12, 12, '2023-12-12 16:00:00', 'Annulée', NULL),
    (13, 13, '2023-12-13 09:30:00', 'Plannifié', NULL),
    (
        14,
        14,
        '2023-12-14 15:00:00',
        'Terminée',
        'Retenu'
    ),
    (15, 15, NULL, 'En attente', NULL),
    (16, 16, NULL, 'En attente', NULL),
    (17, 17, '2023-12-17 10:00:00', 'Plannifié', NULL),
    (
        18,
        18,
        '2023-12-18 15:30:00',
        'Terminée',
        'Rejeté'
    ),
    (19, 19, '2023-12-19 14:00:00', 'Annulée', NULL),
    (20, 20, '2023-12-20 09:00:00', 'Plannifié', NULL),
    (21, 21, NULL, 'En attente', NULL),
    (
        22,
        22,
        '2023-12-22 11:00:00',
        'Terminée',
        'Retenu'
    ),
    (23, 23, '2023-12-23 16:00:00', 'Plannifié', NULL),
    (24, 24, NULL, 'En attente', NULL),
    (25, 25, '2023-12-25 10:30:00', 'Plannifié', NULL),
    (
        26,
        26,
        '2023-12-26 15:00:00',
        'Terminée',
        NULL
    ),
    (27, 27, '2023-12-27 12:00:00', 'Annulée', NULL),
    (28, 28, '2023-12-28 09:30:00', 'Plannifié', NULL),
    (
        29,
        29,
        '2023-12-29 14:00:00',
        'Terminée',
        'Retenu'
    ),
    (30, 30, NULL, 'En attente', NULL);
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
    )
VALUES (
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
INSERT INTO CATEGORIE_PRESTATION (
        IDCATEGORIEPRESTATION,
        LIBELLECATEGORIEPRESTATION,
        DESCRIPTIONCATEGORIEPRESTATION,
        IMAGECATEGORIEPRESTATION
    )
VALUES (
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
    )
VALUES (
        1,
        'UberX',
        'Un voyage standard et économique, parfait pour les trajets quotidiens et les déplacements en ville.',
        'UberX.jpg'
    ),
    (
        2,
        'UberXL',
        'Un service similaire à UberX, mais avec plus d’espace pour accueillir confortablement jusqu’à six passagers et leurs bagages.',
        'UberXL.jpg'
    ),
    (
        3,
        'UberVan',
        'Des chauffeurs professionnels vous transportent dans des vans spacieux, idéaux pour les groupes ou les grandes familles.',
        'UberVan.jpg'
    ),
    (
        4,
        'Confort',
        'Voyagez dans des véhicules modernes et bien équipés pour une expérience de transport plus agréable et relaxante.',
        'Comfort.jpg'
    ),
    (
        5,
        'Green',
        'Optez pour un trajet respectueux de l’environnement avec des véhicules hybrides ou électriques, tout en réduisant votre empreinte carbone.',
        'Green.jpg'
    ),
    (
        6,
        'UberPet',
        'Un service conçu pour les amoureux des animaux, permettant de voyager avec vos compagnons à quatre pattes dans le confort.',
        'UberPet.jpg'
    ),
    (
        7,
        'Berline',
        'Pour des déplacements professionnels ou des occasions spéciales, choisissez une berline élégante et confortable, offrant une expérience de luxe.',
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
    )
VALUES (
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
INSERT INTO A_COMME_TYPE (IDVEHICULE, IDPRESTATION)
VALUES (1, 7),
    (2, 1),
    (3, 4),
    (4, 4),
    (5, 5),
    (6, 6),
    (7, 7),
    (8, 1),
    (9, 2),
    (10, 2),
    (11, 3),
    (12, 2),
    (13, 1),
    (14, 5),
    (15, 4),
    (16, 6),
    (17, 7),
    (18, 1),
    (19, 7),
    (20, 5),
    (21, 3),
    (22, 4),
    (23, 6),
    (24, 2),
    (25, 7),
    (26, 7),
    (27, 2),
    (28, 6),
    (29, 6),
    (30, 1);
INSERT INTO VELO (
        IDVELO,
        IDADRESSE,
        NUMEROVELO,
        ESTDISPONIBLE
    )
VALUES (1, 1, '12345', TRUE),
    (2, 4, '12346', FALSE),
    (3, 18, '12347', TRUE),
    (4, 84, '12348', TRUE),
    (5, 46, '12349', FALSE),
    (6, 18, '12350', TRUE),
    (7, 69, '12351', TRUE),
    (8, 33, '12352', FALSE),
    (9, 71, '12353', TRUE),
    (10, 99, '12354', TRUE);
INSERT INTO ETABLISSEMENT (
        IDETABLISSEMENT,
        TYPEETABLISSEMENT,
        IDADRESSE,
        NOMETABLISSEMENT,
        DESCRIPTION,
        IMAGEETABLISSEMENT,
        LIVRAISON,
        AEMPORTER
    )
VALUES (
        1,
        'Restaurant',
        101,
        'McDonald''s Paris',
        'Chaîne de restauration rapide mondialement connue offrant burgers, frites et boissons gazeuses.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/2fbf5a0b7a62e385368c58d3cda5420b/30be7d11a3ed6f6183354d1933fbb6c7.jpeg',
        TRUE,
        FALSE
    ),
    (
        2,
        'Restaurant',
        102,
        'Waffle Factory',
        'Spécialisé dans les gaufres sucrées et salées préparées à la demande.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/66ba7e9963cfdbe3126a79bd39dcf405/fb86662148be855d931b37d6c1e5fcbe.jpeg',
        FALSE,
        TRUE
    ),
    (
        3,
        'Épicerie',
        103,
        'PAUL',
        'Boulangerie française traditionnelle offrant pains artisanaux, pâtisseries et snacks.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/923496b75901cda79df0e9f8676a63ef/c73ecc27d2a9eaa735b1ee95304ba588.jpeg',
        TRUE,
        TRUE
    ),
    (
        4,
        'Restaurant',
        104,
        'El Chaltén',
        'Restaurant spécialisé dans la cuisine argentine avec des saveurs authentiques.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/39c402fdde3d6556e82959a6a1875629/783282f6131ef2258e5bcd87c46aa87e.jpeg',
        FALSE,
        TRUE
    ),
    (
        5,
        'Restaurant',
        105,
        'Burger King',
        'Chaîne de restauration rapide connue pour ses burgers emblématiques comme le Whopper.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/7ddafa6aaf2de203eb347fecc6104779/30be7d11a3ed6f6183354d1933fbb6c7.jpeg',
        TRUE,
        FALSE
    ),
    (
        6,
        'Restaurant',
        106,
        'Street Pasta',
        'Propose des pâtes fraîches et des sauces maison dans un cadre moderne.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/73557525500196231ccc1aa93616ed6f/3ac2b39ad528f8c8c5dc77c59abb683d.jpeg',
        FALSE,
        TRUE
    ),
    (
        7,
        'Restaurant',
        107,
        'Black And White Burger',
        'Restaurant spécialisé dans les burgers gourmets avec des ingrédients de qualité.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/73b96ac84870d9f13a58811443662dc0/c9252e6c6cd289c588c3381bc77b1dfc.jpeg',
        TRUE,
        FALSE
    ),
    (
        8,
        'Restaurant',
        108,
        'Ben''s Food',
        'Cuisine rapide et savoureuse avec un menu varié comprenant des options végétariennes.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/2587cc6c5b933f5d9bc5064249bbe575/30be7d11a3ed6f6183354d1933fbb6c7.jpeg',
        TRUE,
        TRUE
    ),
    (
        9,
        'Épicerie',
        109,
        'Carrefour',
        'Supermarché proposant une large gamme de produits alimentaires et ménagers.',
        'https://logowik.com/content/uploads/images/210_carrefour.jpg',
        TRUE,
        FALSE
    ),
    (
        10,
        'Restaurant',
        110,
        'Fat Kebab',
        'Spécialisé dans les kebabs généreux et faits maison avec des sauces uniques.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/c570cbbeec2420d1ccdeb75ac2d58231/c9252e6c6cd289c588c3381bc77b1dfc.jpeg',
        TRUE,
        FALSE
    ),
    (
        11,
        'Restaurant',
        111,
        'Island Bowls',
        'Restaurant proposant des bowls sains et équilibrés inspirés de la cuisine hawaïenne.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/57efbcd5e197184d730502035e58bd84/fb86662148be855d931b37d6c1e5fcbe.jpeg',
        FALSE,
        TRUE
    ),
    (
        12,
        'Restaurant',
        112,
        'Pitaya',
        'Cuisine thaïlandaise authentique servie dans une ambiance moderne et conviviale.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/40ecc6916fbbd0cd7287694a97bd1d90/3ac2b39ad528f8c8c5dc77c59abb683d.jpeg',
        TRUE,
        FALSE
    ),
    (
        13,
        'Épicerie',
        113,
        'La Mie Câline',
        'Boulangerie et pâtisserie offrant des produits frais, viennoiseries et sandwichs.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/6f02c35928e2bd90b78a8091edb5976f/fb86662148be855d931b37d6c1e5fcbe.jpeg',
        TRUE,
        TRUE
    ),
    (
        14,
        'Épicerie',
        114,
        'Brioche Dorée',
        'Boulangerie café offrant viennoiseries, sandwichs et menus rapides pour emporter.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/23b865a8185355fdedf2ab992ec28c72/c9252e6c6cd289c588c3381bc77b1dfc.jpeg',
        TRUE,
        FALSE
    ),
    (
        15,
        'Épicerie',
        115,
        'Franprix',
        'Magasin de proximité proposant produits alimentaires et ménagers à prix abordables.',
        'https://www.groupe-asten.fr/wp-content/uploads/2022/07/franprix-1000.png',
        TRUE,
        FALSE
    ),
    (
        16,
        'Épicerie',
        116,
        'Picard',
        'Spécialisé dans les produits surgelés de qualité pour des repas rapides et savoureux.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/a5b4df5effe99c1e6459b51137f07938/029e6f4e0c81c14572126109dfe867f3.png',
        TRUE,
        TRUE
    ),
    (
        17,
        'Épicerie',
        117,
        'Vival',
        'Épicerie de quartier avec une sélection variée de produits alimentaires de base.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/7148418810e489efe27be264ba3694ec/a70f5c9df440d10213e93244e9eb7cad.jpeg',
        FALSE,
        TRUE
    ),
    (
        18,
        'Restaurant',
        118,
        'Instant Rétro',
        'Restaurant au style rétro proposant des plats classiques revisités.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/4be8ea88c2b77663ce2d387b2a7924aa/30be7d11a3ed6f6183354d1933fbb6c7.jpeg',
        TRUE,
        FALSE
    ),
    (
        19,
        'Restaurant',
        119,
        'Chicken HOT',
        'Spécialisé dans le poulet épicé et les accompagnements savoureux.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/dcdd6f59af11b38bfaba9961f80fa0ec/cc592037c936600295e9961933037e19.jpeg',
        FALSE,
        TRUE
    ),
    (
        20,
        'Restaurant',
        120,
        'Subway',
        'Chaîne internationale proposant des sandwichs personnalisés avec des ingrédients frais.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/6fc5ab9e3adaa71256b0ef5d6619159f/3ac2b39ad528f8c8c5dc77c59abb683d.jpeg',
        TRUE,
        TRUE
    ),
    (
        21,
        'Restaurant',
        130,
        'McDonald''s Annecy',
        'Restaurant rapide offrant burgers, frites et menus pour petits et grands.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/2fbf5a0b7a62e385368c58d3cda5420b/30be7d11a3ed6f6183354d1933fbb6c7.jpeg',
        TRUE,
        FALSE
    ),
    (
        22,
        'Épicerie',
        132,
        'Le Petit Casino',
        'Magasin de proximité proposant produits alimentaires et ménagers à prix abordables.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/3a2a6d2c3a233e71731d166c5c907afd/9b3aae4cf90f897799a5ed357d60e09d.jpeg',
        TRUE,
        FALSE
    ),
    (
        23,
        'Restaurant',
        132,
        'Eat Sushi - Compans',
        'Restaurant réputé pour ses plats japonais authentiques et ses créations originales.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/991cb9e636550e8431809e82bca2cf3d/9e31c708e4cf73b6e3ea1bd4a9b6e16b.jpeg',
        FALSE,
        TRUE
    ),
    (
        24,
        'Restaurant',
        133,
        'Pepe Chicken',
        'Une expérience culinaire Halal dans la vibrante ville de Lille.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/3f0c1db6cb87d1b00e94d29c79ff6efb/3ac2b39ad528f8c8c5dc77c59abb683d.jpeg',
        TRUE,
        FALSE
    ),
    (
        25,
        'Épicerie',
        134,
        'Intermarché',
        'Magasin proposant produits alimentaires et ménagers à prix abordables. Tah la lutte contre la vie cher.',
        'https://tse1.mm.bing.net/th?id=OIP.gj4ezEs_JEw1Xum51IbiaAHaEJ&pid=Api&P=0&h=360',
        TRUE,
        TRUE
    ),
    (
        26,
        'Épicerie',
        135,
        'Super U',
        'Magasin proposant produits alimentaires et ménagers à prix abordables.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/9e86653f2d760320b7bfe7d16fa82439/e00617ce8176680d1c4c1a6fb65963e2.png',
        FALSE,
        TRUE
    ),
    (
        27,
        'Restaurant',
        136,
        'Les Burgers de Colette',
        'Restaurant apprécié pour ses burgers de qualité.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/75d3515e5ff7edfcf05258ada26b3922/9e31c708e4cf73b6e3ea1bd4a9b6e16b.jpeg',
        TRUE,
        FALSE
    ),
    (
        28,
        'Épicerie',
        137,
        'Monoprix',
        'Magasin proposant produits alimentaires et ménagers à prix abordables.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/224b86f25c53a5ebb074c1a3f8040df1/e00617ce8176680d1c4c1a6fb65963e2.png',
        FALSE,
        TRUE
    ),
    (
        29,
        'Restaurant',
        138,
        'McDonald''s Le Havre',
        'Restaurant rapide offrant burgers, frites et menus pour petits et grands.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/8849186db50c90063596a8af39664c94/30be7d11a3ed6f6183354d1933fbb6c7.jpeg',
        TRUE,
        TRUE
    ),
    (
        30,
        'Épicerie',
        139,
        'Monop’',
        'Magasin proposant produits alimentaires et ménagers à prix abordables.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/b9ebfa5480bf99aa56f7afec5c302fb1/e00617ce8176680d1c4c1a6fb65963e2.png',
        TRUE,
        FALSE
    ),
    (
        31,
        'Restaurant',
        140,
        'L’Istanbul',
        'Gel abi, gel abla, yemeye geeeeel',
        'https://tb-static.uber.com/prod/image-proc/processed_images/993eb2938e9af3bdd57c87c31f89fdad/16bb0a3ab8ea98cfe8906135767f7bf4.jpeg',
        TRUE,
        TRUE
    ),
    (
        32,
        'Épicerie',
        141,
        'Picard',
        'Spécialisé dans les produits surgelés de qualité pour des repas rapides et savoureux.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/a5b4df5effe99c1e6459b51137f07938/029e6f4e0c81c14572126109dfe867f3.png',
        FALSE,
        TRUE
    ),
    (
        33,
        'Restaurant',
        142,
        'O’Tacos',
        'Une expérience culinaire unique avec une touche française.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/6479fc4485d81ab15424048d665e8c79/30be7d11a3ed6f6183354d1933fbb6c7.jpeg',
        TRUE,
        TRUE
    ),
    (
        34,
        'Épicerie',
        143,
        'Carrefour',
        'Supermarché proposant une large gamme de produits alimentaires et ménagers.',
        'https://logowik.com/content/uploads/images/210_carrefour.jpg',
        FALSE,
        TRUE
    ),
    (
        35,
        'Restaurant',
        175,
        'Chicken Alpes',
        'Un établissement spécialisé dans la cuisine de type "Pollo" de los Pollos Chickanos.',
        'https://tb-static.uber.com/prod/image-proc/processed_images/4fcd2c0217a719acc80ee4b9c2e6d2fd/fb86662148be855d931b37d6c1e5fcbe.jpeg',
        TRUE,
        FALSE
    );
INSERT INTO EST_SITUE_A_2 (IDPRODUIT, IDETABLISSEMENT)
VALUES (1, 1),
    (2, 1),
    (3, 1),
    (4, 1),
    (5, 2),
    (6, 2),
    (7, 6),
    (8, 8),
    (9, 3),
    (10, 3),
    (11, 3),
    (12, 3),
    (13, 4),
    (14, 4),
    (15, 11),
    (16, 15),
    (17, 5),
    (18, 5),
    (19, 5),
    (20, 5),
    (21, 6),
    (22, 6),
    (23, 6),
    (24, 6),
    (25, 7),
    (26, 7),
    (27, 7),
    (28, 7),
    (29, 7),
    (30, 6),
    (31, 20),
    (32, 6),
    (33, 22),
    (34, 25),
    (35, 15),
    (36, 25),
    (37, 17),
    (38, 10),
    (39, 10),
    (40, 10),
    (41, 10),
    (42, 18),
    (43, 11),
    (44, 12),
    (45, 11),
    (46, 11),
    (47, 12),
    (48, 12),
    (49, 13),
    (50, 13),
    (51, 14),
    (52, 14),
    (53, 14),
    (54, 14),
    (55, 14),
    (56, 14),
    (57, 13),
    (58, 14),
    (59, 26),
    (60, 22),
    (61, 15),
    (62, 16),
    (63, 16),
    (64, 15),
    (65, 17),
    (66, 17),
    (67, 17),
    (68, 16),
    (69, 15),
    (70, 4),
    (71, 18),
    (72, 18),
    (73, 20),
    (74, 20),
    (75, 20),
    (76, 20),
    (77, 20),
    (78, 20),
    (79, 19),
    (80, 19),
    (81, 28),
    (82, 9),
    (83, 34),
    (84, 34),
    (85, 34),
    (86, 9),
    (87, 34),
    (88, 9),
    (89, 30),
    (90, 30),
    (91, 9),
    (92, 30),
    (93, 17),
    (94, 30),
    (95, 17),
    (96, 9),
    (97, 17),
    (98, 26),
    (99, 17),
    (100, 22),
    (1, 21),
    (2, 21),
    (3, 21),
    (4, 21),
    (62, 32),
    (63, 32),
    (68, 32),
    (1, 29),
    (2, 29),
    (3, 29),
    (4, 29),
    (101, 24),
    (102, 24),
    (103, 24),
    (104, 24),
    (105, 23),
    (106, 23),
    (107, 27),
    (108, 27),
    (109, 27),
    (110, 28),
    (111, 31),
    (112, 31),
    (113, 31),
    (114, 33),
    (115, 33),
    (116, 35),
    (117, 35),
    (118, 35);
INSERT INTO A_COMME_CATEGORIE (IDETABLISSEMENT, IDCATEGORIEPRESTATION)
VALUES (1, 8),
    (1, 34),
    (1, 7),
    (2, 7),
    (3, 16),
    (3, 21),
    (4, 41),
    (4, 21),
    (5, 8),
    (5, 7),
    (6, 22),
    (7, 8),
    (7, 7),
    (8, 41),
    (8, 21),
    (9, 6),
    (9, 36),
    (10, 7),
    (10, 21),
    (10, 4),
    (11, 41),
    (11, 7),
    (12, 7),
    (12, 11),
    (13, 6),
    (13, 16),
    (14, 6),
    (14, 36),
    (15, 6),
    (15, 36),
    (16, 6),
    (16, 36),
    (17, 16),
    (17, 36),
    (18, 41),
    (18, 40),
    (18, 24),
    (19, 41),
    (19, 7),
    (19, 24),
    (20, 7),
    (20, 21),
    (20, 6),
    (21, 7),
    (21, 8),
    (21, 24),
    (22, 6),
    (22, 16),
    (22, 25),
    (23, 4),
    (23, 7),
    (23, 30),
    (24, 2),
    (24, 7),
    (24, 40),
    (25, 6),
    (25, 36),
    (25, 20),
    (26, 6),
    (26, 7),
    (26, 24),
    (27, 8),
    (27, 7),
    (27, 23),
    (28, 6),
    (28, 36),
    (28, 24),
    (29, 7),
    (29, 8),
    (29, 24),
    (30, 6),
    (30, 25),
    (30, 24),
    (31, 2),
    (31, 7),
    (31, 24),
    (32, 6),
    (32, 36),
    (32, 30),
    (33, 7),
    (33, 21),
    (33, 20),
    (34, 6),
    (34, 36),
    (34, 23),
    (35, 15),
    (35, 7),
    (35, 24);
INSERT INTO HORAIRES_ETABLISSEMENT (
        IDHORAIRES_ETABLISSEMENT,
        JOURSEMAINE,
        HORAIRESOUVERTURE,
        HORAIRESFERMETURE
    )
VALUES (1, 'Lundi', '09:00:00+01', '18:00:00+01'),
    (2, 'Mardi', '09:00:00+01', '18:00:00+01'),
    (3, 'Mercredi', '09:00:00+01', '18:00:00+01'),
    (4, 'Jeudi', '09:00:00+01', '18:00:00+01'),
    (5, 'Vendredi', '09:00:00+01', '18:00:00+01'),
    (6, 'Samedi', '10:00:00+01', '16:00:00+01'),
    (7, 'Dimanche', NULL, NULL);
INSERT INTO A_COMME_HORAIRES (IDHORAIRES_ETABLISSEMENT, IDETABLISSEMENT)
VALUES (1, 1),
    (2, 1),
    (3, 1),
    (4, 1),
    (5, 1),
    (6, 1),
    (7, 1),
    (1, 2),
    (2, 2),
    (3, 2),
    (4, 2),
    (5, 2),
    (6, 2),
    (7, 2),
    (1, 3),
    (2, 3),
    (3, 3),
    (4, 3),
    (5, 3),
    (6, 3),
    (7, 3),
    (1, 4),
    (2, 4),
    (3, 4),
    (4, 4),
    (5, 4),
    (6, 4),
    (7, 4),
    (1, 5),
    (2, 5),
    (3, 5),
    (4, 5),
    (5, 5),
    (6, 5),
    (7, 5),
    (1, 6),
    (2, 6),
    (3, 6),
    (4, 6),
    (5, 6),
    (6, 6),
    (7, 6),
    (1, 7),
    (2, 7),
    (3, 7),
    (4, 7),
    (5, 7),
    (6, 7),
    (7, 7),
    (1, 8),
    (2, 8),
    (3, 8),
    (4, 8),
    (5, 8),
    (6, 8),
    (7, 8),
    (1, 9),
    (2, 9),
    (3, 9),
    (4, 9),
    (5, 9),
    (6, 9),
    (7, 9),
    (1, 10),
    (2, 10),
    (3, 10),
    (4, 10),
    (5, 10),
    (6, 10),
    (7, 10),
    (1, 11),
    (2, 11),
    (3, 11),
    (4, 11),
    (5, 11),
    (6, 11),
    (7, 11),
    (1, 12),
    (2, 12),
    (3, 12),
    (4, 12),
    (5, 12),
    (6, 12),
    (7, 12),
    (1, 13),
    (2, 13),
    (3, 13),
    (4, 13),
    (5, 13),
    (6, 13),
    (7, 13),
    (1, 14),
    (2, 14),
    (3, 14),
    (4, 14),
    (5, 14),
    (6, 14),
    (7, 14),
    (1, 15),
    (2, 15),
    (3, 15),
    (4, 15),
    (5, 15),
    (6, 15),
    (7, 15),
    (1, 16),
    (2, 16),
    (3, 16),
    (4, 16),
    (5, 16),
    (6, 16),
    (7, 16),
    (1, 17),
    (2, 17),
    (3, 17),
    (4, 17),
    (5, 17),
    (6, 17),
    (7, 17),
    (1, 18),
    (2, 18),
    (3, 18),
    (4, 18),
    (5, 18),
    (6, 18),
    (7, 18),
    (1, 19),
    (2, 19),
    (3, 19),
    (4, 19),
    (5, 19),
    (6, 19),
    (7, 19),
    (1, 20),
    (2, 20),
    (3, 20),
    (4, 20),
    (5, 20),
    (6, 20),
    (7, 20),
    (1, 21),
    (2, 21),
    (3, 21),
    (4, 21),
    (5, 21),
    (6, 21),
    (7, 21),
    (1, 22),
    (2, 22),
    (3, 22),
    (4, 22),
    (5, 22),
    (6, 22),
    (7, 22),
    (1, 23),
    (2, 23),
    (3, 23),
    (4, 23),
    (5, 23),
    (6, 23),
    (7, 23),
    (1, 24),
    (2, 24),
    (3, 24),
    (4, 24),
    (5, 24),
    (6, 24),
    (7, 24),
    (1, 25),
    (2, 25),
    (3, 25),
    (4, 25),
    (5, 25),
    (6, 25),
    (7, 25),
    (1, 26),
    (2, 26),
    (3, 26),
    (4, 26),
    (5, 26),
    (6, 26),
    (7, 26),
    (1, 27),
    (2, 27),
    (3, 27),
    (4, 27),
    (5, 27),
    (6, 27),
    (7, 27),
    (1, 28),
    (2, 28),
    (3, 28),
    (4, 28),
    (5, 28),
    (6, 28),
    (7, 28),
    (1, 29),
    (2, 29),
    (3, 29),
    (4, 29),
    (5, 29),
    (6, 29),
    (7, 29),
    (1, 30),
    (2, 30),
    (3, 30),
    (4, 30),
    (5, 30),
    (6, 30),
    (7, 30),
    (1, 31),
    (2, 31),
    (3, 31),
    (4, 31),
    (5, 31),
    (6, 31),
    (7, 31),
    (1, 32),
    (2, 32),
    (3, 32),
    (4, 32),
    (5, 32),
    (6, 32),
    (7, 32),
    (1, 33),
    (2, 33),
    (3, 33),
    (4, 33),
    (5, 33),
    (6, 33),
    (7, 33),
    (1, 34),
    (2, 34),
    (3, 34),
    (4, 34),
    (5, 34),
    (6, 34),
    (7, 34),
    (1, 35),
    (2, 35),
    (3, 35),
    (4, 35),
    (5, 35),
    (6, 35),
    (7, 35);
INSERT INTO HORAIRES_COURSIER (
        IDHORAIRES_COURSIER,
        IDCOURSIER,
        JOURSEMAINE,
        HEUREDEBUT,
        HEUREFIN
    )
VALUES (
        1,
        1,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        2,
        1,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        3,
        1,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        4,
        1,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        5,
        1,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        6,
        1,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        7,
        1,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        8,
        1,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        9,
        1,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        10,
        1,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        11,
        1,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        12,
        1,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        13,
        1,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        14,
        1,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        15,
        2,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        16,
        2,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        17,
        2,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        18,
        2,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        19,
        2,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        20,
        2,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        21,
        2,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        22,
        2,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        23,
        2,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        24,
        2,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        25,
        2,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        26,
        2,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        27,
        2,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        28,
        2,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        29,
        3,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        30,
        3,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        31,
        3,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        32,
        3,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        33,
        3,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        34,
        3,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        35,
        3,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        36,
        3,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        37,
        3,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        38,
        3,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        39,
        3,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        40,
        3,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        41,
        3,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        42,
        3,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        43,
        4,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        44,
        4,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        45,
        4,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        46,
        4,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        47,
        4,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        48,
        4,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        49,
        4,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        50,
        4,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        51,
        4,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        52,
        4,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        53,
        4,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        54,
        4,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        55,
        4,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        56,
        4,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        57,
        5,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        58,
        5,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        59,
        5,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        60,
        5,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        61,
        5,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        62,
        5,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        63,
        5,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        64,
        5,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        65,
        5,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        66,
        5,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        67,
        5,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        68,
        5,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        69,
        5,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        70,
        5,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        71,
        6,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        72,
        6,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        73,
        6,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        74,
        6,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        75,
        6,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        76,
        6,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        77,
        6,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        78,
        6,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        79,
        6,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        80,
        6,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        81,
        6,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        82,
        6,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        83,
        6,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        84,
        6,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        85,
        7,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        86,
        7,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        87,
        7,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        88,
        7,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        89,
        7,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        90,
        7,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        91,
        7,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        92,
        7,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        93,
        7,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        94,
        7,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        95,
        7,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        96,
        7,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        97,
        7,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        98,
        7,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        99,
        8,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        100,
        8,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        101,
        8,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        102,
        8,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        103,
        8,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        104,
        8,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        105,
        8,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        106,
        8,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        107,
        8,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        108,
        8,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        109,
        8,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        110,
        8,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        111,
        8,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        112,
        8,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        113,
        9,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        114,
        9,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        115,
        9,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        116,
        9,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        117,
        9,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        118,
        9,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        119,
        9,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        120,
        9,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        121,
        9,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        122,
        9,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        123,
        9,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        124,
        9,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        125,
        9,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        126,
        9,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        127,
        10,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        128,
        10,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        129,
        10,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        130,
        10,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        131,
        10,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        132,
        10,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        133,
        10,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        134,
        10,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        135,
        10,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        136,
        10,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        137,
        10,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        138,
        10,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        139,
        10,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        140,
        10,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        141,
        11,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        142,
        11,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        143,
        11,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        144,
        11,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        145,
        11,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        146,
        11,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        147,
        11,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        148,
        11,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        149,
        11,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        150,
        11,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        151,
        12,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        152,
        12,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        153,
        12,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        154,
        12,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        155,
        12,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        156,
        12,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        157,
        12,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        158,
        12,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        159,
        12,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        160,
        12,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        161,
        12,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        162,
        12,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        163,
        12,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        164,
        12,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        165,
        13,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        166,
        13,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        167,
        13,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        168,
        13,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        169,
        13,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        170,
        13,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        171,
        13,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        172,
        13,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        173,
        13,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        174,
        13,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        175,
        13,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        176,
        13,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        177,
        13,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        178,
        13,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        179,
        14,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        180,
        14,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        181,
        14,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        182,
        14,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        183,
        14,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        184,
        14,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        185,
        14,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        186,
        14,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        187,
        14,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        188,
        14,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        189,
        14,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        190,
        14,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        191,
        14,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        192,
        14,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        193,
        15,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        194,
        15,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        195,
        15,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        196,
        15,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        197,
        15,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        198,
        15,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        199,
        15,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        200,
        15,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        201,
        16,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        202,
        16,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        203,
        16,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        204,
        16,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        205,
        16,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        206,
        16,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        207,
        16,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        208,
        16,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        209,
        16,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        210,
        16,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        211,
        16,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        212,
        16,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        213,
        16,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        214,
        16,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        215,
        17,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        216,
        17,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        217,
        17,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        218,
        17,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        219,
        17,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        220,
        17,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        221,
        17,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        222,
        17,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        223,
        17,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        224,
        17,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        225,
        17,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        226,
        17,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        227,
        17,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        228,
        17,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        229,
        18,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        230,
        18,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        231,
        18,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        232,
        18,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        233,
        18,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        234,
        18,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        235,
        18,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        236,
        18,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        237,
        18,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        238,
        18,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        239,
        18,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        240,
        18,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        241,
        18,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        242,
        18,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        243,
        19,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        244,
        19,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        245,
        19,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        246,
        19,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        247,
        19,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        248,
        19,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        249,
        19,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        250,
        19,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        251,
        20,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        252,
        20,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        253,
        20,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        254,
        20,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        255,
        20,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        256,
        20,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        257,
        20,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        258,
        20,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        259,
        20,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        260,
        20,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        261,
        20,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        262,
        20,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        263,
        20,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        264,
        20,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        265,
        21,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        266,
        21,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        267,
        21,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        268,
        21,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        269,
        21,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        270,
        21,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        271,
        21,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        272,
        21,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        273,
        21,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        274,
        21,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        275,
        21,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        276,
        21,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        277,
        21,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        278,
        21,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        279,
        22,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        280,
        22,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        281,
        22,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        282,
        22,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        283,
        22,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        284,
        22,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        285,
        22,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        286,
        22,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        287,
        22,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        288,
        22,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        289,
        22,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        290,
        22,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        291,
        22,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        292,
        22,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        293,
        23,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        294,
        23,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        295,
        23,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        296,
        23,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        297,
        23,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        298,
        23,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        299,
        23,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        300,
        23,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        301,
        24,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        302,
        24,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        303,
        24,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        304,
        24,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        305,
        24,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        306,
        24,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        307,
        24,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        308,
        24,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        309,
        24,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        310,
        24,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        311,
        24,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        312,
        24,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        313,
        24,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        314,
        24,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        315,
        25,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        316,
        25,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        317,
        25,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        318,
        25,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        319,
        25,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        320,
        25,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        321,
        25,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        322,
        25,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        323,
        25,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        324,
        25,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        325,
        25,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        326,
        25,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        327,
        25,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        328,
        25,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        329,
        26,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        330,
        26,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        331,
        26,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        332,
        26,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        333,
        26,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        334,
        26,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        335,
        26,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        336,
        26,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        337,
        26,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        338,
        26,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        339,
        26,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        340,
        26,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        341,
        26,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        342,
        26,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        343,
        27,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        344,
        27,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        345,
        27,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        346,
        27,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        347,
        27,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        348,
        27,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        349,
        27,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        350,
        27,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        351,
        27,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        352,
        27,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        353,
        27,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        354,
        27,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        355,
        27,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        356,
        27,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        357,
        28,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        358,
        28,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        359,
        28,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        360,
        28,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        361,
        28,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        362,
        28,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        363,
        28,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        364,
        28,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        365,
        28,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        366,
        28,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        367,
        28,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        368,
        28,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        369,
        28,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        370,
        28,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        371,
        29,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        372,
        29,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        373,
        29,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        374,
        29,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        375,
        29,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        376,
        29,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        377,
        29,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        378,
        29,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        379,
        29,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        380,
        29,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        381,
        29,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        382,
        29,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        383,
        29,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        384,
        29,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        385,
        30,
        'Lundi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        386,
        30,
        'Lundi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        387,
        30,
        'Mardi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        388,
        30,
        'Mardi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        389,
        30,
        'Mercredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        390,
        30,
        'Mercredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        391,
        30,
        'Jeudi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        392,
        30,
        'Jeudi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        393,
        30,
        'Vendredi',
        '08:00:00+01',
        '12:00:00+01'
    ),
    (
        394,
        30,
        'Vendredi',
        '13:00:00+01',
        '17:00:00+01'
    ),
    (
        395,
        30,
        'Samedi',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        396,
        30,
        'Samedi',
        '15:00:00+01',
        '19:00:00+01'
    ),
    (
        397,
        30,
        'Dimanche',
        '10:00:00+01',
        '14:00:00+01'
    ),
    (
        398,
        30,
        'Dimanche',
        '15:00:00+01',
        '19:00:00+01'
    );
INSERT INTO PLANNING_RESERVATION (IDPLANNING, IDCLIENT)
VALUES (1, 1),
    (2, 2),
    (3, 3),
    (4, 4),
    (5, 5),
    (6, 6),
    (7, 7),
    (8, 8),
    (9, 9),
    (10, 10),
    (11, 11),
    (12, 12),
    (13, 13),
    (14, 14),
    (15, 15),
    (16, 16),
    (17, 17),
    (18, 18),
    (19, 19),
    (20, 20),
    (21, 21),
    (22, 22),
    (23, 23),
    (24, 24),
    (25, 25),
    (26, 26),
    (27, 27),
    (28, 28),
    (29, 29),
    (30, 30),
    (31, 31),
    (32, 32),
    (33, 33),
    (34, 34),
    (35, 35),
    (36, 36),
    (37, 37),
    (38, 38),
    (39, 39),
    (40, 40),
    (41, 41),
    (42, 42),
    (43, 43),
    (44, 44),
    (45, 45),
    (46, 46),
    (47, 47),
    (48, 48),
    (49, 49),
    (50, 50);
INSERT INTO RESERVATION (
        IDRESERVATION,
        IDCLIENT,
        IDPLANNING,
        IDVELO,
        DATERESERVATION,
        HEURERESERVATION,
        POURQUI
    )
VALUES (
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
INSERT INTO COURSE (
        IDCOURSE,
        IDCOURSIER,
        IDCB,
        IDADRESSE,
        IDRESERVATION,
        ADR_IDADRESSE,
        IDPRESTATION,
        DATECOURSE,
        HEURECOURSE,
        PRIXCOURSE,
        STATUTCOURSE,
        NOTECOURSE,
        COMMENTAIRECOURSE,
        POURBOIRE,
        DISTANCE,
        TEMPS
    )
VALUES (
        1,
        NULL,
        7,
        32,
        50,
        56,
        7,
        '2024-12-10',
        '08:30:00',
        15.00,
        'En attente',
        NULL,
        NULL,
        NULL,
        3.0,
        10
    ),
    (
        2,
        NULL,
        6,
        35,
        51,
        59,
        6,
        '2024-12-11',
        '09:00:00',
        25.00,
        'En attente',
        NULL,
        NULL,
        NULL,
        5.0,
        20
    ),
    (
        3,
        3,
        4,
        37,
        52,
        46,
        7,
        '2024-12-09',
        '14:15:00',
        0.00,
        'Annulée',
        NULL,
        NULL,
        NULL,
        7.0,
        30
    ),
    (
        4,
        4,
        7,
        70,
        53,
        55,
        3,
        '2024-12-10',
        '15:45:00',
        45.00,
        'En cours',
        NULL,
        NULL,
        NULL,
        10.0,
        50
    ),
    (
        5,
        5,
        8,
        78,
        54,
        8,
        1,
        '2024-12-08',
        '11:30:00',
        80.00,
        'Terminée',
        4.5,
        'Service rapide',
        10.00,
        15.0,
        60
    ),
    (
        6,
        6,
        5,
        18,
        55,
        14,
        7,
        '2024-12-09',
        '13:00:00',
        50.00,
        'Terminée',
        3.5,
        'Bon service',
        5.00,
        8.0,
        30
    ),
    (
        7,
        NULL,
        3,
        16,
        56,
        39,
        3,
        '2024-12-11',
        '10:00:00',
        12.00,
        'En attente',
        NULL,
        NULL,
        NULL,
        2.5,
        10
    ),
    (
        8,
        8,
        1,
        89,
        57,
        31,
        2,
        '2024-12-07',
        '16:20:00',
        40.00,
        'Terminée',
        4.0,
        'Efficace',
        8.00,
        12.0,
        45
    ),
    (
        9,
        9,
        4,
        92,
        58,
        74,
        2,
        '2024-12-08',
        '17:10:00',
        30.00,
        'En cours',
        NULL,
        NULL,
        NULL,
        9.0,
        40
    ),
    (
        10,
        10,
        5,
        82,
        60,
        44,
        2,
        '2024-12-10',
        '14:00:00',
        25.00,
        'En cours',
        NULL,
        NULL,
        NULL,
        4.0,
        15
    ),
    (
        11,
        11,
        1,
        37,
        61,
        17,
        1,
        '2024-12-09',
        '08:00:00',
        90.00,
        'Terminée',
        5.0,
        'Parfait',
        15.00,
        20.0,
        70
    ),
    (
        12,
        12,
        6,
        24,
        62,
        88,
        5,
        '2024-12-10',
        '11:45:00',
        55.00,
        'En cours',
        NULL,
        NULL,
        NULL,
        7.0,
        25
    ),
    (
        13,
        13,
        1,
        27,
        63,
        74,
        1,
        '2024-12-11',
        '09:30:00',
        10.00,
        'En cours',
        NULL,
        NULL,
        NULL,
        2.0,
        15
    ),
    (
        14,
        14,
        6,
        26,
        64,
        11,
        5,
        '2024-12-09',
        '10:45:00',
        65.00,
        'En cours',
        NULL,
        NULL,
        NULL,
        12.0,
        50
    ),
    (
        15,
        15,
        7,
        18,
        65,
        4,
        5,
        '2024-12-08',
        '12:15:00',
        20.00,
        'Terminée',
        3.0,
        'Bon service',
        4.00,
        5.0,
        25
    ),
    (
        16,
        16,
        10,
        46,
        66,
        35,
        4,
        '2024-12-10',
        '13:30:00',
        75.00,
        'Terminée',
        4.8,
        'Rapide et efficace',
        12.00,
        15.0,
        60
    ),
    (
        17,
        17,
        3,
        25,
        67,
        49,
        7,
        '2024-12-09',
        '08:00:00',
        0.00,
        'Annulée',
        NULL,
        NULL,
        NULL,
        5.0,
        20
    ),
    (
        18,
        18,
        10,
        96,
        68,
        6,
        7,
        '2024-12-11',
        '10:15:00',
        30.00,
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
        '2024-12-07',
        '16:45:00',
        8.00,
        'En attente',
        NULL,
        NULL,
        NULL,
        1.5,
        5
    ),
    (
        20,
        20,
        6,
        30,
        70,
        7,
        4,
        '2024-12-08',
        '17:20:00',
        70.00,
        'Terminée',
        4.0,
        'Très bien',
        10.00,
        18.0,
        50
    ),
    (
        21,
        NULL,
        5,
        93,
        71,
        72,
        2,
        '2024-12-09',
        '14:00:00',
        20.00,
        'En attente',
        NULL,
        NULL,
        NULL,
        5.0,
        25
    ),
    (
        22,
        22,
        5,
        67,
        72,
        96,
        6,
        '2024-12-10',
        '11:00:00',
        50.00,
        'En cours',
        NULL,
        NULL,
        NULL,
        10.0,
        40
    ),
    (
        23,
        NULL,
        8,
        5,
        73,
        30,
        3,
        '2024-12-11',
        '09:45:00',
        15.00,
        'En attente',
        NULL,
        NULL,
        NULL,
        4.0,
        15
    ),
    (
        24,
        24,
        1,
        7,
        74,
        30,
        5,
        '2024-12-08',
        '13:30:00',
        80.00,
        'Terminée',
        4.5,
        'Très satisfait',
        12.00,
        16.0,
        60
    ),
    (
        25,
        NULL,
        10,
        38,
        75,
        58,
        2,
        '2024-12-09',
        '15:45:00',
        18.00,
        'En attente',
        NULL,
        NULL,
        NULL,
        3.0,
        10
    ),
    (
        26,
        26,
        3,
        18,
        76,
        14,
        5,
        '2024-12-10',
        '16:30:00',
        60.00,
        'En cours',
        NULL,
        NULL,
        NULL,
        15.0,
        50
    ),
    (
        27,
        27,
        6,
        97,
        77,
        38,
        2,
        '2024-12-11',
        '14:15:00',
        0.00,
        'Annulée',
        NULL,
        NULL,
        NULL,
        12.0,
        45
    ),
    (
        28,
        28,
        6,
        62,
        78,
        39,
        7,
        '2024-12-07',
        '08:30:00',
        55.00,
        'Annulée',
        NULL,
        NULL,
        NULL,
        10.0,
        35
    ),
    (
        29,
        29,
        4,
        23,
        79,
        42,
        3,
        '2024-12-08',
        '17:00:00',
        30.00,
        'En cours',
        NULL,
        NULL,
        NULL,
        8.0,
        30
    ),
    (
        30,
        30,
        2,
        94,
        80,
        27,
        6,
        '2024-12-09',
        '11:30:00',
        0.00,
        'Annulée',
        NULL,
        NULL,
        NULL,
        4.0,
        15
    ),
    (
        31,
        NULL,
        2,
        83,
        81,
        13,
        5,
        '2024-12-11',
        '15:45:00',
        25.00,
        'En attente',
        NULL,
        NULL,
        NULL,
        6.0,
        20
    ),
    (
        32,
        2,
        1,
        55,
        82,
        42,
        3,
        '2024-12-10',
        '16:00:00',
        0.00,
        'Annulée',
        NULL,
        NULL,
        NULL,
        5.0,
        20
    ),
    (
        33,
        3,
        5,
        8,
        83,
        6,
        4,
        '2024-12-09',
        '17:20:00',
        40.00,
        'En cours',
        NULL,
        NULL,
        NULL,
        9.0,
        30
    ),
    (
        34,
        NULL,
        3,
        25,
        84,
        89,
        3,
        '2024-12-08',
        '10:30:00',
        20.00,
        'En attente',
        NULL,
        NULL,
        NULL,
        7.0,
        25
    ),
    (
        35,
        5,
        10,
        31,
        85,
        76,
        1,
        '2024-12-09',
        '08:45:00',
        10.00,
        'En cours',
        NULL,
        NULL,
        NULL,
        2.0,
        5
    ),
    (
        36,
        NULL,
        10,
        79,
        86,
        32,
        6,
        '2024-12-11',
        '12:30:00',
        22.00,
        'En attente',
        NULL,
        NULL,
        NULL,
        3.5,
        12
    ),
    (
        37,
        NULL,
        4,
        100,
        87,
        56,
        4,
        '2024-12-07',
        '11:15:00',
        30.00,
        'En attente',
        NULL,
        NULL,
        NULL,
        6.0,
        20
    ),
    (
        38,
        8,
        9,
        49,
        88,
        44,
        6,
        '2024-12-08',
        '16:50:00',
        90.00,
        'En cours',
        NULL,
        NULL,
        NULL,
        18.0,
        60
    ),
    (
        39,
        9,
        6,
        93,
        89,
        55,
        2,
        '2024-12-09',
        '13:00:00',
        85.00,
        'Terminée',
        5.0,
        'Excellente prestation',
        10.00,
        16.0,
        55
    ),
    (
        40,
        10,
        9,
        24,
        90,
        84,
        3,
        '2024-12-10',
        '14:45:00',
        35.00,
        'Terminée',
        4.0,
        'Bon travail',
        6.00,
        12.0,
        40
    ),
    (
        41,
        11,
        4,
        86,
        91,
        63,
        7,
        '2024-12-07',
        '12:30:00',
        40.00,
        'En cours',
        NULL,
        NULL,
        NULL,
        8.0,
        25
    ),
    (
        42,
        NULL,
        5,
        54,
        92,
        30,
        7,
        '2024-12-09',
        '11:15:00',
        18.00,
        'En attente',
        NULL,
        NULL,
        NULL,
        4.0,
        15
    ),
    (
        43,
        13,
        6,
        72,
        93,
        56,
        2,
        '2024-12-08',
        '15:30:00',
        0.00,
        'Annulée',
        NULL,
        NULL,
        NULL,
        5.0,
        20
    ),
    (
        44,
        14,
        3,
        37,
        94,
        75,
        5,
        '2024-12-11',
        '13:20:00',
        55.00,
        'En cours',
        NULL,
        NULL,
        NULL,
        10.0,
        30
    ),
    (
        45,
        15,
        5,
        25,
        95,
        83,
        5,
        '2024-12-10',
        '16:10:00',
        0.00,
        'Annulée',
        NULL,
        NULL,
        NULL,
        6.0,
        20
    ),
    (
        46,
        16,
        2,
        10,
        96,
        9,
        2,
        '2024-12-09',
        '09:45:00',
        0.00,
        'Annulée',
        NULL,
        NULL,
        NULL,
        3.0,
        10
    ),
    (
        47,
        17,
        2,
        82,
        97,
        80,
        5,
        '2024-12-08',
        '11:00:00',
        85.00,
        'Terminée',
        5.0,
        'Service impeccable',
        10.00,
        15.0,
        45
    ),
    (
        48,
        18,
        6,
        44,
        98,
        28,
        2,
        '2024-12-07',
        '14:15:00',
        95.00,
        'Terminée',
        4.8,
        'Très satisfait',
        12.00,
        18.0,
        50
    ),
    (
        49,
        19,
        5,
        63,
        99,
        39,
        4,
        '2024-12-11',
        '15:30:00',
        20.00,
        'En cours',
        NULL,
        NULL,
        NULL,
        5.0,
        15
    ),
    (
        50,
        NULL,
        3,
        64,
        100,
        20,
        6,
        '2024-12-08',
        '10:15:00',
        35.00,
        'En attente',
        NULL,
        NULL,
        NULL,
        7.0,
        25
    );
INSERT INTO FACTURE_COURSE (
        IDFACTURE,
        IDCOURSE,
        IDPAYS,
        IDCLIENT,
        MONTANTREGLEMENT,
        DATEFACTURE,
        QUANTITE
    )
VALUES (
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
INSERT INTO A_3 (IDPRODUIT, IDCATEGORIE)
VALUES (1, 7),
    (2, 7),
    (3, 7),
    (4, 5),
    (5, 7),
    (6, 7),
    (7, 11),
    (8, 5),
    (9, 7),
    (10, 2),
    (11, 7),
    (12, 6),
    (13, 5),
    (14, 5),
    (15, 11),
    (16, 11),
    (17, 7),
    (18, 7),
    (19, 7),
    (20, 7),
    (21, 7),
    (22, 7),
    (23, 5),
    (24, 7),
    (25, 7),
    (26, 10),
    (27, 7),
    (28, 5),
    (29, 7),
    (30, 7),
    (31, 5),
    (32, 7),
    (33, 13),
    (34, 13),
    (35, 4),
    (36, 3),
    (37, 6),
    (38, 7),
    (39, 7),
    (40, 5),
    (41, 10),
    (42, 7),
    (43, 7),
    (44, 7),
    (45, 7),
    (46, 7),
    (47, 7),
    (48, 7),
    (49, 7),
    (50, 7),
    (51, 6),
    (52, 6),
    (53, 7),
    (54, 7),
    (55, 7),
    (56, 5),
    (57, 5),
    (58, 11),
    (59, 11),
    (60, 10),
    (61, 4),
    (62, 7),
    (63, 5),
    (64, 5),
    (65, 7),
    (66, 10),
    (67, 6),
    (68, 5),
    (69, 7),
    (70, 5),
    (71, 7),
    (72, 7),
    (73, 10),
    (74, 7),
    (75, 7),
    (76, 7),
    (77, 7),
    (78, 10),
    (79, 7),
    (80, 7),
    (81, 13),
    (82, 13),
    (83, 18),
    (84, 13),
    (85, 13),
    (86, 11),
    (87, 13),
    (88, 13),
    (89, 16),
    (90, 16),
    (91, 13),
    (92, 18),
    (93, 18),
    (94, 18),
    (95, 18),
    (96, 13),
    (97, 18),
    (98, 13),
    (99, 18),
    (100, 18),
    (101, 1),
    (102, 1),
    (103, 1),
    (108, 1),
    (107, 1),
    (117, 1),
    (116, 1),
    (118, 1),
    (110, 2),
    (111, 3),
    (113, 3),
    (112, 4),
    (109, 5),
    (106, 6),
    (114, 7),
    (115, 7),
    (105, 7),
    (104, 11);
INSERT INTO CONTIENT_2 (IDPANIER, IDPRODUIT)
VALUES (1, 1),
    (2, 2),
    (2, 3),
    (2, 10),
    (2, 4),
    (3, 3),
    (4, 4),
    (5, 5),
    (6, 6),
    (7, 7),
    (7, 6),
    (7, 4),
    (7, 13),
    (8, 8),
    (9, 9),
    (10, 10),
    (11, 11),
    (12, 12),
    (13, 13),
    (14, 14),
    (15, 15),
    (16, 16),
    (17, 17),
    (18, 18),
    (19, 19),
    (20, 20);
INSERT INTO REGLEMENT_SALAIRE (
        IDREGLEMENT,
        IDCOURSIER,
        MONTANTREGLEMENT
    )
VALUES (1, 1, 1500.00),
    (2, 2, 1700.00),
    (3, 3, 1600.00),
    (4, 4, 1550.00),
    (5, 5, 1450.00),
    (6, 6, 1800.00),
    (7, 7, 1750.00),
    (8, 8, 1500.00),
    (9, 9, 1650.00),
    (10, 10, 1600.00);
-- ADRESSE
DROP SEQUENCE IF EXISTS ADRESSE_ID_SEQ CASCADE;
CREATE SEQUENCE ADRESSE_ID_SEQ START 1;
ALTER TABLE ADRESSE
ALTER COLUMN IDADRESSE
SET DEFAULT NEXTVAL('ADRESSE_id_seq');
SELECT SETVAL('ADRESSE_id_seq', 188);
-- CARTE_BANCAIRE
DROP SEQUENCE IF EXISTS CARTE_BANCAIRE_ID_SEQ CASCADE;
CREATE SEQUENCE CARTE_BANCAIRE_ID_SEQ START 1;
ALTER TABLE CARTE_BANCAIRE
ALTER COLUMN IDCB
SET DEFAULT NEXTVAL('CARTE_BANCAIRE_id_seq');
SELECT SETVAL('CARTE_BANCAIRE_id_seq', 10);
-- CATEGORIE_PRODUIT
DROP SEQUENCE IF EXISTS CATEGORIE_PRODUIT_ID_SEQ CASCADE;
CREATE SEQUENCE CATEGORIE_PRODUIT_ID_SEQ START 1;
ALTER TABLE CATEGORIE_PRODUIT
ALTER COLUMN IDCATEGORIE
SET DEFAULT NEXTVAL('CATEGORIE_PRODUIT_id_seq');
-- CLIENT
DROP SEQUENCE IF EXISTS CLIENT_ID_SEQ CASCADE;
CREATE SEQUENCE CLIENT_ID_SEQ START 1;
ALTER TABLE CLIENT
ALTER COLUMN IDCLIENT
SET DEFAULT NEXTVAL('CLIENT_id_seq');
SELECT SETVAL('CLIENT_id_seq', 50);
-- CODE_POSTAL
DROP SEQUENCE IF EXISTS CODE_POSTAL_ID_SEQ CASCADE;
CREATE SEQUENCE CODE_POSTAL_ID_SEQ START 1;
ALTER TABLE CODE_POSTAL
ALTER COLUMN IDCODEPOSTAL
SET DEFAULT NEXTVAL('CODE_POSTAL_id_seq');
SELECT SETVAL('CODE_POSTAL_id_seq', 40);
-- COMMANDE
DROP SEQUENCE IF EXISTS COMMANDE_ID_SEQ CASCADE;
CREATE SEQUENCE COMMANDE_ID_SEQ START 1;
ALTER TABLE COMMANDE
ALTER COLUMN IDCOMMANDE
SET DEFAULT NEXTVAL('COMMANDE_id_seq');
-- COURSE
DROP SEQUENCE IF EXISTS COURSE_ID_SEQ CASCADE;
CREATE SEQUENCE COURSE_ID_SEQ START 1;
ALTER TABLE COURSE
ALTER COLUMN IDCOURSE
SET DEFAULT NEXTVAL('COURSE_id_seq');
SELECT SETVAL('COURSE_id_seq', 50);
-- COURSIER
DROP SEQUENCE IF EXISTS COURSIER_ID_SEQ CASCADE;
CREATE SEQUENCE COURSIER_ID_SEQ START 1;
ALTER TABLE COURSIER
ALTER COLUMN IDCOURSIER
SET DEFAULT NEXTVAL('COURSIER_id_seq');
SELECT SETVAL('COURSIER_id_seq', 30);
-- DEPARTEMENT
DROP SEQUENCE IF EXISTS DEPARTEMENT_ID_SEQ CASCADE;
CREATE SEQUENCE DEPARTEMENT_ID_SEQ START 1;
ALTER TABLE DEPARTEMENT
ALTER COLUMN IDDEPARTEMENT
SET DEFAULT NEXTVAL('DEPARTEMENT_id_seq');
-- ENTREPRISE
DROP SEQUENCE IF EXISTS ENTREPRISE_ID_SEQ CASCADE;
CREATE SEQUENCE ENTREPRISE_ID_SEQ START 1;
ALTER TABLE ENTREPRISE
ALTER COLUMN IDENTREPRISE
SET DEFAULT NEXTVAL('ENTREPRISE_id_seq');
SELECT SETVAL('ENTREPRISE_id_seq', 20);
-- ENTRETIEN
DROP SEQUENCE IF EXISTS ENTRETIEN_ID_SEQ CASCADE;
CREATE SEQUENCE ENTRETIEN_ID_SEQ START 1;
ALTER TABLE ENTRETIEN
ALTER COLUMN IDENTRETIEN
SET DEFAULT NEXTVAL('ENTRETIEN_id_seq');
SELECT SETVAL('ENTRETIEN_id_seq', 30);
-- ETABLISSEMENT
DROP SEQUENCE IF EXISTS ETABLISSEMENT_ID_SEQ CASCADE;
CREATE SEQUENCE ETABLISSEMENT_ID_SEQ START 1;
ALTER TABLE ETABLISSEMENT
ALTER COLUMN IDETABLISSEMENT
SET DEFAULT NEXTVAL('ETABLISSEMENT_id_seq');
SELECT SETVAL('ETABLISSEMENT_id_seq', 35);
-- FACTURE_COURSE
DROP SEQUENCE IF EXISTS FACTURE_COURSE_ID_SEQ CASCADE;
CREATE SEQUENCE FACTURE_COURSE_ID_SEQ START 1;
ALTER TABLE FACTURE_COURSE
ALTER COLUMN IDFACTURE
SET DEFAULT NEXTVAL('FACTURE_COURSE_id_seq');
SELECT SETVAL('FACTURE_COURSE_id_seq', 20);
-- HORAIRES
DROP SEQUENCE IF EXISTS HORAIRES_ID_SEQ CASCADE;
CREATE SEQUENCE HORAIRES_ID_SEQ START 1;
ALTER TABLE HORAIRES_ETABLISSEMENT
ALTER COLUMN IDHORAIRES_ETABLISSEMENT
SET DEFAULT NEXTVAL('HORAIRES_id_seq');
-- HORAIRES_COURSIER
DROP SEQUENCE IF EXISTS HORAIRES_COURSIER_ID_SEQ CASCADE;
CREATE SEQUENCE HORAIRES_COURSIER_ID_SEQ START 1;
ALTER TABLE HORAIRES_COURSIER
ALTER COLUMN IDHORAIRES_COURSIER
SET DEFAULT NEXTVAL('HORAIRES_COURSIER_id_seq');
-- PANIER
DROP SEQUENCE IF EXISTS PANIER_ID_SEQ CASCADE;
CREATE SEQUENCE PANIER_ID_SEQ START 1;
ALTER TABLE PANIER
ALTER COLUMN IDPANIER
SET DEFAULT NEXTVAL('PANIER_id_seq');
SELECT SETVAL('PANIER_id_seq', 50);
-- PAYS
DROP SEQUENCE IF EXISTS PAYS_ID_SEQ CASCADE;
CREATE SEQUENCE PAYS_ID_SEQ START 1;
ALTER TABLE PAYS
ALTER COLUMN IDPAYS
SET DEFAULT NEXTVAL('PAYS_id_seq');
-- PLANNING_RESERVATION
DROP SEQUENCE IF EXISTS PLANNING_RESERVATION_ID_SEQ CASCADE;
CREATE SEQUENCE PLANNING_RESERVATION_ID_SEQ START 1;
ALTER TABLE PLANNING_RESERVATION
ALTER COLUMN IDPLANNING
SET DEFAULT NEXTVAL('PLANNING_RESERVATION_id_seq');
SELECT SETVAL('PLANNING_RESERVATION_id_seq', 50);
-- PRODUIT
DROP SEQUENCE IF EXISTS PRODUIT_ID_SEQ CASCADE;
CREATE SEQUENCE PRODUIT_ID_SEQ START 1;
ALTER TABLE PRODUIT
ALTER COLUMN IDPRODUIT
SET DEFAULT NEXTVAL('PRODUIT_id_seq');
SELECT SETVAL('PRODUIT_id_seq', 118);
-- REGLEMENT_SALAIRE
DROP SEQUENCE IF EXISTS REGLEMENT_SALAIRE_ID_SEQ CASCADE;
CREATE SEQUENCE REGLEMENT_SALAIRE_ID_SEQ START 1;
ALTER TABLE REGLEMENT_SALAIRE
ALTER COLUMN IDREGLEMENT
SET DEFAULT NEXTVAL('REGLEMENT_SALAIRE_id_seq');
SELECT SETVAL('REGLEMENT_SALAIRE_id_seq', 10);
-- RESERVATION
DROP SEQUENCE IF EXISTS RESERVATION_ID_SEQ CASCADE;
CREATE SEQUENCE RESERVATION_ID_SEQ START 1;
ALTER TABLE RESERVATION
ALTER COLUMN IDRESERVATION
SET DEFAULT NEXTVAL('RESERVATION_id_seq');
SELECT SETVAL('RESERVATION_id_seq', 100);
-- TYPE_PRESTATION
DROP SEQUENCE IF EXISTS TYPE_PRESTATION_ID_SEQ CASCADE;
CREATE SEQUENCE TYPE_PRESTATION_ID_SEQ START 1;
ALTER TABLE TYPE_PRESTATION
ALTER COLUMN IDPRESTATION
SET DEFAULT NEXTVAL('TYPE_PRESTATION_id_seq');
-- CATEGORIE_PRESTATION
DROP SEQUENCE IF EXISTS CATEGORIE_PRESTATION_ID_SEQ CASCADE;
CREATE SEQUENCE CATEGORIE_PRESTATION_ID_SEQ START 1;
ALTER TABLE CATEGORIE_PRESTATION
ALTER COLUMN IDCATEGORIEPRESTATION
SET DEFAULT NEXTVAL('CATEGORIE_PRESTATION_id_seq');
-- VEHICULE
DROP SEQUENCE IF EXISTS VEHICULE_ID_SEQ CASCADE;
CREATE SEQUENCE VEHICULE_ID_SEQ START 1;
ALTER TABLE VEHICULE
ALTER COLUMN IDVEHICULE
SET DEFAULT NEXTVAL('VEHICULE_id_seq');
-- VELO
DROP SEQUENCE IF EXISTS VELO_ID_SEQ CASCADE;
CREATE SEQUENCE VELO_ID_SEQ START 1;
ALTER TABLE VELO
ALTER COLUMN IDVELO
SET DEFAULT NEXTVAL('VELO_id_seq');
-- VILLE
DROP SEQUENCE IF EXISTS VILLE_ID_SEQ CASCADE;
CREATE SEQUENCE VILLE_ID_SEQ START 1;
ALTER TABLE VILLE
ALTER COLUMN IDVILLE
SET DEFAULT NEXTVAL('VILLE_id_seq');
SELECT SETVAL('VILLE_id_seq', 40);
-- check_adresse_difference adresse départ ne peut pas être égal à adresse arrivée
CREATE OR REPLACE FUNCTION check_adresse_difference() RETURNS TRIGGER AS $$ BEGIN IF NEW.IDADRESSE = NEW.ADR_IDADRESSE THEN RAISE EXCEPTION 'IDADRESSE ne peut pas être égal à ADR_IDADRESSE';
END IF;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;
CREATE TRIGGER trigger_check_adresse_difference BEFORE
INSERT
    OR
UPDATE ON COURSE FOR EACH ROW EXECUTE FUNCTION check_adresse_difference();
-- check_statut_update statut d'une course terminée ne peut pas être modifié
CREATE OR REPLACE FUNCTION check_statut_update() RETURNS TRIGGER AS $$ BEGIN IF OLD.STATUTCOURSE = 'Terminée' THEN IF NEW.NOTECOURSE IS DISTINCT
FROM OLD.NOTECOURSE
    OR NEW.POURBOIRE IS DISTINCT
FROM OLD.POURBOIRE THEN RETURN NEW;
END IF;
RAISE EXCEPTION 'Le statut d''une course terminée ne peut pas être modifié, sauf pour la note et le pourboire.';
END IF;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;
-- empecher_modif empêche l'insertion, la suppression et la modification de la table departement
CREATE OR REPLACE FUNCTION empecher_modif() RETURNS trigger AS $$ BEGIN RAISE EXCEPTION 'Impossible de modifier les données des départements !';
END;
$$ LANGUAGE plpgsql;
CREATE OR REPLACE TRIGGER bf_del_upd_departement BEFORE
UPDATE
    OR DELETE ON departement EXECUTE PROCEDURE empecher_modif();