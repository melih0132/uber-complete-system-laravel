-- Les produits et établissements avec leurs prix respective
select nometablissement, nomproduit, prixproduit
from etablissement e
join est_situe_a_2 est on est.idetablissement = e.idetablissement
join produit p on p.idproduit = est.idproduit

-- Les adresses des établissements
select nometablissement, libelleadresse, nomville, nompays
from etablissement e
join adresse a on e.idadresse = a.idadresse
join ville v on a.idville = v.idville
join pays p on a.idpays = p.idpays

-- 	Détail des users
select genreclient, nomuser, prenomuser, emailuser, libelleadresse, nomville, nompays
from client c
join utilisateur u on u.idclient = c.idclient
join adresse a on c.idadresse = a.idadresse
join ville v on a.idville = v.idville
join pays p on a.idpays = p.idpays
join entreprise e on c.identreprise = e.identreprise
where p.nompays = 'France'

-- Détails des coursiers
select genrecoursier, nomuser, prenomuser, nomentreprise, numerocartevtc, notemoyenne, datedebutactivite
from coursier c
join entreprise e on c.identreprise = e.identreprise
join utilisateur u on u.idcoursier = c.idcoursier

-- Réservations
select r.idreservation, nomuser, prenomuser, datereservation, heurereservation
from reservation r
join client c on r.idclient = c.idclient
join utilisateur u on u.idclient = c.idclient
order by r.datereservation

-- Facture des courses des clients
select genreclient, nomuser, prenomuser, emailuser, c.prixcourse , c.tempscourse, c.statut, c.notecourse, c.commentairecourse
from client cl
join utilisateur u on u.idclient = cl.idclient
join course c on cl.idclient = c.idclient

-- Commandes des clients
select nomuser, prenomuser, emailuser, co.prixcommande, co.tempscommande,  co.estlivraison, co.statut, co.datecommande, co.heurecommande
from client cl
join utilisateur u on u.idclient = cl.idclient
join appartient_3 a on cl.idclient = a.idclient
join panier p on a.idpanier = p.idpanier
join commande co on p.idpanier = co.idpanier

-- Les voitures des coursiers
select genrecoursier, nomuser, prenomuser, emailuser, v.marque, v.modele
from coursier c
join utilisateur u on u.idcoursier = c.idcoursier
join vehicule v on c.idcoursier = v.idcoursier