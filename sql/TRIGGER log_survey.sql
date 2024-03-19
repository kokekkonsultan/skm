DELIMITER $$

CREATE TRIGGER log_app_cst65
AFTER INSERT
ON responden_cst65 FOR EACH ROW
BEGIN
        INSERT INTO log_survey_cst65(log_value, log_time)
        VALUES(CONCAT(NEW.nama_lengkap, ', sedang mengisi survey.'), NOW());
END$$

DELIMITER ;
