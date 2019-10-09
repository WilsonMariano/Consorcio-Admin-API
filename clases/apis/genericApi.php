<?php

include_once __DIR__ . '/../_FuncionesEntidades.php';

class GenericApi{

	public static function GetAll($request, $response, $args){
		//Traigo  todos los items
		$apiParams = $request->getQueryParams();
		$listado= Funciones::GetAll($apiParams["t"]);
		
		if($listado)
			return $response->withJson($listado, 200); 		
		else   
			return $response->withJson(false, 400);
	} 
	
	public static function GetPagedWithOptionalFilter($request, $response, $args){
		$apiParams = $request->getQueryParams();
		
		$e  = $apiParams['entity'];
		$c1 = $apiParams['col1'] ?? null; 
		$t1 = $apiParams['txt1'] ?? null; 
		$c2 = $apiParams['col2'] ?? null; 
		$t2 = $apiParams['txt2'] ?? null; 
		$r = $apiParams['rows'];
		$p = $apiParams['page'];
					
		$data = Funciones::GetPagedWithOptionalFilter($e, $c1, $t1, $c2, $t2, $r, $p);
		
		if($data)
			return $response->withJson($data, 200); 
		else
			return $response->withJson(false, 400);  
	} 
		
	public static function GetOne($request, $response, $args){
		$apiParams = $request->getQueryParams();
		$id = json_decode($args['id']);
		
		$obj= Funciones::GetOne($id,$apiParams["t"]);
		
		if($obj)
			return $response->withJson($obj, 200); 
		else
			return $response->withJson(false, 400);  
	} 

	public static function UpdateOne($request, $response, $args){
		//Datos recibidos por QueryString y Body
		$apiParamsQS = $request->getQueryParams();
		$apiParamsBody = $request->getParsedBody();	

		// Obtengo instancia de clase correspondiente.
		$objEntidad = Funciones::GetObjEntidad($apiParamsQS['t'], $apiParamsBody);

		if($objEntidad)
			if(Funciones::UpdateOne($objEntidad))
				return $response->withJson(true, 200); 
			else
				return $response->withJson(false, 500);  			
		else
			return $response->withJson(false, 400);  			
					
	}

	public static function Insert($request, $response, $args){
		//Datos recibidos por QueryString y Body
		$apiParamsQS = $request->getQueryParams();
		$apiParamsBody = $request->getParsedBody();	
		
		// Obtengo instancia de clase correspondiente.
		$objEntidad = Funciones::GetObjEntidad($apiParamsQS['t'], $apiParamsBody);

		if($objEntidad)
			if(Funciones::InsertOne($objEntidad))
				return $response->withJson(true, 200); 
			else
				return $response->withJson(false, 500);  			
		else
			return $response->withJson(false, 400);  			
	}

	public static function DeleteOne($request, $response, $args){
		$apiParams = $request->getQueryParams();
		$id = json_decode($args['id']);
	
		$obj = Funciones::GetOne($id,$apiParams['t']); 

		if($obj)
			if(Funciones::DeleteOne($id, $apiParams['t']))
				return $response->withJson(true, 200); 
			else
				return $response->withJson(false, 500);  			
		else
			return $response->withJson(false, 400);  	
	}

	public static function IsDuplicated($request, $response, $args){
		$apiParams = $request->getQueryParams();
		$id = json_decode($args['id']);

		$obj = Funciones::GetObjEntidad($apiParams['t']);

		if($obj){
			$obj->id = $id;
			if(Funciones::IsDuplicated($obj))
				return $response->withJson(true, 200); 
			else
				return $response->withJson(false, 500);  			
		}else{
			return $response->withJson(false, 400);  	
		}
	}

}//class
