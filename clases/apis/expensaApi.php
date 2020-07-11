<?php   

require_once __DIR__ . '/../Helpers/NumHelper.php';
require_once __DIR__ . '/../Helpers/StrHelper.php';
require_once __DIR__ . '/../enums/EntityTypeEnum.php';
require_once __DIR__ . '/../enums/RentalContractEnum.php';
require_once __DIR__ . '/../enums/LiquidacionTypeEnum.php';


class ExpensaApi{
 
	private const TXT_LIQ_EXPENSA = "TXT_LIQ_EXPENSA";
	private static $arrLiquidaciones = array();
	private static $arrExpensas = array();
	private static $idLiqGlobal;
	private static $objLiquidacionGlobal;
	
	/**
	 * Aplica un gasto a una entidad en específico.
	 */
	private static function ApplyExpenseToEntity($relacionGasto, $gastoLiq)
	{
		switch ($relacionGasto["entidad"]) {
			case EntityTypeEnum::Manzana :
				self::ApplyExpenseToManzana(
					$relacionGasto["nroEntidad"], $arrGastosLiq[$i]["monto"], $gastoLiq["id"]);
				break;
			case EntityTypeEnum::Edificio :
				self::ApplyExpenseToEdificio(
					$relacionGasto["idManzana"], $relacionGasto["nroEntidad"], $gastoLiq["monto"], $gastoLiq["id"]);
				break;
			case EntityTypeEnum::UnidadFuncional :
				self::ApplyExpenseToUF(
					$relacionGasto["idManzana"], $relacionGasto["nroEntidad"], $gastoLiq["monto"], $gastoLiq["id"]);
				break;
		}
	}

	/**
	 * Aplica un gasto a todas las unidades funcionales de una manzana.
	 */
	private static function ApplyExpenseToManzana($nroManzana, $montoGastoManzana, $idGastoLiquidacion){
		$arrUF = UF::GetByNroManzana($nroManzana);		
		foreach ($arrUF as $uf){
			$montoGastoUF = NumHelper::NumFormat($montoGastoManzana) * $uf->coeficiente;		 
			self::SaveGastoAndAccumulateAmount($uf, $montoGastoUF, $idGastoLiquidacion);
		}
	}

	/**
	 * Aplica un gasto a todas las unidades funcionales de un edificio.
	 */
	private static function ApplyExpenseToEdificio($idManzana, $nroEdificio, $montoGastoEdificio, $idGastoLiquidacion){
		$cantUF = Edificios::GetByManzanaAndNumero($idManzana, $nroEdificio)->cantUF;
		$montoGastoUF = NumHelper::NumFormat($montoGastoEdificio) / $cantUF;

		$arrUF = UF::GetByManzanaAndEdificio($idManzana, $nroEdificio);				  
		foreach ($arrUF as $uf)
			self::SaveGastoAndAccumulateAmount($uf, $montoGastoUF, $idGastoLiquidacion);
	}

	/**
	 * Aplica un gasto a una unidad funcional.
	 */
	private static function ApplyExpenseToUF($idManzana, $nroUF, $montoGasto, $idGastoLiquidacion){
		$uf = UF::GetByManzanaAndNumero($idManzana, $nroUF);
		self::SaveGastoAndAccumulateAmount($uf, $montoGasto, $idGastoLiquidacion);
	}

	/**
	 * Guarda el gasto en la bd y acumula el monto del gasto en la expensa correspondiente.
	 */
	private static function SaveGastoAndAccumulateAmount($uf, $montoGasto, $idGastoLiquidacion){
		$monto = NumHelper::NumFormat($montoGasto);
		self::InsertGastoExpensa($uf, $monto, $idGastoLiquidacion);
		foreach (self::$arrLiquidaciones as $liquidacion){
			if($liquidacion->idUF == $uf->id){
				$liquidacion->monto += self::CheckContractTax($uf, $monto);
				break;
			}
		}
	}

	/**
	 * Guarda un gastoExpensa en la BD.
	 */
	private static function InsertGastoExpensa($uf, $montoGastoUF, $idGastoLiquidacion){
		$gastoUF = new GastosExpensas();
		$gastoUF->idExpensa = self::GetIdExpensa($uf);
		$gastoUF->idGastosLiquidaciones = $idGastoLiquidacion;
		$gastoUF->monto = $montoGastoUF;

		Funciones::InsertAndSaveID($gastoUF);
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
				if($liquidacion->idUF == $uf->id){
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
		$expensa->coeficiente = $uf->coeficiente;

		return Funciones::InsertAndSaveID($expensa);
	}

	/**
	 * Obtiene el id de una liquidación para la UF especificada.
	 */
	private static function GetIdLiquidacion($uf){
		if(!is_null(self::$arrLiquidaciones)){
			foreach (self::$arrLiquidaciones as $liquidacion){
				if($liquidacion->idUF == $uf->id){
					return $liquidacion->id;
				}
			}
		}
		// Sino existe en el array generamos una nueva expensa 
		$newLiquidacion = LiquidacionApi::NewLiquidacion($uf);
		array_push(self::$arrLiquidaciones, $newLiquidacion);
		return $newLiquidacion->id;
	}

	/**
	 * Verifica la situación del contrato de la UF y aplica recargos cuando corresponda.
	 */
	private static function CheckContractTax($uf, $montoGasto){
		$tax = 0;
		if($uf->codAlquila == RentalContractEnum::InquilinoSinContrato)
			$tax = NumHelper::NumFormat(Diccionario::GetValue(RentalContractEnum::TaxInqSinContrato));
		elseif($uf->codAlquila == RentalContractEnum::InquilinoConContrato)
			$tax = NumHelper::NumFormat(Diccionario::GetValue(RentalContractEnum::TaxInqConContrato));

		return $montoGasto += ($montoGasto * $tax) / 100;
	}

	/**
	 * Actualiza algunos campos (que inicialmente se graban como null) en todas las liquidaciones por unidad funcional.
	 * Utiliza los array de clase que contienen las liquidacionesUF y sus montos.
	 */
	private static function UpdateLiquidacionesUF(){

		foreach(self::$arrLiquidaciones as $liquidacion){
			$liquidacion->saldoMonto = $liquidacion->monto * -1;
			if(!Funciones::UpdateOne($liquidacion))
				throw new Exception("No se pudo actualizar el monto en una de las liquidaciones por unidad funcional.");

			self::SetCtaCte($liquidacion);
		}
	}

	/**
	 * Gestiona el insert de un nuevo registro en la tabla CtasCTes y devuelve el id generado por la BD.
	 */
	private static function SetCtaCte($liquidacion){
		$ctaCte = new CtasCtes();
		$ctaCte->idUF = $liquidacion->idUF;
		$ctaCte->idLiquidacion = $liquidacion->id;
		$ctaCte->fecha = date("Y-m-d");
		$ctaCte->descripcion = self::GetDescripcion();
		$ctaCte->monto = $liquidacion->saldoMonto;
		$saldoActual = NumHelper::NumFormat(CtasCtes::GetLastSaldo($liquidacion->idUF) ?? 0);
		$ctaCte->saldo = $saldoActual - $liquidacion->monto;
		Funciones::InsertAndSaveID($ctaCte);
	}

	/**
	 * Genera el texto para la descripción de las expensas en cuentas corrientes.
	 */
	private static function GetDescripcion(){
		$textoDescripcion = Diccionario::GetValue(self::TXT_LIQ_EXPENSA);
		return StrHelper::TxtPadRight($textoDescripcion) . self::$objLiquidacionGlobal->mes . "/" . self::$objLiquidacionGlobal->anio;
	}

	/**
	 * Agrega el cobro de los fondos especiales para cada uf previamente procesada
	 */
	private static function AddFondosEspeciales(){
		foreach(self::$arrLiquidaciones as $liquidacion){
			$uf = Funciones::GetOne($liquidacion->idUF, UF::class);

			$newLiquidacionFR = LiquidacionApi::NewLiquidacion($uf);
			$newLiquidacionFR->monto = Manzanas::GetMontoFondoEspecial($uf->idManzana, LiquidacionTypeEnum::FondoReserva);
			$newLiquidacionFR->saldoMonto = $newLiquidacionFR->monto * -1;
			$newFondoReserva = self::NewFondoEspecial($newLiquidacionFR, LiquidacionTypeEnum::FondoReserva);
			Funciones::UpdateOne($newLiquidacionFR);

			$newLiquidacionFP = LiquidacionApi::NewLiquidacion($uf);
			$newLiquidacionFP->monto = Manzanas::GetMontoFondoEspecial($uf->idManzana, LiquidacionTypeEnum::FondoPrevision);
			$newLiquidacionFP->saldoMonto = $newLiquidacionFP->monto * -1;
			$newFondoPrevision = self::NewFondoEspecial($newLiquidacionFP, LiquidacionTypeEnum::FondoPrevision);
			Funciones::UpdateOne($newLiquidacionFP);
		}
	}

	public static function NewFondoEspecial($liquidacion, $tipoLiquidacion){
		$fondo = new FondosEspeciales();
		$fondo->idLiquidacion = $liquidacion->id;
		$fondo->idLiquidacionGlobal = self::$idLiqGlobal;
		$fondo->tipoLiquidacion = $tipoLiquidacion;

		return Funciones::InsertAndSaveID($fondo);
	}


	/**
	 * ******************************************************************************************************************************************************************************
	 * 
	 *               CALCULO DE EXPENSAS. FUNCION PRINCIPAL DE LA CLASE
	 * 
	 * Procesa una liquidaciónGlobal generando las liquidaciones para cada unidad funcional. Se asume que previamente están cargados todos los GastosLiquidaciones correctamente.
	 * Recibe via httpParam un idLiquidacionGlobal.
	 * ******************************************************************************************************************************************************************************
	 */
	public static function ProcessExpenses($request, $response, $args){
		try{  
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$objetoAccesoDato->beginTransaction();

			self::$idLiqGlobal = $request->getParsedBody()[0];
			self::$objLiquidacionGlobal = Funciones::GetOne(self::$idLiqGlobal, LiquidacionesGlobales::class);

			// Valido si la liquidacionGlobal ya fue cerrada
			If(!LiquidacionGlobalApi::IsOpen(self::$objLiquidacionGlobal))
				throw new Exception("La liquidación ya se encuentra cerrada.");

			$arrGastosLiq = GastosLiquidaciones::GetByLiquidacionGlobal(self::$idLiqGlobal);
			for($i = 0; $i < sizeof($arrGastosLiq); $i++){
				$arrRelacionesGastos = RelacionesGastos::GetByIdGastoLiquidacion($arrGastosLiq[$i]["id"]);   
				if(sizeof($arrRelacionesGastos)== 1){
					self::ApplyExpenseToEntity($arrRelacionesGastos[0], $arrGastosLiq[$i] );
				}
				else // Else: el gasto está relacionado con varias entidades (en este punto solo pueden ser manzanas). Se calcula porcentaje de c/ manzana.
				{
					// Extraigo solo el nroManzana (campo nroEntidad) de las relaciones de cada gasto.
					$arrManzanas = array_map(function($var) { return $var['nroEntidad']; }, $arrRelacionesGastos);

					$arrCoefManzanas = ManzanaApi::GetPorcentajes($arrManzanas);	
					foreach ($arrCoefManzanas as $nroManzana => $coefManzana){
						// Calculo la porción de gasto aplicable a cada manzana.
						$montoGastoManzana = (NumHelper::NumFormat($arrGastosLiq[$i]["monto"]) * $coefManzana) / 100;
						self::ApplyExpenseToManzana($nroManzana, $montoGastoManzana, $arrGastosLiq[$i]["id"]);
					}
				}
			}
			self::UpdateLiquidacionesUF();
			self::AddFondosEspeciales();
			LiquidacionGlobalApi::CloseLiquidacionGlobal(self::$objLiquidacionGlobal);
		
			$objetoAccesoDato->commit();
			return $response->withJson(true, 200);
			
		}catch(Exception $e){
			$objetoAccesoDato->rollBack();
			ErrorHelper::LogError(__FUNCTION__, self::$idLiqGlobal, $e);		 
			return $response->withJson($e->getMessage(), 500);
		}
	}
		   
}//class