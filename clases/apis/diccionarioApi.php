<?php

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
	
	public static function GetValue ($request, $response, $args){
		$apiParams = $request->getQueryParams();
		$value = Diccionario::GetValue($apiParams["codigo"]);

		if($value)
			return $response->withJson($value, 200); 
		else
			return $response->withJson(false, 400);  
	}

}//class
