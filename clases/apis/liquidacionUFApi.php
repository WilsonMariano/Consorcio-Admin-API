<?php   

include_once __DIR__ . '/../_FuncionesEntidades.php';
include_once __DIR__ . '/../NumHelper.php';
include_once __DIR__ . '/../LiquidacionesUF.php';
include_once __DIR__ . '/../Manzanas.php';
include_once __DIR__ . '/../UF.php';

class LiquidacionUfApi{
 
    private static $arrIdLiquidacionUF;
    private static $idLiqGlobal;

    private static function GetIdLiquidacionUF($uf){
        //Se utiliza un array de clase para evitar consultar a la bd innecesariamente; iremos guardando aquí los idLiqUF.
        //Además aseguramos que se genere una única LiqUF por cada UF.
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

    private static function NewLiquidacionUF($uf){
        $liquidacionUF = new LiquidacionesUF();
        $liquidacionUF->idLiquidacionGlobal = self::$idLiqGlobal;
        $liquidacionUF->coeficiente = $uf['coeficiente'];

        return LiquidacionesUF::Insert($liquidacionUF);
    }

    private static function InsertGastoUF($uf, $montoGastoManzana, $idGastoLiquidacion){
        $montoGastoUF = NumHelper::Format($montoGastoManzana) * $uf['coeficiente'];
    
        $gastoUF = new GastosLiquidacionesUF();
        $gastoUF->idLiquidacionUF = self::GetIdLiquidacionUF($uf);
        $gastoUF->idGastosLiquidaciones = $idGastoLiquidacion;
        $gastoUF->monto = $montoGastoUF;
        return Funciones::InsertOne($gastoUF);
    }

    // TODO: Una vez liquidados los gastosUF, actualizar LiquidacionesUF campo monto y saldo.
    private static function GetIdCtaCte($uf, $monto){
        // Obtengo el periodo liquidado
        $liqGbl = Funciones::GetOne(self::$idLiqGlobal, "LiquidacionesGlobales");

        $ctaCte = new CtasCtes();
        $ctaCte->idUF = $uf['id'];
        $ctaCte->fecha = date("Y-m-d");
        $ctaCte->descripcion = "LIQUIDACION EXPENSA PERIODO " . $liqGbl->mes . "/" . $liqGbl->anio;
        $ctaCte->monto = $monto;

        return CtasCtes::Insert($ctaCte);
    }

    //TODO: Calcular el monto total de la expensa para actualizar la liquidacionuf
    private static function CalculateLiqUFAmount(){
        return true;
    }

    public static function ProcessExpenses($request, $response, $args){
        // Proceso el request y obtengo todos los gastos de la liquidacion global.        
        self::$idLiqGlobal = $request->getParsedBody()[0];
        $arrGastosLiq = GastosLiquidaciones::GetByLiquidacionGlobal(self::$idLiqGlobal);

        for($i = 0; $i < sizeof($arrGastosLiq); $i++){
            // Por cada gasto obtengo las relacionesGastos
            $arrRelacionesGastos = RelacionesGastos::GetByIdGastoLiquidacion($arrGastosLiq[$i]["id"]);
            //Si hay solo una relacion , aplico calculo según tipo entidad.
            if(sizeof($arrRelacionesGastos)==1){
                switch ($arrRelacionesGastos[0]["entidad"]) {
                    case "TIPO_ENTIDAD_1":
                        // echo "todo: manzana";
                        break;
                    case "TIPO_ENTIDAD_2":
                        // echo "todo: edificio";
                        break;
                    case "TIPO_ENTIDAD_3":
                        // echo "todo: uf";
                        break;
                }
            }
            else //El gasto está relacionado con varias manzanas. Aplicar calculo de coeficiente.
            {
                //Extraigo solo el idManzana de las relaciones de cada gasto.
                $arrManzanas = array_map(function($var) { return $var['numero']; }, $arrRelacionesGastos);
                //Con los idManzana calculo los coeficientes de cada manzana. 
                $arrCoefManzanas = Manzanas::GetCoeficientes($arrManzanas);

                //Proceso las manzanas relacionadas al gasto.
                foreach ($arrCoefManzanas as $idManzana => $coefManzana){
                    //Calculo la porción de gasto aplicable a cada manzana.
                    $montoGastoManzana = (NumHelper::Format($arrGastosLiq[$i]["monto"]) * $coefManzana) / 100;

                    //Imputo el gasto a todas las UF de la manzana.
                    $arrUF = UF::GetByManzana($idManzana);                  
                    foreach ($arrUF as $uf){
                        self::InsertGastoUF($uf, $montoGastoManzana, $arrGastosLiq[$i]["id"]);
                    }                    
                }
            }
        }
    }
      	 
}//class