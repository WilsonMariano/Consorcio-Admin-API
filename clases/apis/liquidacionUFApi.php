<?php   

include_once __DIR__ . '/../LiquidacionesUF.php';
include_once __DIR__ . '/../Diccionario.php';
include_once __DIR__ . '/../Manzanas.php';

class LiquidacionUfApi{
 
    public static function ProcessExpenses($request, $response, $args){
    // Proceso el request y obtengo todos los gastos de la liquidacion global.        
        $idLiqGlobal = $request->getParsedBody();
        $arrGastosLiq = GastosLiquidaciones::GetByLiquidacionGlobal($idLiqGlobal[0]);
    
        for($i = 0; $i < sizeof($arrGastosLiq); $i++){
            // Por cada gasto obtengo las relacionesGastos
            $arrRelacionesGastos = RelacionesGastos::GetByIdGastoLiquidacion($arrGastosLiq[$i]["id"]);
      
            if(sizeof($arrRelacionesGastos)==1){
                switch ($arrRelacionesGastos[0]["entidad"]) {
                    case "TIPO_ENTIDAD_1":
                        echo "todo: manzana";
                        break;
                    case "TIPO_ENTIDAD_2":
                        echo "todo: edificio";
                        break;
                    case "TIPO_ENTIDAD_3":
                        echo "todo: uf";
                        break;
                }
            }else{
                //Gasto vinculado a mas de una manzana. Aplicar calculo de coeficiente.
                $arrManzanas = array();
                foreach ($arrRelacionesGastos as $relacionGasto) {
                    array_push($arrManzanas, $relacionGasto['numero']);
                }    
                $coefManzanas = Manzanas::GetCoeficientes($arrManzanas);
                // var_dump($coefManzanas);
                /*
                    todo: con los coeficientes iterar UF buscando las unidades de cada manzana
                    a partir del porcentaje que le corresponde a la manzana, imputar el gasto a cada uf
                    usando su coeficiente  (monto del gasto correspondiente a la manzana * coeficiente).
                */
            }
        }
    }
    
  	 
}//class