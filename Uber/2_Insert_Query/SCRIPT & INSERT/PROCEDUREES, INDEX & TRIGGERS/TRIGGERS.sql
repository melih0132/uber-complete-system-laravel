-- 1. Empêcher les commandes de plus de 500€
CREATE OR REPLACE FUNCTION check_commande_prix()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.PRIXCOMMANDE > 500 THEN
        RAISE EXCEPTION 'Le montant d''une commande ne peut pas dépasser 500€';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_check_commande_prix
BEFORE INSERT OR UPDATE ON COMMANDE
FOR EACH ROW
EXECUTE FUNCTION check_commande_prix();

-- 2. Vérifier que les coursiers et livreurs ont au moins 18 ans
CREATE OR REPLACE FUNCTION check_age()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.DATENAISSANCE > CURRENT_DATE - INTERVAL '18 years' THEN
        RAISE EXCEPTION 'Les coursiers et livreurs doivent avoir au moins 18 ans';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_check_age_coursier
BEFORE INSERT OR UPDATE ON COURSIER
FOR EACH ROW
EXECUTE FUNCTION check_age();

CREATE TRIGGER trg_check_age_livreur
BEFORE INSERT OR UPDATE ON LIVREUR
FOR EACH ROW
EXECUTE FUNCTION check_age();

-- 3. Valider le paiement des courses et commandes
CREATE OR REPLACE FUNCTION check_payment()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.STATUTCOMMANDE = 'Paiement validé' AND NEW.IDCB IS NULL THEN
        RAISE EXCEPTION 'Une commande validée doit avoir un moyen de paiement';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_check_payment_commande
BEFORE INSERT OR UPDATE ON COMMANDE
FOR EACH ROW
EXECUTE FUNCTION check_payment();

-- 4. Mise à jour de la disponibilité des vélos après réservation
CREATE OR REPLACE FUNCTION update_velo_disponibilite()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE VELO SET ESTDISPONIBLE = FALSE WHERE IDVELO = NEW.IDVELO;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_velo_disponibilite
AFTER INSERT ON VELO_RESERVATION
FOR EACH ROW
EXECUTE FUNCTION update_velo_disponibilite();

-- 5. Enregistrement automatique de la date de création des courses et commandes
CREATE OR REPLACE FUNCTION set_creation_time()
RETURNS TRIGGER AS $$
BEGIN
    NEW.HEURECREATION = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_set_creation_time_commande
BEFORE INSERT ON COMMANDE
FOR EACH ROW
EXECUTE FUNCTION set_creation_time();

CREATE TRIGGER trg_set_creation_time_course
BEFORE INSERT ON COURSE
FOR EACH ROW
EXECUTE FUNCTION set_creation_time();

-- 6. Mise à jour de la note moyenne du coursier ou livreur après évaluation
CREATE OR REPLACE FUNCTION update_avg_rating()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.NOTECOURSE IS NOT NULL THEN
        UPDATE COURSIER
        SET NOTEMOYENNE = (
            SELECT AVG(NOTECOURSE) FROM COURSE WHERE IDCOURSIER = NEW.IDCOURSIER AND NOTECOURSE IS NOT NULL
        )
        WHERE IDCOURSIER = NEW.IDCOURSIER;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_avg_rating
AFTER INSERT OR UPDATE ON COURSE
FOR EACH ROW
EXECUTE FUNCTION update_avg_rating();

-- 7. Vérifier qu'un coursier ne prenne pas deux courses en même temps
CREATE OR REPLACE FUNCTION check_coursier_availability()
RETURNS TRIGGER AS $$
BEGIN
    IF (SELECT COUNT(*) FROM COURSE WHERE IDCOURSIER = NEW.IDCOURSIER AND STATUTCOURSE = 'En cours') > 0 THEN
        RAISE EXCEPTION 'Un coursier ne peut pas prendre deux courses en même temps';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_check_coursier_availability
BEFORE INSERT OR UPDATE ON COURSE
FOR EACH ROW
EXECUTE FUNCTION check_coursier_availability();

-- 8. Empêcher une réservation de vélo si le vélo n'est pas disponible
CREATE OR REPLACE FUNCTION check_velo_disponibilite()
RETURNS TRIGGER AS $$
BEGIN
    IF (SELECT ESTDISPONIBLE FROM VELO WHERE IDVELO = NEW.IDVELO) = FALSE THEN
        RAISE EXCEPTION 'Ce vélo est déjà réservé ou indisponible';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_check_velo_disponibilite
BEFORE INSERT ON VELO_RESERVATION
FOR EACH ROW
EXECUTE FUNCTION check_velo_disponibilite();
