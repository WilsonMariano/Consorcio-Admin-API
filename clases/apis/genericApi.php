<?php

    include_once __DIR__ . '/../_FuncionesEntidades.php';
   

    class GenericApi
    {

        public static function GetAll($request, $response, $args)
        {
           	//Traigo  todos los items
			$datosRecibidos = $request->getQueryParams();
			$listado=\Funciones::GetAll($datosRecibidos["t"]);
    		return $response->write(json_encode($listado));           
        } 
		
		
		public static function GetWithFilter($request, $response, $args)
        {
			$datosRecibidos = $request->getQueryParams();
			$e = $datosRecibidos['entity'];
			$c = $datosRecibidos['column'];
			$t = $datosRecibidos['text'];
			$r = $datosRecibidos['rows'];
			$p = $datosRecibidos['page'];
			 	
			return $response->withJson(Funciones::GetWithFilter($e,$c,$t,$r,$p), 200); 
        } 
		
		
		public static function GetWithPaged($request, $response, $args)
        {
			$datosRecibidos = $request->getQueryParams();
			$v = $datosRecibidos['entity'];
			$r = $datosRecibidos['rows'];
			$p = $datosRecibidos['page'];
			 	
			return $response->withJson(Funciones::GetWithPaged($v,$r,$p), 200); 
        } 
		
		
		public static function GetOne($request, $response, $args)
        {
           	$datosRecibidos = $request->getQueryParams();
			$id = json_decode($args['id']);
      		$objEntidad=\Funciones::GetOne($id,$datosRecibidos["t"]);
    		return $response->write(json_encode($objEntidad));           
        } 


        public static function UpdateOne($request, $response, $args)
        {
         //Datos recibidos por QueryString
            $datosRecibidosQS = $request->getQueryParams();
                    
            //Datos recibidos por body
            $datosRecibidosBody = $request->getParsedBody();	

            return $response->write(json_encode(Funciones::UpdateOne($datosRecibidosQS,$datosRecibidosBody)));
        }


        public static function Insert($request, $response, $args)
        {
            //Datos recibidos por QueryString
            $datosRecibidosQS = $request->getQueryParams();
                    
            //Datos recibidos por body
            $datosRecibidosBody = $request->getParsedBody();	

            return $response->write(json_encode(Funciones::InsertOne($datosRecibidosQS,$datosRecibidosBody)));
        }
    
	
        public static function DeleteOne($request, $response, $args)
        {
             $datosRecibidos = $request->getQueryParams();
             $id = $datosRecibidos['id'];
       

            //Busco el Persona mediante el id
            $objEntidad = Funciones::GetOne($id,$datosRecibidos['t']); 
            // //Si no se encontró grabo un mensaje
            if($objEntidad == null)
            {
                $response->write("Registro no encontrado");
            }else{
            //Sino borro el registro y muestro los datos 
                Funciones::DeleteOne($id,$datosRecibidos['t']);
                $response->write(json_encode($objEntidad));
            }
            return $response;
        }

    }
