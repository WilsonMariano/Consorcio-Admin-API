<?php   

include_once __DIR__ . '/../LiquidacionesUF.php';
include_once __DIR__ . '/../Diccionario.php';
include_once __DIR__ . '/../Manzanas.php';
include_once __DIR__ . '/../UF.php';

class LiquidacionUfApi{
 
    public static function ProcessExpenses($request, $response, $args){
        // Proceso el request y obtengo todos los gastos de la liquidacion global.        
        $idLiqGlobal = $request->getParsedBody();
        $arrGastosLiq = GastosLiquidaciones::GetByLiquidacionGlobal($idLiqGlobal[0]);
    
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
            }else{
                //El gasto está relacionado con varias manzanas. Aplicar calculo de coeficiente.
                $arrManzanas = array();
                foreach ($arrRelacionesGastos as $relacionGasto) {
                    array_push($arrManzanas, $relacionGasto['numero']);
                }    
                $arrCoefManzanas = Manzanas::GetCoeficientes($arrManzanas);
                
                //Calculo la porción de gasto aplicable a cada manzana.
                foreach ($arrCoefManzanas as $idManzana => $coefManzana){
                    $montoAux = number_format($arrGastosLiq[$i]["monto"], 2, ".", "");
                    $montoManzana = ($coefManzana * $montoAux)/100;
                
                    //Obtengo todas las UF de la manzana e imputo el gasto a c/u.
                    $arrUF = UF::GetByManzana($idManzana);              
                    foreach ($arrUF as $uf){
                        //TODO: verificar formato del montoUF calculado
                        $montoUF = $montoManzana * $uf->coeficiente;
                        $gasto = new GastosLiquidacionesUF();
                        //TODO: INSERTAR LIQUF y obtener el ID
                        $gasto->idLiquidacionUF = "99";
                        $gasto->idGastosLiquidaciones = $arrGastosLiq[$i]["id"];
                        $gasto->monto = $montoUF;
                        // Funciones::InsertOne($gasto);
                    }
                }
            }
        }
    }
    
  	 
}//class