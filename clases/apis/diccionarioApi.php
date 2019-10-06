<?php

include_once __DIR__ . '/../Diccionario.php';

class DiccionarioApi{
	
	public static function GetAll($request, $response, $args){
		//Traigo  todos los items
		$apiParams = $request->getQueryParams();
				
		$listado = Diccionario::GetAll($apiParams["codigo"]);
		
		if($listado)
			return $response->withJson($listado, 200); 		
		else   
			return $response->withJson(false, 400);
	} 
	
	public static function GetOne ($request, $response, $args){
		$apiParams = $request->getQueryParams();

		$objEntidad = Diccionario::GetOne($apiParams["codigo"]);

		if($objEntidad)
			return $response->withJson($objEntidad, 200); 
		else
			return $response->withJson(false, 400);  
	}

}//class
