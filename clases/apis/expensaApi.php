<?php   

include_once __DIR__ . '/../_FuncionesEntidades.php';
include_once __DIR__ . '/../Helper.php';
include_once __DIR__ . '/../Expensas.php';
include_once __DIR__ . '/../Manzanas.php';
include_once __DIR__ . '/../Diccionario.php';
include_once __DIR__ . '/../UF.php';
include_once __DIR__ . '/../enums/LiqGlobalStatesEnum.php';
include_once __DIR__ . '/../enums/EntityTypeEnum.php';
include_once __DIR__ . '/../enums/RentalContractEnum.php';


class ExpensaApi{
 
	// Variables de clase
	private static $arrExpensa = array();
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
	 * Cierra la liquidación global procesada en el request. Modifica el campo codEstado y setea la fecha de emisión.
	 */
	private static function CloseLiquidacionGlobal(){
		$liquidacionGlobal = Funciones::GetOne(self::$idLiqGlobal, "LiquidacionesGlobales");
		$liquidacionGlobal->fechaEmision = date("Y-m-d");
		$liquidacionGlobal->codEstado = LiqGlobalStatesEnum::Cerrada;
		Funciones::UpdateOne($liquidacionGlobal);
	}

	/**
	 * Obtiene un idExpensa, ya sea del array de clase arrExpensa o generando uno nuevo 
	 * (si no existe, crea la expensa y guarda el id nuevo en el array).
	 */
	private static function GetIdExpensa($uf){
		// Se utiliza un array de clase para evitar consultar a la bd innecesariamente; iremos guardando aquí las liquidacionesUF.
		// Además aseguramos que se genere una única LiqUF por cada UF.
		if(!is_null(self::$arrExpensa)){
			foreach (self::$arrExpensa as $expensa){
				if($expensa->nroManzana == $uf['nroManzana'] && $expensa->nroUF == $uf['nroUF']){
					return $expensa->id;
				}
			}
		}
		$newExpensa = self::NewExpensa($uf);
		// Guardo la liquidaciónUF en el array de clase.
		array_push(self::$arrExpensa, $newExpensa);
		return $newExpensa->id;
	}

	/**
	 * Genera una nueva expensa en la BD. Devuelve el objeto generado.
	 */
	private static function NewExpensa($uf){
		$expensa = new Expensas();
		$expensa->idLiquidacion = self::$idLiquidacion;
		$expensa->idLiquidacionGlobal = self::$idLiqGlobal;
		$expensa->coeficiente = $uf['coeficiente'];

		$newId = Expensas::Insert($expensa);
		if($newId < 1){
			throw new Exception("No se pudo generar una liquidación nueva para una de las unidades funcionales.");
		}else{
			$expensa->id = $newId;
			return $expensa;
		}
	}

	/**
	 * Actualiza algunos campos (que inicialmente se graban como null) en todas las liquidaciones por unidad funcional.
	 * Utiliza los array de clase que contienen las liquidacionesUF y sus montos.
	 */
	private static function UpdateLiquidacionesUF(){

		foreach(self::$arrExpensa as $expensa){
			$expensa->saldoMonto = $expensa->monto * -1;
			$expensa->idCtaCte = self::SetCtaCteAndGetId($expensa);
			
			if(!Funciones::UpdateOne($expensa))
				throw new Exception("No se pudo actualizar el monto en una de las liquidaciones por unidad funcional.");
		}
	}

	/**
	 * Gestiona el insert de un nuevo registro en la tabla CtasCTes y devuelve el id generado por la BD.
	 */
	private static function SetCtaCteAndGetId($expensa){
		// Obtengo el periodo a liquidar de la liquidacion global.
		$liqGbl = Funciones::GetOne(self::$idLiqGlobal, "LiquidacionesGlobales");

		$ctaCte = new CtasCtes();
		$ctaCte->nroUF = $expensa->nroUF;
		$ctaCte->fecha = date("Y-m-d");
		$ctaCte->descripcion = "LIQUIDACION EXPENSA PERIODO " . $liqGbl->mes . "/" . $liqGbl->anio;
		$ctaCte->monto = $expensa->monto * -1;
		$saldoActual = Helper::NumFormat(CtasCtes::GetLastSaldo($expensa->nroUF) ?? 0);
		$ctaCte->saldo = $saldoActual - $expensa->monto;
		
		$newId =  CtasCtes::Insert($ctaCte);
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
		self::InsertGastoUF($uf, $monto, $idGastoLiquidacion);
		foreach (self::$arrExpensa as $expensa){
			if($expensa->nroManzana == $uf['nroManzana'] && $expensa->nroUF == $uf['nroUF']){
				$expensa->monto += self::CheckContractTax($uf, $monto);
				break;
			}
		}
	}

	/**
	 * Guarda un gastoExpensa en la BD.
	 * Recibe instancia de la clase UF, el monto del gastoUF para dicha UF y el id de la liquidacion global.
	 */
	private static function InsertGastoUF($uf, $montoGastoUF, $idGastoLiquidacion){
		$gastoUF = new GastosExpensas();
		$gastoUF->idExpensa = self::GetIdExpensa($uf);
		$gastoUF->idGastosLiquidaciones = $idGastoLiquidacion;
		$gastoUF->monto = $montoGastoUF;

		if(!Funciones::InsertOne($gastoUF) > 0){
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
			// self::UpdateLiquidacionesUF();
			// self::CloseLiquidacionGlobal();
		
			// $objetoAccesoDato->commit();
			// return $response->withJson(true, 200);
			
		}catch(Exception $e){
			$objetoAccesoDato->rollBack();
			return $response->withJson($e->getMessage(), 500);
		}
	}
		   
}//class