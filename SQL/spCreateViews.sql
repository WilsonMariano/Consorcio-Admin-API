DELIMITER $$

DROP PROCEDURE IF EXISTS createViews$$
CREATE PROCEDURE createViews()
BEGIN
	
	DROP VIEW IF EXISTS vwUF; 
	CREATE VIEW vwUF AS 
		-- SELECT UF.id , UF.nroUF, ma.nroManzana , ad.nroAdherente , ad.nombre, ad.apellido, ed.nroEdificio , ddd.valor AS codDepartamento , d.valor  AS codSitLegal , UF.coeficiente, dd.valor AS codAlquila
		SELECT UF.id , UF.nroUF, ma.id AS idManzana, ma.nroManzana , ad.nroAdherente, ad.nombre, ad.apellido, ed.nroEdificio , UF.codDepartamento, ddd.valor AS departamento , UF.codSitLegal, d.valor  AS sitLegal, UF.coeficiente, UF.codAlquila, dd.valor AS alquila
		FROM UF  
		INNER JOIN Manzanas   ma   ON UF.idManzana = ma.id
		INNER JOIN Adherentes ad   ON UF.idAdherente = ad.id
		INNER JOIN Edificios  ed   ON UF.idEdificio = ed.id
		INNER JOIN Diccionario d   ON UF.codSitLegal = d.codigo
		INNER JOIN Diccionario dd  ON UF.codAlquila = dd.codigo
		INNER JOIN Diccionario ddd ON UF.codDepartamento = ddd.codigo;
	
	
	DROP VIEW IF EXISTS vwCtasCtes;
	CREATE VIEW vwCtasCtes AS 
		SELECT UF.id, UF.nroUF, ma.nroManzana, ad.nroAdherente , CONCAT(ad.apellido,', ', ad.nombre) AS adherente, SUM(cc.saldo) AS 'saldo'
		FROM CtasCtes cc
        INNER JOIN UF  ON cc.idUF = UF.id
        INNER JOIN Adherentes ad ON UF.idAdherente = ad.id
        INNER JOIN Manzanas ma ON UF.idManzana = ma.id
		GROUP BY UF.id;
        

	DROP VIEW IF EXISTS vwLiquidacionesGlobales;
	CREATE VIEW vwLiquidacionesGlobales AS 
		SELECT lg.*, d.valor  AS codEstadoText , li.fechaEmision    
		FROM LiquidacionesGlobales lg 
		INNER JOIN Diccionario d ON lg.codEstado = d.codigo
		INNER JOIN FondosEspeciales fe ON fe.idLiquidacionGlobal = lg.id
		INNER JOIN Liquidaciones li ON fe.idLiquidacion = li.id
		UNION
		SELECT lg.*, d.valor  AS codEstadoText , li.fechaEmision    
		FROM LiquidacionesGlobales lg 
		INNER JOIN Diccionario d ON lg.codEstado = d.codigo
		INNER JOIN Expensas ex ON ex.idLiquidacionGlobal = lg.id
		INNER JOIN Liquidaciones li ON ex.idLiquidacion = li.id;

	DROP VIEW IF EXISTS vwGastosExpensa;
	CREATE VIEW vwGastosExpensa AS 
		SELECT ma.id AS nroManzana, UF.nroUF, gl.codConceptoGasto, cg.ConceptoGasto, gl.detalle, ge.monto
		FROM GastosExpensas ge
        INNER JOIN GastosLiquidaciones gl ON ge.idGastosLiquidaciones = gl.id
        INNER JOIN ConceptosGastos cg ON gl.codConceptoGasto = cg.codigo
		INNER JOIN Expensas ex ON ge.idExpensa = ex.id
		INNER JOIN CtasCtes cc ON ex.idLiquidacion = cc.idLiquidacion
		INNER JOIN UF  ON  UF.id = cc.idUF
		INNER JOIN Manzanas ma ON UF.idManzana = ma.id;
		
		
	DROP VIEW IF EXISTS vwGastosFull;
	CREATE VIEW vwGastosFull AS 
		SELECT gl.id AS 'idGastosLiquidaciones', cg.conceptoGasto, dc.valor AS 'tipoEntidad', rg.idManzana, rg.nroEntidad, ge.monto
		FROM GastosExpensas ge
		INNER JOIN GastosLiquidaciones gl ON ge.idGastosLiquidaciones = gl.id
		INNER JOIN RelacionesGastos rg ON gl.id = rg.idGastosLiquidaciones
		INNER JOIN ConceptosGastos cg ON gl.codConceptoGasto = cg.codigo
		INNER JOIN Diccionario dc ON rg.entidad = dc.codigo;
		
		
	DROP VIEW IF EXISTS vwDeudasUF;
	CREATE VIEW vwDeudasUF AS
		SELECT fe.idLiquidacion, li.fechaEmision, UF.idManzana, UF.id as 'idUF', UF.nroUF, cc.descripcion AS 'detalle', 
			   li.monto AS 'montoOriginal', li.interesAcumulado AS 'montoInteres', lg.primerVencimiento AS 'vencimiento',
			  (li.saldoInteres + li.saldoMonto) AS 'montoPagar' , (li.interesAcumulado + li.monto) - (li.saldoInteres + li.saldoMonto) AS 'montoPagado'
		FROM CtasCtes cc
		INNER JOIN UF ON cc.idUF = UF.id
		INNER JOIN Liquidaciones li ON cc.idLiquidacion = li.id
		INNER JOIN FondosEspeciales fe ON fe.idLiquidacion = li.id
		INNER JOIN LiquidacionesGlobales lg ON lg.id = fe.idLiquidacionGlobal
		WHERE li.saldoMonto < 0 
		UNION
		SELECT ex.idLiquidacion, li.fechaEmision, UF.idManzana, UF.id as 'idUF', UF.nroUF, cc.descripcion AS 'detalle', 
			   li.monto AS 'montoOriginal', li.interesAcumulado AS 'montoInteres', lg.primerVencimiento AS 'vencimiento',
			  (li.saldoInteres + li.saldoMonto) AS 'montoPagar' , (li.interesAcumulado + li.monto) - (li.saldoInteres + li.saldoMonto) AS 'montoPagado'
		FROM CtasCtes cc
		INNER JOIN UF ON cc.idUF = UF.id
		INNER JOIN Liquidaciones li ON cc.idLiquidacion = li.id
		INNER JOIN Expensas ex  ON ex.idLiquidacion = li.id
		INNER JOIN LiquidacionesGlobales lg ON lg.id = ex.idLiquidacionGlobal
		WHERE li.saldoMonto < 0
		UNION
		SELECT nd.idLiquidacion, li.fechaEmision, UF.idManzana, UF.id as 'idUF', UF.nroUF, cc.descripcion AS 'detalle', 
			   li.monto AS 'montoOriginal', li.interesAcumulado AS 'montoInteres',	nd.fechaVencimiento AS 'vencimiento',
			   (li.saldoInteres + li.saldoMonto) AS 'montoPagar', (li.interesAcumulado + li.monto) - (li.saldoInteres + li.saldoMonto) AS 'montoPagado'
        FROM CtasCtes cc
		INNER JOIN UF ON cc.idUF = UF.id
		INNER JOIN Liquidaciones li ON cc.idLiquidacion = li.id
		INNER JOIN NotasDebito nd  ON nd.idLiquidacion = li.id
		WHERE li.saldoMonto < 0 ;
		

END$$

DELIMITER ;
