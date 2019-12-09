<?php   

include_once __DIR__ . '/../_FuncionesEntidades.php';
include_once __DIR__ . '/../Helper.php';
include_once __DIR__ . '/../LiquidacionesUF.php';
include_once __DIR__ . '/../Manzanas.php';
include_once __DIR__ . '/../Diccionario.php';
include_once __DIR__ . '/../UF.php';
include_once __DIR__ . '/../enums/LiqGlobalStatesEnum.php';
include_once __DIR__ . '/../enums/EntityTypeEnum.php';
include_once __DIR__ . '/../enums/RentalContractEnum.php';


class LiquidacionUfApi{
 
	// Variables de clase
	private static $arrLiquidacionUF = array();
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
	 * Obtiene un idLiquidacionUF, ya sea del array de clase arrLiquidacionUF o generando uno nuevo 
	 * (si no existe, crea la liquidacionUF y guarda el id nuevo en el array).
	 */
	private static function GetIdLiquidacionUF($uf){
		// Se utiliza un array de clase para evitar consultar a la bd innecesariamente; iremos guardando aquí las liquidacionesUF.
		// Además aseguramos que se genere una única LiqUF por cada UF.
		if(!is_null(self::$arrLiquidacionUF)){
			foreach (self::$arrLiquidacionUF as $liquidacionUF){
				if($liquidacionUF->nroManzana == $uf['nroManzana'] && $liquidacionUF->nroUF == $uf['nroUF']){
					return $liquidacionUF->id;
				}
			}
		}
		$newLiquidacionUF = self::NewLiquidacionUF($uf);
		// Guardo la liquidaciónUF en el array de clase.
		array_push(self::$arrLiquidacionUF, $newLiquidacionUF);
		return $newLiquidacionUF->id;
	}

	/**
	 * Genera una nueva liquidacionUF en la BD. Devuelve el objeto generado.
	 */
	private static function NewLiquidacionUF($uf){
		$liquidacionUF = new LiquidacionesUF();
		$liquidacionUF->idLiquidacionGlobal = self::$idLiqGlobal;
		$liquidacionUF->nroManzana = $uf['nroManzana'];
		$liquidacionUF->nroUF = $uf['nroUF'];
		$liquidacionUF->coeficiente = $uf['coeficiente'];

		$newId = LiquidacionesUF::Insert($liquidacionUF);
		if($newId < 1){
			throw new Exception("No se pudo generar una liquidación nueva para una de las unidades funcionales.");
		}else{
			$liquidacionUF->id = $newId;
			return $liquidacionUF;
		}
	}

	/**
	 * Actualiza algunos campos (que inicialmente se graban como null) en todas las liquidaciones por unidad funcional.
	 * Utiliza los array de clase que contienen las liquidacionesUF y sus montos.
	 */
	private static function UpdateLiquidacionesUF(){
		foreach(self::$arrLiquidacionUF as $liquidacionUF){
			$liquidacionUF->saldoMonto = $liquidacionUF->monto * -1;
			$liquidacionUF->idCtaCte = self::SetCtaCteAndGetId($liquidacionUF);
			
			if(!Funciones::UpdateOne($liquidacionUF))
				throw new Exception("No se pudo actualizar el monto en una de las liquidaciones por unidad funcional.");
		}
	}

	/**
	 * Gestiona el insert de un nuevo registro en la tabla CtasCTes y devuelve el id generado por la BD.
	 */
	private static function SetCtaCteAndGetId($liquidacionUF){
		// Obtengo el periodo a liquidar de la liquidacion global.
		$liqGbl = Funciones::GetOne(self::$idLiqGlobal, "LiquidacionesGlobales");

		$ctaCte = new CtasCtes();
		$ctaCte->nroUF = $liquidacionUF->nroUF;
		$ctaCte->fecha = date("Y-m-d");
		$ctaCte->descripcion = "LIQUIDACION EXPENSA PERIODO " . $liqGbl->mes . "/" . $liqGbl->anio;
		$ctaCte->monto = $liquidacionUF->monto * -1;
		$saldoActual = Helper::NumFormat(CtasCtes::GetLastSaldo($liquidacionUF->nroUF) ?? 0);
		$ctaCte->saldo = $saldoActual - $liquidacionUF->monto;
		
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
	private static function ApplyExpenseToManzana($nroManzana, $montoGastoManzana, $idGastoLiquidacion){
		$arrUF = UF::GetByManzana($nroManzana);				  
		foreach ($arrUF as $uf){
			$montoGastoUF = Helper::NumFormat($montoGastoManzana) * $uf['coeficiente'];		 
			self::SaveGastoAndAccumulateAmount($uf, $montoGastoUF, $idGastoLiquidacion);
		}
	}

	/**
	 * Aplica un gasto a todas las unidades funcionales de un edificio.
	 * Recibe por parámetro el número de edificio, el monto del gasto y el id de la LiquidacionGlobal.
	 */
	private static function ApplyExpenseToEdificio($nroManzana, $nroEdificio, $montoGastoEdificio, $idGastoLiquidacion){
		$cantUF = Edificios::GetOne($nroManzana, $nroEdificio)->cantUF;
		$montoGastoUF = Helper::NumFormat($montoGastoEdificio) / $cantUF;

		$arrUF = UF::GetByEdificio($nroManzana, $nroEdificio);				  
		foreach ($arrUF as $uf)
			self::SaveGastoAndAccumulateAmount($uf, $montoGastoUF, $idGastoLiquidacion);
	}

	/**
	 * Aplica un gasto a una unidad funcional.
	 * Recibe por parámetro un id de UF, el monto del gasto y el id de la liquidacion global.
	 */
	private static function ApplyExpenseToUF($nroManzana, $nroUF, $montoGasto, $idGastoLiquidacion){
		$uf = UF::GetByNumero($nroManzana, $nroUF);
		self::SaveGastoAndAccumulateAmount($uf, $montoGasto, $idGastoLiquidacion);
	}

	/**
	 * Guarda el gasto en la bd y acumula el monto del gasto en la liquidacionuf correspondiente.
	 */
	private static function SaveGastoAndAccumulateAmount($uf, $montoGasto, $idGastoLiquidacion){
		$monto = Helper::NumFormat($montoGasto);
		self::InsertGastoUF($uf, $monto, $idGastoLiquidacion);
		foreach (self::$arrLiquidacionUF as $liquidacionuf){
			if($liquidacionuf->nroUF == $uf['nroUF']){
				$liquidacionuf->monto += self::CheckContractTax($uf, $monto);
				break;
			}
		}
	}

	/**
	 * Guarda un gastoliquidacionUF en la BD.
	 * Recibe instancia de la clase UF, el monto del gastoUF para dicha UF y el id de la liquidacion global.
	 */
	private static function InsertGastoUF($uf, $montoGastoUF, $idGastoLiquidacion){
		$gastoUF = new GastosLiquidacionesUF();
		$gastoUF->idLiquidacionUF = self::GetIdLiquidacionUF($uf);
		$gastoUF->idGastosLiquidaciones = $idGastoLiquidacion;
		$gastoUF->monto = $montoGastoUF;

		if(!Funciones::InsertOne($gastoUF)){
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
							self::ApplyExpenseToManzana($arrRelacionesGastos[0]["nroEntidad"], $arrGastosLiq[$i]["monto"], $arrGastosLiq[$i]["id"]);
							break;
						case EntityTypeEnum::Edificio :
							self::ApplyExpenseToEdificio(
								$arrRelacionesGastos[0]["nroManzana"], $arrRelacionesGastos[0]["nroEntidad"], $arrGastosLiq[$i]["monto"], $arrGastosLiq[$i]["id"]);
							break;
						case EntityTypeEnum::UnidadFuncional :
							self::ApplyExpenseToUF(
								$arrRelacionesGastos[0]["nroManzana"], $arrRelacionesGastos[0]["nroEntidad"], $arrGastosLiq[$i]["monto"], $arrGastosLiq[$i]["id"]);
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