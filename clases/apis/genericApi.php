<?php

    include_once __DIR__ . '/../_FuncionesEntidades.php';
    include_once __DIR__ . '/../Helper.php';
   

    class GenericApi
    {

        public static function GetAll($request, $response, $args)
        {
           	//Traigo  todos los items
			$apiParam = $request->getQueryParams();
			$listado= Funciones::GetAll($apiParam["t"]);
    		
			if($listado)
				return $response->withJson($listado, 200); 		
			else   
				return $response->withJson(false, 400);
        } 
		
		
		public static function GetPagedWithOptionalFilter($request, $response, $args)
        {
			$apiParam = $request->getQueryParams();
			
			$e  = $apiParam['entity'];
			$c1 = $apiParam['col1'] ?? null; 
			$t1 = $apiParam['txt1'] ?? null; 
			$c2 = $apiParam['col2'] ?? null; 
			$t2 = $apiParam['txt2'] ?? null; 
			$r = $apiParam['rows'];
			$p = $apiParam['page'];
					 
			$data = Funciones::GetPagedWithOptionalFilter($e, $c1, $t1, $c2, $t2, $r, $p);
			
			if($data)
				return $response->withJson($data, 200); 
			else
			    return $response->withJson(false, 400);  
        } 
		
		
		private static function GetWithPaged($request, $response, $args)
        {
			$apiParam = $request->getQueryParams();
			$v = $apiParam['entity'];
			$r = $apiParam['rows'];
			$p = $apiParam['page'];
			 	
			// return $response->withJson(Funciones::GetWithPaged($v,$r,$p), 200); 
			$data = Funciones::GetWithPaged($v,$r,$p);
			
			if($data)
				return $response->withJson($data, 200); 
			else
			   return $response->withJson(false, 400);  
        } 
		
		
		public static function GetOne($request, $response, $args)
        {
           	$apiParam = $request->getQueryParams();
			$id = json_decode($args['id']);
      		$objEntidad= Funciones::GetOne($id,$apiParam["t"]);
    		
			
			if($objEntidad)
				return $response->withJson($objEntidad, 200); 
			else
			   return $response->withJson(false, 400);  
        } 


        public static function UpdateOne($request, $response, $args)
        {
         //Datos recibidos por QueryString
            $apiParamQS = $request->getQueryParams();
                    
            //Datos recibidos por body
            $apiParamBody = $request->getParsedBody();	

            $result = Funciones::UpdateOne($apiParamQS,$apiParamBody);
					
			if($result)
				return $response->withJson(true, 200); 
			else
				return $response->withJson(false, 400);  			
        }


        public static function Insert($request, $response, $args)
        {
            //Datos recibidos por QueryString
            $apiParamQS = $request->getQueryParams();
                    
            //Datos recibidos por body
            $apiParamBody = $request->getParsedBody();	

			$result = Funciones::InsertOne($apiParamQS['t'],$apiParamBody);
			
			if($result)
				return $response->withJson(true, 200); 
			else
				return $response->withJson(false, 400);  			
            
        }
    
	
        public static function DeleteOne($request, $response, $args)
        {
             $apiParam = $request->getQueryParams();
             $id = json_decode($args['id']);
       
            //Busco el Persona mediante el id
            $objEntidad = Funciones::GetOne($id,$apiParam['t']); 
            
			//Si no se encontró grabo un mensaje
            if($objEntidad == null)
            {
                $response->write("Registro no encontrado");
            }else{
				//Sino borro el registro
                $result = Funciones::DeleteOne($id,$apiParam['t']);
				if($result)
					return $response->withJson(true, 200); 
				else
					return $response->withJson(false, 400);  			
            }
        }

    }
