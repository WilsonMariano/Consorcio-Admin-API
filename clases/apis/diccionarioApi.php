<?php

    include_once __DIR__ . '/../Diccionario.php';
    

    class DiccionarioApi
    {
		
        public static function GetAll($request, $response, $args)
        {
           	//Traigo  todos los items
			$datosRecibidos = $request->getQueryParams();
					
			$listado= Diccionario::GetAll($datosRecibidos["codigo"]);
    		
			if($listado)
				return $response->withJson($listado, 200); 		
			else   
				return $response->withJson(false, 400);
        } 


		public static function GetOne ($request, $response, $args)
		{
         	$datosRecibidos = $request->getQueryParams();
 	
			$objEntidad=Diccionario::GetOne($datosRecibidos["codigo"]);
			
			if($objEntidad)
				return $response->withJson($objEntidad, 200); 
			else
			   return $response->withJson(false, 400);  
			
		}


    }
