<?php   

include_once __DIR__ . '/../_FuncionesEntidades.php';
include_once __DIR__ . '/../Helper.php';
include_once __DIR__ . '/../LiquidacionesUF.php';
include_once __DIR__ . '/../Manzanas.php';
include_once __DIR__ . '/../Diccionario.php';
include_once __DIR__ . '/../UF.php';
include_once __DIR__ . '/../enums/LiqGlobalStatesEnum.php';
include_once __DIR__ . '/../enums/EntityTypeEnum.php';


class LiquidacionUfApi{
 
    // Variables de clase
    private static $arrIdLiquidacionUF;
    private static $idLiqGlobal;
    private static $arrMontoTotalLiqUF;

    /**
	 * Obtiene un idLiquidacionUF, ya sea del array de clase arrIdLiquidacionUF o generando uno nuevo 
     * (si no existe, crea la liquidacionUF y guarda el id nuevo en el array).
     */
    private static function GetIdLiquidacionUF($uf){
        // Se utiliza un array de clase para evitar consultar a la bd innecesariamente; iremos guardando aquí los idLiqUF.
        // Además aseguramos que se genere una única LiqUF por cada UF.
        if(!is_null(self::$arrIdLiquidacionUF)){
            foreach (self::$arrIdLiquidacionUF as $idUF => $idLiqUF){
                if($idUF == $uf['id']){
                    return $idLiqUF;
                }
            }
        }
        $newId = self::NewLiquidacionUF($uf);
        self::$arrIdLiquidacionUF[$uf['id']] = $newId;
        return $newId;
    }

    /**
	 * Obtiene un nuevo IdLiquidacionUF, a partir de la generación de una nueva liquidacionUF en la BD.
     * Recibe por parámetro una instancia de la clase UF.
	 */
    private static function NewLiquidacionUF($uf){
        $liquidacionUF = new LiquidacionesUF();
        $liquidacionUF->idLiquidacionGlobal = self::$idLiqGlobal;
        $liquidacionUF->coeficiente = $uf['coeficiente'];

        $newId = LiquidacionesUF::Insert($liquidacionUF);
        if($newId < 1)
            throw new Exception("No se pudo obtener un id de liquidación nuevo para una de las unidades funcionales.");
        else
            return $newId;
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
	 * Gestiona el insert de un nuevo registro en la tabla CtasCTes y devuelve el id generado por la BD.
     * Recibe por parámetros un objeto UF y el monto a reflejar en el movimiento.
	 */
    private static function SetCtaCteAndGetId($idUf, $montoTotalLiqUF){
        // Obtengo el periodo a liquidar
        $liqGbl = Funciones::GetOne(self::$idLiqGlobal, "LiquidacionesGlobales");

        $ctaCte = new CtasCtes();
        $ctaCte->idUF = $idUf;
        $ctaCte->fecha = date("Y-m-d");
        $ctaCte->descripcion = "LIQUIDACION EXPENSA PERIODO " . $liqGbl->mes . "/" . $liqGbl->anio;
        $ctaCte->monto = $montoTotalLiqUF;
        $saldoActual = Helper::NumFormat(CtasCtes::GetLastSaldo($idUf) ?? 0);
        $ctaCte->saldo = $saldoActual + $montoTotalLiqUF;
        
        $newId =  CtasCtes::Insert($ctaCte);
        if($newId < 1)
            throw new Exception("No se pudo actualizar uno de los movimientos en las cuentas corrientes.");
        else
            return $newId;
    }

    /**
	 * Actualiza algunos campos (que inicialmente se graban como null) en todas las liquidaciones por unidad funcional.
     * Utiliza los array de clase que contienen las liquidacionesUF y sus montos.
	 */
    private static function UpdateLiquidacionesUF(){
        foreach (self::$arrIdLiquidacionUF as $idUf1 => $idLiquidacionUF){
            foreach(self::$arrMontoTotalLiqUF as $idUf2 => $montoTotalLiqUF){
                if($idUf1 == $idUf2){
                    $liquidacionUF = Funciones::GetOne($idLiquidacionUF,"LiquidacionesUF");
                    $liquidacionUF->monto = $montoTotalLiqUF;
                    $liquidacionUF->saldo = $montoTotalLiqUF;
                    $liquidacionUF->idCtaCte = self::SetCtaCteAndGetId($idUf1, $montoTotalLiqUF);
                    if(!Funciones::UpdateOne($liquidacionUF))
                        throw new Exception("No se pudo actualizar el monto en una de las liquidaciones por unidad funcional.");
                }
            }
        }
    }

    /**
	 * Aplica un gasto a una unidad funcional.
     * Recibe por parámetro una instancia de UF, el monto del gasto y el id de la liquidacion global.
	 */
    private static function ApplyExpenseToUF($uf, $montoGasto, $idLiquidacionGlobal){
        $montoGastoUF = Helper::NumFormat($montoGasto) * $uf['coeficiente'];
        // Acumulo el monto del gasto para luego actualizar la liquidacionUF.
        self::$arrMontoTotalLiqUF[$uf['id']] =+ $montoGastoUF;
        self::InsertGastoUF($uf, $montoGastoUF, $idLiquidacionGlobal);
    }

    /**
	 * Aplica un gasto a todas las unidades funcionales de un edificio.
     * Recibe por parámetro el número de edificio, el monto del gasto y el id de la LiquidacionGlobal.
	 */
    private static function ApplyExpenseToEdificio($edificio, $montoGastoEdificio, $idLiquidacionGlobal){
        $arrUF = UF::GetByEdificio($edificio);                  
        foreach ($arrUF as $uf)
            self::ApplyExpenseToUF($uf, $montoGastoEdificio, $idLiquidacionGlobal);
    }

    /**
	 * Aplica un gasto a todas las unidades funcionales de una manzana.
     * Recibe por parámetro el id de la manzana, el monto del gasto y el id de la LiquidacionGlobal.
	 */
    private static function ApplyExpenseToManzana($idManzana, $montoGastoManzana, $idLiquidacionGlobal){
        $arrUF = UF::GetByManzana($idManzana);                  
        foreach ($arrUF as $uf)
            self::ApplyExpenseToUF($uf, $montoGastoManzana, $idLiquidacionGlobal);
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

            $arrGastosLiq = GastosLiquidaciones::GetByLiquidacionGlobal(self::$idLiqGlobal);
            for($i = 0; $i < sizeof($arrGastosLiq); $i++){
                $arrRelacionesGastos = RelacionesGastos::GetByIdGastoLiquidacion($arrGastosLiq[$i]["id"]);
           
                if(sizeof($arrRelacionesGastos)==1){
                    // Si hay solo una relacion , aplico calculo según tipo entidad.
                    switch ($arrRelacionesGastos[0]["entidad"]) {
                        case EntityTypeEnum::Manzana :
                            self::ApplyExpenseToManzana($arrRelacionesGastos[0]["numero"], $arrGastosLiq[$i]["monto"], self::$idLiqGlobal);
                            break;
                        case EntityTypeEnum::Edificio :
                            $cantUF = Diccionario::GetValue("CANT_UF_EDIFICIO");
                            self::ApplyExpenseToEdificio($arrRelacionesGastos[0]["numero"], $arrGastosLiq[$i]["monto"] / $cantUF, self::$idLiqGlobal);
                            break;
                        case EntityTypeEnum::UnidadFuncional :
                            $uf = Funciones::GetOne($arrRelacionesGastos[0]["numero"],"UF");
                            self::ApplyExpenseToUF($uf, $arrGastosLiq[$i]["monto"], self::$idLiqGlobal);
                            break;
                    }
                }
                else // Else: el gasto está relacionado con varias manzanas. Aplicar calculo de coeficiente.
                {
                    // Extraigo solo el idManzana de las relaciones de cada gasto.
                    $arrManzanas = array_map(function($var) { return $var['numero']; }, $arrRelacionesGastos);
                    
                    $arrCoefManzanas = Manzanas::GetPorcentajes($arrManzanas);                    
                    
                    // Proceso el gasto por cada manzana relacionada.
                    foreach ($arrCoefManzanas as $idManzana => $coefManzana){
                        // Calculo la porción de gasto aplicable a cada manzana.
                        $montoGastoManzana = (Helper::NumFormat($arrGastosLiq[$i]["monto"]) * $coefManzana) / 100;
                        self::ApplyExpenseToManzana($idManzana, $montoGastoManzana, $arrGastosLiq[$i]["id"]);
                    }
                }
            }
            self::UpdateLiquidacionesUF();
            LiquidacionesGlobales::ChangeState(self::$idLiqGlobal, LiqGlobalStatesEnum::Cerrada);
            $objetoAccesoDato->commit();
            return $response->withJson(true, 200);
            
		}catch(Exception $e){
			$objetoAccesoDato->rollBack();
            return $response->withJson($e->getMessage(), 500);
		}
    }
      	 
}//class