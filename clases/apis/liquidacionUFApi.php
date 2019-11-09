    <?php   

include_once __DIR__ . '/../_FuncionesEntidades.php';
include_once __DIR__ . '/../NumHelper.php';
include_once __DIR__ . '/../LiquidacionesUF.php';
include_once __DIR__ . '/../Manzanas.php';
include_once __DIR__ . '/../UF.php';

class LiquidacionUfApi{
 
    private static $arrIdLiquidacionUF;

    private static function GetIdLiquidacionUF($uf){
        //Se utiliza un array de clase para evitar consultar a la bd innecesariamente; iremos guardando aquí los idLiqUF.
        //Además aseguramos que se genere una única LiqUF por cada UF.
        if(!is_null(Self::$arrIdLiquidacionUF)){
            foreach (Self::$arrIdLiquidacionUF as $idUF => $idLiqUF){
                if($idUF == $uf['id']){
                    return $idLiqUF;
                }
            }
        }
        $newId = Self::NewLiquidacionUF();
        Self::$arrIdLiquidacionUF[$uf['id']] = $newId;
        return $newId;
    }

    private static function NewLiquidacionUF(){
        // TODO: generar nueva liq uf, considerando el insert a ctas ctes
        return "99";
    }

    public static function ProcessExpenses($request, $response, $args){
        // Proceso el request y obtengo todos los gastos de la liquidacion global.        
        $idLiqGlobal = $request->getParsedBody();

        // Obtengo todos los gastos la liquidación global
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
            }
            else //El gasto está relacionado con varias manzanas. Aplicar calculo de coeficiente.
            {
                //Extraigo solo el idManzana de las relaciones de cada gasto.
                $arrManzanas = array_map(function($var) { return $var['numero']; }, $arrRelacionesGastos);
                
                //Con los idManzana calculo los coeficientes de cada manzana. 
                $arrCoefManzanas = Manzanas::GetCoeficientes($arrManzanas);
                
                //Proceso las manzanas, para generar los gastos de cada UF.
                foreach ($arrCoefManzanas as $idManzana => $coefManzana){
                    //Calculo la porción de gasto aplicable a cada manzana.
                    $montoGastoManzana = (NumHelper::Multiply($arrGastosLiq[$i]["monto"], $coefManzana)) / 100;

                    //Imputo el gasto a todas las UF de la manzana.
                    $arrUF = UF::GetByManzana($idManzana);  
                    
                    foreach ($arrUF as $uf){
                        $montoGastoUF = NumHelper::Multiply($montoGastoManzana, $uf['coeficiente']);
                       
                        $gasto = new GastosLiquidacionesUF();
                        $gasto->idLiquidacionUF = Self::GetIdLiquidacionUF($uf);
                        $gasto->idGastosLiquidaciones = $arrGastosLiq[$i]["id"];
                        $gasto->monto = $montoGastoUF;
                        Funciones::InsertOne($gasto);
                    }                    
                }
            }
        }

        // TODO: Una vez liquidados los gastosUF, actualizar LiquidacionesUF campo monto y saldo.


    }
    
  
  	 
}//class