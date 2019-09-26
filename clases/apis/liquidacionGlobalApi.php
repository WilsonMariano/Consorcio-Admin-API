<?php   

include_once __DIR__ . '/../LiquidacionesGlobales.php';


class LiquidacionGlobalApi 
{


    public static function AddNewExpense($request , $response, $args){

        $datosRecibidos = $request->getParsedBody();
        $liq = $datosRecibidos["liq"];
        
        $objLiquidacionGbl = new LiquidacionesGlobales();
        $objLiquidacionGbl->id = $liq["id"];
        $objLiquidacionGbl->mes = $liq["mes"];
        $objLiquidacionGbl->anio = $liq["anio"];
        $objLiquidacionGbl->primerVencimiento = $liq["primerVencimiento"];
        $objLiquidacionGbl->segundoVencimiento = $liq["segundoVencimiento"];
        $objLiquidacionGbl->fechaEmision = $liq["fechaEmision"];
        $objLiquidacionGbl->tasaInteres = $liq["tasaInteres"];


        $data = LiquidacionesGlobales::AddNewExpense($objLiquidacionGbl,$datosRecibidos["gastos"]);

		if($data)
				return $response->withJson($data, 200); 		
			else   
				return $response->withJson(false, 400);

    }


}