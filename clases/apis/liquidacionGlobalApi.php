<?php   

include_once __DIR__ . '/../LiquidacionesGlobales.php';
include_once __DIR__ . '/../GastosLiquidaciones.php';


class LiquidacionGlobalApi 
{


    public static function AddNewExpense($request , $response, $args){
        //Proceso los datos recibidos por body
        $datosRecibidos = $request->getParsedBody();

        //Obtengo instancia de LiquidacionGlobal
        $liquidacionGbl = new LiquidacionesGlobales($datosRecibidos["LiquidacionGlobal"]);

        //Genero un array de objetos del tipo GastosLiquidaciones    
        $arrGastos = [];
        for ($i = 0; $i < sizeof($datosRecibidos["GastoLiquidacion"]); $i++) {
            $gasto = new GastosLiquidaciones($datosRecibidos["GastoLiquidacion"][$i]);
            array_push($arrGastos, $gasto);             
        }

        //Envio los datos para el alta de la expensa con sus gastos y relaciones
        $data = LiquidacionesGlobales::AddNewExpense($liquidacionGbl,$arrGastos);

		if($data)
				return $response->withJson($data, 200); 		
			else   
				return $response->withJson(false, 400);
    }





}