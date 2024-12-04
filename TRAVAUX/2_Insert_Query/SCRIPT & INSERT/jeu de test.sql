select e.idetablissement, nometablissement, typeetablissement, libellecategorieprestation, descriptioncategorieprestation, a.libelleadresse, v.nomville from etablissement e
	join a_comme_categorie ac on ac.idetablissement = e.idetablissement
	join categorie_prestation cp on cp.idcategorieprestation = ac.idcategorieprestation
	join adresse a on a.idadresse = e.idadresse
	join ville v on a.idville = v.idville

select nomproduit, nomcategorie from produit p
	join a_3 a on a.idproduit = p.idproduit
	join categorie_produit cp on cp.idcategorie = a.idcategorie

select p.idproduit, nomproduit, e.idetablissement, nometablissement from produit p
	join est_situe_a_2 es on es.idproduit = p.idproduit
	join etablissement e on e.idetablissement = es.idetablissement

select * from produit

select * from a_3

select * from categorie_produit

select * from etablissement

select * from a_comme_categorie

select * from categorie_prestation
