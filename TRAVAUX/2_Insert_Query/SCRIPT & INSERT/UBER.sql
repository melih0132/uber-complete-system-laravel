/*==============================================================*/
/* Nom de SGBD :  PostgreSQL 8                                  */
/* Date de création :  14/11/2024 15:23:14                      */
/*==============================================================*/


drop table if exists ADRESSE cascade;

drop table if exists APPARTIENT_2 cascade;

drop table if exists APPARTIENT_3 cascade;

drop table if exists A_3 cascade;

drop table if exists A_COMME_TYPE cascade; 

drop table if exists CARTE_BANCAIRE cascade;

drop table if exists CATEGORIE_PRODUIT cascade;

drop table if exists CLIENT cascade;

drop table if exists CODE_POSTAL cascade;

drop table if exists COMMANDE cascade; 

drop table if exists CONTIENT_2 cascade;

drop table if exists COURSE cascade;

drop table if exists COURSIER cascade;

drop table if exists DEPARTEMENT cascade;

drop table if exists ENTREPRISE cascade;

drop table if exists EST_POUR cascade;

drop table if exists EST_POUR_2 cascade; 

drop table if exists EST_SITUE_A_2 cascade;

drop table if exists ETABLISSEMENT cascade;

drop table if exists PANIER cascade;

drop table if exists PAYS cascade;

drop table if exists PRODUIT cascade;

drop table if exists REGLEMENT_SALAIRE cascade; 

drop table if exists RESERVATION cascade;

drop table if exists TYPE_PRESTATION cascade;

drop table if exists UTILISATEUR cascade;

drop table if exists VEHICULE cascade; 

drop table if exists VELO cascade;

drop table if exists VILLE cascade;


/*==============================================================*/
/* Table : ADRESSE                                              */
/*==============================================================*/
create table ADRESSE (
   IDADRESSE            INT4                 not null,
   IDCODEPOSTAL         INT4                 not null,
   IDPAYS               INT4                 not null,
   IDDEPARTEMENT        INT4                 not null,
   IDVILLE              INT4                 not null,
   LIBELLEADRESSE       VARCHAR(100)         null,
   constraint PK_ADRESSE primary key (IDADRESSE)
);

/*==============================================================*/
/* Table : APPARTIENT_2                                         */
/*==============================================================*/
create table APPARTIENT_2 (
   IDCB                 INT4                 not null,
   IDCLIENT             INT4                 not null,
   constraint PK_APPARTIENT_2 primary key (IDCB, IDCLIENT)
);

/*==============================================================*/
/* Table : APPARTIENT_3                                         */
/*==============================================================*/
create table APPARTIENT_3 (
   IDPANIER             INT4                 not null,
   IDCLIENT             INT4                 not null,
   constraint PK_APPARTIENT_3 primary key (IDPANIER, IDCLIENT)
);

/*==============================================================*/
/* Table : A_3                                                  */
/*==============================================================*/
create table A_3 (
   IDPRODUIT            INT4                 not null,
   IDCATEGORIE          INT4                 not null,
   constraint PK_A_3 primary key (IDPRODUIT, IDCATEGORIE)
);

/*==============================================================*/
/* Table : A_COMME_TYPE                                         */
/*==============================================================*/
create table A_COMME_TYPE (
   IDVEHICULE           INT4                 not null,
   IDPRESTATION         INT4                 not null,
   constraint PK_A_COMME_TYPE primary key (IDVEHICULE, IDPRESTATION)
);

/*==============================================================*/
/* Table : CARTE_BANCAIRE                                      */
/*==============================================================*/
create table CARTE_BANCAIRE (
   IDCB                 INT4                 not null,
   NUMEROCB             NUMERIC(18,1)        not null,
constraint UQ_CARTE_BANCAIRE_NUM unique (NUMEROCB),
   DATEEXPIRECB         DATE                 not null,
constraint CK_CARTE_BANCAIRE_DATEEXPIRE check (DATEEXPIRECB > CURRENT_DATE),
   CRYPTOGRAMME         NUMERIC(4,1)         not null,
   TYPECARTE            VARCHAR(6)           not null,
   TYPERESEAUX          VARCHAR(25)          not null,
constraint PK_CARTE_BANCAIRE primary key (IDCB)
);
/*==============================================================*/
/* Table : CATEGORIE_PRODUIT                                    */
/*==============================================================*/
create table CATEGORIE_PRODUIT (
   IDCATEGORIE          INT4                 not null,
   NOMCATEGORIE         VARCHAR(50)          not null,
   constraint PK_CATEGORIE_PRODUIT primary key (IDCATEGORIE)
);

/*==============================================================*/
/* Table : CLIENT                                               */
/*==============================================================*/
create table CLIENT (
   IDCLIENT             INT4                 not null,
   IDENTREPRISE         INT4                 null,
   IDADRESSE            INT4                 not null,
   GENRECLIENT          VARCHAR(20)          not null,
constraint CK_CLIENT_GENRE check(GENRECLIENT in ('Monsieur', 'Madame')),
   IMAGECLIENT          VARCHAR(300)         null,
   constraint PK_CLIENT primary key (IDCLIENT)
);

/*==============================================================*/
/* Table : CODE_POSTAL                                          */
/*==============================================================*/
create table CODE_POSTAL (
   IDCODEPOSTAL         INT4                 not null,
   CODEPOSTAL           CHAR(5)              not null,
	CONSTRAINT UQ_CODEPOSTAL UNIQUE (CODEPOSTAL),
   constraint PK_CODE_POSTAL primary key (IDCODEPOSTAL)
);

/*==============================================================*/
/* Table : COMMANDE                                             */
/*==============================================================*/
create table COMMANDE (
   IDCOMMANDE           INT4                 not null,
   IDPANIER             INT4                 not null,
   IDCLIENT             INT4                 not null,
   PRIXCOMMANDE         DECIMAL(5,2)         not null,
constraint CK_COMMANDE_PRIX check (PRIXCOMMANDE >= 0),
   TEMPSCOMMANDE        INT4                 not null,
constraint ck_TEMPS_COMMANDE check (TEMPSCOMMANDE >= 0), 
   ESTLIVRAISON        BOOL                 not null,
   STATUT               VARCHAR(20)          not null,
constraint ck_STATUT_COMMANDE check (STATUT in ('En attente', 'En cours', 'Livrée', 'Annulée')),
   DATECOMMANDE         DATE                 not null,
constraint ck_DATE_COMMANDE check (DATECOMMANDE <= CURRENT_DATE),
   HEURECOMMANDE        TIME                 not null,
   constraint PK_COMMANDE primary key (IDCOMMANDE)
);

/*==============================================================*/
/* Table : CONTIENT_2                                           */
/*==============================================================*/
create table CONTIENT_2 (
   IDPANIER             INT4                 not null,
   IDPRODUIT            INT4                 not null,
   constraint PK_CONTIENT_2 primary key (IDPANIER, IDPRODUIT)
);

/*==============================================================*/
/* Table : COURSE                                               */
/*==============================================================*/
create table COURSE (
   IDCOURSE             INT4                 not null,
   IDADRESSE            INT4                 not null,
   IDCLIENT             INT4                 not null,
   IDPRESTATION         INT4                 not null,
   PRIXCOURSE           NUMERIC(5,2)         not null,
constraint CK_COURSE_PRIX check (PRIXCOURSE >= 0),
   TEMPSCOURSE          INT4                 not null,
   STATUT               VARCHAR(20)          not null,
constraint CK_COURSE_STATUT check (STATUT in ('En attente', 'En cours', 'Terminée')),
   NOTECOURSE           NUMERIC(2,1)         null,
	constraint CK_COURSE_NOTE check(NOTECOURSE >= 1 and NOTECOURSE <= 5),
   COMMENTAIRECOURSE    VARCHAR(1500)        null,
   constraint PK_COURSE primary key (IDCOURSE)
);

/*==============================================================*/
/* Table : COURSIER                                             */
/*==============================================================*/
create table COURSIER (
   IDCOURSIER           INT4                 not null,
   IDENTREPRISE         INT4          	     not null,
   IDRESERVATION        INT4                 not null,
   GENRECOURSIER        VARCHAR(20)          not null,
constraint CK_COURSIER_GENRE check(GENRECOURSIER in ('Monsieur', 'Madame')),
   NUMEROCARTEVTC       NUMERIC(15,1)        not null,
constraint UQ_COURSIER_NUMCARTE unique (NUMEROCARTEVTC),
   IBAN                 NUMERIC(28,1)        not null,
constraint UQ_COURSIER_IBAN unique (IBAN),
   DATEDEBUTACTIVITE    DATE                 not null,
constraint CK_COURSIER_DATE_DEBUT check (DATEDEBUTACTIVITE <= CURRENT_DATE),
   NOTEMOYENNE          NUMERIC(2,1)         null,
constraint CK_COURSIER_NOTE check(NOTEMOYENNE >= 1 and NOTEMOYENNE <= 5),
   constraint PK_COURSIER primary key (IDCOURSIER)
);

/*==============================================================*/
/* Table : DEPARTEMENT                                          */
/*==============================================================*/
create table DEPARTEMENT (
   IDDEPARTEMENT        INT4                 not null,
   CODEDEPARTEMENT      CHAR(3)              not null,
   LIBELLEDEPARTEMENT   VARCHAR(50)          null,
   constraint PK_DEPARTEMENT primary key (IDDEPARTEMENT)
);

/*==============================================================*/
/* Table : ENTREPRISE                                           */
/*==============================================================*/
create table ENTREPRISE (
   IDENTREPRISE         INT4                 not null,
	constraint UQ_ENTREPRISE unique (IDENTREPRISE),
   IDCLIENT             INT4                 not null,
   IDADRESSE            INT4                 not null,
   SIRETENTREPRISE 	VARCHAR(20)	     not null,
   NOMENTREPRISE        VARCHAR(50)          not null,
   TAILLE               INT4                 not null,
   constraint PK_ENTREPRISE primary key (SIRETENTREPRISE)
);

/*==============================================================*/
/* Table : EST_POUR                                             */
/*==============================================================*/
create table EST_POUR (
   IDRESERVATION        INT4                 not null,
   IDCOURSE             INT4                 not null,
   constraint PK_EST_POUR primary key (IDRESERVATION, IDCOURSE)
);

/*==============================================================*/
/* Table : EST_POUR_2                                           */
/*==============================================================*/
create table EST_POUR_2 (
   IDRESERVATION        INT4                 not null,
   IDVELO               INT4                 not null,
   constraint PK_EST_POUR_2 primary key (IDRESERVATION, IDVELO)
);

/*==============================================================*/
/* Table : EST_SITUE_A_2                                        */
/*==============================================================*/
create table EST_SITUE_A_2 (
   IDPRODUIT            INT4                 not null,
   IDETABLISSEMENT      INT4                 not null,
   constraint PK_EST_SITUE_A_2 primary key (IDPRODUIT, IDETABLISSEMENT)
);

/*==============================================================*/
/* Table : ETABLISSEMENT                                        */
/*==============================================================*/
create table ETABLISSEMENT (
   IDETABLISSEMENT      INT4                 not null,
   IDADRESSE            INT4                 not null,
   NOMETABLISSEMENT     VARCHAR(50)          not null,
   IMAGEETABLISSEMENT   VARCHAR(200)         not null,
   constraint PK_ETABLISSEMENT primary key (IDETABLISSEMENT)
);

/*==============================================================*/
/* Table : PANIER                                               */
/*==============================================================*/
create table PANIER (
   IDPANIER             INT4                 not null,
   PRIX                 DECIMAL(5,2)         not null,
constraint ck_PANIER_PRIX check (PRIX >= 0),
   constraint PK_PANIER primary key (IDPANIER)
);

/*==============================================================*/
/* Table : PAYS                                                 */
/*==============================================================*/
create table PAYS (
   IDPAYS               INT4                 not null,
   NOMPAYS              VARCHAR(50)          not null,
   POURCENTAGETVA       NUMERIC(4,2)         not null,
   constraint PK_PAYS primary key (IDPAYS),
CONSTRAINT UQ_NOMPAYS UNIQUE (NOMPAYS),
CONSTRAINT CK_TVA    CHECK (POURCENTAGETVA >= 0 AND POURCENTAGETVA < 100)
);

/*==============================================================*/
/* Table : PRODUIT                                              */
/*==============================================================*/
create table PRODUIT (
   IDPRODUIT            INT4                 not null,
   NOMPRODUIT           VARCHAR(50)          not null,
   PRIXPRODUIT          NUMERIC(4,2)         not  null,
constraint ck_PRODUIT_PRIX check (PRIXPRODUIT > 0),
   IMAGEPRODUIT         VARCHAR(300)         not null,
   DESCRIPTION          VARCHAR(1500)        not null,
   constraint PK_PRODUIT primary key (IDPRODUIT)
);

/*==============================================================*/
/* Table : REGLEMENT_SALAIRE                                    */
/*==============================================================*/
create table REGLEMENT_SALAIRE (
   IDREGLEMENT          INT4                 not null,
   IDCOURSIER           INT4                 not null,
   MONTANT              NUMERIC(6,2)         not null,
constraint ck_SALAIRE_MNT check (MONTANT >= 0),
   constraint PK_REGLEMENT_SALAIRE primary key (IDREGLEMENT)
);

/*==============================================================*/
/* Table : RESERVATION                                          */
/*==============================================================*/
create table RESERVATION (
   IDRESERVATION        INT4                 not null,
   IDCLIENT             INT4                 not null,
   IDADRESSE            INT4                 not null,
   IDCOURSIER           INT4                 not null,
   DATERESERVATION      DATE                 not null,
   HEURERESERVATION     TIME                 not null,
   constraint PK_RESERVATION primary key (IDRESERVATION),
constraint chk_DATE_RESERVATION CHECK (DATERESERVATION >= CURRENT_DATE)
);

/*==============================================================*/
/* Table : TYPE_PRESTATION                                      */
/*==============================================================*/
create table TYPE_PRESTATION (
   IDPRESTATION         INT4                 not null,
   LIBELLEPRESTATION    VARCHAR(50)          not null,
   IMAGEPRESTATION      VARCHAR(300)         null,
   constraint PK_TYPE_PRESTATION primary key (IDPRESTATION)
);

/*==============================================================*/
/* Table : UTILISATEUR                                          */
/*==============================================================*/
create table UTILISATEUR (
   IDUSER               INT4                 not null,
   IDCLIENT             INT4                 not null,
   IDCOURSIER           INT4                 not null,
   NOMUSER              VARCHAR(50)          not null,
   PRENOMUSER           VARCHAR(50)          not null,
   DATENAISSANCE        DATE                 not null,
CONSTRAINT CHK_DATE_DE_NAISSANCE CHECK (
       DATENAISSANCE <= CURRENT_DATE AND
       DATENAISSANCE <= CURRENT_DATE - INTERVAL '18 years'
   ),
   EMAILUSER            VARCHAR(200)         not null,
constraint UQ_UTILISATEUR unique (EMAILUSER),
   MOTDEPASSEUSER       VARCHAR(200)         not null,
   TELEPHONE            VARCHAR(15)          not null,
constraint CK_COURSIER_TEL check (TELEPHONE like '06%' OR TELEPHONE like '07%' AND LENGTH(TELEPHONE) = 10),
   constraint PK_UTILISATEUR primary key (IDUSER)
);

/*==============================================================*/
/* Table : VEHICULE                                             */
/*==============================================================*/
create table VEHICULE (
   IDVEHICULE           INT4                 not null,
   IDCOURSIER           INT4                 not null,
   IMMATRICULATION      CHAR(9)              not null,
constraint UQ_VEHICULE_IMMA unique (IMMATRICULATION),
constraint CK_VEHICULE_IMMA check (IMMATRICULATION ~ '^[A-Z]{2}-[0-9]{3}-[A-Z]{2}$'),
   MARQUE               VARCHAR(50)          not null,
   MODELE               VARCHAR(50)          not null,
   CAPACITE             INT4                 not null,
constraint CK_VEHICULE_CAPACITE check (CAPACITE BETWEEN 2 AND 7),
   ACCEPTEANIMAUX       BOOL                 not null,
   ESTELECTRIQUE        BOOL                 not null,
   ESTCONFORTABLE       BOOL                 not null,
   ESTRECENT            BOOL                 not null,
   ESTLUXUEUX           BOOL                 not null,
   COULEUR              VARCHAR(20)          null,
   constraint PK_VEHICULE primary key (IDVEHICULE)
);

/*==============================================================*/
/* Table : VELO                                                 */
/*==============================================================*/
create table VELO (
   IDVELO               INT4                 not null,
   NUMEROVELO           INT4                 not null,
   ESTDISPONIBLE        BOOL                 not null,
   constraint PK_VELO primary key (IDVELO)
);

/*==============================================================*/
/* Table : VILLE                                               Pizza */
/*==============================================================*/
create table VILLE (

   IDVILLE              INT4                 not null,
   IDCODEPOSTAL		INT4		     not null,
   NOMVILLE             VARCHAR(50)          not null,
   constraint PK_VILLE primary key (IDVILLE)
);

-----------------------------------------------------------------------------------------------------------------------------------------------

INSERT INTO ETABLISSEMENT (IDETABLISSEMENT, IDADRESSE, NOMETABLISSEMENT, IMAGEETABLISSEMENT) 
VALUES
(1, 10, 'Le Gourmet Parisien', 'image1.jpg'),
(2, 11, 'Le Bistrot Lyonnais', 'image2.jpg'),
(3, 12, 'Chez Mamma', 'image3.jpg'),
(4, 13, 'Le Petit Savoyard', 'image4.jpg'),
(5, 14, 'L’Épicurienne', 'image5.jpg'), 
(6, 15, 'La Table du Chef', 'image6.jpg'),
(7, 16, 'Le Bistro du Marché', 'image7.jpg'),
(8, 17, 'La Brasserie de la Gare', 'image8.jpg'),
(9, 18, 'Géant', 'image9.jpg'),
(10, 19, 'Le Palais des Pâtes', 'image10.jpg'),
(11, 20, 'Le Comptoir du Vin', 'image11.jpg'),
(12, 21, 'Le Jardin Gourmand', 'image12.jpg'),
(13, 22, 'L’Oasis de Saveurs', 'image13.jpg'),
(14, 23, 'Les Folies Gourmandes', 'image14.jpg'),
(15, 24, 'Chez Jean-Claude', 'image15.jpg'),
(16, 25, 'La Cuisine de Mamie', 'image16.jpg'),
(17, 26, 'Auchan', 'image17.jpg'),
(18, 27, 'La Grange à Manger', 'image18.jpg'),
(19, 28, 'Le Marché de Provence', 'image19.jpg'),
(20, 29, 'Les Délices de la Mer', 'image20.jpg'),
(21, 30, 'La Taverne des Artisans', 'image21.jpg'),
(22, 31, 'Le Grill du Coin', 'image22.jpg'),
(23, 32, 'Les Saveurs du Sud', 'image23.jpg'),
(24, 33, 'Le Petit Café de Paris', 'image24.jpg'),
(25, 34, 'Le Gourmet Italien', 'image25.jpg'),
(26, 35, 'Leclerc', 'image26.jpg'),
(27, 36, 'Le Château des Mets', 'image27.jpg'),
(28, 37, 'La Pâtisserie Gourmande', 'image28.jpg'),
(29, 38, 'Le Relais du Parc', 'image29.jpg'),
(30, 39, 'Le Bistronomique', 'image30.jpg'),
(31, 40, 'La Table de l’Abbaye', 'image31.jpg'), 
(32, 41, 'Aldi', 'image32.jpg'),
(33, 42, 'Le Petit Bistro Parisien', 'image33.jpg'),
(34, 43, 'La Cuisine d’Antan', 'image34.jpg'), 
(35, 44, 'La Villa Gourmande', 'image35.jpg'),
(36, 45, 'Les Papilles en Fête', 'image36.jpg'),
(37, 46, 'Carrefour', 'image37.jpg'),
(38, 47, 'Le Comptoir du Marché', 'image38.jpg'),
(39, 48, 'Le Festin de Provence', 'image39.jpg'),
(40, 49, 'Les Délices de Paris', 'image40.jpg');

-- Insertion de 80 produits (plats, boissons) avec les descriptions corrigées
INSERT INTO PRODUIT (IDPRODUIT, NOMPRODUIT, PRIXPRODUIT, IMAGEPRODUIT, DESCRIPTION) 
VALUES
(1, 'Pizza Margherita', 12.99, 'pizza.jpg', 'Une pizza garnie de tomates fraîches, mozzarella et basilic.'),
(2, 'Spaghetti Bolognese', 14.50, 'spaghetti.jpg', 'Pâtes longues avec une sauce bolognaise riche à base de viande.'),
(3, 'Café Espresso', 3.50, 'espresso.jpg', 'Café intense et aromatique, idéal pour une pause rapide.'),
(4, 'Tarte au Citron', 5.50, 'tartecitron.jpg', 'Pâte sablée croustillante avec une crème citronnée acidulée.'),
(5, 'Croissant', 2.00, 'croissant.jpg', 'Viennoiserie au beurre, dorée et croustillante.'),
(6, 'Quiche Lorraine', 8.50, 'quichelorraine.jpg', 'Tarte salée garnie de lardons, œufs et crème fraîche.'),
(7, 'Gratin Dauphinois', 7.90, 'gratindauphinois.jpg', 'Pommes de terre fines, gratinées avec de la crème et du fromage.'),
(8, 'Escargots de Bourgogne', 12.00, 'escargots.jpg', 'Escargots cuisinés avec un beurre persillé et aillé.'),
(9, 'Saumon Grillé', 17.50, 'salmongrille.jpg', 'Filet de saumon grillé, servi avec des légumes de saison.'),
(10, 'Curry de Poulet', 13.90, 'currypoulet.jpg', 'Poulet tendre mijoté dans une sauce curry aux épices douces.'),
(11, 'Pâté en Croûte', 9.00, 'pateencroute.jpg', 'Pâté de viande enrobé dans une pâte croustillante.'),
(12, 'Lasagnes à la Bolognaise', 14.99, 'lasagnes.jpg', 'Pâtes en couches avec sauce bolognaise et fromage gratiné.'),
(13, 'Burger Vegan', 11.00, 'burgervegan.jpg', 'Burger à base de légumes et galette de pois chiches.'),
(14, 'Salade de Quinoa', 10.50, 'saladequinoa.jpg', 'Quinoa mélangé avec légumes frais et vinaigrette légère.'),
(15, 'Pizza Pepperoni', 13.50, 'pizzapepperoni.jpg', 'Pizza avec sauce tomate, mozzarella et tranches de pepperoni.'),
(16, 'Tartare de Boeuf', 18.00, 'tartareboeuf.jpg', 'Viande de bœuf hachée crue, assaisonnée d’épices et de condiments.'),
(17, 'Lait', 1.20, 'lait.jpg', 'Boisson lactée fraîche, idéale pour accompagner vos repas.'),
(18, 'Moules Marinières', 15.50, 'moulesmarinieres.jpg', 'Moules fraîches cuites avec du vin blanc, ail et persil.'),
(19, 'Fromage', 3.50, 'fromage.jpg', 'Assortiment de fromages affinés, parfait pour un plateau dégustation.'),
(20, 'Couscous', 16.00, 'couscous.jpg', 'Plat complet avec semoule, légumes, et viande épicée.'),
(21, 'Cheesecake', 6.50, 'cheesecake.jpg', 'Gâteau crémeux au fromage frais, avec une base biscuitée.'),
(22, 'Pêche Melba', 5.00, 'pechememba.jpg', 'Dessert aux pêches, glace vanille et coulis de framboise.'),
(23, 'Poulet', 9.50, 'poulet.jpg', 'Filet de poulet grillé, juteux et savoureux.'),
(24, 'Ratatouille', 9.90, 'ratatouille.jpg', 'Mélange de légumes provençaux mijotés à la perfection.'),
(25, 'Chocolat Liégeois', 4.50, 'chocolatliegeois.jpg', 'Crème glacée au chocolat avec chantilly et sauce chocolat.'),
(26, 'Boeuf', 19.00, 'boeuf.jpg', 'Filet de bœuf grillé, tendre et juteux, servi avec une sauce au choix.'),
(27, 'Focaccia', 5.50, 'focaccia.jpg', 'Pain italien moelleux, assaisonné d’herbes et d’huile d’olive.'),
(28, 'Côte de Boeuf', 25.00, 'cotedeboeuf.jpg', 'Côte de bœuf épaisse, grillée et servie avec un accompagnement.'),
(29, 'Pâtes', 11.00, 'pates.jpg', 'Pâtes fraîches servies avec une sauce maison au choix.'),
(30, 'Éclair', 3.50, 'eclair.jpg', 'Pâtisserie allongée garnie de crème pâtissière et nappée de glaçage.'),
(31, 'Salade', 7.00, 'salade.jpg', 'Salade verte croquante avec vinaigrette maison.'),
(32, 'Brownie', 3.90, 'brownie.jpg', 'Gâteau au chocolat dense et moelleux, avec des morceaux de noix.'),
(33, 'Riz', 2.50, 'riz.jpg', 'Riz blanc parfumé, cuit à la perfection.'),
(34, 'Porc', 15.00, 'porc.jpg', 'Côte de porc marinée et grillée, servie avec une sauce au choix.'),
(35, 'Frites', 3.00, 'frites.jpg', 'Frites croustillantes, dorées et salées à point.'),
(36, 'Rillettes', 5.00, 'rillettes.jpg', 'Rillettes de porc servies avec des tranches de pain grillé.'),
(37, 'Risotto', 13.00, 'risotto.jpg', 'Risotto crémeux préparé avec du bouillon de légumes et du parmesan.'),
(38, 'Glace', 2.00, 'glace.jpg', 'Glace artisanale, disponible en plusieurs saveurs.'),
(39, 'Gigot', 18.00, 'gigot.jpg', 'Gigot d’agneau rôti, servi avec des légumes de saison.'),
(40, 'Poisson', 12.00, 'poisson.jpg', 'Poisson grillé, servi avec une sauce citronnée.'),
(41, 'Tiramisu au Nutella', 6.00, 'tiramisu_nutella.jpg', 'Tiramisu classique avec une touche de Nutella pour un goût unique.'),
(42, 'Paella', 18.50, 'paella.jpg', 'Riz safrané avec fruits de mer, poulet et légumes.'),
(43, 'Côtelettes d’Agneau', 19.00, 'cotelettesagneau.jpg', 'Côtelettes d’agneau grillées servies avec une sauce au romarin.'),
(44, 'Pizza 4 Fromages', 14.99, 'pizza4fromages.jpg', 'Pizza avec mozzarella, gorgonzola, chèvre et emmental.'),
(45, 'Moules Frites', 13.90, 'moulesfrites.jpg', 'Moules cuites à la marinière accompagnées de frites maison.'),
(46, 'Soufflé au Fromage', 10.00, 'soufflefromage.jpg', 'Soufflé aérien à base de fromage râpé et de béchamel.'),
(47, 'Salmon Sushi', 9.50, 'salmon_sushi.jpg', 'Sushi de saumon frais avec avocat et riz vinaigré.'),
(48, 'Côtes de Boeuf', 24.00, 'cotesdeboeuf.jpg', 'Côtes de boeuf grillées servies avec une sauce béarnaise.'),
(49, 'Tartare de Saumon', 15.50, 'tartaresaumon.jpg', 'Tartare de saumon frais avec avocat et sauce soja.'),
(50, 'Choucroute Garnie', 17.90, 'choucroute.jpg', 'Choucroute accompagnée de saucisses, de lard et de viande de porc.'),
(51, 'Curry de Légumes', 12.50, 'currylegumes.jpg', 'Curry végétarien avec des légumes de saison et du lait de coco.'),
(52, 'Sushi Avocat', 7.00, 'sushiavocat.jpg', 'Sushi avec avocat frais, riz vinaigré et algue nori.'),
(53, 'Moussaka Végétarienne', 13.90, 'moussakavégétarienne.jpg', 'Version végétarienne de la moussaka avec légumes et tofu.'),
(54, 'Tarte Tatin', 6.99, 'tartetatin.jpg', 'Tarte renversée aux pommes caramélisées.'),
(55, 'Poulet Rôti', 16.00, 'pouletroti.jpg', 'Poulet rôti accompagné de légumes de saison.'),
(56, 'Fondue Savoyarde', 22.00, 'fondue.jpg', 'Fromages fondues, pain et légumes pour une fondue savoyarde traditionnelle.'),
(57, 'Salade César', 10.50, 'saladecesar.jpg', 'Salade romaine, poulet grillé, croûtons et sauce César crémeuse.'),
(58, 'Soupe de Potimarron', 7.50, 'soupepotimarron.jpg', 'Soupe onctueuse à base de potimarron et de crème fraîche.'),
(59, 'Grillades de Légumes', 8.00, 'grilladeslegumes.jpg', 'Assortiment de légumes grillés, parfumés à l’huile d’olive.'),
(60, 'Pavé de Merlu', 18.00, 'pavemerlu.jpg', 'Poisson blanc grillé accompagné de sauce beurre blanc.'),
(61, 'Pizza Végétarienne', 13.50, 'pizzavegetarienne.jpg', 'Pizza avec légumes frais, mozzarella et sauce tomate maison.'),
(62, 'Tartare de Thon', 17.50, 'tartarethon.jpg', 'Tartare de thon frais avec sauce soja, avocat et gingembre.'),
(63, 'Riz Cantonnais', 9.50, 'rizcantonnais.jpg', 'Riz sauté avec légumes, œuf, jambon et sauce soja.'),
(64, 'Raviolis aux Champignons', 13.00, 'raviolischampignons.jpg', 'Raviolis maison farcis aux champignons de saison.'),
(65, 'Pâtes Carbonara', 14.00, 'patescarbonara.jpg', 'Pâtes avec une sauce crémeuse à base de lardons, œufs et parmesan.'),
(66, 'Maki Légumes', 8.50, 'makilegumes.jpg', 'Maki végétarien avec concombre, avocat et légumes frais.'),
(67, 'Pâté de Campagne', 9.00, 'patecampagne.jpg', 'Pâté de campagne maison accompagné de cornichons et de pain de campagne.'),
(68, 'Quiche au Brocoli', 8.00, 'quichebrocoli.jpg', 'Quiche légère au brocoli, fromage râpé et crème fraîche.'),
(69, 'Poke Bowl', 15.00, 'pokebowl.jpg', 'Salade composée de riz, légumes frais, avocat, saumon mariné et sauce soja.'),
(70, 'Cannelés Bordelais', 4.50, 'cannelesbordelais.jpg', 'Petits gâteaux bordelais caramélisés à l’extérieur et moelleux à l’intérieur.'),
(71, 'Crêpes au Nutella', 6.50, 'crepesnutella.jpg', 'Crêpes garnies de Nutella et de bananes fraîches.'),
(72, 'Brioche Perdue', 5.00, 'briocheperdue.jpg', 'Pain brioché doré, servi avec du sucre glace et du sirop d’érable.'),
(73, 'Tartines de Beurre Salé', 4.00, 'tartinesbeurresale.jpg', 'Tartines de pain frais avec du beurre salé breton.'),
(74, 'Gratin de Courgettes', 10.00, 'gratincourgettes.jpg', 'Gratin crémeux de courgettes au fromage râpé.'),
(75, 'Grilled Cheese', 8.50, 'grilledcheese.jpg', 'Sandwich chaud au fromage fondu et pain grillé.'),
(76, 'Clafoutis aux Cerises', 7.00, 'clafoutiscerises.jpg', 'Clafoutis maison avec des cerises fraîches.'),
(77, 'Raviolis Ricotta Épinards', 13.50, 'raviolisricottaepinards.jpg', 'Raviolis farcis à la ricotta et aux épinards dans une sauce tomate.'),
(78, 'Gratin de Pommes de Terre', 9.00, 'gratinpommesdeterre.jpg', 'Pommes de terre gratinées avec crème et fromage râpé.'),
(79, 'Gâteau au Chocolat', 5.50, 'gateauchocolat.jpg', 'Gâteau au chocolat fondant avec un cœur coulant.'),
(80, 'Tartelette au Fruit', 4.00, 'tartelettefruit.jpg', 'Tartelette garnie de fruits frais de saison et d’une crème légère.');

-- Insertion de 30 chauffeurs
INSERT INTO COURSIER (IDCOURSIER, IDENTREPRISE, IDRESERVATION, GENRECOURSIER, NUMEROCARTEVTC, IBAN, DATEDEBUTACTIVITE, NOTEMOYENNE) 
VALUES
(1, 1, 1, 'Monsieur', 1234567890123, 1234567890123456, '2021-01-01', 4.5),
(2, 2, 2, 'Madame', 2345678901234, 2345678901234567, '2020-06-15', 4.2),
(3,3, 3, 'Monsieur', 3456789012345, 3456789012345678, '2019-03-01', 3.8),
(4, 4, 4, 'Monsieur', 45678901234567, 5001234567891234, '2021-02-10', 4.7),
(5, 5, 5, 'Madame', 56789001234568, 5002234567891234, '2021-05-18', null),
(6, 6, 6, 'Monsieur', 67890101234569, 5003234567891234, '2020-07-22', 4.1),
(7, 7, 7, 'Madame', 78901201234570, 5004234567891234, '2019-09-25', 4.4),
(8, 8, 8, 'Monsieur', 89012301234571, 5005234567891234, '2018-11-05', 3.9),
(9, 9, 9, 'Madame', 90123401234572, 5006234567891234, '2017-10-14', 4.2),
(10, 10, 10, 'Monsieur', 12345601234573, 5007234567891234, '2021-06-30', 4.6),
(11, 11, 11, 'Madame', 23456701234574, 5008234567891234, '2020-02-01', null),
(12, 12, 12, 'Monsieur', 34567801234575, 5009234567891234, '2019-01-20', 3.8),
(13, 13, 13, 'Madame', 45678901234576, 5010234567891234, '2018-03-10', 4.5),
(14, 14, 14, 'Monsieur', 56789001234577, 5011234567891234, '2020-04-22', 4.3),
(15, 15, 15, 'Madame', 67890101234578, 5012234567891234, '2019-08-14', 4.2),
(16, 16, 16, 'Monsieur', 78901201234579, 5013234567891234, '2018-06-30', 4.1),
(17, 17, 17, 'Madame', 89012301234580, 5014234567891234, '2020-10-05', 4.4),
(18, 18, 18, 'Monsieur', 90123401234581, 5015234567891234, '2019-12-18', 4.0),
(19,19, 19, 'Madame', 12345601234582, 5016234567891234, '2020-03-15', 4.3),
(20,20, 20, 'Monsieur', 23456701234583, 5017234567891234, '2019-04-10', 4.6),
(21, 1, 21, 'Madame', 34567801234584, 5018234567891234, '2018-09-21', null),
(22, 2, 22, 'Monsieur', 45678901234585, 5019234567891234, '2019-02-11', 4.5),
(23, 3, 23, 'Madame', 56789001234586, 5020234567891234, '2020-12-28', 4.4),
(24, 4, 24, 'Monsieur', 67890101234587, 5021234567891234, '2021-07-25', 4.3),
(25, 5, 25, 'Madame', 78901201234588, 5022234567891234, '2018-04-02', 4.0),
(26, 6, 26, 'Monsieur', 89012301234589, 5023234567891234, '2019-10-16', 4.6),
(27, 7, 27, 'Madame', 90123401234590, 5024234567891234, '2021-02-22', 4.1),
(28, 8, 28, 'Monsieur', 12345601234591, 5025234567891234, '2020-09-05', 4.3),
(29, 9, 29, 'Madame', 23456701234592, 5026234567891234, '2021-11-11', 4.2),
(30,1, 30, 'Monsieur', 34567801234593, 5027234567891234, '2019-05-09', 3.9),
(31, 1, 31, 'Madame', 45678901234594, 5028234567891234, '2018-08-12', null),
(32, 2, 32, 'Monsieur', 56789001234595, 5029234567891234, '2020-12-28', 4.4),
(33, 3, 33, 'Madame', 67890101234596, 5030234567891234, '2019-11-07', 4.2),
(34,4, 34, 'Monsieur', 78901201234597, 5031234567891234, '2021-04-16', 4.1),
(35, 5, 35, 'Madame', 89012301234598, 5032234567891234, '2020-07-08', 4.3),
(36, 6, 36, 'Monsieur', 90123401234599, 5033234567891234, '2021-09-19', 4.0),
(37, 7, 37, 'Madame', 12345601234600, 5034234567891234, '2020-04-03',null);

-- Insertion de 50 utilisateurs avec mots de passe améliorés
INSERT INTO UTILISATEUR (IDUSER, IDCLIENT, IDCOURSIER, NOMUSER, PRENOMUSER, DATENAISSANCE, EMAILUSER, MOTDEPASSEUSER, TELEPHONE)
VALUES
  (1, 1, 1, 'Talley', 'Chancellor', '2003-08-21', 'eget.massa.suspendisse@icloud.fr', 'FjK72@mK*0vY$', '0601122334'),
  (2, 2, 2, 'Daniels', 'Dai', '1996-01-11', 'tempor.augue@outlook.com', 'CqE41$gKQ8Pl%', '0602233445'),
  (3, 3, 3, 'Bradshaw', 'Veronica', '1987-07-18', 'ornare.tortor.at@outlook.fr', 'BcT67!sWb4Jo$', '0603344556'),
  (4, 4, 4, 'Parker', 'Ayanna', '1983-10-21', 'habitant.morbi@hotmail.fr', 'Mmn87*suH5Wt!', '0604455667'),
  (5, 5, 5, 'Gibson', 'Griffin', '2003-06-26', 'augue@icloud.com', 'GxP44$DkC8hl%', '0605566778'),
  (6, 6, 6, 'Boyer', 'Graiden', '1992-03-11', 'nibh@aol.fr', 'DfQ43$xCg2Rm!', '0606677889'),
  (7, 7, 7, 'Hayes', 'Karleigh', '2004-01-06', 'fringilla.purus.mauris@outlook.com', 'kRu13$sQe1sx$', '0607788990'),
  (8, 8, 8, 'Davidson', 'Thor', '1992-09-15', 'purus.sapien@aol.com', 'nOy04*DfU1qF$', '0608899001'),
  (9, 9, 9, 'Sanchez', 'Ezekiel', '2003-02-22', 'mus@outlook.fr', 'GSh46&nKC1vU%', '0609900112'),
  (10, 10, 10, 'Wood', 'Emi', '2004-03-15', 'tincidunt.pede@yahoo.fr', 'FwK36*rZb5Ts!', '0601011123'),
  (11, 11, 11, 'Hess', 'Nadine', '1987-11-11', 'tellus.non@yahoo.fr', 'cLu91$lTw1Td$', '0602122234'),
  (12, 12, 12, 'Bruce', 'Thor', '1997-06-07', 'pede@hotmail.com', 'lKk54*uJD0Cx$', '0603233345'),
  (13, 13, 13, 'Douglas', 'Faith', '1998-08-04', 'dapibus@icloud.com', 'bEs81*MrD6uJ%', '0604344456'),
  (14, 14, 14, 'Gross', 'Kennan', '1997-05-19', 'parturient.montes@hotmail.fr', 'uDQ36$Drd3Jk!', '0605455567'),
  (15, 15, 15, 'Whitney', 'Oliver', '1995-02-14', 'aliquam.tincidunt@yahoo.com', 'oCL35*nTv3Dh$', '0606566678'),
  (16, 16, 16, 'Norris', 'Caesar', '2000-02-15', 'ipsum.leo@yahoo.com', 'vMe05$CcR2Tt!', '0607677789'),
  (17, 17, 17, 'Stevens', 'Igor', '1991-11-25', 'luctus.aliquet@aol.fr', 'AxR49&pNN3lZ%', '0608788890'),
  (18, 18, 18, 'Porter', 'Ora', '1994-09-06', 'semper.et@icloud.com', 'wRJ76$Lii3Jv!', '0609899001'),
  (19, 19, 19, 'Howe', 'Chaim', '2003-03-04', 'nunc@outlook.com', 'lFy26$aYv5Le$', '0610000112'),
  (20, 20, 20, 'Heath', 'Hayes', '1985-07-31', 'nullam@google.fr', 'sKe09$uRJ1Ki%', '0611122233'),
  (21, 21, 21, 'Saunders', 'Ezra', '1990-10-23', 'molestie.tortor.nibh@icloud.com', 'vVo14$jUm4zS!', '0612233344'),
  (22, 22, 22, 'Merritt', 'Alyssa', '1996-11-06', 'tincidunt.orci@google.fr', 'pLz34$dJR7aU$', '0613344455'),
  (23, 23, 23, 'Dixon', 'Kyra', '1998-10-16', 'vel.convallis@hotmail.fr', 'tHe72&pGu8pG!', '0614455566'),
  (24, 24, 24, 'Randolph', 'Randall', '2004-03-06', 'mauris.blandit.mattis@google.com', 'mXf75$aHg6cE$', '0615566677'),
  (25, 25, 25, 'Welch', 'Akeem', '1988-07-24', 'massa.vestibulum@hotmail.fr', 'zDx64$tGp5oT!', '0616677788'),
  (26, 26, 26, 'Tucker', 'Cade', '1996-10-16', 'dolor@hotmail.com', 'dFx38$Awg7Qa$', '0617788899'),
  (27, 27, 27, 'Buchanan', 'Eric', '2005-10-20', 'mauris.aliquam@yahoo.com', 'yVz02$xIR4Ng%', '0618899901'),
  (28, 28, 28, 'Dejesus', 'Octavia', '1986-10-29', 'enim.nec@yahoo.fr', 'JyV27$qPk7nP!', '0620000012'),
  (29, 29, 29, 'William', 'Stella', '2000-06-12', 'consectetuer.mauris@google.com', 'lYn79$hPc6jO$', '0621121123'),
  (30, 30, 30, 'Collier', 'Murphy', '1995-01-10', 'amet.consectetuer@aol.com', 'gWw21$OUw0dR%', '0622232234'),
  (31, 31, 31, 'Ratliff', 'Allegra', '2005-11-10', 'nec.leo@hotmail.com', 'xGl44$yRq7Ru$', '0623343345'),
  (32, 32, 32, 'Parsons', 'Ariana', '2005-08-17', 'ut@icloud.com', 'uBn36$uQc7La!', '0624454456'),
  (33, 33, 33, 'Willis', 'Lawrence', '2002-02-19', 'duis.risus@hotmail.fr', 'nQr68$IkI4Xe%', '0625565567'),
  (34, 34, 34, 'Kane', 'Jasper', '1996-08-29', 'eu@icloud.fr', 'cWu61$uKv8Et!', '0626676678'),
  (35, 35, 35, 'Vargas', 'Hanna', '1996-07-05', 'mi.ac.mattis@yahoo.com', 'zMn31$pNs2eM$', '0627787789'),
  (36, 36, 36, 'Herrera', 'Britanney', '2003-10-05', 'nulla.aliquet@hotmail.com', 'dLu78$lBq5Ed$', '0628898890'),
  (37, 37, 34, 'Carroll', 'Hedda', '2001-10-16', 'suspendisse.dui@aol.com', 'fNk67$zXc1Xn%', '0630000001'),
  (38, 38, 34, 'Blake', 'Talon', '1998-12-24', 'gravida@hotmail.com', 'tSe11$bNa1Bg!', '0632500001'),
  (39, 39, 34, 'Mccarty', 'Curran', '2004-07-13', 'natoque.penatibus@google.com', 'rDd71$cYk1Ct%', '0630356001'),
  (40, 40, 34, 'Mcclure', 'Mufutau', '1988-09-24', 'elementum@yahoo.fr', 'uCe70$uEf0cX!', '0730014001'),
  (41, 41, 34, 'Johnson', 'Miriam', '1995-01-17', 'pede@google.fr', 'tIa28$qzR0vU%', '0639680001'),
  (42, 42, 34, 'Potter', 'Chadwick', '1998-12-19', 'elementum.at@yahoo.fr', 'gRb32$sPc8jU!', '0632800001'),
  (43, 43, 34, 'Mcgowan', 'Alice', '1995-12-19', 'non.magna@aol.com', 'aZv05$RlP7Ny!', '0630067001'),
  (44, 44, 34, 'Moses', 'Josiah', '1995-10-18', 'enim.etiam@icloud.com', 'pCa21$eOr2vG!', '0630048001'),
  (45, 45, 34, 'Howe', 'Connor', '1988-10-06', 'sem.mollis.dui@hotmail.fr', 'eWe17$zOr0hE%', '0630009101'),
  (46, 46, 34, 'Newman', 'Cole', '1985-08-23', 'dignissim@google.com', 'eIh16$NyZ4Hr!', '0630003701'),
  (47, 47, 34, 'Greene', 'Clarke', '1988-12-30', 'neque.sed@icloud.fr', 'cEd87$dQs0pB!', '0612300001'),
  (48, 48, 34, 'Olsen', 'Fulton', '1991-04-10', 'pharetra@outlook.com', 'eJj61$rJf1Ba!', '0630780001'),
  (49, 49, 34, 'Medina', 'Ebony', '2003-10-16', 'proin@hotmail.com', 'oWj38$nCz7tX%', '0630001402'),
  (50, 50, 34, 'Flores', 'Amelia', '1994-12-11', 'mauris@aol.fr', 'nTl72$tIe5Tf!', '0630000251');

-- Insertion de 100 réservations
INSERT INTO RESERVATION (IDRESERVATION, IDCLIENT, IDADRESSE, IDCOURSIER, DATERESERVATION, HEURERESERVATION)
VALUES
(1, 1, 10, 1, '2024-11-15', '21:30:00'),
(2, 2, 11, 2, '2024-11-16', '14:00:00'),
(3, 3, 12, 3, '2024-11-17', '18:15:00'),
(4, 4, 13, 1, '2024-11-18', '09:00:00'),
(5, 5, 14, 2, '2024-11-19', '10:30:00'),
(6, 6, 15, 3, '2024-11-20', '11:45:00'),
(7, 7, 16, 4, '2024-11-21', '13:00:00'),
(8, 8, 17, 5, '2024-11-22', '13:30:00'),
(9, 9, 18, 6, '2024-11-23', '14:00:00'),
(10, 10, 19, 7, '2024-11-24', '15:00:00'),
(11, 11, 20, 1, '2024-11-25', '16:00:00'),
(12, 12, 21, 2, '2024-11-26', '16:30:00'),
(13, 13, 22, 3, '2024-11-27', '17:00:00'),
(14, 14, 23, 4, '2024-11-28', '17:30:00'),
(15, 15, 24, 5, '2024-11-29', '18:00:00'),
(16, 16, 25, 6, '2024-11-30', '18:30:00'),
(17, 17, 26, 7, '2024-12-01', '19:00:00'),
(18, 18, 27, 1, '2024-12-02', '19:30:00'),
(19, 19, 28, 2, '2024-12-03', '20:00:00'),
(20, 20, 29, 3, '2024-12-04', '20:30:00'),
(21, 21, 30, 4, '2024-12-05', '21:00:00'),
(22, 22, 31, 5, '2024-12-06', '21:30:00'),
(23, 23, 32, 6, '2024-12-07', '22:00:00'),
(24, 24, 33, 7, '2024-12-08', '22:30:00'),
(25, 25, 34, 1, '2024-12-09', '23:00:00'),
(26, 26, 35, 2, '2024-12-10', '23:30:00'),
(27, 27, 36, 3, '2024-12-11', '00:00:00'),
(28, 28, 37, 4, '2024-12-12', '00:30:00'),
(29, 29, 38, 5, '2024-12-13', '01:00:00'),
(30, 30, 39, 6, '2024-12-14', '01:30:00'),
(31, 31, 40, 7, '2024-12-15', '02:00:00'),
(32, 32, 41, 1, '2024-12-16', '02:30:00'),
(33, 33, 42, 2, '2024-12-17', '03:00:00'),
(34, 34, 43, 3, '2024-12-18', '03:30:00'),
(35, 35, 44, 4, '2024-12-19', '04:00:00'),
(36, 36, 45, 5, '2024-12-20', '04:30:00'),
(37, 37, 46, 6, '2024-12-21', '05:00:00'),
(38, 38, 47, 7, '2024-12-22', '05:30:00'),
(39, 39, 48, 1, '2024-12-23', '06:00:00'),
(40, 40, 49, 2, '2024-12-24', '06:30:00');

-- Insertion de cartes bancaires pour 10 clients
INSERT INTO CARTE_BANCAIRE (IDCB, NUMEROCB, DATEEXPIRECB, CRYPTOGRAMME, TYPECARTE, TYPERESEAUX) 
VALUES
(1, 1234567890123456, '2025-12-31', 123, 'Débit', 'MasterCard'),
(2, 2345678901234567, '2026-01-15', 234, 'Débit', 'Visa'),
(3, 3456789012345678, '2024-11-20', 345, 'Débit', 'American Express'),
(4, 4567890123456789, '2027-05-10', 456, 'Crédit', 'Visa'),
(5, 5678901234567890, '2025-08-22', 567, 'Débit', 'MasterCard'),
(6, 6789012345678901, '2026-02-11', 678, 'Crédit', 'American Express'),
(7, 7890123456789012, '2025-09-30', 789, 'Débit', 'MasterCard'),
(8, 8901234567890123, '2028-03-14', 890, 'Crédit', 'Visa'),
(9, 9012345678901234, '2025-06-25', 901, 'Débit', 'American Express'),
(10, 9876543210987654, '2027-10-10', 987, 'Crédit', 'Visa');


-- Insertion de paniers
INSERT INTO PANIER (IDPANIER, PRIX) 
VALUES
(1, 50.00),
(2, 75.50),
(3, 30.20),
(4, 20.00),
(5, 45.00),
(6, 19.99),
(7, 55.75),
(8, 10.50),
(9, 99.99),
(10, 280.00),
(11, 34.95),
(12, 60.00),
(13, 25.50),
(14, 70.80),
(15, 15.25),
(16, 140.00),
(17, 88.10),
(18, 120.00),
(19, 58.00),
(20, 42.95),
(21, 37.00),
(22, 22.50),
(23, 69.99),
(24, 82.40),
(25, 230.00),
(26, 49.95),
(27, 66.60),
(28, 51.20),
(29, 15.10),
(30, 77.80),
(31, 28.30),
(32, 35.50),
(33, 91.00),
(34, 47.70),
(35, 73.00),
(36, 60.90),
(37, 32.40),
(38, 63.50),
(39, 85.30),
(40, 55.60),
(100, 120.00);



-- Insertion de courses
INSERT INTO COURSE (IDCOURSE, IDADRESSE, IDCLIENT, IDPRESTATION, PRIXCOURSE, TEMPSCOURSE, STATUT, NOTECOURSE, COMMENTAIRECOURSE) 
VALUES
(1, 10, 1, 1, 15.00, 30, 'En attente', null,null),
(2, 11, 2, 2, 20.00, 40, 'En cours', 5,null),
(3, 12, 3, 3, 10.00, 20, 'Terminée', null,null),
(4, 13, 4, 4, 25.00, 50, 'En attente', null,null),
(5, 14, 5, 5, 18.00, 30, 'En cours', 4,null),
(6, 15, 6, 1, 12.50, 15, 'Terminée', null,null),
(7, 16, 7, 2, 22.00, 35, 'En attente', 3,null),
(8, 17, 8, 3, 8.00, 10, 'En cours', null,null),
(9, 18, 9, 4, 28.00, 60, 'Terminée', null,null),
(10, 19, 10, 5, 14.50, 25, 'En attente', 1,'La communication n''était pas ouf'),
(11, 20, 11, 1, 20.00, 40, 'En cours', null,null),
(12, 21, 12, 2, 16.50, 30, 'Terminée', 2,null),
(13, 22, 13, 3, 18.00, 45, 'En attente', null,null),
(14, 23, 14, 4, 24.00, 55, 'En cours',4,null),
(15, 24, 15, 5, 19.50, 30, 'Terminée', null,null),
(16, 25, 16, 1, 15.00, 25, 'En attente', null,null),
(17, 26, 17, 2, 21.00, 50, 'En cours', 5,null),
(18, 27, 18, 3, 11.00, 20, 'Terminée', null,null),
(19, 28, 19, 4, 27.00, 60, 'En attente',4,null),
(20, 29, 20, 5, 23.50, 35, 'En cours', null,null),
(21, 30, 21, 1, 19.00, 45, 'Terminée', 2,null),
(22, 31, 22, 2, 25.00, 40, 'En attente', null,null),
(23, 32, 23, 3, 17.00, 30, 'En cours', null,null),
(24, 33, 24, 4, 22.00, 50, 'Terminée', null,null),
(25, 34, 25, 5, 13.00, 20, 'En attente', 1,'Voiture en mauvaise état'),
(26, 35, 26, 1, 30.00, 55, 'En cours', null,null),
(27, 36, 27, 2, 16.00, 25, 'Terminée', null,null),
(28, 37, 28, 3, 18.50, 40, 'En attente', 5,null),
(29, 38, 29, 4, 26.00, 50, 'En cours', 4,null),
(30, 39, 30, 5, 22.50, 35, 'Terminée', null,null),
(31, 40, 31, 1, 24.00, 30, 'En attente', null,null),
(32, 41, 32, 2, 20.50, 45, 'En cours', 2,'Beaucoup d''attente'),
(33, 42, 33, 3, 14.00, 25, 'Terminée', null,null),
(34, 43, 34, 4, 28.00, 50, 'En attente', null,null),
(35, 44, 35, 5, 19.00, 30, 'En cours', 4,null),
(36, 45, 36, 1, 22.50, 40, 'Terminée', null,null),
(37, 46, 37, 2, 17.00, 35, 'En attente', 5,null);



-- Insertion de 100 adresses
INSERT INTO ADRESSE (IDADRESSE, IDCODEPOSTAL, IDVILLE, IDPAYS, IDDEPARTEMENT, LIBELLEADRESSE)
VALUES
(1, 1, 1, 1, 1, '15 rue de la Paix'),
(2, 2, 2, 1, 2, '25 avenue des Brotteaux'),
(3, 3, 3, 1, 3, '8 place de la République'),
(4, 4, 4, 1, 4, '10 rue des Champs Élysées'),
(5, 5, 5, 1, 5, '12 boulevard de la Liberté'),
(6, 6, 6, 1, 6, '30 rue de la Gare'),
(7, 7, 7, 1, 7, '50 avenue de la République'),
(8, 8, 8, 1, 8, '14 rue des Lilas'),
(9, 9, 9, 1, 9, '22 place des Ternes'),
(10, 10, 10, 1, 10, '35 rue des Amandiers'),
(11, 11, 11, 1, 1, '18 avenue de la Bastille'),
(12, 12, 12, 1, 2, '8 rue de la Paix'),
(13, 13, 13, 1, 3, '40 boulevard de la République'),
(14, 14, 14, 1, 4, '7 rue de la Liberté'),
(15, 15, 15, 1, 5, '23 avenue de la Tour'),
(16, 16, 16, 1, 6, '56 rue du Parc'),
(17, 17, 17, 1, 7, '15 rue des Vignes'),
(18, 18, 18, 1, 8, '11 rue des Acacias'),
(19, 19, 19, 1, 9, '27 rue de la Gare'),
(20, 20, 20, 1, 10, '13 boulevard des Fleurs'),
(21, 21, 21, 1, 1, '9 rue des Églantiers'),
(22, 22, 22, 1, 2, '25 avenue du Parc'),
(23, 23, 23, 1, 3, '4 place des Halles'),
(24, 24, 24, 1, 4, '3 rue des Tilleuls'),
(25, 25, 25, 1, 5, '6 rue des Tulipes'),
(26, 26, 26, 1, 6, '1 avenue de la Ville'),
(27, 27, 27, 1, 7, '17 rue des Roses'),
(28, 28, 28, 1, 8, '30 rue des Arcs'),
(29, 29, 29, 1, 9, '24 rue du Chêne'),
(30, 30, 30, 1, 10, '12 avenue des Pervenches'),
(31, 31, 31, 1, 1, '20 rue du Pont'),
(32, 32, 32, 1, 2, '5 rue des Martyrs'),
(33, 33, 33, 1, 3, '22 rue du Marché'),
(34, 34, 34, 1, 4, '8 boulevard du Soleil'),
(35, 35, 35, 1, 5, '14 rue de la Montagne'),
(36, 36, 36, 1, 6, '29 rue des Charmes'),
(37, 37, 37, 1, 7, '19 rue de la Fontaine'),
(38, 38, 38, 1, 8, '21 avenue de la Mer'),
(39, 39, 39, 1, 9, '11 rue des Bois'),
(40, 40, 40, 1, 10, '3 rue des Orangers'),
(41, 41, 41, 1, 1, '5 rue des Cerisiers'),
(42, 42, 42, 1, 2, '9 rue des Lavandes'),
(43, 43, 43, 1, 3, '17 avenue de la Gare'),
(44, 44, 44, 1, 4, '10 boulevard des Forges'),
(45, 45, 45, 1, 5, '12 rue de l’Océan'),
(46, 46, 46, 1, 6, '22 avenue du Loup'),
(47, 47, 47, 1, 7, '18 rue des Cèdres'),
(48, 48, 48, 1, 8, '11 place des Artistes'),
(49, 49, 49, 1, 9, '3 rue de la Borne'),
(50, 50, 50, 1, 10, '7 avenue du Soleil');


-- Insertion de 20 villes
INSERT INTO VILLE (IDVILLE,IDCODEPOSTAL, NOMVILLE)
VALUES
(1, 1,'Paris'),
(2,2, 'Lyon'),
(3, 3,'Marseille'),
(4, 4,'Bordeaux'),
(5,5, 'Nice'),
(6, 6,'Toulouse'),
(7,7, 'Nantes'),
(8, 8,'Strasbourg'),
(9, 9,'Montpellier'),
(10, 10,'Lille'),
(11, 11,'Rennes'),
(12, 12,'Le Havre'),
(13,13, 'Saint-Étienne'),
(14,14, 'Toulon'),
(15,15, 'Angers'),
(16,16, 'Aix-en-Provence'),
(17,17, 'Grenoble'),
(18,18, 'Dijon'),
(19,19, 'Brest'),
(20,20, 'Reims'),
(21,21, 'Le Mans'),
(22,22, 'Amiens'),
(23,23, 'Saint-Denis'),
(24,24, 'Limoges'),
(25,25, 'Clermont-Ferrand'),
(26,26, 'Metz'),
(27,27, 'Perpignan'),
(28,28, 'Besançon'),
(29,29, 'Orléans'),
(30,30, 'Caen'),
(31,31, 'Mulhouse'),
(32,32, 'Rouen'),
(33,33, 'Nancy'),
(34,34, 'Tours'),
(35,35, 'Saint-Nazaire'),
(36,36, 'Poitiers'),
(37,37, 'La Rochelle'),
(38,38, 'Avignon'),
(39,39, 'Cannes'),
(40,40, 'Antibes'),
(41,41, 'Vannes'),
(42,42, 'Chalon-sur-Saône'),
(43,43, 'Chartres'),
(44,44, 'Boulogne-Billancourt'),
(45,45, 'Nîmes'),
(46,46, 'Colmar'),
(47,47, 'Sète'),
(48, 48,'Le Creusot'),
(49,49, 'Sarcelles'),
(50,50, 'Troyes');


-- Insertion de 5 pays
INSERT INTO PAYS (IDPAYS, NOMPAYS, POURCENTAGETVA)
VALUES
(1, 'France', 20.00 ),
(2, 'Belgique', 21.00),
(3, 'Allemagne', 19.00),
(4, 'Espagne', 21.00),
(5, 'Suisse', 8.10);



-- Insertion de 10 départements
INSERT INTO DEPARTEMENT (IDDEPARTEMENT, LIBELLEDEPARTEMENT, CODEDEPARTEMENT)
VALUES
(1, 'Paris', '75'),
(2, 'Rhône', '69'),
(3, 'Bouches-du-Rhône', '13'),
(4, 'Gironde', '33'),
(5, 'Alpes-Maritimes', '06'),
(6, 'Haute-Garonne', '31'),
(7, 'Loire-Atlantique', '44'),
(8, 'Bas-Rhin', '67'),
(9, 'Hérault', '34'),
(10, 'Nord', '59');


-- Insertion de 20 entreprises
INSERT INTO ENTREPRISE (IDENTREPRISE,SIRETENTREPRISE, IDCLIENT, TAILLE, NOMENTREPRISE, IDADRESSE)
VALUES
(1,'12345678901234', 1, 50, 'Entreprise A', 1),
(2,'23456789012345', 2, 200, 'Entreprise B', 2),
(3,'34567890123456', 3, 10, 'Entreprise C', 3),
(4,'45678901234567', 4, 100, 'Entreprise D', 4),
(5,'56789012345678', 5, 150, 'Entreprise E', 5),
(6,'67890123456789', 6, 30, 'Entreprise F', 6),
(7,'78901234567890', 7, 40, 'Entreprise G', 7),
(8,'89012345678901', 8, 70, 'Entreprise H', 8),
(9,'90123456789012', 9, 120, 'Entreprise I', 9),
(10,'01234567890123', 10, 60, 'Entreprise J', 10),
(11,'23456789012346', 11, 80, 'Entreprise K', 11),
(12,'34567890123457', 12, 110, 'Entreprise L', 12),
(13,'45678901234568', 13, 90, 'Entreprise M', 13),
(14,'56789012345679', 14, 40, 'Entreprise N', 14),
(15,'67890123456790', 15, 250, 'Entreprise O', 15),
(16,'78901234567891', 16, 150, 'Entreprise P', 16),
(17,'89012345678902', 17, 300, 'Entreprise Q', 17),
(18,'90123456789013', 18, 200, 'Entreprise R', 18),
(19,'01234567890124', 19, 500, 'Entreprise S', 19),
(20,'98765432109876', 20, 400, 'Entreprise T', 20);

-- Insertion de 7 types de prestations
INSERT INTO TYPE_PRESTATION (IDPRESTATION, LIBELLEPRESTATION, IMAGEPRESTATION)
VALUES
(1, 'UberXL', 'images/UberXL.jpg'),
(2, 'UberX', 'images/UberX.jpg'),
(3, 'Green', 'images/Green.jpg'),
(4, 'Van', 'images/Van.jpg'),
(5, 'Berline', 'images/Berline.jpg'),
(6, 'Comfort', 'images/Comfort.jpg'),
(7, 'Uber Pet', 'images/Uber_Pet.jpg');





-- Insertion de 100 codes postaux
INSERT INTO CODE_POSTAL (IDCODEPOSTAL, CODEPOSTAL)
VALUES
(1, '75001'),
(2, '69001'),
(3, '13001'),
(4, '33000'),
(5, '06000'),
(6, '31000'),
(7, '44000'),
(8, '67000'),
(9, '34000'),
(10, '59000'),
(11, '75002'),
(12, '69002'),
(13, '13002'),
(14, '33001'),
(15, '06001'),
(16, '31001'),
(17, '44001'),
(18, '67001'),
(19, '34001'),
(20, '59001'),
(21, '75003'),
(22, '69003'),
(23, '13003'),
(24, '33002'),
(25, '06002'),
(26, '31002'),
(27, '44002'),
(28, '67002'),
(29, '34002'),
(30, '59002'),
(31, '75004'),
(32, '69004'),
(33, '13004'),
(34, '33003'),
(35, '06003'),
(36, '31003'),
(37, '44003'),
(38, '67003'),
(39, '34003'),
(40, '59003'),
(41, '75005'),
(42, '69005'),
(43, '13005'),
(44, '33004'),
(45, '06004'),
(46, '31004'),
(47, '44004'),
(48, '67004'),
(49, '34004'),
(50, '59004'),
-- 90 autres codes postaux ici
(100, '93000');

-- Insertion de 10 vélos
INSERT INTO VELO (IDVELO, NUMEROVELO, ESTDISPONIBLE) 
VALUES
(1, 10000001, TRUE),  
(2, 10000002, TRUE),  
(3, 10000003, FALSE), 
(4, 10000004, TRUE),  
(5, 10000005, TRUE),  
(6, 10000006, FALSE), 
(7, 10000007, TRUE),  
(8, 10000008, TRUE),  
(9, 10000009, FALSE), 
(10, 10000010, TRUE); 



-- Insertion de 100 relations entre course et réservation
INSERT INTO EST_POUR (IDRESERVATION, IDCOURSE)
VALUES
(1, 1),
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
(37, 37);


-- Insertion de 100 règlements de salaire
INSERT INTO REGLEMENT_SALAIRE (IDREGLEMENT, IDCOURSIER, MONTANT)
VALUES
(1, 1, 1425.50),
(2, 2, 2335.00),
(3, 3, 2415.75),
(4, 4, 2828.00),
(5, 5, 2340.25),
(6, 6, 3022.50),
(7, 7, 1233.00),
(8, 8, 2637.75),
(9, 9, 2527.00),
(10, 10, 2131.50),
(11, 11, 1224.00),
(12, 12, 1829.75),
(13, 13, 1923.25),
(14, 14, 1738.00),
(15, 15, 1632.50),
(16, 16, 2121.00),
(17, 17, 2525.75),
(18, 18, 2434.50),
(19, 19, 2629.00),
(20, 20, 2726.00),
(21, 21, 1830.25),
(22, 22, 1235.50),
(23, 23, 2432.00),
(24, 24, 2323.50),
(25, 25, 2128.50),
(26, 26, 1440.00),
(27, 27, 1336.75),
(28, 28, 1233.25),
(29, 29, 1431.00),
(30, 30, 1525.00),
(31, 31, 1337.00),
(32, 32, 1224.75),
(33, 33, 1229.50),
(34, 34, 1527.25),
(35, 35, 2132.75),
(36, 36, 2421.50);


-- Insertion des liens entre clients et cartes bancaires
INSERT INTO APPARTIENT_2 (IDCB, IDCLIENT)
VALUES
(1, 1),
(2, 2),
(3, 3),
(10, 10);


-- Insertion des paniers et liens avec les clients
INSERT INTO APPARTIENT_3 (IDPANIER, IDCLIENT)
VALUES
(1, 1),
(2, 2),
(3, 3);


-- Insertion des produits dans les paniers
INSERT INTO CONTIENT_2 (IDPANIER, IDPRODUIT)
VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);


-- Insertion des établissements et leur localisation
INSERT INTO EST_SITUE_A_2 (IDETABLISSEMENT, IDPRODUIT)
VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(1, 11),
(2, 12),
(3, 13),
(4, 14),
(5, 15),
(6, 16),
(7, 17),
(8, 18),
(9, 19),
(10, 20),
(1, 21),
(2, 22),
(3, 23),
(4, 24),
(5, 25),
(6, 26),
(7, 27),
(8, 28),
(9, 29),
(10, 30);



-- Insertion dans A_3 (liens entre produits et catégories)
INSERT INTO A_3 (IDPRODUIT, IDCATEGORIE)
VALUES
(1, 1),
(2, 2),
(3, 3);

-- Insertion dans A_COMME_TYPE (liens entre prestations et véhicules)
INSERT INTO A_COMME_TYPE (IDVEHICULE, IDPRESTATION)
VALUES
(1, 1),   
(2, 2),   
(3, 3),   
(4, 4),  
(5, 5);  

-- Insertion des catégories de produits
INSERT INTO CATEGORIE_PRODUIT (IDCATEGORIE, NOMCATEGORIE) VALUES
(1, 'Boissons'),
(2, 'Plats principaux'),
(3, 'Desserts'),
(4, 'Entrées'),
(5, 'Snacks');

-- Insertion dans la table CLIENT
INSERT INTO CLIENT (IDCLIENT, IDENTREPRISE, IDADRESSE, GENRECLIENT, IMAGECLIENT) 
VALUES
(1, 1, 1, 'Monsieur', 'image1.jpg'),
(2, 2, 2, 'Monsieur', 'image2.jpg'),
(3, 3, 3, 'Madame', 'image3.jpg'),
(4, 4, 4, 'Monsieur', 'image4.jpg'),
(5, 5, 5, 'Madame', 'image5.jpg'),
(6, 6, 1, 'Monsieur', 'image6.jpg'),
(7, 7, 2, 'Madame', 'image7.jpg'),
(8, 8, 3, 'Monsieur', 'image8.jpg'),
(9, 9, 4, 'Madame', 'image9.jpg'),
(10, 10, 5, 'Monsieur', 'image10.jpg'),
(11, 11, 1, 'Madame', 'image11.jpg'),
(12, 12, 2, 'Monsieur', 'image12.jpg'),
(13, 13, 3, 'Madame', 'image13.jpg'),
(14, 14, 4, 'Monsieur', 'image14.jpg'),
(15, 15, 5, 'Madame', 'image15.jpg'),
(16, 16, 1, 'Monsieur', 'image16.jpg'),
(17, 17, 2, 'Madame', 'image17.jpg'),
(18, 18, 3, 'Monsieur', 'image18.jpg'),
(19, 19, 4, 'Madame', 'image19.jpg'),
(20, 20, 5, 'Monsieur', 'image20.jpg'),
(21, 1, 1, 'Madame', 'image21.jpg'),
(22, 2, 2, 'Monsieur', 'image22.jpg'),
(23, 3, 3, 'Madame', 'image23.jpg'),
(24, 4, 4, 'Monsieur', 'image24.jpg'),
(25, 5, 5, 'Madame', 'image25.jpg'),
(26, 6, 1, 'Monsieur', 'image26.jpg'),
(27,7, 2, 'Madame', 'image27.jpg'),
(28, 8, 3, 'Monsieur', 'image28.jpg'),
(29, 9, 4, 'Madame', 'image29.jpg'),
(30, 1, 5, 'Monsieur', 'image30.jpg'),
(31, 11, 1, 'Madame', 'image31.jpg'),
(32, 12, 2, 'Monsieur', 'image32.jpg'),
(33, 13, 3, 'Madame', 'image33.jpg'),
(34, 14, 4, 'Monsieur', 'image34.jpg'),
(35, 15, 5, 'Madame', 'image35.jpg'),
(36, 16, 1, 'Monsieur', 'image36.jpg'),
(37, 17, 2, 'Madame', 'image37.jpg'),
(38, 18, 3, 'Monsieur', 'image38.jpg'),
(39, 19, 4, 'Madame', 'image39.jpg'),
(40, 20, 5, 'Monsieur', 'image40.jpg'),
(41, 12, 1, 'Monsieur', 'image41.jpg'),
(42, 13, 2, 'Madame', 'image42.jpg'),
(43, 14, 3, 'Monsieur', 'image43.jpg'),
(44, 4, 4, 'Madame', 'image44.jpg'),
(45,5, 5, 'Monsieur', 'image45.jpg'),
(46, 6, 1, 'Madame', 'image46.jpg'),
(47, 7, 2, 'Monsieur', 'image47.jpg'),
(48, 8, 3, 'Madame', 'image48.jpg'),
(49, 9, 4, 'Monsieur', 'image49.jpg'),
(50, 2, 5, 'Madame', 'image50.jpg');


-- Insertion des commandes
INSERT INTO COMMANDE (IDCOMMANDE, IDPANIER, IDCLIENT, PRIXCOMMANDE, TEMPSCOMMANDE, ESTLIVRAISON, STATUT, DATECOMMANDE, HEURECOMMANDE)
VALUES
(1, 1, 1, 25.50, 30, TRUE, 'En attente', '2024-11-01', '10:15:00'),
(2, 2, 2, 45.75, 45, FALSE, 'Livrée', '2024-11-02', '12:30:00'),
(3, 3, 3, 20.00, 20, TRUE, 'Annulée', '2024-11-03', '14:45:00'),
(4, 4, 4, 30.00, 25, TRUE, 'En attente', '2024-11-04', '16:00:00'),
(5, 5, 5, 50.00, 40, TRUE, 'Livrée', '2024-11-05', '18:30:00'),
(6, 6, 6, 15.00, 10, FALSE, 'Livrée', '2024-11-06', '09:00:00'),
(7, 7, 7, 35.25, 35, TRUE, 'En attente', '2024-11-07', '13:15:00'),
(8, 8, 8, 60.00, 60, TRUE, 'Livrée', '2024-11-08', '17:45:00'),
(9, 9, 9, 40.00, 50, FALSE, 'Annulée', '2024-11-09', '20:00:00'),
(10, 10, 10, 55.25, 30, TRUE, 'En attente', '2024-11-10', '11:30:00');


-- Insertion dans EST_POUR_2 (liens entre réservations et vélos)
INSERT INTO EST_POUR_2 (IDRESERVATION, IDVELO)
VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- Insertion des véhicules
INSERT INTO VEHICULE (IDVEHICULE, IDCOURSIER, IMMATRICULATION, MARQUE, MODELE, CAPACITE, ACCEPTEANIMAUX, ESTELECTRIQUE, ESTCONFORTABLE, ESTRECENT, ESTLUXUEUX, COULEUR)
VALUES
(1, 1, 'AB-123-CD', 'Toyota', 'Yaris', 4, TRUE, FALSE, TRUE, TRUE, FALSE, 'Rouge'),
(2, 2, 'EF-456-GH', 'Peugeot', '208', 4, FALSE, FALSE, TRUE, TRUE, FALSE, 'Bleu'),
(3, 3, 'IJ-789-KL', 'Renault', 'Clio', 4, TRUE, FALSE, TRUE, TRUE, FALSE, 'Blanc'),
(4, 4, 'MN-012-OP', 'Mercedes', 'Classe A', 5, FALSE, TRUE, TRUE, TRUE, TRUE, 'Noir'),
(5, 5, 'QR-345-ST', 'BMW', 'Serie 3', 5, FALSE, FALSE, TRUE, TRUE, TRUE, 'Gris');


-----------------------------------------------------------

alter table ADRESSE
   add constraint FK_ADRESSE_EST_DANS_CODE_POS foreign key (IDCODEPOSTAL)
      references CODE_POSTAL (IDCODEPOSTAL)
      on delete restrict on update restrict;

alter table ADRESSE
   add constraint FK_ADRESSE_EST_DANS__VILLE foreign key (IDVILLE)
      references VILLE (IDVILLE)
      on delete restrict on update restrict;

alter table ADRESSE
   add constraint FK_ADRESSE_EST_DANS__PAYS foreign key (IDPAYS)
      references PAYS (IDPAYS)
      on delete restrict on update restrict;

alter table ADRESSE
   add constraint FK_ADRESSE_EST_DANS__DEPARTEM foreign key (IDDEPARTEMENT)
      references DEPARTEMENT (IDDEPARTEMENT)
      on delete restrict on update restrict;

alter table APPARTIENT_2
   add constraint FK_APPARTIE_APPARTIEN_CARTE_BA foreign key (IDCB)
      references CARTE_BANCAIRE (IDCB)
      on delete restrict on update restrict;

alter table APPARTIENT_2
   add constraint FK_APPARTIE_APPARTIEN_CLIENT foreign key (IDCLIENT)
      references CLIENT (IDCLIENT)
      on delete restrict on update restrict;

alter table APPARTIENT_3
   add constraint FK_APPARTIE_APPARTIEN_PANIER foreign key (IDPANIER)
      references PANIER (IDPANIER)
      on delete restrict on update restrict;

alter table APPARTIENT_3
   add constraint FK_APPARTIE_APPARTIEN_CLIENT foreign key (IDCLIENT)
      references CLIENT (IDCLIENT)
      on delete restrict on update restrict;

alter table A_3
   add constraint FK_A_3_A_3_PRODUIT foreign key (IDPRODUIT)
      references PRODUIT (IDPRODUIT)
      on delete restrict on update restrict;

alter table A_3
   add constraint FK_A_3_A_4_CATEGORI foreign key (IDCATEGORIE)
      references CATEGORIE_PRODUIT (IDCATEGORIE)
      on delete restrict on update restrict;

alter table A_COMME_TYPE
   add constraint FK_A_COMME__A_COMME_T_VEHICULE foreign key (IDVEHICULE)
      references VEHICULE (IDVEHICULE)
      on delete restrict on update restrict;

alter table A_COMME_TYPE
   add constraint FK_A_COMME__A_COMME_T_TYPE_PRE foreign key (IDPRESTATION)
      references TYPE_PRESTATION (IDPRESTATION)
      on delete restrict on update restrict;

alter table CLIENT
   add constraint FK_CLIENT_FAIT_PART_ENTREPRI foreign key (IDENTREPRISE)
      references ENTREPRISE (IDENTREPRISE)
      on delete restrict on update restrict;

alter table CLIENT
   add constraint FK_CLIENT_HABITE_ADRESSE foreign key (IDADRESSE)
      references ADRESSE (IDADRESSE)
      on delete restrict on update restrict;

alter table COMMANDE
   add constraint FK_COMMANDE_CONTIENT_PANIER foreign key (IDPANIER)
      references PANIER (IDPANIER)
      on delete restrict on update restrict;

alter table COMMANDE
   add constraint FK_COMMANDE_PASSE_CLIENT foreign key (IDCLIENT)
      references CLIENT (IDCLIENT)
      on delete restrict on update restrict;

alter table CONTIENT_2
   add constraint FK_CONTIENT_CONTIENT__PANIER foreign key (IDPANIER)
      references PANIER (IDPANIER)
      on delete restrict on update restrict;

alter table CONTIENT_2
   add constraint FK_CONTIENT_CONTIENT__PRODUIT foreign key (IDPRODUIT)
      references PRODUIT (IDPRODUIT)
      on delete restrict on update restrict;

alter table COURSE
   add constraint FK_COURSE_A_2_TYPE_PRE foreign key (IDPRESTATION)
      references TYPE_PRESTATION (IDPRESTATION)
      on delete restrict on update restrict;

alter table COURSE
   add constraint FK_COURSE_RECOIT_FA_CLIENT foreign key (IDCLIENT)
      references CLIENT (IDCLIENT)
      on delete restrict on update restrict;

alter table COURSE
   add constraint FK_COURSE_SE_FINIT_ADRESSE foreign key (IDADRESSE)
      references ADRESSE (IDADRESSE)
      on delete restrict on update restrict;

alter table COURSIER
   add constraint FK_COURSIER_EST_CONDU_RESERVAT foreign key (IDRESERVATION)
      references RESERVATION (IDRESERVATION)
      on delete restrict on update restrict;

alter table COURSIER
   add constraint FK_COURSIER_FAIT_PART_ENTREPRI foreign key (IDENTREPRISE)
      references ENTREPRISE (IDENTREPRISE)
      on delete restrict on update restrict;

alter table ENTREPRISE
   add constraint FK_ENTREPRI_FAIT_PART_CLIENT foreign key (IDCLIENT)
      references CLIENT (IDCLIENT)
      on delete restrict on update restrict;

alter table ENTREPRISE
   add constraint FK_ENTREPRI_SE_SITUE__ADRESSE foreign key (IDADRESSE)
      references ADRESSE (IDADRESSE)
      on delete restrict on update restrict;

alter table EST_POUR
   add constraint FK_EST_POUR_EST_POUR_RESERVAT foreign key (IDRESERVATION)
      references RESERVATION (IDRESERVATION)
      on delete restrict on update restrict;

alter table EST_POUR
   add constraint FK_EST_POUR_EST_POUR2_COURSE foreign key (IDCOURSE)
      references COURSE (IDCOURSE)
      on delete restrict on update restrict;

alter table EST_POUR_2
   add constraint FK_EST_POUR_EST_POUR__RESERVAT foreign key (IDRESERVATION)
      references RESERVATION (IDRESERVATION)
      on delete restrict on update restrict;

alter table EST_POUR_2
   add constraint FK_EST_POUR_EST_POUR__VELO foreign key (IDVELO)
      references VELO (IDVELO)
      on delete restrict on update restrict;

alter table EST_SITUE_A_2
   add constraint FK_EST_SITU_EST_SITUE_PRODUIT foreign key (IDPRODUIT)
      references PRODUIT (IDPRODUIT)
      on delete restrict on update restrict;

alter table EST_SITUE_A_2
   add constraint FK_EST_SITU_EST_SITUE_ETABLISS foreign key (IDETABLISSEMENT)
      references ETABLISSEMENT (IDETABLISSEMENT)
      on delete restrict on update restrict;

alter table ETABLISSEMENT
   add constraint FK_ETABLISS_EST_SITUE_ADRESSE foreign key (IDADRESSE)
      references ADRESSE (IDADRESSE)
      on delete restrict on update restrict;

alter table REGLEMENT_SALAIRE
   add constraint FK_REGLEMEN_RECOIT_RE_COURSIER foreign key (IDCOURSIER)
      references COURSIER (IDCOURSIER)
      on delete restrict on update restrict;

alter table RESERVATION
   add constraint FK_RESERVAT_EST_CONDU_COURSIER foreign key (IDCOURSIER)
      references COURSIER (IDCOURSIER)
      on delete restrict on update restrict;

alter table RESERVATION
   add constraint FK_RESERVAT_PEUT_CLIENT foreign key (IDCLIENT)
      references CLIENT (IDCLIENT)
      on delete restrict on update restrict;

alter table RESERVATION
   add constraint FK_RESERVAT_SE_SITUE_ADRESSE foreign key (IDADRESSE)
      references ADRESSE (IDADRESSE)
      on delete restrict on update restrict;

alter table UTILISATEUR
   add constraint FK_UTILISAT_HERITAGE__COURSIER foreign key (IDCOURSIER)
      references COURSIER (IDCOURSIER)
      on delete restrict on update restrict;

alter table UTILISATEUR
   add constraint FK_UTILISAT_HERITAGE__CLIENT foreign key (IDCLIENT)
      references CLIENT (IDCLIENT)
      on delete restrict on update restrict;

alter table VEHICULE
   add constraint FK_VEHICULE_APPARTIEN_COURSIER foreign key (IDCOURSIER)
      references COURSIER (IDCOURSIER)
      on delete restrict on update restrict;

alter table VILLE
  add constraint FK_VILLE_APPARTIEN_CODE_POS foreign key (IDCODEPOSTAL)
      references CODE_POSTAL (IDCODEPOSTAL)
      on delete restrict on update restrict;

