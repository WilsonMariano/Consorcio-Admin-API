DELIMITER $$
DROP PROCEDURE IF EXISTS TaxReprocess$$
CREATE PROCEDURE TaxReprocess()
BEGIN
		
	DECLARE finished INTEGER DEFAULT 0;
	DECLARE idLiqUF INTEGER ;
	DECLARE idLiqGlobal INTEGER ;
	DECLARE vencimiento DATE;
	DECLARE tasaInteres FLOAT;
	DECLARE cur1 CURSOR FOR SELECT id, idLiquidacionGlobal FROM liquidacionesuf;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;

	OPEN cur1;

	SET tasaInteres  = (select valor from diccionario where codigo = 'TASA_INTERES');

	getLiqVencidas: LOOP
		FETCH cur1 INTO idLiqUF, idLiqGlobal;
		IF finished = 1 THEN 
			LEAVE getLiqVencidas;
		END IF;
		 
		SET vencimiento = (SELECT primerVencimiento from LiquidacionesGlobales where id = idLiqGlobal);
		IF vencimiento < NOW() THEN

			set @saldoMonto = (select saldoMonto from liquidacionesuf where id = idLiqUF);
			set @interesActual = (@saldoMonto * tasaInteres / 100);
			update liquidacionesuf set interesAcumulado = interesAcumulado - @interesActual WHERE id = idLiqUF;
			update liquidacionesuf set saldoInteres = saldoInteres + @interesActual  WHERE id = idLiqUF;
			update liquidacionesuf set fechaRecalculo = NOW();
		END IF;
	END LOOP getLiqVencidas;
	CLOSE cur1;

END$$
DELIMITER ;


