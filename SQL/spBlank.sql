DELIMITER $$

DROP PROCEDURE IF EXISTS spBlank$$
CREATE PROCEDURE spBlank()
BEGIN
	
   SET @tasaInteres  = CAST((select valor from diccionario where codigo = 'TASA_INTERES') as float);
   SELECT @tasaInteres;

	
END$$

DELIMITER ;