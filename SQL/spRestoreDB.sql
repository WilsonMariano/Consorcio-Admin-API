DELIMITER $$

DROP PROCEDURE IF EXISTS restoreDB$$
CREATE PROCEDURE restoreDB()
BEGIN
	
	drop table if exists Adherentes;
	drop table if exists Comprobantes;
	drop table if exists ConceptosGastos;
	drop table if exists CtasCtes;
	drop table if exists DetallesComprobante;
	drop table if exists Diccionario;
	drop table if exists Edificios;
	drop table if exists Expensas;
	drop table if exists Feriados;
	drop table if exists FondosEspeciales;
	drop table if exists GastosExpensas;
	drop table if exists GastosLiquidaciones;
	drop table if exists Liquidaciones;
	drop table if exists LiquidacionesGlobales;
	drop table if exists Manzanas;
	drop table if exists MovimientosFP;
	drop table if exists MovimientosFR;
	drop table if exists MovimientosFondosEsp;
	drop table if exists NotasDebito;
	drop table if exists RelacionesGastos;
	drop table if exists UF;
	drop table if exists Usuarios;
	
	
	create table Adherentes (
		id            int  unsigned auto_increment  primary key,
		nroAdherente  int,
		nombre        varchar(50) not null,
		apellido      varchar(50) not null,
		nroDocumento  int(8)      not null,
		telefono      varchar(20),
		email         varchar(80) not null,
		INDEX         (id, apellido(4))
	);
	insert into Adherentes(nroAdherente, nombre, apellido, nroDocumento, telefono, email) values
		(01,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(02,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(03,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(04,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(05,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(06,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(07,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(08,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(09,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(10,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(11,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(12,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(13,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(14,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(15,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(16,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(17,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(18,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(19,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(20,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(21,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(22,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(23,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(24,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(25,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(26,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(27,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(28,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(29,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(30,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(31,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(32,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(33,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(34,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(35,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(36,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(37,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(38,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com"),
		(39,"Pablo"  , "Valenzuela", 32444555, "1155336655", "pablo86v@gmail.com"),
		(40,"Mariano", "Wilson"    , 32666777, "1122338899", "mgw009@gmail.com");


	create table Comprobantes (
		id                   int unsigned auto_increment  primary key,
		nroComprobante       int not null,
		codMedioPago         varchar(20),
		montoTotal           decimal(13,2),
		INDEX                (id, nroComprobante)
	);


	create table ConceptosGastos (
		id             int unsigned auto_increment  primary key,
		codigo         varchar(3) not null,
		conceptoGasto  varchar(80) not null,
		INDEX          (id,codigo)
	);
	insert into ConceptosGastos (codigo, conceptoGasto) values 
		('FOT', 'FOTOCOPIAS'),
		('EDE', 'EDESUR'),
		('MET', 'METROGAS'),
		('SUE', 'SUELDOS Y JORNALES'),
		('VIA', 'VIÁTICOS');


	create table CtasCtes (
		id             int  unsigned auto_increment  primary key,
		idUF           int not null,
		idLiquidacion  int,
		monto          decimal(13,2) not null,
		descripcion    varchar(80),
		fecha          date not null,
		saldo          decimal(13,2) not null,
		INDEX          (id, idUF, descripcion(4))
	);
	-- insert into CtasCtes (idUf, idLiquidacion, monto, descripcion, fecha, saldo) values
	-- 	(1, 1, -2200, "Nota debito", "2019-11-30", -2200),
	-- 	(1, 2, -1300, "LIQUIDACION EXPENSA PERIODO 12/19", "2020-01-04", -1300),
	-- 	(2, 3, -1400, "LIQUIDACION FONDO RESERVA 12/19", "2020-01-04", -1400),
	-- 	(2, 4, -1400, "Nota debito", "2020-01-01", -1400);

	create table DetallesComprobante (
        id                   int unsigned auto_increment  primary key,
        idCtaCte             int not null,
        idComprobante        int not null,
        monto                decimal(13,2) not null,
        interes              decimal(13,2) not null,
		INDEX                (id, idComprobante)
	);	


	create table Diccionario (
		id      int unsigned auto_increment  primary key,
		codigo  varchar(30) not null,
		valor   varchar(80) not null,
		INDEX   (id,codigo)
	);
	insert into Diccionario (codigo, valor) values
		('COD_SIT_LEGAL_1'        ,'ESCRITURA'),
		('COD_SIT_LEGAL_2'        ,'JUDICIAL'),
		('COD_SIT_LEGAL_3'        ,'SENTENCIA'),
		('COD_SIT_LEGAL_4'        ,'SALDO EN CUOTAS'),
		('COD_ALQ_1'              ,'PROPIETARIO'),
		('COD_ALQ_2'              ,'INQUILINO SIN CONTRATO'),
		('COD_ALQ_3'              ,'INQUILINO CON CONTRATO'),
		('COD_DEPARTAMENTO_1'     ,'A'),
		('COD_DEPARTAMENTO_2'     ,'B'),
		('COD_DEPARTAMENTO_3'     ,'C'),
		('COD_DEPARTAMENTO_4'     ,'D'),
		('TIPO_ENTIDAD_1'         ,'MANZANA'),
		('TIPO_ENTIDAD_2'         ,'EDIFICIO'),
		('TIPO_ENTIDAD_3'         ,'UF' ),
		('MEDIO_PAGO_1'           ,'EFECTIVO'),
		('MEDIO_PAGO_2'           ,'BANCO'),
		('TASA_INTERES'           ,'2'),
		('COD_ESTADO_1'           ,'ABIERTA'),
		('COD_ESTADO_2'           ,'CERRADA'),
		('TAX_INQ_S_CONTRATO'     ,'00'),
		('TAX_INQ_C_CONTRATO'     ,'00'),
		('FERIADO_INAMOVIBLE'     ,'INAMOVIBLE'),
		('FERIADO_OPTATIVO'       ,'OPTATIVO'),
		('TIPO_LIQ_1'             ,'EXPENSAS'),
		('TIPO_LIQ_2'             ,'FONDO DE RESERVA'),
		('TIPO_LIQ_3'             ,'FONDO DE PREVISION'),
		('TXT_LIQ_EXPENSA'        ,'LIQUIDACION EXPENSAS PERIODO');


	create table Edificios (
		id           int unsigned auto_increment  primary key,
		idManzana    int not null,
		nroEdificio  int not null,
		cantUF       int,
		INDEX        (id,idManzana)
	);
	insert into Edificios (idManzana, nroEdificio, cantUF) values 
		(1, 01, 12),
		(1, 03, 12),
		(2, 03, 12),
		(2, 04, 12),
		(3, 05, 12),
		(3, 06, 12);


	create table Expensas (
			id                   int unsigned auto_increment  primary key,
			idLiquidacion        int not null,
			idLiquidacionGlobal  int not null,
			coeficiente          float,
			INDEX                (id, idLiquidacion)
		);
		-- insert into Expensas (idLiquidacion, idLiquidacionGlobal, coeficiente) values
		-- 	(2, 1, "2.00");


	create table Feriados (
		id           int unsigned auto_increment  primary key,
		dia          varchar(2) not null,
		mes          varchar(2) not null,
		anio         varchar(2),
		tipo         varchar(30) not null,
		descripcion  varchar (100),
		INDEX        (id,dia,mes)
	);
	insert into Feriados(dia, mes, tipo, descripcion) values
		('01', '01','FERIADO_INAMOVIBLE', 'Año nuevo'),
		('24', '03','FERIADO_INAMOVIBLE', 'Día Nacional de la Memoria por la Verdad y la Justicia'),
		('02', '04','FERIADO_INAMOVIBLE', 'Día del Veterano y de los Caídos en la Guerra de Malvinas'),
		('01', '05','FERIADO_INAMOVIBLE', 'Día del trabajador');


	create table FondosEspeciales (
		id                   int unsigned auto_increment  primary key,
		idLiquidacion        int not null,
		idLiquidacionGlobal  int not null,
		tipoLiquidacion      varchar(30),
		INDEX                (id, idLiquidacion)
	);
	-- insert into FondosEspeciales (idLiquidacion, idLiquidacionGlobal, tipoLiquidacion) values 
	-- 	(3, 1, "TIPO_LIQ_2");


	create table GastosExpensas (
		id                     int unsigned auto_increment  primary key,
		idExpensa              int not null,
		idGastosLiquidaciones  int not null,
		monto                  decimal(13,2) not null,
		INDEX                  (id,idExpensa)
	);


	create table GastosLiquidaciones (
		id                   int unsigned auto_increment  primary key,
		idLiquidacionGlobal  int not null,
		monto                decimal(13,2) not null,
		codConceptoGasto     varchar(3)  ,
		detalle              varchar(80),
		INDEX                (id,idLiquidacionGlobal,codConceptoGasto)
	);
	insert into GastosLiquidaciones (idLiquidacionGlobal, monto, codConceptoGasto) values 
		(01, 25000.00, 'MET'),
		(01, 4300.00 , 'FOT'),
		(01, 30000.00, 'EDE'),
		(02, 15000.00, 'MET'),
		(02, 1300.00 , 'FOT'),
		(02, 10000.00, 'EDE');


	create table Liquidaciones (
		id             	     int  unsigned auto_increment  primary key,
		idUF                 int not null,
		interesAcumulado     decimal(13,2),
		saldoInteres         decimal(13,2),
		monto                decimal(13,2),
		saldoMonto           decimal(13,2),
		fechaRecalculo       date,
		fechaEmision         date,
		tasaInteres          double,
		INDEX                (id,idUF)
	);
	-- insert into Liquidaciones (idUF, interesAcumulado, saldoInteres, monto, saldoMonto) values
	-- 	(1,100,100,2200,2200),
	-- 	(1,50,20,1300,1300),
	-- 	(2,60,0,1400,400),
	-- 	(2,60,0,1400,0);


	create table LiquidacionesGlobales (
		id                  int  unsigned auto_increment  primary key,
		mes                 int(2) not null,
		anio                int(4) not null,
		primerVencimiento   date,
		segundoVencimiento  date,
		codEstado           varchar(30),
		INDEX               (id, mes, anio)
	);
	insert into LiquidacionesGlobales (mes, anio, primerVencimiento, segundoVencimiento, codEstado) values
		(05, 2020, '2020-06-25', '2020-06-30', 'COD_ESTADO_1' ),
		(06, 2020, '2020-06-25', '2020-06-30', 'COD_ESTADO_1' );


	create table Manzanas (
		id                       int  unsigned auto_increment  primary key,
		nroManzana               int,
		mtsCuadrados             float not null,
		tipoVivienda             varchar(10),
		nombreConsorcio          varchar(80) not null,
		montoFondoReserva        float,
		montoFondoPrevision      float,
		INDEX                    (id, nroManzana)
	);
	insert into Manzanas (nroManzana, mtsCuadrados, tipoVivienda, nombreConsorcio, montoFondoReserva, montoFondoPrevision) values
		(140, 9611.73, 'CHALET', 'MUTUAL DE EMP. DE COMERCIO DE ALTE BROWN', 350, 130),
		(141, 7088.16, 'DEPTO' , 'MUTUAL DE EMP. DE COMERCIO DE ALTE BROWN', 350, 130),
		(142, 6323.62, 'CHALET', 'MUTUAL DE EMP. DE COMERCIO DE ALTE BROWN', 350, 130),
		(143, 74.53  , 'CHALET', 'MUTUAL DE EMP. DE COMERCIO DE ALTE BROWN', 350, 130);
	
	
	create table MovimientosFP(
		id                   int unsigned auto_increment  primary key,
		idMovimientoFondoEsp int not null,
		idLiquidacionGlobal  int,
		INDEX                (id, idMovimientoFondoEsp)	
	);


	create table MovimientosFR (
		id                   int unsigned auto_increment  primary key,
		idMovimientoFondoEsp int not null,
		idGastoLiquidacion   int,
		INDEX                (id, idMovimientoFondoEsp)
	);
	
	
	create table MovimientosFondosEsp (
		id                int unsigned auto_increment  primary key,
		idManzana         int not null,
		monto             float,
		descripcion       varchar(80),
		saldo             float,
		tipoLiquidacion   varchar(30),
		INDEX             (id, idManzana)
	);


	create table NotasDebito (
		id                int unsigned auto_increment  primary key,
		idLiquidacion     int not null,
		fechaVencimiento  date,
		fechaEmision      date,
		observaciones     varchar(80),
		INDEX             (id,idLiquidacion)
	);
	-- insert into NotasDebito (idLiquidacion, fechaVencimiento, fechaEmision) values
		-- (1, "2019-12-31", "2019-12-20"),
		-- (4, "2020-01-31", "2019-12-31");


	-- La entidad puede ser Manzana, Edificio o UF. Número corresponde a esta entidad.
	create table RelacionesGastos (
		id                     int unsigned auto_increment  primary key,
		idGastosLiquidaciones  int not null,
		entidad                varchar(20),
		idManzana                 int,
		nroEntidad                 int,
		INDEX                  (id,idGastosLiquidaciones)
	);
	insert into RelacionesGastos (idGastosLiquidaciones, entidad, idManzana, nroEntidad ) values
		(01, 'TIPO_ENTIDAD_1', 1, 140),
		(01, 'TIPO_ENTIDAD_1', 2, 141),
		(02, 'TIPO_ENTIDAD_2', 1, 3),
		(03, 'TIPO_ENTIDAD_3', 1, 1),
		(04, 'TIPO_ENTIDAD_1', 1, 140),
		(04, 'TIPO_ENTIDAD_1', 2, 141),
		(05, 'TIPO_ENTIDAD_2', 1, 3),
		(06, 'TIPO_ENTIDAD_3', 1, 1);
  	
	
	create table UF (
		id               int  unsigned auto_increment  primary key,
		idManzana        int not null,
		idAdherente      int not null,
		idEdificio       int,
		nroUF            int,
		piso             int,
		codDepartamento  varchar(30),
		codSitLegal      varchar(30) not null,
		coeficiente      float not null,
		codAlquila       varchar(30) not null,
		INDEX            (id, idManzana, nroUF)
	);	
	insert into UF (idManzana, idAdherente, nroUF, idEdificio, piso, codDepartamento, codSitLegal, coeficiente, codAlquila) values
		(1, 1,  1,  3, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_1', 0.025, 'COD_ALQ_1'),
		(1, 2,  2,  4, 1, 'COD_DEPARTAMENTO_2', 'COD_SIT_LEGAL_2', 0.086, 'COD_ALQ_2'),
		(1, 3,  3,  5, 0, 'COD_DEPARTAMENTO_3', 'COD_SIT_LEGAL_3', 0.055, 'COD_ALQ_3'),
		(1, 4,  4,  3, 1, 'COD_DEPARTAMENTO_4', 'COD_SIT_LEGAL_1', 0.056, 'COD_ALQ_1'),
		(1, 5,  5,  4, 2, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_2', 0.065, 'COD_ALQ_2'),
		(1, 6,  6,  5, 0, 'COD_DEPARTAMENTO_3', 'COD_SIT_LEGAL_3', 0.097, 'COD_ALQ_3'),
		(1, 7,  7,  3, 0, 'COD_DEPARTAMENTO_2', 'COD_SIT_LEGAL_1', 0.022, 'COD_ALQ_1'),
		(1, 8,  8,  4, 0, 'COD_DEPARTAMENTO_4', 'COD_SIT_LEGAL_2', 0.096, 'COD_ALQ_2'),
		(1, 9,  9,  5, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_3', 0.052, 'COD_ALQ_3'),
		(1, 10, 10, 3, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_1', 0.055, 'COD_ALQ_1'),
		(1, 11, 11, 4, 0, 'COD_DEPARTAMENTO_2', 'COD_SIT_LEGAL_2', 0.093, 'COD_ALQ_2'),
		(1, 12, 12, 5, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_3', 0.054, 'COD_ALQ_3'),
		(1, 13, 13, 3, 0, 'COD_DEPARTAMENTO_3', 'COD_SIT_LEGAL_1', 0.026, 'COD_ALQ_1'),
		(1, 14, 14, 4, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_2', 0.046, 'COD_ALQ_2'),
		(1, 15, 15, 5, 0, 'COD_DEPARTAMENTO_4', 'COD_SIT_LEGAL_3', 0.075, 'COD_ALQ_3'),
		(1, 16, 16, 3, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_1', 0.097, 'COD_ALQ_1'),
		(2, 17, 1,  4, 0, 'COD_DEPARTAMENTO_3', 'COD_SIT_LEGAL_2', 0.095, 'COD_ALQ_2'),
		(2, 18, 2,  5, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_3', 0.087, 'COD_ALQ_3'),
		(2, 19, 3,  3, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_1', 0.065, 'COD_ALQ_1'),
		(2, 20, 4,  4, 0, 'COD_DEPARTAMENTO_2', 'COD_SIT_LEGAL_2', 0.076, 'COD_ALQ_2'),
		(2, 21, 5,  3, 0, 'COD_DEPARTAMENTO_2', 'COD_SIT_LEGAL_1', 0.085, 'COD_ALQ_1'),
		(2, 22, 6,  4, 0, 'COD_DEPARTAMENTO_3', 'COD_SIT_LEGAL_2', 0.077, 'COD_ALQ_2'),
		(2, 23, 7,  5, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_3', 0.082, 'COD_ALQ_3'),
		(2, 24, 8,  3, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_1', 0.079, 'COD_ALQ_1'),
		(2, 25, 9,  4, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_2', 0.082, 'COD_ALQ_2'),
		(2, 26, 10, 5, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_3', 0.085, 'COD_ALQ_3'),
		(2, 27, 11, 3, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_1', 0.093, 'COD_ALQ_1'),
		(2, 28, 12, 4, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_2', 0.094, 'COD_ALQ_2'),
		(3, 29, 1,  5, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_3', 0.9,   'COD_ALQ_3'),
		(3, 30, 2,  3, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_1', 0.3,   'COD_ALQ_1'),
		(3, 31, 3,  4, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_2', 0.6,   'COD_ALQ_2'),
		(3, 32, 4,  5, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_3', 0.9,   'COD_ALQ_3'),
		(3, 33, 5,  3, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_1', 0.3,   'COD_ALQ_1'),
		(3, 34, 6,  4, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_2', 0.6,   'COD_ALQ_2'),
		(3, 35, 7,  5, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_3', 0.9,   'COD_ALQ_3'),
		(3, 36, 8,  3, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_1', 0.3,   'COD_ALQ_1'),
		(3, 37, 9,  4, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_2', 0.6,   'COD_ALQ_2'),
		(3, 38, 10, 5, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_3', 0.9,   'COD_ALQ_3'),
		(3, 39, 11, 3, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_1', 0.3,   'COD_ALQ_1'),
		(3, 40, 12, 4, 0, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_2', 0.6,   'COD_ALQ_2');
	
	-- ****************** TEST   **************************
	-- insert into UF (idManzana, nroAdherente, nroUF, idEdificio, codDepartamento, codSitLegal, coeficiente, codAlquila) values
		-- (140, 01, 01, 01, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_1', 0.750, 'COD_ALQ_1'),
		-- (140, 02, 02, 03, 'COD_DEPARTAMENTO_2', 'COD_SIT_LEGAL_2', 0.250, 'COD_ALQ_2'),
		-- (141, 17, 01, 03, 'COD_DEPARTAMENTO_3', 'COD_SIT_LEGAL_2', 0.800, 'COD_ALQ_2'),
		-- (141, 18, 02, 04, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_3', 0.200, 'COD_ALQ_3'),
		-- (142, 29, 01, 05, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_3', 0.9, 'COD_ALQ_3'),
		-- (142, 30, 02, 06, 'COD_DEPARTAMENTO_1', 'COD_SIT_LEGAL_1', 0.1, 'COD_ALQ_1');
	

	create table Usuarios (
		id        int  unsigned auto_increment  primary key,
		email     varchar(80) not null,
		password  varchar(30) not null,
		nombre    varchar(30) not null,
		apellido  varchar(30) not null,
		INDEX     (id, email(6))
	);
	insert into Usuarios (email, password,  nombre, apellido) values
		("pablo86v@gmail.com", "1234", "Pablo", "Valenzuela"),
		("mgw009@gmail.com", "1234", "Mariano", "Wilson");
	

END$$

DELIMITER ;

