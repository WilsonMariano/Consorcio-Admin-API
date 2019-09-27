<?php   

include_once __DIR__ . '/../LiquidacionesGlobales.php';
include_once __DIR__ . '/../GastosLiquidaciones.php';


class LiquidacionGlobalApi 
{


    public static function AddNewExpense($request , $response, $args){

        $datosRecibidos = $request->getParsedBody();

        $liquidacionGbl = LiquidacionesGlobales::GetInstanceFromArray($datosRecibidos["LiquidacionGlobal"]);

        $arrGastos = [];
        for ($i = 0; $i < sizeof($datosRecibidos["GastoLiquidacion"]); $i++) {
            $gasto = GastosLiquidaciones::GetInstanceFromArray($datosRecibidos["GastoLiquidacion"][$i]);
            array_push($arrGastos, $gasto);             
        }

        $data = LiquidacionesGlobales::AddNewExpense($liquidacionGbl,$arrGastos);

		if($data)
				return $response->withJson($data, 200); 		
			else   
				return $response->withJson(false, 400);
    }





}