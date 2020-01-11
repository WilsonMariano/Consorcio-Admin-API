<?php   

include_once __DIR__ . '/../_FuncionesEntidades.php';
include_once __DIR__ . '/../Helper.php';
include_once __DIR__ . '/../Expensas.php';
include_once __DIR__ . '/../Manzanas.php';
include_once __DIR__ . '/../Diccionario.php';
include_once __DIR__ . '/../Liquidaciones.php';
include_once __DIR__ . '/../UF.php';
include_once __DIR__ . '/../enums/LiqGlobalStatesEnum.php';
include_once __DIR__ . '/../enums/EntityTypeEnum.php';
include_once __DIR__ . '/../enums/RentalContractEnum.php';


class ExpensaApi{
 
	// Variables de clase
	private static $arrLiquidaciones = array();
	private static $arrExpensas = array();
	private static $idLiqGlobal;
	
	
	/**	
	 * Verifica si una liquidación global está en estado "abierta".
	 */
	private static function IsOpen($idLiqGlobal){
		$liquidacionGlobal = Funciones::GetOne($idLiqGlobal, "LiquidacionesGlobales");
		if($liquidacionGlobal)
			return $liquidacionGlobal->codEstado == LiqGlobalStatesEnum::Abierta;
		else 
			throw new Exception("No se pudo encontrar la liquidacion informada. Reintente.");
	}	

	/**
	 * Cierra la liquidación global procesada en el request. Actualiza el campo codEstado.
	 */
	private static function CloseLiquidacionGlobal(){
		$liquidacionGlobal = Funciones::GetOne(self::$idLiqGlobal, "LiquidacionesGlobales");
		$liquidacionGlobal->codEstado = LiqGlobalStatesEnum::Cerrada;
		Funciones::UpdateOne($liquidacionGlobal);
	}

	/**
	 * Obtiene un idExpensa, ya sea del array de clase arrExpensas o generando un id nuevo 
	 * (se crea la expensa y guardamos el id nuevo en el array).
	 */
	private static function GetIdExpensa($uf){
		// Se utilizan arrays de clase para evitar consultar a la bd innecesariamente; iremos guardando aquí las expensas y liqudaciones.
		// Además aseguramos que se genere una única expensa por cada UF.
		if(!is_null(self::$arrLiquidaciones)){
			foreach (self::$arrLiquidaciones as $liquidacion){
				if($liquidacion->idUF == $uf['id']){
					foreach (self::$arrExpensas as $expensa){
						if($expensa->idLiquidacion == $liquidacion->id){
							return $expensa->id;
						}
					}
				}
			}
		}
		// Sino existe en el array generamos una nueva expensa 
		$newExpensa = self::NewExpensa($uf);
		array_push(self::$arrExpensas, $newExpensa);
		return $newExpensa->id;
	}

	/**
	 * Genera una nueva expensa en la BD. Devuelve el objeto generado.
	 */
	private static function NewExpensa($uf){
		$expensa = new Expensas();
		$expensa->idLiquidacion = self::GetIdLiquidacion($uf);
		$expensa->idLiquidacionGlobal = self::$idLiqGlobal;
		$expensa->coeficiente = $uf['coeficiente'];

		$newId = Funciones::InsertOne($expensa);
		if($newId < 1){
			throw new Exception("No se pudo generar una expensa nueva para una de las unidades funcionales.");
		}else{
			$expensa->id = $newId;
			return $expensa;
		}
	}

	private static function GetIdLiquidacion($uf){
		if(!is_null(self::$arrLiquidaciones)){
			foreach (self::$arrLiquidaciones as $liquidacion){
				if($liquidacion->idUF == $uf['id']){
					return $liquidacion->id;
				}
			}
		}
		// Sino existe en el array generamos una nueva expensa 
		$newLiquidacion = self::NewLiquidacion($uf);
		array_push(self::$arrLiquidaciones, $newLiquidacion);
		return $newLiquidacion->id;

	}

	private static function NewLiquidacion($uf){
		$liquidacion = new Liquidaciones();
		$liquidacion->idUF = $uf['id'];
		$liquidacion->fechaEmision = date("Y-m-d");
		$liquidacion->tasaInteres = Diccionario::GetValue("TASA_INTERES");

		$newId = Funciones::InsertOne($liquidacion);
		if($newId < 1){
			throw new Exception("No se pudo generar una liquidación nueva para una de las unidades funcionales.");
		}else{
			$liquidacion->id = $newId;
			return $liquidacion;
		}
	}

	/**
	 * Actualiza algunos campos (que inicialmente se graban como null) en todas las liquidaciones por unidad funcional.
	 * Utiliza los array de clase que contienen las liquidacionesUF y sus montos.
	 */
	private static function UpdateLiquidacionesUF(){

		foreach(self::$arrLiquidaciones as $liquidacion){
			$liquidacion->saldoMonto = $liquidacion->monto * -1;
			self::SetCtaCteAndGetId($liquidacion);
			
			if(!Funciones::UpdateOne($liquidacion))
				throw new Exception("No se pudo actualizar el monto en una de las liquidaciones por unidad funcional.");
		}
	}

	/**
	 * Gestiona el insert de un nuevo registro en la tabla CtasCTes y devuelve el id generado por la BD.
	 */
	private static function SetCtaCteAndGetId($liquidacion){
		// Obtengo el periodo a liquidar de la liquidacion global.
		$liqGbl = Funciones::GetOne(self::$idLiqGlobal, "LiquidacionesGlobales");

		$ctaCte = new CtasCtes();
		$ctaCte->idUF = $liquidacion->idUF;
		$ctaCte->idLiquidacion = $liquidacion->id;
		$ctaCte->fecha = date("Y-m-d");
		$ctaCte->descripcion = "LIQUIDACION EXPENSA PERIODO " . $liqGbl->mes . "/" . $liqGbl->anio;
		$ctaCte->monto = $liquidacion->monto * -1;
		$saldoActual = Helper::NumFormat(CtasCtes::GetLastSaldo($liquidacion->idUF) ?? 0);
		$ctaCte->saldo = $saldoActual - $liquidacion->monto;
		
		$newId =  Funciones::InsertOne($ctaCte);
		if($newId < 1)
			throw new Exception("No se pudo actualizar uno de los movimientos en las cuentas corrientes.");
		else
			return $newId;
	}

	/**
	 * Aplica un gasto a todas las unidades funcionales de una manzana.
	 * Recibe por parámetro el id de la manzana, el monto del gasto y el id de la LiquidacionGlobal.
	 */
	private static function ApplyExpenseToManzana($idManzana, $montoGastoManzana, $idGastoLiquidacion){
		$arrUF = UF::GetByIdManzana($idManzana);			
	
		foreach ($arrUF as $uf){
			$montoGastoUF = Helper::NumFormat($montoGastoManzana) * $uf['coeficiente'];		 
			self::SaveGastoAndAccumulateAmount($uf, $montoGastoUF, $idGastoLiquidacion);
		}
	}

	/**
	 * Aplica un gasto a todas las unidades funcionales de un edificio.
	 * Recibe por parámetro el número de edificio, el monto del gasto y el id de la LiquidacionGlobal.
	 */
	private static function ApplyExpenseToEdificio($idManzana, $nroEdificio, $montoGastoEdificio, $idGastoLiquidacion){
		$cantUF = Edificios::GetByManzanaAndNumero($idManzana, $nroEdificio)->cantUF;
		$montoGastoUF = Helper::NumFormat($montoGastoEdificio) / $cantUF;

		$arrUF = UF::GetByManzanaAndEdificio($idManzana, $nroEdificio);				  
		foreach ($arrUF as $uf)
			self::SaveGastoAndAccumulateAmount($uf, $montoGastoUF, $idGastoLiquidacion);
	}

	/**
	 * Aplica un gasto a una unidad funcional.
	 * Recibe por parámetro un id de UF, el monto del gasto y el id de la liquidacion global.
	 */
	private static function ApplyExpenseToUF($idManzana, $nroUF, $montoGasto, $idGastoLiquidacion){
		$uf = UF::GetByManzanaAndNumero($idManzana, $nroUF);
		self::SaveGastoAndAccumulateAmount($uf, $montoGasto, $idGastoLiquidacion);
	}

	/**
	 * Guarda el gasto en la bd y acumula el monto del gasto en la expensa correspondiente.
	 */
	private static function SaveGastoAndAccumulateAmount($uf, $montoGasto, $idGastoLiquidacion){
		$monto = Helper::NumFormat($montoGasto);
		self::InsertGastoExpensa($uf, $monto, $idGastoLiquidacion);
		foreach (self::$arrLiquidaciones as $liquidacion){
			if($liquidacion->idUF == $uf['id']){
				$liquidacion->monto += self::CheckContractTax($uf, $monto);
				break;
			}
		}
	}

	/**
	 * Guarda un gastoExpensa en la BD.
	 * Recibe instancia de la clase UF, el monto del gastoUF para dicha UF y el id de la liquidacion global.
	 */
	private static function InsertGastoExpensa($uf, $montoGastoUF, $idGastoLiquidacion){
		$gastoUF = new GastosExpensas();
		$gastoUF->idExpensa = self::GetIdExpensa($uf);
		$gastoUF->idGastosLiquidaciones = $idGastoLiquidacion;
		$gastoUF->monto = $montoGastoUF;

		if(Funciones::InsertOne($gastoUF) < 1){
			throw new Exception("No se pudo guardar un gasto en la liquidación de la unidad funcional.");
		}
	}

	/**
	 * Verifica la situación del contrato de la UF y aplica recargos cuando corresponda.
	 */
	private static function CheckContractTax($uf, $montoGasto){
		$tax = 0;
		if($uf['codAlquila'] == RentalContractEnum::InquilinoSinContrato)
			$tax = Helper::NumFormat(Diccionario::GetValue(RentalContractEnum::InquilinoSinContrato));
		elseif($uf['codAlquila'] == RentalContractEnum::InquilinoConContrato)
			$tax = Helper::NumFormat(Diccionario::GetValue(RentalContractEnum::InquilinoConContrato));

		return $montoGasto += ($montoGasto * $tax) / 100;
	}

	/**
	 * Procesa una liquidaciónGlobal generando las liquidaciones para cada unidad funcional. Se asume que previamente están cargados todos los GastosLiquidaciones correctamente.
	 * Recibe via httpParam un idLiquidacionGlobal.
	 */
	public static function ProcessExpenses($request, $response, $args){
		try{  
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$objetoAccesoDato->beginTransaction();
					   
			self::$idLiqGlobal = $request->getParsedBody()[0];
			If(!self::IsOpen(self::$idLiqGlobal))
				throw new Exception("La liquidación ya se encuentra cerrada.");

			$arrGastosLiq = GastosLiquidaciones::GetByLiquidacionGlobal(self::$idLiqGlobal);

			for($i = 0; $i < sizeof($arrGastosLiq); $i++){
				$arrRelacionesGastos = RelacionesGastos::GetByIdGastoLiquidacion($arrGastosLiq[$i]["id"]);   
				if(sizeof($arrRelacionesGastos)==1){
					// Si hay solo una relacion , aplico calculo según tipo entidad.
					switch ($arrRelacionesGastos[0]["entidad"]) {
						case EntityTypeEnum::Manzana :
							self::ApplyExpenseToManzana(
								$arrRelacionesGastos[0]["idManzana"], $arrGastosLiq[$i]["monto"], $arrGastosLiq[$i]["id"]);
							break;
						case EntityTypeEnum::Edificio :
							self::ApplyExpenseToEdificio(
								$arrRelacionesGastos[0]["idManzana"], $arrRelacionesGastos[0]["nroEntidad"], $arrGastosLiq[$i]["monto"], $arrGastosLiq[$i]["id"]);
							break;
						case EntityTypeEnum::UnidadFuncional :
							self::ApplyExpenseToUF(
								$arrRelacionesGastos[0]["idManzana"], $arrRelacionesGastos[0]["nroEntidad"], $arrGastosLiq[$i]["monto"], $arrGastosLiq[$i]["id"]);
							break;
					}
				}
				else // Else: el gasto está relacionado con varias entidades (en este punto solo pueden ser manzanas). Calcular porcentaje de c/ manzana.
				{
					// Extraigo solo el nroManzana de las relaciones de cada gasto.
					$arrManzanas = array_map(function($var) { return $var['nroEntidad']; }, $arrRelacionesGastos);
					
					// Proceso el gasto por cada manzana relacionada.
					$arrCoefManzanas = Manzanas::GetPorcentajes($arrManzanas);	
					
					foreach ($arrCoefManzanas as $nroManzana => $coefManzana){
						// Calculo la porción de gasto aplicable a cada manzana.
						$montoGastoManzana = (Helper::NumFormat($arrGastosLiq[$i]["monto"]) * $coefManzana) / 100;
						self::ApplyExpenseToManzana($nroManzana, $montoGastoManzana, $arrGastosLiq[$i]["id"]);
					}
				}
			}
			self::UpdateLiquidacionesUF();
			self::CloseLiquidacionGlobal();
		
			$objetoAccesoDato->commit();
			return $response->withJson(true, 200);
			
		}catch(Exception $e){
			$objetoAccesoDato->rollBack();
			return $response->withJson($e->getMessage(), 500);
		}
	}
		   
}//class