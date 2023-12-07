CREATE OR REPLACE TRIGGER CheckAdopterConstraint
BEFORE INSERT ON AdoptersInfo
FOR EACH ROW
DECLARE
    v_count INT;
BEGIN
    SELECT COUNT(*) INTO v_count FROM Adopt WHERE adopterID = :new.adopterID;

    IF v_count = 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'Every Adopter must be associated with at least one Animal.');
    END IF;
END;