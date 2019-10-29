<?php   

include_once __DIR__ . '/../LiquidacionesUF.php';
include_once __DIR__ . '/../Diccionario.php';
include_once __DIR__ . '/../Manzanas.php';

class LiquidacionUFApi{
 
    public static function ProcessExpenses($idLiqGlobal){
        // Obtengo todos los gastos de la liquidacion global.        
        $arrGastosLiq = GastosLiquidaciones::GetByLiquidacionGlobal($idLiqGlobal);
       
        foreach ($arrGastosLiq as $gastoLiq){
            // Por cada gasto obtengo las relacionesGastos
            $arrRelacionesGastos = RelacionesGastos::GetByIdGastoLiquidacion($gastoliq->id);
            if(count($arrRelacionesGastos)==1){
                switch ($arrRelacionesGastos[0]->entidad) {
                    case "TIPO_ENTIDAD_1":
                        //todo: manzana 
                        break;
                    case "TIPO_ENTIDAD_2":
                        //todo: edificio
                        break;
                    case "TIPO_ENTIDAD_3":
                        //todo: uf
                        break;
                }
            }else{
                //Gasto vinculado a mas de una manzana. Aplicar calculo de coeficiente.
                $arrManzanas = array();
                foreach ($arrRelacionesGastos as $relacionGasto) {
                    array_push($arrManzanas, $relacionGasto->numero);
                }    
                $coefManzanas = Manzanas::GetCoeficientes($arrManzanas);
                /*
                    todo: con los coeficientes iterar UF buscando las unidades de cada manzana
                    a partir del porcentaje que le corresponde a la manzana, imputar el gasto a cada uf
                    usando su coeficiente  (monto del gasto correspondiente a la manzana * coeficiente).
                */
            }
        }
    }
    
  	 
}//class