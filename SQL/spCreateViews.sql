DELIMITER $$

DROP PROCEDURE IF EXISTS createViews$$
CREATE PROCEDURE createViews()
BEGIN
	
	DROP VIEW IF EXISTS vwUF; 
	CREATE VIEW vwUF AS 
		-- SELECT uf.id , uf.nroUF, ma.nroManzana , ad.nroAdherente , ad.nombre, ad.apellido, ed.nroEdificio , ddd.valor AS codDepartamento , d.valor  AS codSitLegal , uf.coeficiente, dd.valor AS codAlquila
		SELECT uf.id , uf.nroUF, ma.id AS idManzana, ma.nroManzana , ad.nroAdherente, ad.nombre, ad.apellido, ed.nroEdificio , uf.codDepartamento, ddd.valor AS departamento , uf.codSitLegal, d.valor  AS sitLegal, uf.coeficiente, uf.codAlquila, dd.valor AS alquila
		FROM uf  
		INNER JOIN manzanas   ma   ON uf.idManzana = ma.id
		INNER JOIN adherentes ad   ON uf.idAdherente = ad.id
		INNER JOIN edificios  ed   ON uf.idEdificio = ed.id
		INNER JOIN diccionario d   ON uf.codSitLegal = d.codigo
		INNER JOIN diccionario dd  ON uf.codAlquila = dd.codigo
		INNER JOIN diccionario ddd ON uf.codDepartamento = ddd.codigo;
	
	
	DROP VIEW IF EXISTS vwCtasCtes;
	CREATE VIEW vwCtasCtes AS 
		SELECT uf.id, uf.nroUF, ma.nroManzana, ad.nroAdherente , CONCAT(ad.apellido,', ', ad.nombre) AS adherente, SUM(cc.saldo) AS 'saldo'
		FROM ctasctes cc
        INNER JOIN uf  ON cc.idUF = uf.id
        INNER JOIN adherentes ad ON uf.idAdherente = ad.id
        INNER JOIN manzanas ma ON uf.idManzana = ma.id
		GROUP BY uf.id;
        

	DROP VIEW IF EXISTS vwLiquidacionesGlobales;
	CREATE VIEW vwLiquidacionesGlobales AS 
		SELECT lg.*, d.valor  AS codEstadoText , li.fechaEmision    
		FROM liquidacionesglobales lg 
		INNER JOIN Diccionario d ON lg.codEstado = d.codigo
		INNER JOIN FondosEspeciales fe ON fe.idLiquidacionGlobal = lg.id
		INNER JOIN Liquidaciones li ON fe.idLiquidacion = li.id
		UNION
		SELECT lg.*, d.valor  AS codEstadoText , li.fechaEmision    
		FROM liquidacionesglobales lg 
		INNER JOIN Diccionario d ON lg.codEstado = d.codigo
		INNER JOIN Expensas ex ON ex.idLiquidacionGlobal = lg.id
		INNER JOIN Liquidaciones li ON ex.idLiquidacion = li.id;

	DROP VIEW IF EXISTS vwGastosExpensa;
	CREATE VIEW vwGastosExpensa AS 
		SELECT ma.id AS nroManzana, uf.nroUF, gl.codConceptoGasto, cg.ConceptoGasto, gl.detalle, ge.monto
		FROM GastosExpensas ge
        INNER JOIN GastosLiquidaciones gl ON ge.idGastosLiquidaciones = gl.id
        INNER JOIN ConceptosGastos cg ON gl.codConceptoGasto = cg.codigo
		INNER JOIN Expensas ex ON ge.idExpensa = ex.id
		INNER JOIN ctasctes cc ON ex.idLiquidacion = cc.idLiquidacion
		INNER JOIN UF  ON  UF.id = cc.idUF
		INNER JOIN manzanas ma ON uf.idManzana = ma.id;
		
		
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
		FROM Ctasctes cc
		INNER JOIN UF ON cc.idUF = UF.id
		INNER JOIN Liquidaciones li ON cc.idLiquidacion = li.id
		INNER JOIN FondosEspeciales fe ON fe.idLiquidacion = li.id
		INNER JOIN LiquidacionesGlobales lg ON lg.id = fe.idLiquidacionGlobal
		WHERE li.saldoMonto < 0 
		UNION
		SELECT ex.idLiquidacion, li.fechaEmision, UF.idManzana, UF.id as 'idUF', UF.nroUF, cc.descripcion AS 'detalle', 
			   li.monto AS 'montoOriginal', li.interesAcumulado AS 'montoInteres', lg.primerVencimiento AS 'vencimiento',
			  (li.saldoInteres + li.saldoMonto) AS 'montoPagar' , (li.interesAcumulado + li.monto) - (li.saldoInteres + li.saldoMonto) AS 'montoPagado'
		FROM Ctasctes cc
		INNER JOIN UF ON cc.idUF = UF.id
		INNER JOIN Liquidaciones li ON cc.idLiquidacion = li.id
		INNER JOIN Expensas ex  ON ex.idLiquidacion = li.id
		INNER JOIN LiquidacionesGlobales lg ON lg.id = ex.idLiquidacionGlobal
		WHERE li.saldoMonto < 0
		UNION
		SELECT nd.idLiquidacion, li.fechaEmision, UF.idManzana, UF.id as 'idUF', UF.nroUF, cc.descripcion AS 'detalle', 
			   li.monto AS 'montoOriginal', li.interesAcumulado AS 'montoInteres',	nd.fechaVencimiento AS 'vencimiento',
			   (li.saldoInteres + li.saldoMonto) AS 'montoPagar', (li.interesAcumulado + li.monto) - (li.saldoInteres + li.saldoMonto) AS 'montoPagado'
        FROM Ctasctes cc
		INNER JOIN UF ON cc.idUF = UF.id
		INNER JOIN Liquidaciones li ON cc.idLiquidacion = li.id
		INNER JOIN NotasDebito nd  ON nd.idLiquidacion = li.id
		WHERE li.saldoMonto < 0 ;
		

END$$

DELIMITER ;
