-- check_adresse_difference adresse départ ne peut pas être égal à adresse arrivée
CREATE OR REPLACE FUNCTION check_adresse_difference()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.IDADRESSE = NEW.ADR_IDADRESSE THEN
        RAISE EXCEPTION 'IDADRESSE ne peut pas être égal à ADR_IDADRESSE';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_check_adresse_difference
BEFORE INSERT OR UPDATE ON COURSE
FOR EACH ROW
EXECUTE FUNCTION check_adresse_difference();

-- check_statut_update statut d'une course terminée ne peut pas être modifié
CREATE OR REPLACE FUNCTION check_statut_update() 
RETURNS TRIGGER AS $$
BEGIN
    IF OLD.STATUTCOURSE = 'Terminée' THEN
        RAISE EXCEPTION 'Le statut d''une course terminée ne peut pas être modifié';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_check_statut_update
BEFORE UPDATE ON COURSE
FOR EACH ROW
EXECUTE FUNCTION check_statut_update();

-- empecher_modif empêche l'insertion, la suppression et la modification de la table departement
CREATE OR REPLACE FUNCTION empecher_modif()
RETURNS trigger AS $$
BEGIN
    RAISE EXCEPTION 'Impossible de modifier les données des départements !';
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER bf_del_upd_departement
BEFORE UPDATE OR DELETE ON departement
EXECUTE PROCEDURE empecher_modif();